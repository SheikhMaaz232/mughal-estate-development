<?php

namespace App\Services;

use App\Models\AccountLedger;
use App\Models\CashPaymentVoucher;
use App\Models\DetailAccount;
use App\Models\SubSubSubHead;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class CashPaymentVoucherService
{
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function getById($id)
    {
        return CashPaymentVoucher::findOrFail($id);
    }


    public function create(array $data): CashPaymentVoucher
    {
        // Handle image uploads directly from $data if present

        if (!empty($data['attachment']) && $data['attachment'] instanceof \Illuminate\Http\UploadedFile) {
            $data['attachment'] = $this->commonService->uploadImage($data['attachment'], 'cashPaymentVouchers');
        }

        // Create the cashPaymentVoucher
        $cashPaymentVoucher = CashPaymentVoucher::create($data);

        return $cashPaymentVoucher;
    }



    public function update(int $id, array $data, $attachmentImage = null): CashPaymentVoucher
    {
        $cashPaymentVoucher = CashPaymentVoucher::findOrFail($id);

        if ($attachmentImage) {
            // Optionally delete the old image
            if ($cashPaymentVoucher->attachment && Storage::disk('public')->exists($cashPaymentVoucher->attachment)) {
                Storage::disk('public')->delete($cashPaymentVoucher->attachment);
            }

            // Upload and store new image
            $data['attachment'] = $this->commonService->uploadImage($attachmentImage, 'cashPaymentVouchers');
        }
        $cashPaymentVoucher->update($data);

        return $cashPaymentVoucher;
    }

    public function delete($id)
    {
        $cashPaymentVoucher = CashPaymentVoucher::findOrFail($id);
        $documentNo = 'CPV' . '-' . $id;
        AccountLedger::where('document_number', $documentNo)->where('invoice_id', $id)->delete();

        //  delete the cashPaymentVoucher
        return $cashPaymentVoucher->delete();
    }

    public function getCashAccountsAndDetailAccount($projectId)
    {
        $field = App::getLocale() === 'ur' ? 'name_ur' : 'name_en';

        $subSubSubHeadIds = SubSubSubHead::where('project_id', $projectId)->pluck('id');

        $coaPayables = DetailAccount::select($field, 'id')
            ->where('main_head_id', 2)
            ->whereIn('sub_sub_sub_head_id', $subSubSubHeadIds)
            ->pluck($field, 'id');

        $coaCashAccounts = DetailAccount::select($field, 'id')
            ->where('sub_sub_head_id', 18)
            ->whereIn('sub_sub_sub_head_id', $subSubSubHeadIds)
            ->pluck($field, 'id');
        return [
            'payables' => $coaPayables,
            'cashAccounts'    => $coaCashAccounts,
        ];
    }

    public function prepareAccountDebitData($request, $voucherParentId)
    {
        $partyId = DetailAccount::where('id', $request['detail_account_id'])->value('party_id');

        return AccountLedger::create([
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'project_id' =>  $request['project_id'],
            'invoice_id' => $voucherParentId,
            'party_id' =>  $partyId ?? null,
            'detail_account_id' =>  $request['detail_account_id'],
            'description_en' => $request['description_en'],
            'description_ur' => $request['description_ur'],
            'document_number' => 'CPV' . '-' . $voucherParentId,
            'debit' => $request['total_amount'],
            'credit' => config('constants.ZERO'),
        ]);
    }

    public function prepareAccountCreditData($request, $voucherParentId)
    {

        return AccountLedger::create([
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'project_id' =>  $request['project_id'],
            'invoice_id' => $voucherParentId,
            'party_id' =>  null,
            'detail_account_id' =>  $request['cash_account_id'],
            'description_en' => $request['description_en'],
            'description_ur' => $request['description_ur'],
            'document_number' => 'CPV' . '-' . $voucherParentId,
            'debit' => config('constants.ZERO'),
            'credit' => $request['total_amount'],
        ]);
    }
}
