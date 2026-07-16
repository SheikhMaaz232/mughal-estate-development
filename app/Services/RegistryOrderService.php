<?php

namespace App\Services;

use App\Models\AccountLedger;
use App\Models\BookingApplication;
use App\Models\DetailAccount;
use App\Models\GeneralJournal;
use App\Models\Party;
use App\Models\Product;
use App\Models\Project;
use App\Models\RegistryOrder;
use App\Services\CommonService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RegistryOrderService
{
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function getById($id)
    {
        return RegistryOrder::findOrFail($id);
    }


    public function create(array $data): RegistryOrder
    {
        return DB::transaction(function () use ($data) {

            // Create Registry Order
            $registryOrder = RegistryOrder::create($data);

            //2. Prepare values
            $registryFees       = $data['registry_fees'] ?? 0;
            $receivableAccount  = $data['registry_fees_receivable_account'] ?? null;

            $projectId = BookingApplication::where('id', $data['booking_id'])->value('project_id');
            $partyId        = BookingApplication::where('id', $data['booking_id'])->value('party_id');
            $customerAccount =  BookingApplication::where('id', $data['booking_id'])->value('detail_account_id');
            $productId =  BookingApplication::where('id', $data['booking_id'])->value('product_id');
            $productNameEN = Product::where('id', $productId)->value('name_en');
            $productNameUR = Product::where('id', $productId)->value('name_ur');

            $productAccount = DetailAccount::where('project_id', $projectId)->where('name_en', $productNameEN)->value('id');
            $projectNameEN = Project::where('id', $projectId)->value('name_en');
            $projectNameUR = Project::where('id', $projectId)->value('name_ur');
            $partyNameEN   = Party::where('id', $partyId)->value('name_en');
            $partyNameUR   = Party::where('id', $partyId)->value('name_ur');

            // 3. Insert 4 Entries (only when completed)
            if (
                $registryFees > 0 &&
                $receivableAccount &&
                $productAccount &&
                $customerAccount
            ) {

                $entries = [

                    //  Debit Customer
                    [
                        'date' => $registryOrder->date,
                        'project_id' => $projectId,
                        'invoice_id' => $registryOrder->id,
                        'party_id' => $partyId,
                        'detail_account_id' => $customerAccount,
                        'description_en' => "Registry fees for {$partyNameEN} for {$productNameEN} in {$projectNameEN}",
                        'description_ur' => "{$projectNameUR} میں {$partyNameUR} کے لیے {$productNameUR} کی رجسٹری فیس",
                        'document_number' => 'R-L-' . $registryOrder->id,
                        'debit' => $registryFees,
                        'credit' => 0,
                        'is_fee_entry' => 0,
                        'transaction_type' => 'registry_fees',

                    ],

                    // Credit Product
                    [
                        'date' => $registryOrder->date,
                        'project_id' => $projectId,
                        'invoice_id' => $registryOrder->id,
                        'party_id' => null,
                        'detail_account_id' => $productAccount,
                        'description_en' => "Registry fees for {$partyNameEN} for {$productNameEN} in {$projectNameEN}",
                        'description_ur' => "{$projectNameUR} میں {$partyNameUR} کے لیے {$productNameUR} کی رجسٹری فیس",
                        'document_number' => 'R-L-' . $registryOrder->id,
                        'debit' => 0,
                        'credit' => $registryFees,
                        'is_fee_entry' => 0,
                        'transaction_type' => null,
                    ],

                    //  Debit Product
                    [
                        'date' => $registryOrder->date,
                        'project_id' => $projectId,
                        'invoice_id' => $registryOrder->id,
                        'party_id' => null,
                        'detail_account_id' => $productAccount,
                        'description_en' => "Registry Fees Adjustment for {$partyNameEN}",
                        'description_ur' => "{$partyNameUR} کے لیے رجسٹری فیس ایڈجسٹمنٹ",
                        'document_number' => 'R-L-' . $registryOrder->id,
                        'debit' => $registryFees,
                        'credit' => 0,
                        'is_fee_entry' => 0,
                        'transaction_type' => null,
                    ],

                    // Credit Receivable Account
                    [
                        'date' => $registryOrder->date,
                        'project_id' => $projectId,
                        'invoice_id' => $registryOrder->id,
                        'party_id' => null,
                        'detail_account_id' => $receivableAccount,
                        'description_en' => "Registry fees of {$productNameEN} of {$projectNameEN} receivable from {$partyNameEN}",
                        'description_ur' => "{$projectNameUR} میں {$partyNameUR} سے {$productNameUR} کی  رجسٹری فیس قابل وصول",
                        'document_number' => 'R-L-' . $registryOrder->id,
                        'debit' => 0,
                        'credit' => $registryFees,
                        'is_fee_entry' => 0,
                        'transaction_type' => null,
                    ],
                ];

                AccountLedger::insert($entries);
                GeneralJournal::insert($entries);
            }

            return $registryOrder;
        });
    }

    public function update(array $data, $id): RegistryOrder
    {
        return DB::transaction(function () use ($data, $id) {

            $registryOrder = RegistryOrder::findOrFail($id);

            $projectId = BookingApplication::where('id', $data['booking_id'])->value('project_id');
            $partyId        = BookingApplication::where('id', $data['booking_id'])->value('party_id');
            $customerAccount =  BookingApplication::where('id', $data['booking_id'])->value('detail_account_id');
            $productId =  BookingApplication::where('id', $data['booking_id'])->value('product_id');
            $productNameEN = Product::where('id', $productId)->value('name_en');
            $productNameUR = Product::where('id', $productId)->value('name_ur');

            $productAccount = DetailAccount::where('project_id', $projectId)->where('name_en', $productNameEN)->value('id');
            $projectNameEN = Project::where('id', $projectId)->value('name_en');
            $projectNameUR = Project::where('id', $projectId)->value('name_ur');
            $partyNameEN   = Party::where('id', $partyId)->value('name_en');
            $partyNameUR   = Party::where('id', $partyId)->value('name_ur');

            //  1. Delete old entries
            AccountLedger::where('invoice_id', $registryOrder->id)
                ->where('document_number', 'R-L-' . $registryOrder->id)
                ->delete();

            GeneralJournal::where('invoice_id', $registryOrder->id)
                ->where('document_number', 'R-L-' . $registryOrder->id)
                ->delete();

            //  2. Update main record
            $registryOrder->update($data);

            //  3. Prepare values
            $registryFees = $data['registry_fees'] ?? 0;
            $receivableAccount = $data['registry_fees_receivable_account'] ?? null;


            //  4. Insert 4 entries
            if (
                $registryFees > 0 &&
                $receivableAccount &&
                $productAccount &&
                $customerAccount
            ) {

                $entries = [
                    //  Debit Customer
                    [
                        'date' => $registryOrder->date,
                        'project_id' => $projectId,
                        'invoice_id' => $registryOrder->id,
                        'party_id' => $partyId,
                        'detail_account_id' => $customerAccount,
                        'description_en' => "Registry fees for {$partyNameEN} for {$productNameEN} in {$projectNameEN}",
                        'description_ur' => "{$projectNameUR} میں {$partyNameUR} کے لیے {$productNameUR} کی رجسٹری فیس",
                        'document_number' => 'R-L-' . $registryOrder->id,
                        'debit' => $registryFees,
                        'credit' => 0,
                        'is_fee_entry' => 0,
                        'transaction_type' => 'registry_fees',
                    ],

                    // Credit Product
                    [
                        'date' => $registryOrder->date,
                        'project_id' => $projectId,
                        'invoice_id' => $registryOrder->id,
                        'party_id' => null,
                        'detail_account_id' => $productAccount,
                        'description_en' => "Registry fees for {$partyNameEN} for {$productNameEN} in {$projectNameEN}",
                        'description_ur' => "{$projectNameUR} میں {$partyNameUR} کے لیے {$productNameUR} کی رجسٹری فیس",
                        'document_number' => 'R-L-' . $registryOrder->id,
                        'debit' => 0,
                        'credit' => $registryFees,
                        'is_fee_entry' => 0,
                        'transaction_type' => null,
                    ],

                    // Debit Product
                    [
                        'date' => $registryOrder->date,
                        'project_id' => $projectId,
                        'invoice_id' => $registryOrder->id,
                        'party_id' => null,
                        'detail_account_id' => $productAccount,
                        'description_en' => "Registry Fees Adjustment for {$partyNameEN}",
                        'description_ur' => "{$partyNameUR} کے لیے رجسٹری فیس ایڈجسٹمنٹ",
                        'document_number' => 'R-L-' . $registryOrder->id,
                        'debit' => $registryFees,
                        'credit' => 0,
                        'is_fee_entry' => 0,
                        'transaction_type' => null,
                    ],

                    //  Credit Receivable Account
                    [
                        'date' =>   $registryOrder->date,
                        'project_id' => $projectId,
                        'invoice_id' => $registryOrder->id,
                        'party_id' => null,
                        'detail_account_id' => $receivableAccount,
                        'description_en' => "Registry fees of {$productNameEN} of {$projectNameEN} receivable from {$partyNameEN}",
                        'description_ur' => "{$projectNameUR} میں {$partyNameUR} سے {$productNameUR} کی  رجسٹری فیس قابل وصول",
                        'document_number' => 'R-L-' . $registryOrder->id,
                        'debit' => 0,
                        'credit' => $registryFees,
                        'is_fee_entry' => 0,
                        'transaction_type' => null,
                    ],
                ];

                AccountLedger::insert($entries);
                GeneralJournal::insert($entries);
            }

            return $registryOrder;
        });
    }



    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $registryOrder = RegistryOrder::findOrFail($id);

            $documentNumber = 'R-L-' . $registryOrder->id;

            // Delete related ledger entries
            AccountLedger::where('invoice_id', $registryOrder->id)
                ->where('document_number', $documentNumber)
                ->delete();

            // Delete journal entries
            GeneralJournal::where('invoice_id', $registryOrder->id)
                ->where('document_number', $documentNumber)
                ->delete();

            // Delete main record
            $registryOrder->delete();

            DB::commit();

            return true; // or return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();

            // Log error for debugging
            Log::error('Registry Order Delete Error: ' . $e->getMessage());

            return false; // or return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
}
