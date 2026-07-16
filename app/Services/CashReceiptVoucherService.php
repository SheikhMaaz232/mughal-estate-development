<?php

namespace App\Services;

use App\Models\AccountLedger;
use App\Models\CashReceiptVoucher;
use App\Models\DetailAccount;
use App\Models\GeneralJournal;
use App\Models\SubSubSubHead;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class CashReceiptVoucherService
{
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function getById($id)
    {
        return CashReceiptVoucher::findOrFail($id);
    }


    public function create(array $data): CashReceiptVoucher
    {
        // Handle image uploads directly from $data if present

        if (!empty($data['attachment']) && $data['attachment'] instanceof \Illuminate\Http\UploadedFile) {
            $data['attachment'] = $this->commonService->uploadImage($data['attachment'], 'cashReceiptVouchers');
        }

        // Create the cashReceiptVoucher
        $cashReceiptVoucher = CashReceiptVoucher::create($data);

        return $cashReceiptVoucher;
    }



    public function update(int $id, array $data, $attachmentImage = null): CashReceiptVoucher
    {
        $cashReceiptVoucher = CashReceiptVoucher::findOrFail($id);

        if ($attachmentImage) {
            // Optionally delete the old image
            if ($cashReceiptVoucher->attachment && Storage::disk('public')->exists($cashReceiptVoucher->attachment)) {
                Storage::disk('public')->delete($cashReceiptVoucher->attachment);
            }

            // Upload and store new image
            $data['attachment'] = $this->commonService->uploadImage($attachmentImage, 'cashReceiptVouchers');
        }
        $cashReceiptVoucher->update($data);

        return $cashReceiptVoucher;
    }

    public function delete($id)
    {
        $cashReceiptVoucher = CashReceiptVoucher::findOrFail($id);
        $documentNo = 'CRV' . '-' . $id;
        AccountLedger::where('document_number', $documentNo)->where('invoice_id', $id)->delete();
        //  delete the cashReceiptVoucher
        return $cashReceiptVoucher->delete();
    }

    public function getCashAccountsAndDetailAccount($projectId)
    {
        $field = App::getLocale() === 'ur' ? 'name_ur' : 'name_en';

        $subSubSubHeadIds = SubSubSubHead::where('project_id', $projectId)->pluck('id');

        $coaReceivables = DetailAccount::select($field, 'id')
            ->where('sub_head_id', 1)
            ->whereIn('sub_sub_sub_head_id', $subSubSubHeadIds)
            ->pluck($field, 'id');

        $coaCashAccounts = DetailAccount::select($field, 'id')
            ->where('sub_sub_head_id', 18)
            ->whereIn('sub_sub_sub_head_id', $subSubSubHeadIds)
            ->pluck($field, 'id');
        return [
            'receivables' => $coaReceivables,
            'cashAccounts'    => $coaCashAccounts,
        ];
    }

    public function prepareAccountDebitData($request, $voucherParentId)
    {
        $data = [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'project_id' =>  $request['project_id'],
            'invoice_id' => $voucherParentId,
            'party_id' =>  null,
            'detail_account_id' =>  $request['cash_account_id'],
            'description_en' => $request['description_en'],
            'description_ur' => $request['description_ur'],
            'document_number' => 'CRV' . '-' . $voucherParentId,
            'debit' => $request['total_amount'],
            'credit' => config('constants.ZERO'),
            'transaction_type' =>  null,
            'is_fee_entry' => 0,
        ];
        AccountLedger::create($data);
        GeneralJournal::create($data);
    }

    public function prepareAccountCreditData($request, $voucherParentId)
    {
        $partyId = DetailAccount::where('id', $request['detail_account_id'])->value('party_id');
        $data = [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'project_id' =>  $request['project_id'],
            'invoice_id' => $voucherParentId,
            'party_id' =>  $partyId ?? null,
            'detail_account_id' => $request['detail_account_id'],
            'description_en' => $request['description_en'],
            'description_ur' => $request['description_ur'],
            'document_number' => 'CRV' . '-' . $voucherParentId,
            'debit' => config('constants.ZERO'),
            'credit' => $request['total_amount'],
            'transaction_type' => $request['transaction_type'] ?? null,
            'is_fee_entry' => 0,
        ];
        AccountLedger::create($data);
        GeneralJournal::create($data);
    }
}
