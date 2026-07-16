<?php

namespace App\Services;

use App\Models\AccountLedger;
use App\Models\ClientInvoice;
use App\Models\DetailAccount;
use App\Models\GeneralJournal;
use App\Models\JournalEntry;
use App\Models\JournalVoucher;
use App\Models\SubSubSubHead;
use App\Models\Tender;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ClientInvoiceService
{
    protected $journalVoucherService;

    public function __construct(JournalVoucherService $journalVoucherService)
    {
        $this->journalVoucherService = $journalVoucherService;
    }

    /**
     * Get all client invoices with pagination
     */
    public function getAll(array $filters = [])
    {
        $perPage = $filters['per_page'] ?? 15;

        return ClientInvoice::search($filters)
            ->with(['tender', 'client', 'journalVoucher', 'verifiedBy'])
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    /**
     * Get by ID
     */
    public function getById($id)
    {
        return ClientInvoice::with(['tender', 'client', 'journalVoucher', 'verifiedBy'])->findOrFail($id);
    }

    /**
     * Store new client invoice
     */
    public function store(array $data): ClientInvoice
    {
        $data['created_by'] = Auth::id();

        return ClientInvoice::create($data);
    }

    /**
     * Update client invoice (only if draft)
     */
    public function update($clientInvoice, array $data): ClientInvoice
    {
        if (!$clientInvoice->canBeEdited()) {
            throw new \Exception(__('messages.invoice-cannot-be-edited'));
        }

        $data['updated_by'] = Auth::id();

        $clientInvoice->update($data);

        return $clientInvoice;
    }

    /**
     * Verify invoice and create JV
     */
    public function verify(ClientInvoice $invoice): ClientInvoice
    {
        if (!$invoice->canBeVerified()) {
            throw new \Exception(__('messages.invoice-cannot-be-verified'));
        }

        return DB::transaction(function () use ($invoice) {
            // Create Journal Voucher for Accounts Receivable
            $journalVoucher = $this->createAccountingEntry($invoice);

            // Update invoice with voucher ID and status
            $invoice->update([
                'journal_voucher_id' => $journalVoucher->id,
                'status' => 'verified',
                'verified_by' => Auth::id(),
                'verified_at' => now(),
                'updated_by' => Auth::id(),
            ]);

            return $invoice->refresh();
        });
    }

    /**
     * Create accounting entry (JV posting)
     */
    private function createAccountingEntry(ClientInvoice $invoice): JournalVoucher
    {
        $tender = $invoice->tender;

        // Validate required accounts
        if (!$tender->revenue_account_id) {
            throw new \Exception(__('messages.revenue-account-not-configured'));
        }

        if (!$invoice->client_id) {
            throw new \Exception(__('messages.client-account-required'));
        }

        // Get the client's accounts receivable account
        $clientDetail = DetailAccount::findOrFail($invoice->client_id);

        // Create JV entry
        $voucherData = [
            'voucher_no' => $this->generateVoucherNumber(),
            'voucher_date' => $invoice->invoice_date->format('Y-m-d'),
            'description' => "Client Invoice: {$invoice->invoice_no}",
            'total_debit' => $invoice->amount,
            'total_credit' => $invoice->amount,
        ];

        $journalVoucher = JournalVoucher::create($voucherData);

        // Create Journal Entry - Debit Accounts Receivable (Client Account)
        $debitEntry = JournalEntry::create([
            'journal_voucher_id' => $journalVoucher->id,
            'debit_detail_account_id' => $clientDetail->id,
            'credit_detail_account_id' => null,
            'debit' => $invoice->amount,
            'credit' => 0,
            'detail_description_en' => "Invoice: {$invoice->invoice_no}",
            'detail_description_ur' => "انوائس: {$invoice->invoice_no}",
            'document_number' => "INV-{$invoice->invoice_no}",
        ]);

        // Create Journal Entry - Credit Revenue Account
        $creditEntry = JournalEntry::create([
            'journal_voucher_id' => $journalVoucher->id,
            'debit_detail_account_id' => null,
            'credit_detail_account_id' => $tender->revenue_account_id,
            'debit' => 0,
            'credit' => $invoice->amount,
            'detail_description_en' => "Invoice: {$invoice->invoice_no}",
            'detail_description_ur' => "انوائس: {$invoice->invoice_no}",
            'document_number' => "INV-{$invoice->invoice_no}",
        ]);

        // Post to Account Ledger - Debit
        $subSubSubHeadDebit = SubSubSubHead::find($clientDetail->sub_sub_sub_head_id);
        if ($debitEntry) {
            AccountLedger::create([
                'date' => $invoice->invoice_date->format('Y-m-d'),
                'project_id' => $subSubSubHeadDebit->project_id ?? null,
                'invoice_id' => $journalVoucher->id,
                'party_id' => $clientDetail->party_id ?? null,
                'detail_account_id' => $clientDetail->id,
                'description_en' => "Invoice: {$invoice->invoice_no}",
                'description_ur' => "انوائس: {$invoice->invoice_no}",
                'document_number' => "INV-{$invoice->invoice_no}",
                'debit' => $invoice->amount,
                'credit' => 0,
            ]);
        }

        // Post to Account Ledger - Credit
        $revenueAccount = DetailAccount::find($tender->revenue_account_id);
        $subSubSubHeadCredit = $revenueAccount ? SubSubSubHead::find($revenueAccount->sub_sub_sub_head_id) : null;
        if ($creditEntry && $revenueAccount) {
            AccountLedger::create([
                'date' => $invoice->invoice_date->format('Y-m-d'),
                'project_id' => $subSubSubHeadCredit->project_id ?? null,
                'invoice_id' => $journalVoucher->id,
                'party_id' => $revenueAccount->party_id ?? null,
                'detail_account_id' => $revenueAccount->id,
                'description_en' => "Invoice: {$invoice->invoice_no}",
                'description_ur' => "انوائس: {$invoice->invoice_no}",
                'document_number' => "INV-{$invoice->invoice_no}",
                'debit' => 0,
                'credit' => $invoice->amount,
            ]);
        }

        // Post to General Journal - Debit
        if ($debitEntry) {
            GeneralJournal::create([
                'date' => $invoice->invoice_date->format('Y-m-d'),
                'project_id' => $subSubSubHeadDebit->project_id ?? null,
                'invoice_id' => $journalVoucher->id,
                'party_id' => $clientDetail->party_id ?? null,
                'detail_account_id' => $clientDetail->id,
                'description_en' => "Invoice: {$invoice->invoice_no}",
                'description_ur' => "انوائس: {$invoice->invoice_no}",
                'document_number' => "INV-{$invoice->invoice_no}",
                'debit' => $invoice->amount,
                'credit' => 0,
            ]);
        }

        // Post to General Journal - Credit
        if ($creditEntry && $revenueAccount) {
            GeneralJournal::create([
                'date' => $invoice->invoice_date->format('Y-m-d'),
                'project_id' => $subSubSubHeadCredit->project_id ?? null,
                'invoice_id' => $journalVoucher->id,
                'party_id' => $revenueAccount->party_id ?? null,
                'detail_account_id' => $revenueAccount->id,
                'description_en' => "Invoice: {$invoice->invoice_no}",
                'description_ur' => "انوائس: {$invoice->invoice_no}",
                'document_number' => "INV-{$invoice->invoice_no}",
                'debit' => 0,
                'credit' => $invoice->amount,
            ]);
        }

        return $journalVoucher;
    }

    /**
     * Generate unique voucher number
     */
    private function generateVoucherNumber(): string
    {
        $prefix = 'CI-' . now()->format('Ymd');
        $latest = ClientInvoice::where('invoice_no', 'like', "{$prefix}%")
            ->orderByDesc('id')
            ->first();

        if (!$latest) {
            return "{$prefix}-001";
        }

        $number = (int) substr($latest->invoice_no, -3) + 1;
        return "{$prefix}-" . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Delete invoice (only draft invoices)
     */
    public function delete(ClientInvoice $invoice): bool
    {
        if (!$invoice->canBeEdited()) {
            throw new \Exception(__('messages.cannot-delete-verified-invoice'));
        }

        return $invoice->delete();
    }

    /**
     * Force delete (for admin)
     */
    public function forceDelete(ClientInvoice $invoice): bool
    {
        return $invoice->forceDelete();
    }

    /**
     * Cancel invoice
     */
    public function cancel(ClientInvoice $invoice): ClientInvoice
    {
        if ($invoice->isJVPosted()) {
            throw new \Exception(__('messages.cannot-cancel-posted-invoice'));
        }

        $invoice->update([
            'status' => 'cancelled',
            'updated_by' => Auth::id(),
        ]);

        return $invoice->refresh();
    }

    /**
     * Get available clients for a tender
     */
    public function getAvailableClients($tenderId)
    {
        $tender = Tender::findOrFail($tenderId);

        return DetailAccount::select(['id', 'name_en', 'name_ur', 'party_id'])
            ->with('party')
            ->get()
            ->map(fn($account) => [
                'id' => $account->id,
                'name' => $account->name_en,
                'name_ur' => $account->name_ur,
                'party_id' => $account->party_id,
            ]);
    }

    /**
     * Get invoice for printing
     */
    public function getInvoiceForPrint($id)
    {
        return ClientInvoice::with([
            'tender.constructionSite',
            'tender.contracteeAccount',
            'client',
            'journalVoucher',
            'verifiedBy',
            'receipts.voucher',
            'receipts.createdBy'
        ])->findOrFail($id);
    }

    /**
     * Get invoice with receipts for detailed view
     */
    public function getInvoiceWithReceipts($id)
    {
        return ClientInvoice::with([
            'tender',
            'client',
            'journalVoucher',
            'verifiedBy',
            'receipts.voucher.journalEntries',
            'receipts.createdBy'
        ])->findOrFail($id);
    }
}
