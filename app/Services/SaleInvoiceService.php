<?php

namespace App\Services;

use App\Models\AccountLedger;
use App\Models\DetailAccount;
use App\Models\GeneralJournal;
use App\Models\Item;
use App\Models\Party;
use App\Models\Product;
use App\Models\Project;
use App\Models\SaleInvoice;
use App\Models\SaleInvoiceDetail;
use App\Models\StockLedger;
use App\Models\Unit;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class SaleInvoiceService
{

    public function getById($id)
    {
        return SaleInvoice::findOrFail($id);
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {

            $master = SaleInvoice::create([
                'sale_invoice_no'  => $data['sale_invoice_no'],
                'date'               => $data['date'],
                'project_id'         => $data['project_id'],
                'party_id'           => $data['party_id'],
                'detail_account_id'  => $data['detail_account_id'],
                'status' => $data['status'],
                'gross_bill'         => $data['gross_bill'],
                'total_quantity'     => $data['total_quantity'],
                'remarks'            => $data['remarks'] ?? null,
            ]);

            foreach ($data['product_id'] as $index => $productId) {

                SaleInvoiceDetail::create([
                    'sale_invoice_master_id' => $master->id,
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

    public function getSaleInvoiceDetails($id)
    {
        return SaleInvoiceDetail::where('sale_invoice_master_id', $id)
            ->whereNull('deleted_at')
            ->get();
    }

    /**
     * Update Sale Invoice (Master + Details)
     */
    // public function update(array $data, SaleInvoice $master)
    // {
    //     return DB::transaction(function () use ($data, $master) {

    //         $master->update([
    //             'sale_invoice_no'  => $data['sale_invoice_no'],
    //             'date'               => $data['date'],
    //             'project_id'         => $data['project_id'],
    //             'party_id'           => $data['party_id'],
    //             'detail_account_id'  => $data['detail_account_id'],
    //             'status' => $data['status'],
    //             'gross_bill'         => $data['gross_bill'],
    //             'total_quantity'     => $data['total_quantity'],
    //             'remarks'            => $data['remarks'] ?? null,

    //         ]);

    //         $master->details()->forceDelete();

    //         foreach ($data['product_id'] as $index => $productId) {

    //             SaleInvoiceDetail::create([
    //                 'sale_invoice_master_id' => $master->id,
    //                 'product_id'         => $productId,
    //                 'quantity'           => $data['quantity'][$index],
    //                 'price'              => $data['price'][$index],
    //                 'amount'             => $data['amount'][$index],
    //                 'detail_remarks'            => $data['detail_remarks'][$index] ?? null,
    //             ]);
    //         }

    //         return $master;
    //     });
    // }


    public function update(array $data, SaleInvoice $master)
    {
        return DB::transaction(function () use ($data, $master) {

            //1. Update Master
            $master->update([
                'sale_invoice_no'  => $data['sale_invoice_no'],
                'date'             => $data['date'],
                'project_id'       => $data['project_id'],
                'party_id'         => $data['party_id'],
                'detail_account_id' => $data['detail_account_id'],
                'status'           => $data['status'],
                'gross_bill'       => $data['gross_bill'],
                'total_quantity'   => $data['total_quantity'],
                'remarks'          => $data['remarks'] ?? null,
            ]);

            //2. Delete OLD Details
            $master->details()->forceDelete();

            //3. Re-create Details
            $details = [];
            foreach ($data['product_id'] as $index => $productId) {
                $details[] = [
                    'sale_invoice_master_id' => $master->id,
                    'product_id'   => $productId,
                    'quantity'     => $data['quantity'][$index],
                    'price'        => $data['price'][$index],
                    'amount'       => $data['amount'][$index],
                    'detail_remarks' => $data['detail_remarks'][$index] ?? null,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];
            }

            SaleInvoiceDetail::insert($details);

            //4. DELETE OLD LEDGER ENTRIES
            AccountLedger::where('invoice_id', $master->id)
                ->where('document_number', 'S-I-' . $master->id)
                ->delete();

            GeneralJournal::where('invoice_id', $master->id)
                ->where('document_number', 'S-I-' . $master->id)
                ->delete();

            StockLedger::where('invoice_id', $master->id)
                ->where('document_number', 'S-I-' . $master->id)
                ->delete();

            //5. Get Fresh Details
            $saleInvoiceDetails = SaleInvoiceDetail::where('sale_invoice_master_id', $master->id)->get();

            //6. Re-create Ledger Entries
            $this->createLedgerEntry($master, $saleInvoiceDetails);

            return $master;
        });
    }


    public function createLedgerEntry($saleInvoiceMaster, $saleInvoiceDetails): void
    {
        $dealerMainParty = DetailAccount::where('id', $saleInvoiceMaster->dealer_id)->value('party_id');
        $productNameEN = Product::where('id', $saleInvoiceMaster->product_id)->value('name_en');
        $projectNameEN = Project::where('id', $saleInvoiceMaster->project_id)->value('name_en');
        $projectNameUR = Project::where('id', $saleInvoiceMaster->project_id)->value('name_ur');
        $partyNameEN = Party::where('id', $saleInvoiceMaster->party_id)->value('name_en');
        $partyNameUR = Party::where('id', $saleInvoiceMaster->party_id)->value('name_ur');
        $product = DetailAccount::where('project_id', $saleInvoiceMaster->project_id)->where('name_en', $productNameEN)->value('id');

        $debitData = [
            'date' => $saleInvoiceMaster->date,
            'project_id' => $saleInvoiceMaster->project_id,
            'invoice_id' => $saleInvoiceMaster->id,
            'party_id' => $saleInvoiceMaster->party_id,
            'detail_account_id' => $saleInvoiceMaster->detail_account_id,
            'description_en' => "Sale to {$partyNameEN} at {$projectNameEN}",
            'description_ur' => "{$projectNameUR} میں {$partyNameUR}  سے فروخت ",
            'document_number' => 'S-I' . '-' . $saleInvoiceMaster->id,
            'debit' => $saleInvoiceMaster->gross_bill,
            'credit' => 0,
        ];

        if (!empty($debitData)) {
            AccountLedger::create($debitData);
            GeneralJournal::create($debitData);
        }

        if ($saleInvoiceDetails && $saleInvoiceDetails->isNotEmpty()) {
            foreach ($saleInvoiceDetails as $saleInvoiceDetail) {
                $projectProductNameEN = item::where('id', $saleInvoiceDetail->product_id)->value('name_en');
                $projectProductNameUR = item::where('id', $saleInvoiceDetail->product_id)->value('name_ur');
                $detailAccountData = DetailAccount::where('project_id', $saleInvoiceMaster->project_id)->where('name_en', $projectProductNameEN)->value('id');
                $productData = Product::where('project_id', $saleInvoiceMaster->project_id)->where('name_en', $projectProductNameEN)->value('id');

                $creditData = [
                    'date' => $saleInvoiceMaster->date,
                    'project_id' => $saleInvoiceMaster->project_id,
                    'invoice_id' => $saleInvoiceMaster->id,
                    'party_id' => null,
                    'detail_account_id' => $detailAccountData,
                    'description_en' => 'Sale Of ' . $projectProductNameEN . ' of ' . $projectNameEN,
                    'description_ur' => 'کی فروخت ' . $projectProductNameUR . ' کے ' . $projectNameUR,
                    'document_number' => 'S-I' . '-' . $saleInvoiceMaster->id,
                    'debit' => 0,
                    'credit' => $saleInvoiceDetail->amount ?? 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $generalJournalCreditData = $creditData;
                AccountLedger::insert($creditData);
                GeneralJournal::insert($generalJournalCreditData);

                $stockLedgerData = [

                    'date' => $saleInvoiceMaster->date,
                    'project_id' => $saleInvoiceMaster->project_id,
                    'product_id' => $productData,
                    'invoice_id' => $saleInvoiceMaster->id,
                    'party_title_en' => $partyNameEN,
                    'party_title_ur' => $partyNameUR,
                    'description_en' => "Sale of {$projectProductNameEN} in {$projectNameEN} from {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} سے {$projectNameUR} کے {$projectProductNameUR} کی فروخت  ",
                    'document_number' => 'S-I' . '-' . $saleInvoiceMaster->id,
                    'stock_in_quantity' => 0,
                    'stock_out_quantity' => $saleInvoiceDetail->quantity ?? 0,
                ];
                if (!empty($stockLedgerData)) {
                    StockLedger::create($stockLedgerData);
                }
            }
        }
    }

    public function delete($id)
    {
        $saleInvoiceMaster = SaleInvoice::findOrFail($id);

        SaleInvoiceDetail::where('sale_invoice_master_id', $id)->delete();

        //  delete the goodsReceivedNoteMaster
        return $saleInvoiceMaster->delete();
    }

    public function getItemMeasurementUnit($id)
    {
        $itemMeasurementUnit = Item::where('id', $id)->value('measurement_unit_id');
        $field = App::getLocale() === 'ur' ? 'name_ur' : 'name_en';
        return Unit::where('id', $itemMeasurementUnit)->value($field);
    }
}
