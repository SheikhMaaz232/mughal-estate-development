<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Unit;
use App\Models\Party;
use App\Models\Product;
use App\Models\Project;
use App\Models\StockLedger;
use App\Models\AccountLedger;
use App\Models\DetailAccount;
use App\Models\GeneralJournal;
use App\Models\PurchaseDetail;
use App\Models\PurchaseMaster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

class PurchaseService
{

    public function getById($id)
    {
        return PurchaseMaster::findOrFail($id);
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {

            $master = PurchaseMaster::create([
                'grn_no'             => $data['grn_no'],
                'purchase_order_no'  => $data['purchase_order_no'],
                'date'               => $data['date'],
                'project_id'         => $data['project_id'],
                'party_id'           => $data['party_id'],
                'detail_account_id'  => $data['detail_account_id'],
                'supplier_bill_no'   => $data['supplier_bill_no'],
                'status' => $data['status'],
                'unloaded_by'        => $data['unloaded_by'],
                'carriage'           => $data['carriage'] ?? null,
                'gross_bill'         => $data['gross_bill'],
                'other_amount'         => $data['other_amount'],
                'tax'                => $data['tax'] ?? null,
                'net_amount'         => $data['net_amount'],
                'total_quantity'     => $data['total_quantity'],
                'remarks_en'            => $data['remarks_en'] ?? null,
                'remarks_ur'            => $data['remarks_ur'] ?? null,
            ]);

            foreach ($data['product_id'] as $index => $productId) {

                PurchaseDetail::create([
                    'purchase_master_id' => $master->id,
                    'product_id'         => $productId,
                    'quantity'           => $data['quantity'][$index],
                    'price'              => $data['price'][$index],
                    'amount'             => $data['amount'][$index],
                    'detail_remarks_en'            => $data['detail_remarks_en'][$index] ?? null,
                    'detail_remarks_ur'            => $data['detail_remarks_ur'][$index] ?? null,
                ]);
            }

            return $master;
        });
    }

    public function getPurchaseDetails($id)
    {
        return PurchaseDetail::where('purchase_master_id', $id)
            ->whereNull('deleted_at')
            ->get();
    }

    /**
     * Update Purchase Invoice (Master + Details)
     */
    public function update(array $data, $master)
    {
        return DB::transaction(function () use ($data, $master) {


            $master->update([
                'grn_no'             => $data['grn_no'],
                'purchase_order_no'  => $data['purchase_order_no'],
                'date'               => $data['date'],
                'project_id'         => $data['project_id'],
                'party_id'           => $data['party_id'],
                'detail_account_id'  => $data['detail_account_id'],
                'supplier_bill_no'   => $data['supplier_bill_no'],
                'unloaded_by'        => $data['unloaded_by'],
                'carriage'           => $data['carriage'] ?? null,
                'gross_bill'         => $data['gross_bill'],
                'tax'                => $data['tax'] ?? null,
                'net_amount'         => $data['net_amount'],
                'other_amount'         => $data['other_amount'],
                'total_quantity'     => $data['total_quantity'],
                'remarks_en'            => $data['remarks_en'] ?? null,
                'remarks_ur'            => $data['remarks_ur'] ?? null,
            ]);

            $master->details()->delete();

            foreach ($data['product_id'] as $index => $productId) {

                PurchaseDetail::create([
                    'purchase_master_id' => $master->id,
                    'product_id'         => $productId,
                    'quantity'           => $data['quantity'][$index],
                    'price'              => $data['price'][$index],
                    'amount'             => $data['amount'][$index],
                    'detail_remarks_en'            => $data['detail_remarks_en'][$index] ?? null,
                    'detail_remarks_ur'            => $data['detail_remarks_ur'][$index] ?? null,
                ]);
            }

            return $master;
        });
    }


    public function createLedgerEntry($purchaseMaster, $PurchaseDetail): void
    {

        $dealerMainParty = DetailAccount::where('id', $purchaseMaster->dealer_id)->value('party_id');
        $productNameEN = Product::where('id', $purchaseMaster->product_id)->value('name_en');
        $productNameUR = Product::where('id', $purchaseMaster->product_id)->value('name_ur');
        $projectNameEN = Project::where('id', $purchaseMaster->project_id)->value('name_en');
        $projectNameUR = Project::where('id', $purchaseMaster->project_id)->value('name_ur');
        $partyNameEN = Party::where('id', $purchaseMaster->party_id)->value('name_en');
        $partyNameUR = Party::where('id', $purchaseMaster->party_id)->value('name_ur');

        $creditData = [
            'date' => $purchaseMaster->date,
            'project_id' => $purchaseMaster->project_id,
            'invoice_id' => $purchaseMaster->id,
            'party_id' => $purchaseMaster->party_id,
            'detail_account_id' => $purchaseMaster->detail_account_id,
            'description_en' => $purchaseMaster->remarks_en ?? '',
            'description_ur' => $purchaseMaster->remarks_ur ?? '',
            'document_number' => 'P-I' . '-' . $purchaseMaster->id,
            'debit' => 0,
            'credit' => $purchaseMaster->gross_bill,
            'transaction_type' => null,
            'is_fee_entry' => '0',
        ];

        if (!empty($creditData)) {
            AccountLedger::create($creditData);
            GeneralJournal::create($creditData);
        }

        if ($PurchaseDetail && $PurchaseDetail->isNotEmpty()) {
            foreach ($PurchaseDetail as $purchase) {
                $projectProductNameEN = item::where('id', $purchase->product_id)->value('name_en');
                $projectProductNameUR = item::where('id', $purchase->product_id)->value('name_ur');
                $detailAccountData = DetailAccount::where('project_id', $purchaseMaster->project_id)->where('name_en', $projectProductNameEN)->value('id');
                $productData = Product::where('project_id', $purchaseMaster->project_id)->where('name_en', $projectProductNameEN)->value('id');
                $debitData = [
                    'date' => $purchaseMaster->date,
                    'project_id' => $purchaseMaster->project_id,
                    'invoice_id' => $purchaseMaster->id,
                    'transaction_type' => null,
                    'is_fee_entry' => 0,
                    'party_id' => null,
                    'detail_account_id' => $detailAccountData,
                    'description_en' => $purchase->detail_remarks_en ?? '',
                    'description_ur' => $purchase->detail_remarks_ur ?? '',
                    'document_number' => 'P-I' . '-' . $purchaseMaster->id,
                    'debit' => $purchase->amount ?? 0,
                    'credit' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                AccountLedger::create($debitData);
                GeneralJournal::create($debitData);

                $stockLedgerData = [

                    'date' => $purchaseMaster->date,
                    'project_id' => $purchaseMaster->project_id,
                    'product_id' => $productData,
                    'invoice_id' => $purchaseMaster->id,
                    'transaction_type' => null,
                    'is_fee_entry' => 0,
                    'party_title_en' => $partyNameEN,
                    'party_title_ur' => $partyNameUR,
                    'description_en' => $purchase->detail_remarks_en ?? '',
                    'description_ur' => $purchase->detail_remarks_ur ?? '',
                    'document_number' => 'P-I' . '-' . $purchaseMaster->id,
                    'stock_in_quantity' => $purchase->quantity ?? 0,
                    'stock_out_quantity' => 0,
                ];
                if (!empty($stockLedgerData)) {
                    StockLedger::create($stockLedgerData);
                }
            }
        }
    }

    public function delete($id)
    {
        $purchaseMaster = PurchaseMaster::findOrFail($id);

        PurchaseDetail::where('purchase_master_id', $id)->delete();

        //  delete the goodsReceivedNoteMaster
        return $purchaseMaster->delete();
    }

    public function getItemMeasurementUnit($id)
    {
        $itemMeasurementUnit = Item::where('id', $id)->value('measurement_unit_id');
        $field = App::getLocale() === 'ur' ? 'name_ur' : 'name_en';
        return Unit::where('id', $itemMeasurementUnit)->value($field);
    }
}
