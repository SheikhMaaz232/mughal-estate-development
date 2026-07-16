<?php

namespace App\Services;

use App\Models\ClientInvoice;
use App\Models\ClientInvoiceReceipt;
use App\Models\JournalVoucher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientInvoiceReceiptService
{
    /**
     * Link a voucher to an invoice as a receipt
     */
    public function linkReceipt(ClientInvoice $invoice, JournalVoucher $voucher, float $amount): ClientInvoiceReceipt
    {
        DB::beginTransaction();

        try {
            // Create the receipt record
            $receipt = ClientInvoiceReceipt::create([
                'client_invoice_id' => $invoice->id,
                'voucher_id' => $voucher->id,
                'amount' => $amount,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            // Update invoice status based on receipts
            $invoice->updateStatusBasedOnReceipts();

            DB::commit();

            return $receipt;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get receipts for an invoice
     */
    public function getReceiptsForInvoice(int $invoiceId)
    {
        return ClientInvoiceReceipt::with(['voucher', 'createdBy'])
            ->forInvoice($invoiceId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get outstanding receivables for a client
     */
    public function getOutstandingReceivables(int $clientId = null)
    {
        $query = ClientInvoice::with(['tender', 'receipts'])
            ->whereIn('status', ['verified', 'partial_received'])
            ->whereRaw('(amount - (SELECT COALESCE(SUM(amount), 0) FROM client_invoice_receipts WHERE client_invoice_id = client_invoices.id)) > 0');

        if ($clientId) {
            $query->where('client_id', $clientId);
        }

        return $query->get()->map(function ($invoice) {
            return [
                'invoice' => $invoice,
                'outstanding_amount' => $invoice->outstanding_receivable,
                'total_received' => $invoice->total_received,
            ];
        });
    }

    /**
     * Get receipt history for reporting
     */
    public function getReceiptHistory(array $filters = [])
    {
        $query = ClientInvoiceReceipt::with([
            'clientInvoice.tender',
            'clientInvoice.client',
            'voucher',
            'createdBy'
        ]);

        // Apply filters
        if (!empty($filters['client_id'])) {
            $query->whereHas('clientInvoice', function ($q) use ($filters) {
                $q->where('client_id', $filters['client_id']);
            });
        }

        if (!empty($filters['tender_id'])) {
            $query->whereHas('clientInvoice', function ($q) use ($filters) {
                $q->where('tender_id', $filters['tender_id']);
            });
        }

        if (!empty($filters['from_date'])) {
            $query->whereDate('created_at', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('created_at', '<=', $filters['to_date']);
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    /**
     * Remove a receipt link
     */
    public function removeReceipt(ClientInvoiceReceipt $receipt): bool
    {
        DB::beginTransaction();

        try {
            $invoice = $receipt->clientInvoice;

            $receipt->delete();

            // Update invoice status
            $invoice->updateStatusBasedOnReceipts();

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get available vouchers for linking (BRV/CRV that match the client)
     */
    public function getAvailableVouchersForInvoice(ClientInvoice $invoice)
    {
        // Get BRV and CRV vouchers that have the client as the debit account
        return JournalVoucher::with(['journalEntries'])
            ->whereHas('journalEntries', function ($query) use ($invoice) {
                $query->where('debit_account_id', $invoice->client->detail_account_id ?? null);
            })
            ->whereDoesntHave('clientInvoiceReceipts') // Not already linked
            ->orderBy('date', 'desc')
            ->get()
            ->filter(function ($voucher) {
                // Additional filtering can be added here
                return true;
            });
    }
}
