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
use App\Models\PurchaseReturnDetail;
use App\Models\PurchaseReturnMaster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

class PurchaseReturnService
{

    public function getById($id)
    {
        return PurchaseReturnMaster::findOrFail($id);
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {

            $master = PurchaseReturnMaster::create([
                'grn_no'             => $data['grn_no'],
                'purchase_order_no'  => $data['purchase_order_no'],
                'purchase_invoice_no'  => $data['purchase_invoice_no'],
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
                'remarks'            => $data['remarks'] ?? null,
            ]);

            foreach ($data['product_id'] as $index => $productId) {

                PurchaseReturnDetail::create([
                    'purchase_return_master_id' => $master->id,
                    'product_id'         => $productId,
                    'quantity'           => $data['quantity'][$index],
                    'price'              => $data['price'][$index],
                    'amount'             => $data['amount'][$index],
                    'detail_remarks'            => $data['detail_remarks'][$index] ?? null,
                ]);
            }

            return $master;
        });
    }

    public function getPurchaseDetails($id)
    {
        return PurchaseReturnDetail::where('purchase_return_master_id', $id)
            ->whereNull('deleted_at')
            ->get();
    }

    /**
     * Update Purchase Invoice (Master + Details)
     */
    public function update(array $data, PurchaseReturnMaster $master)
    {
        return DB::transaction(function () use ($data, $master) {

            $master->update([
                'grn_no'             => $data['grn_no'],
                'purchase_order_no'  => $data['purchase_order_no'],
                'purchase_invoice_no'  => $data['purchase_invoice_no'],
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
                'total_quantity'     => $data['total_quantity'],
                'remarks'            => $data['remarks'] ?? null,
            ]);

            $master->details()->delete();

            foreach ($data['product_id'] as $index => $productId) {

                PurchaseReturnDetail::create([
                    'purchase_return_master_id' => $master->id,
                    'product_id'         => $productId,
                    'quantity'           => $data['quantity'][$index],
                    'price'              => $data['price'][$index],
                    'amount'             => $data['amount'][$index],
                    'remarks'            => $data['detail_remarks'][$index] ?? null,
                ]);
            }

            return $master;
        });
    }


    public function createLedgerEntry($purchaseReturnMaster, $PurchaseReturnDetails): void
    {
        $dealerMainParty = DetailAccount::where('id', $purchaseReturnMaster->dealer_id)->value('party_id');
        $productNameEN = Product::where('id', $purchaseReturnMaster->product_id)->value('name_en');
        $projectNameEN = Project::where('id', $purchaseReturnMaster->project_id)->value('name_en');
        $projectNameUR = Project::where('id', $purchaseReturnMaster->project_id)->value('name_ur');
        $partyNameEN = Party::where('id', $purchaseReturnMaster->party_id)->value('name_en');
        $partyNameUR = Party::where('id', $purchaseReturnMaster->party_id)->value('name_ur');
        $product = DetailAccount::where('project_id', $purchaseReturnMaster->project_id)->where('name_en', $productNameEN)->value('id');

        $creditData = [
            'date' => $purchaseReturnMaster->date,
            'project_id' => $purchaseReturnMaster->project_id,
            'invoice_id' => $purchaseReturnMaster->id,
            'party_id' => $purchaseReturnMaster->party_id,
            'detail_account_id' => $purchaseReturnMaster->detail_account_id,
            'description_en' => "Purchase from {$partyNameEN} at {$projectNameEN}",
            'description_ur' => "{$projectNameUR} میں {$partyNameUR}  سے خریداری ",
            'document_number' => 'P-I' . '-' . $purchaseReturnMaster->id,
            'debit' => 0,
            'credit' => $purchaseReturnMaster->gross_bill,
        ];

        if (!empty($creditData)) {
            AccountLedger::create($creditData);
        }

        $generalJournalCreditData = [
            'date' => $purchaseReturnMaster->date,
            'project_id' => $purchaseReturnMaster->project_id,
            'invoice_id' => $purchaseReturnMaster->id,
            'party_id' => $purchaseReturnMaster->party_id,
            'detail_account_id' => $purchaseReturnMaster->detail_account_id,
            'description_en' => "Purchase from {$partyNameEN} at {$projectNameEN}",
            'description_ur' => "{$projectNameUR} میں {$partyNameUR}  سے خریداری ",
            'document_number' => 'P-I' . '-' . $purchaseReturnMaster->id,
            'debit' => 0,
            'credit' => $purchaseReturnMaster->gross_bill,
        ];
        if (!empty($generalJournalCreditData)) {
            GeneralJournal::create($generalJournalCreditData);
        }


        // $commissionCreditData = [
        //     'date' => now()->toDateString(),
        //     'project_id' => $purchaseMaster->project_id,
        //     'invoice_id' => $purchaseMaster->id,
        //     'party_id' => $dealerMainParty ?? null,
        //     'detail_account_id' => $purchaseMaster->dealer_id,
        //     'description_en' => 'Commission On Sale Of ' . $productNameEN . ' of ' . $projectNameEN,
        //     'description_ur' => ' کی فروخت پر کمیشن ' . $productNameUR . ' کے ' . $projectNameUR,
        //     'document_number' => 'B-A' . '-' . $purchaseMaster->id,
        //     'debit' => 0,
        //     'credit' => $purchaseMaster->commission,
        // ];
        // if (!empty($commissionCreditData)) {
        //     AccountLedger::create($commissionCreditData);
        // }

        // $generalJournalsCommissionCreditData = [
        //     'date' => now()->toDateString(),
        //     'project_id' => $purchaseMaster->project_id,
        //     'invoice_id' => $purchaseMaster->id,
        //     'party_id' => $dealerMainParty ?? null,
        //     'detail_account_id' => $purchaseMaster->dealer_id,
        //     'description_en' => 'Commission On Sale Of ' . $productNameEN . ' of ' . $projectNameEN,
        //     'description_ur' => ' کی فروخت پر کمیشن ' . $productNameUR . ' کے ' . $projectNameUR,
        //     'document_number' => 'B-A' . '-' . $purchaseMaster->id,
        //     'debit' => 0,
        //     'credit' => $purchaseMaster->commission,
        // ];
        // if (!empty($generalJournalsCommissionCreditData)) {
        //     GeneralJournal::create($generalJournalsCommissionCreditData);
        // }

        if ($PurchaseReturnDetails && $PurchaseReturnDetails->isNotEmpty()) {
            foreach ($PurchaseReturnDetails as $PurchaseReturnDetail) {
                $projectProductNameEN = item::where('id', $PurchaseReturnDetail->product_id)->value('name_en');
                $projectProductNameUR = item::where('id', $PurchaseReturnDetail->product_id)->value('name_ur');
                $detailAccountData = DetailAccount::where('project_id', $purchaseReturnMaster->project_id)->where('name_en', $projectProductNameEN)->value('id');
                $productData = Product::where('project_id', $purchaseReturnMaster->project_id)->where('name_en', $projectProductNameEN)->value('id');

                $debitData = [
                    'date' => $purchaseReturnMaster->date,
                    'project_id' => $purchaseReturnMaster->project_id,
                    'invoice_id' => $purchaseReturnMaster->id,
                    'party_id' => null,
                    'detail_account_id' => $detailAccountData,
                    'description_en' => 'Purchase Return Of ' . $projectProductNameEN . ' of ' . $projectNameEN,
                    'description_ur' => 'کی خریداری کی واپسی ' . $projectProductNameUR . ' کے ' . $projectNameUR,
                    'document_number' => 'P-R' . '-' . $purchaseReturnMaster->id,
                    'debit' => $PurchaseReturnDetail->amount ?? 0,
                    'credit' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $generalJournalDebitData = $debitData;
                AccountLedger::insert($debitData);
                GeneralJournal::insert($generalJournalDebitData);

                $stockLedgerData = [

                    'date' => $purchaseReturnMaster->date,
                    'project_id' => $purchaseReturnMaster->project_id,
                    'product_id' => $productData,
                    'invoice_id' => $purchaseReturnMaster->id,
                    'party_title_en' => $partyNameEN,
                    'party_title_ur' => $partyNameUR,
                    'description_en' => "Purchase Return of {$projectProductNameEN} in {$projectNameEN} from {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} سے {$projectNameUR} کے {$projectProductNameUR} کی خریداری کی واپسی",
                    'document_number' => 'P-R' . '-' . $purchaseReturnMaster->id,
                    'stock_in_quantity' => 0,
                    'stock_out_quantity' => $purchaseReturnDetail->quantity ?? 0,
                ];
                if (!empty($stockLedgerData)) {
                    StockLedger::create($stockLedgerData);
                }
            }
        }
    }

    public function delete($id)
    {
        $purchaseReturnMaster = PurchaseReturnMaster::findOrFail($id);

        PurchaseReturnDetail::where('purchase_return_master_id', $id)->delete();

        //  delete the goodsReceivedNoteMaster
        return $purchaseReturnMaster->delete();
    }

    public function getItemMeasurementUnit($id)
    {
        $itemMeasurementUnit = Item::where('id', $id)->value('measurement_unit_id');
        $field = App::getLocale() === 'ur' ? 'name_ur' : 'name_en';
        return Unit::where('id', $itemMeasurementUnit)->value($field);
    }
}
