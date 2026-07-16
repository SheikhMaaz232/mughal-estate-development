<?php

namespace App\Services;

use App\Models\AccountLedger;
use App\Models\DetailAccount;
use App\Models\SubSubSubHead;
use Illuminate\Support\Carbon;
use App\Models\BankPaymentVoucher;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class BankPaymentVoucherService
{
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function getById($id)
    {
        return BankPaymentVoucher::findOrFail($id);
    }


    public function create(array $data): BankPaymentVoucher
    {
        // Handle image uploads directly from $data if present

        if (!empty($data['attachment']) && $data['attachment'] instanceof \Illuminate\Http\UploadedFile) {
            $data['attachment'] = $this->commonService->uploadImage($data['attachment'], 'bankPaymentVouchers');
        }

        // Create the BankPaymentVoucher
        $bankPaymentVoucher = BankPaymentVoucher::create($data);

        return $bankPaymentVoucher;
    }



    public function update(int $id, array $data, $attachmentImage = null): BankPaymentVoucher
    {
        $bankPaymentVoucher = BankPaymentVoucher::findOrFail($id);

        if ($attachmentImage) {
            // Optionally delete the old image
            if ($bankPaymentVoucher->attachment && Storage::disk('public')->exists($bankPaymentVoucher->attachment)) {
                Storage::disk('public')->delete($bankPaymentVoucher->attachment);
            }

            // Upload and store new image
            $data['attachment'] = $this->commonService->uploadImage($attachmentImage, 'bankPaymentVouchers');
        }
        $bankPaymentVoucher->update($data);

        return $bankPaymentVoucher;
    }

    public function delete($id)
    {
        $bankPaymentVoucher = BankPaymentVoucher::findOrFail($id);
        $documentNo = 'BPV' . '-' . $id;
        AccountLedger::where('document_number', $documentNo)->where('invoice_id', $id)->delete();

        //  delete the bankPaymentVoucher
        return $bankPaymentVoucher->delete();
    }

    public function getBankAndDetailAccount($projectId)
    {
        $field = App::getLocale() === 'ur' ? 'name_ur' : 'name_en';

        $subSubSubHeadIds = SubSubSubHead::where('project_id', $projectId)->pluck('id');

        $coaPayables = DetailAccount::select($field, 'id')
            ->where('main_head_id', 2)
            ->whereIn('sub_sub_sub_head_id', $subSubSubHeadIds)
            ->pluck($field, 'id');

        $coaBanks = DetailAccount::select($field, 'id')
            ->where('sub_sub_head_id', 19)
            ->whereIn('sub_sub_sub_head_id', $subSubSubHeadIds)
            ->pluck($field, 'id');
        return [
            'payables' => $coaPayables,
            'banks'    => $coaBanks,
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
            'document_number' => 'BPV' . '-' . $voucherParentId,
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
            'detail_account_id' =>  $request['bank_id'],
            'description_en' => $request['description_en'],
            'description_ur' => $request['description_ur'],
            'document_number' => 'BPV' . '-' . $voucherParentId,
            'debit' => config('constants.ZERO'),
            'credit' => $request['total_amount'],
        ]);
    }
}
