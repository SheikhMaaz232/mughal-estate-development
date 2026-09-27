<?php

namespace App\Services;

use App\Models\Party;
use App\Models\Product;
use App\Models\Project;
use App\Models\StockLedger;
use App\Models\AccountLedger;
use App\Models\DetailAccount;
use App\Models\GeneralJournal;
use App\Models\PlotPurchaseDetail;
use App\Models\PlotPurchaseMaster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

class PlotPurchaseService
{

    public function getById($id)
    {
        return PlotPurchaseMaster::findOrFail($id);
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {

            $master = PlotPurchaseMaster::create([
                'date'               => $data['date'],
                'project_id'         => $data['project_id'],
                'party_id'           => $data['party_id'],
                'detail_account_id'  => $data['detail_account_id'],
                'status' => $data['status'],
                'gross_bill'         => $data['gross_bill'],
                'total_quantity'     => $data['total_quantity'],
                'remarks_en'            => $data['remarks_en'] ?? null,
                'remarks_ur'            => $data['remarks_ur'] ?? null,

            ]);

            foreach ($data['product_id'] as $index => $productId) {

                PlotPurchaseDetail::create([
                    'plot_purchase_master_id' => $master->id,
                    'product_id'         => $productId,
                    'size'           => $data['size'][$index],
                    'per_marla_rate'              => $data['per_marla_rate'][$index],
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
        return PlotPurchaseDetail::where('plot_purchase_master_id', $id)
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
                'date'               => $data['date'],
                'project_id'         => $data['project_id'],
                'party_id'           => $data['party_id'],
                'detail_account_id'  => $data['detail_account_id'],
                'status' => $data['status'],
                'gross_bill'         => $data['gross_bill'],
                'total_quantity'     => $data['total_quantity'],
                'remarks_en'            => $data['remarks_en'] ?? null,
                'remarks_ur'            => $data['remarks_ur'] ?? null,
            ]);

            $master->details()->delete();

            foreach ($data['product_id'] as $index => $productId) {

                PlotPurchaseDetail::create([
                    'plot_purchase_master_id' => $master->id,
                    'product_id'         => $productId,
                    'size'           => $data['size'][$index],
                    'per_marla_rate'              => $data['per_marla_rate'][$index],
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
            'document_number' => 'P-P-I' . '-' . $purchaseMaster->id,
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
                $projectProductNameEN = Product::where('id', $purchase->product_id)->value('name_en');
                $projectProductNameUR = Product::where('id', $purchase->product_id)->value('name_ur');
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
                    'document_number' => 'P-P-I' . '-' . $purchaseMaster->id,
                    'debit' => $purchase->amount ?? 0,
                    'credit' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                AccountLedger::create($debitData);
                GeneralJournal::create($debitData);
            }
        }
    }

    public function delete($id)
    {
        $purchaseMaster = PlotPurchaseMaster::findOrFail($id);

        PlotPurchaseDetail::where('plot_purchase_master_id', $id)->delete();

        //  delete the goodsReceivedNoteMaster
        return $purchaseMaster->delete();
    }

    // public function getItemMeasurementUnit($id)
    // {
    //     $itemMeasurementUnit = App::getLocale() === 'ur' ? 'مرلہ' : 'Marla';
    //     $field = App::getLocale() === 'ur' ? 'name_ur' : 'name_en';
    //     return $itemMeasurementUnit;
    // }


    public function getItemMeasurementUnit($id)
    {
        $itemMeasurementUnit = App::getLocale() === 'ur' ? 'مرلہ' : 'Marla';

        $item = Product::find($id);

        if (!$item) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'unit' => $itemMeasurementUnit,
                'total_marla' => $item->total_marla,
                'amount_in_pkr' => $item->amount_in_pkr,
                'total_amount' => $item->total_amount,
            ],
        ]);
    }
}
