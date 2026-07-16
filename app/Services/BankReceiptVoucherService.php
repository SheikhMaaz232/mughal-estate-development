<?php

namespace App\Services;

use App\Models\AccountLedger;
use App\Models\BankReceiptVoucher;
use App\Models\DetailAccount;
use App\Models\GeneralJournal;
use App\Models\SubSubSubHead;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class BankReceiptVoucherService
{
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function getById($id)
    {
        return BankReceiptVoucher::findOrFail($id);
    }


    public function create(array $data): BankReceiptVoucher
    {
        // Handle image uploads directly from $data if present

        if (!empty($data['attachment']) && $data['attachment'] instanceof \Illuminate\Http\UploadedFile) {
            $data['attachment'] = $this->commonService->uploadImage($data['attachment'], 'bankReceiptVouchers');
        }

        // Create the bankReceiptVoucher
        $bankReceiptVoucher = BankReceiptVoucher::create($data);

        return $bankReceiptVoucher;
    }



    public function update(int $id, array $data, $attachmentImage = null): BankReceiptVoucher
    {
        $bankReceiptVoucher = BankReceiptVoucher::findOrFail($id);

        if ($attachmentImage) {
            // Optionally delete the old image
            if ($bankReceiptVoucher->attachment && Storage::disk('public')->exists($bankReceiptVoucher->attachment)) {
                Storage::disk('public')->delete($bankReceiptVoucher->attachment);
            }

            // Upload and store new image
            $data['attachment'] = $this->commonService->uploadImage($attachmentImage, 'bankReceiptVouchers');
        }
        $bankReceiptVoucher->update($data);

        return $bankReceiptVoucher;
    }

    public function delete($id)
    {
        $bankReceiptVoucher = BankReceiptVoucher::findOrFail($id);
        $documentNo = 'BRV' . '-' . $id;
        AccountLedger::where('document_number', $documentNo)->where('invoice_id', $id)->delete();
        //  delete the bankReceiptVoucher
        return $bankReceiptVoucher->delete();
    }

    public function getBankAndDetailAccount($projectId)
    {
        $field = App::getLocale() === 'ur' ? 'name_ur' : 'name_en';

        $subSubSubHeadIds = SubSubSubHead::where('project_id', $projectId)->pluck('id');

        $coaReceivables = DetailAccount::select($field, 'id')
            ->where('sub_head_id', 1)
            ->whereIn('sub_sub_sub_head_id', $subSubSubHeadIds)
            ->pluck($field, 'id');

        $coaBanks = DetailAccount::select($field, 'id')
            ->where('sub_sub_head_id', 19)
            ->whereIn('sub_sub_sub_head_id', $subSubSubHeadIds)
            ->pluck($field, 'id');
        return [
            'receivables' => $coaReceivables,
            'banks'    => $coaBanks,
        ];
    }

    public function prepareAccountDebitData($request, $voucherParentId)
    {
        $data = [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'project_id' => $request['project_id'],
            'invoice_id' => $voucherParentId,
            'party_id' => null,
            'detail_account_id' => $request['bank_id'],
            'description_en' => $request['description_en'],
            'description_ur' => $request['description_ur'],
            'document_number' => 'BRV-' . $voucherParentId,
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
        $partyId = DetailAccount::where('id', $request['detail_account_id'])
            ->value('party_id');


        $data = [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'project_id' => $request['project_id'],
            'invoice_id' => $voucherParentId,
            'party_id' => $partyId ?? null,
            'detail_account_id' => $request['detail_account_id'],
            'description_en' => $request['description_en'],
            'description_ur' => $request['description_ur'],
            'document_number' => 'BRV-' . $voucherParentId,
            'debit' => config('constants.ZERO'),
            'credit' => $request['total_amount'],
            'transaction_type' => $request['transaction_type'] ?? null,
            'is_fee_entry' => 0,
        ];

        AccountLedger::create($data);

        GeneralJournal::create($data);
    }
}
