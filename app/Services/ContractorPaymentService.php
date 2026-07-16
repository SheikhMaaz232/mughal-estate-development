<?php

namespace App\Services;

use App\Models\ContractorBill;
use App\Models\ContractorBillPayment;
use App\Models\DetailAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContractorPaymentService
{
    /**
     * Get payment details for a bill
     */
    public function getBillPaymentDetails($billId)
    {
        $bill = ContractorBill::with(['payments', 'contractorAccount', 'tender'])
            ->findOrFail($billId);

        return [
            'bill' => $bill,
            'paid_amount' => $bill->getPaidAmount(),
            'outstanding_amount' => $bill->getOutstandingAmount(),
            'posted_payments' => $bill->getPostedPayments(),
            'pending_payments' => $bill->getPendingPayments(),
            'payment_status' => $bill->status,
            'can_accept_payment' => $bill->canAcceptPayment(),
            'payments' => $bill->getPaymentHistory(),
        ];
    }

    /**
     * Prepare data for make payment form
     */
    public function prepareMakePaymentData($billId)
    {
        $bill = ContractorBill::with(['contractorAccount', 'tender'])
            ->findOrFail($billId);

        if (!$bill->canAcceptPayment()) {
            throw new \Exception('This bill cannot accept payments. Status: ' . $bill->status);
        }

        return [
            'bill_id' => $bill->id,
            'bill_no' => $bill->bill_no,
            'contractor_id' => $bill->contractor_account_id,
            'contractor_name' => app()->getLocale() === 'ur' ? ($bill->contractorAccount->party->name_ur ?? 'N/A') : ($bill->contractorAccount->party->name_en ?? 'N/A'),
            'tender_id' => $bill->tender_id,
            'tender_name' => app()->getLocale() === 'ur' ? ($bill->tender->title_ur ?? 'N/A') : ($bill->tender->title_en ?? 'N/A'),
            'bill_amount' => $bill->total_amount,
            'paid_amount' => $bill->getPaidAmount(),
            'outstanding_amount' => $bill->getOutstandingAmount(),
            'max_payment_amount' => $bill->getOutstandingAmount(),
        ];
    }

    /**
     * Create payment record linking to voucher
     */
    public function recordPayment($billId, $voucherId, $voucherType, $amount, $remarks = null)
    {
        return DB::transaction(function () use ($billId, $voucherId, $voucherType, $amount, $remarks) {
            $bill = ContractorBill::findOrFail($billId);

            // Validate payment amount
            $outstanding = $bill->getOutstandingAmount();
            if ($amount > $outstanding) {
                throw new \Exception("Payment amount exceeds outstanding balance. Outstanding: {$outstanding}");
            }

            // Create payment record
            $payment = ContractorBillPayment::create([
                'contractor_bill_id' => $billId,
                'voucher_id' => $voucherId,
                'voucher_type' => $voucherType,
                'amount' => $amount,
                'payment_date' => now(),
                'payment_method' => strtolower($voucherType),
                'remarks' => $remarks,
                'status' => 'posted',
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            // Update bill payment status
            $bill->updatePaymentStatus();

            return $payment;
        });
    }

    /**
     * Get contractor outstanding balance across all bills
     */
    public function getContractorOutstandingBalance($contractorId)
    {
        return ContractorBill::where('contractor_account_id', $contractorId)
            ->where('status', '!=', 'draft')
            ->where('status', '!=', 'cancelled')
            ->get()
            ->sum(function ($bill) {
                return $bill->getOutstandingAmount();
            });
    }

    /**
     * Get contractor payment summary
     */
    public function getContractorPaymentSummary($contractorId)
    {
        $bills = ContractorBill::where('contractor_account_id', $contractorId)
            ->where('status', '!=', 'draft')
            ->where('status', '!=', 'cancelled')
            ->with('payments')
            ->get();

        $totalBillAmount = $bills->sum('amount');
        $totalPaidAmount = $bills->sum(function ($bill) {
            return $bill->getPaidAmount();
        });
        $totalOutstanding = $bills->sum(function ($bill) {
            return $bill->getOutstandingAmount();
        });

        return [
            'total_bills' => $bills->count(),
            'total_bill_amount' => $totalBillAmount,
            'total_paid_amount' => $totalPaidAmount,
            'total_outstanding' => $totalOutstanding,
            'payment_percentage' => $totalBillAmount > 0 ? ($totalPaidAmount / $totalBillAmount) * 100 : 0,
        ];
    }

    /**
     * Get all payments for a contractor
     */
    public function getContractorPayments($contractorId, $filters = [])
    {
        $query = ContractorBillPayment::whereHas('contractorBill', function ($q) use ($contractorId) {
            $q->where('contractor_account_id', $contractorId);
        });

        // Apply filters
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['from_date'])) {
            $query->whereDate('payment_date', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('payment_date', '<=', $filters['to_date']);
        }

        if (!empty($filters['voucher_type'])) {
            $query->where('voucher_type', $filters['voucher_type']);
        }

        return $query->with(['contractorBill', 'createdBy'])
            ->orderBy('payment_date', 'desc')
            ->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get payment history for a bill
     */
    public function getBillPaymentHistory($billId, $filters = [])
    {
        $query = ContractorBillPayment::where('contractor_bill_id', $billId);

        // Apply filters
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->with(['createdBy'])
            ->orderBy('payment_date', 'desc')
            ->paginate($filters['per_page'] ?? 10);
    }

    /**
     * Cancel a payment
     */
    public function cancelPayment($paymentId)
    {
        return DB::transaction(function () use ($paymentId) {
            $payment = ContractorBillPayment::findOrFail($paymentId);

            if (!$payment->canCancel()) {
                throw new \Exception('Only pending payments can be cancelled');
            }

            $payment->markAsCancelled();

            // Update bill status
            $payment->contractorBill->updatePaymentStatus();

            return $payment;
        });
    }

    /**
     * Get contractor payment report
     */
    public function getPaymentReport($filters = [])
    {
        $query = ContractorBillPayment::query()
            ->with(['contractorBill.contractorAccount', 'contractorBill.tender', 'createdBy']);

        // Apply filters
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['contractor_id'])) {
            $query->whereHas('contractorBill', function ($q) use ($filters) {
                $q->where('contractor_account_id', $filters['contractor_id']);
            });
        }

        if (!empty($filters['from_date'])) {
            $query->whereDate('payment_date', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('payment_date', '<=', $filters['to_date']);
        }

        if (!empty($filters['voucher_type'])) {
            $query->where('voucher_type', $filters['voucher_type']);
        }

        return $query->orderBy('payment_date', 'desc')
            ->paginate($filters['per_page'] ?? 50);
    }

    /**
     * Export payment data
     */
    public function exportPaymentData($filters = [])
    {
        $payments = ContractorBillPayment::query()
            ->with(['contractorBill.contractorAccount', 'contractorBill.tender'])
            ->where('status', '!=', 'cancelled')
            ->get();

        // Apply filters
        if (!empty($filters['contractor_id'])) {
            $payments = $payments->filter(function ($p) use ($filters) {
                return $p->contractorBill->contractor_account_id == $filters['contractor_id'];
            });
        }

        if (!empty($filters['from_date'])) {
            $payments = $payments->filter(function ($p) use ($filters) {
                return $p->payment_date >= $filters['from_date'];
            });
        }

        if (!empty($filters['to_date'])) {
            $payments = $payments->filter(function ($p) use ($filters) {
                return $p->payment_date <= $filters['to_date'];
            });
        }

        return $payments;
    }

    /**
     * Validate payment amount
     */
    public function validatePaymentAmount($billId, $amount)
    {
        $bill = ContractorBill::findOrFail($billId);
        $outstanding = $bill->getOutstandingAmount();

        if ($amount <= 0) {
            throw new \Exception('Payment amount must be greater than 0');
        }

        if ($amount > $outstanding) {
            throw new \Exception("Payment amount cannot exceed outstanding balance of {$outstanding}");
        }

        return true;
    }
}
