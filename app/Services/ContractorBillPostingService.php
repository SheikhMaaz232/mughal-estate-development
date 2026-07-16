<?php

namespace App\Services;

use App\Models\ContractorBill;
use App\Models\JournalVoucher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContractorBillPostingService
{
    protected $journalVoucherService;

    public function __construct(JournalVoucherService $journalVoucherService)
    {
        $this->journalVoucherService = $journalVoucherService;
    }

    public function postBill(ContractorBill $bill, ?int $userId = null): ContractorBill
    {
        if ($bill->status !== 'draft') {
            throw new \Exception('Only draft bills may be verified.');
        }

        if ($bill->voucher_id !== null) {
            throw new \Exception('This bill has already been posted.');
        }

        if ($bill->total_amount <= 0) {
            throw new \Exception('Bill amount must be greater than zero before verification.');
        }

        $tender = $bill->tender;
        $expenseAccountId = $tender->expense_account_id ?? null;
        $contractorAccountId = $bill->contractor_account_id;

        if (!$expenseAccountId) {
            throw new \Exception('Tender expense account is not configured.');
        }

        if (!$contractorAccountId) {
            throw new \Exception('Contractor account is not configured.');
        }

        return DB::transaction(function () use ($bill, $userId, $expenseAccountId, $contractorAccountId) {
            $voucherData = [
                'debit_detail_account_id' => [$expenseAccountId],
                'credit_detail_account_id' => [$contractorAccountId],
                'debit' => [$bill->total_amount],
                'credit' => [$bill->total_amount],
                'detail_description_en' => ["Contractor Bill #{$bill->bill_no}"],
                'detail_description_ur' => ["Contractor Bill #{$bill->bill_no}"],
                'voucher_date' => $bill->bill_date->format('Y-m-d'),
                'description' => "Contractor bill verification for {$bill->bill_no}",
                'id' => JournalVoucher::max('id') + 1,
            ];

            $preparedData = $this->journalVoucherService->prepare($voucherData);
            $voucher = $this->journalVoucherService->store($preparedData);

            $bill->update([
                'status' => 'verified',
                'voucher_id' => $voucher->id,
                'verified_by' => $userId ?? Auth::id(),
                'verified_at' => now(),
            ]);

            return $bill->fresh();
        });
    }
}
