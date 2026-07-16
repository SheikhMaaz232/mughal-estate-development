<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Models\ClientInvoice;
use App\Models\ClientInvoiceReceipt;
use App\Models\JournalVoucher;
use App\Services\ClientInvoiceReceiptService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientInvoiceReceiptController extends Controller
{
    protected $receiptService;

    public function __construct(ClientInvoiceReceiptService $receiptService)
    {
        $this->receiptService = $receiptService;
    }

    /**
     * Show receipt history for an invoice
     */
    public function show(ClientInvoice $invoice)
    {
        $receipts = $this->receiptService->getReceiptsForInvoice($invoice->id);

        return view('registration.client-invoices.receipts.show', compact('invoice', 'receipts'));
    }

    /**
     * Show form to link a receipt to an invoice
     */
    public function create(ClientInvoice $invoice)
    {
        // Only allow linking receipts to verified or partially received invoices
        if (!in_array($invoice->status, ['verified', 'partial_received'])) {
            return redirect()
                ->route('client-invoices.show', $invoice)
                ->withError(__('messages.can-only-link-receipts-to-verified-invoices'));
        }

        $availableVouchers = $this->receiptService->getAvailableVouchersForInvoice($invoice);

        return view('registration.client-invoices.receipts.create', compact('invoice', 'availableVouchers'));
    }

    /**
     * Link a voucher as receipt
     */
    public function store(Request $request, ClientInvoice $invoice)
    {
        $request->validate([
            'voucher_id' => 'required|exists:journal_vouchers,id',
            'amount' => 'required|numeric|min:0.01|max:' . $invoice->outstanding_receivable,
        ]);

        try {
            $voucher = JournalVoucher::findOrFail($request->voucher_id);

            $receipt = $this->receiptService->linkReceipt($invoice, $voucher, $request->amount);

            return redirect()
                ->route('client-invoices.receipts.show', $invoice)
                ->with('success', __('messages.receipt-linked-successfully'));
        } catch (\Exception $e) {
            return back()
                ->withError(__('messages.error-linking-receipt') . ': ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove a receipt link
     */
    public function destroy(ClientInvoice $invoice, ClientInvoiceReceipt $receipt)
    {
        // Ensure the receipt belongs to the invoice
        if ($receipt->client_invoice_id !== $invoice->id) {
            abort(404);
        }

        try {
            $this->receiptService->removeReceipt($receipt);

            return redirect()
                ->route('client-invoices.receipts.show', $invoice)
                ->with('success', __('messages.receipt-removed-successfully'));
        } catch (\Exception $e) {
            return back()
                ->withError(__('messages.error-removing-receipt') . ': ' . $e->getMessage());
        }
    }

    /**
     * Get outstanding receivables report
     */
    public function outstandingReceivables(Request $request)
    {
        $filters = [
            'client_id' => $request->input('client_id'),
        ];

        $outstanding = $this->receiptService->getOutstandingReceivables($filters['client_id']);

        return view('registration.client-invoices.receipts.outstanding', compact('outstanding'));
    }

    /**
     * Get receipt history report
     */
    public function history(Request $request)
    {
        $filters = [
            'client_id' => $request->input('client_id'),
            'tender_id' => $request->input('tender_id'),
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
        ];

        $receipts = $this->receiptService->getReceiptHistory($filters);

        return view('registration.client-invoices.receipts.history', compact('receipts'));
    }

    /**
     * API: Get available vouchers for an invoice
     */
    public function getAvailableVouchers(ClientInvoice $invoice)
    {
        try {
            $vouchers = $this->receiptService->getAvailableVouchersForInvoice($invoice);

            return response()->json([
                'success' => true,
                'data' => $vouchers->map(function ($voucher) {
                    return [
                        'id' => $voucher->id,
                        'voucher_no' => $voucher->voucher_no,
                        'date' => $voucher->date->format('Y-m-d'),
                        'description' => $voucher->description_en,
                        'total_amount' => $voucher->total_debit,
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
