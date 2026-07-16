<?php

namespace App\Http\Controllers\ConstructionModule;

use App\Http\Controllers\Controller;
use App\Models\ContractorBill;
use App\Models\ContractorBillPayment;
use App\Models\DetailAccount;
use App\Services\ContractorPaymentService;
use Illuminate\Http\Request;

class ContractorPaymentController extends Controller
{
    protected $paymentService;

    public function __construct(ContractorPaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Show payment details for a bill
     */
    public function showPaymentDetails($billId)
    {
        try {
            $data = $this->paymentService->getBillPaymentDetails($billId);
            return view('Construction-Module.contractor-bills.payment-details', $data);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show make payment form
     */
    public function makePaymentForm($billId)
    {
        try {
            $paymentData = $this->paymentService->prepareMakePaymentData($billId);
            return view('Construction-Module.contractor-bills.make-payment', compact('paymentData'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Process payment (redirect to create BPV/CPV)
     */
    public function initiatePayment(Request $request, $billId)
    {
        try {
            $bill = ContractorBill::findOrFail($billId);

            if (!$bill->canAcceptPayment()) {
                return back()->with('error', 'This bill cannot accept payments');
            }

            $amount = $request->input('amount');
            $voucherType = $request->input('voucher_type'); // 'BPV' or 'CPV'

            // Validate amount
            $this->paymentService->validatePaymentAmount($billId, $amount);

            // Store payment intent in session for retrieval after voucher creation
            session([
                'payment_intent' => [
                    'bill_id' => $billId,
                    'amount' => $amount,
                    'voucher_type' => $voucherType,
                    'contractor_id' => $bill->contractor_account_id,
                    'tender_id' => $bill->tender_id,
                ]
            ]);

            // Redirect to appropriate voucher creation form
            if ($voucherType === 'BPV') {
                return redirect()->route('bank-payment-voucher.create')
                    ->with('info', 'Please create Bank Payment Voucher for Rs. ' . $amount);
            } else {
                return redirect()->route('cash-payment-voucher.create')
                    ->with('info', 'Please create Cash Payment Voucher for Rs. ' . $amount);
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Record payment after voucher creation
     */
    public function recordPayment(Request $request, $billId)
    {
        try {
            $validated = $request->validate([
                'voucher_id' => 'required|integer',
                'voucher_type' => 'required|in:BPV,CPV',
                'amount' => 'required|numeric|min:0.01',
                'remarks' => 'nullable|string|max:1000',
            ]);

            $payment = $this->paymentService->recordPayment(
                $billId,
                $validated['voucher_id'],
                $validated['voucher_type'],
                $validated['amount'],
                $validated['remarks'] ?? null
            );

            return redirect()->route('contractor-bills.show', $billId)
                ->with('success', 'Payment recorded successfully. Amount: Rs. ' . number_format($payment->amount, 2));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Show payment history for a bill
     */
    public function paymentHistory($billId, Request $request)
    {
        try {
            $bill = ContractorBill::findOrFail($billId);
            $filters = [
                'status' => $request->input('status'),
                'per_page' => $request->input('per_page', 10),
            ];

            $payments = $this->paymentService->getBillPaymentHistory($billId, $filters);

            return view('Construction-Module.contractor-bills.payment-history', compact(
                'bill',
                'payments',
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show all contractor payments
     */
    public function contractorPayments($contractorId, Request $request)
    {
        try {
            $contractor = DetailAccount::findOrFail($contractorId);
            $summary = $this->paymentService->getContractorPaymentSummary($contractorId);

            $filters = [
                'status' => $request->input('status'),
                'from_date' => $request->input('from_date'),
                'to_date' => $request->input('to_date'),
                'voucher_type' => $request->input('voucher_type'),
                'per_page' => $request->input('per_page', 15),
            ];

            $payments = $this->paymentService->getContractorPayments($contractorId, $filters);

            return view('Construction-Module.contractor-bills.contractor-payments', compact(
                'contractor',
                'summary',
                'payments',
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show payment reports
     */
    public function paymentReports(Request $request)
    {
        $filters = [
            'status' => $request->input('status'),
            'contractor_id' => $request->input('contractor_id'),
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
            'voucher_type' => $request->input('voucher_type'),
            'per_page' => $request->input('per_page', 50),
        ];

        $payments = $this->paymentService->getPaymentReport($filters);
        $contractors = DetailAccount::whereHas('contractorBills')
            ->select('id', 'name')
            ->get();

        return view('Construction-Module.contractor-bills.payment-reports', compact(
            'payments',
            'contractors',
        ));
    }

    /**
     * Export payment data to CSV
     */
    public function exportPayments(Request $request)
    {
        try {
            $filters = [
                'contractor_id' => $request->input('contractor_id'),
                'from_date' => $request->input('from_date'),
                'to_date' => $request->input('to_date'),
                'voucher_type' => $request->input('voucher_type'),
            ];

            $payments = $this->paymentService->exportPaymentData($filters);

            $filename = 'contractor-payments-' . now()->format('Y-m-d-H-i-s') . '.csv';
            $handle = fopen('php://memory', 'r+');

            // Add headers
            fputcsv($handle, [
                'Bill No',
                'Contractor',
                'Tender',
                'Amount',
                'Voucher Type',
                'Voucher ID',
                'Payment Date',
                'Status',
            ]);

            // Add data
            foreach ($payments as $payment) {
                fputcsv($handle, [
                    $payment->contractorBill->bill_no,
                    $payment->contractorBill->contractorAccount->name,
                    $payment->contractorBill->tender->title_en,
                    $payment->amount,
                    $payment->voucher_type,
                    $payment->voucher_id,
                    $payment->payment_date?->format('Y-m-d'),
                    $payment->status,
                ]);
            }

            rewind($handle);
            $csv = stream_get_contents($handle);
            fclose($handle);

            return response($csv)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', "attachment; filename=$filename");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Cancel a payment
     */
    public function cancelPayment($paymentId)
    {
        try {
            $payment = ContractorBillPayment::findOrFail($paymentId);
            $billId = $payment->contractor_bill_id;

            $this->paymentService->cancelPayment($paymentId);

            return redirect()->route('contractor-bills.show', $billId)
                ->with('success', 'Payment cancelled successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Get contractor outstanding balance (AJAX)
     */
    public function getOutstandingBalance($contractorId)
    {
        try {
            $outstanding = $this->paymentService->getContractorOutstandingBalance($contractorId);
            $summary = $this->paymentService->getContractorPaymentSummary($contractorId);

            return response()->json([
                'outstanding' => $outstanding,
                'summary' => $summary,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Get bill outstanding amount (AJAX)
     */
    public function getBillOutstanding($billId)
    {
        try {
            $bill = ContractorBill::findOrFail($billId);

            return response()->json([
                'outstanding' => $bill->getOutstandingAmount(),
                'paid' => $bill->getPaidAmount(),
                'total' => $bill->amount,
                'can_accept_payment' => $bill->canAcceptPayment(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
