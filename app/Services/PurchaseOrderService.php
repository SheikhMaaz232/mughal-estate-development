<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Unit;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Models\PurchaseOrderDetails;

class PurchaseOrderService
{
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function getById($id)
    {
        return PurchaseOrder::findOrFail($id);
    }

    public function create(array $data): PurchaseOrder
    {
        return DB::transaction(function () use ($data) {
            $purchaseOrder = PurchaseOrder::create([
                'date' => $data['date'],
                'project_id' => $data['project_id'],
                'party_id' => $data['party_id'],
                'detail_account_id' => $data['detail_account_id'],
                'contact_person' => $data['contact_person'] ?? null,
                'status' => $data['status'] ?? 'Unverified',
                'remarks' => $data['remarks'] ?? null,
                'gross_total' => $data['gross_total'] ?? 0,
                'tax_amount' => $data['tax_amount'] ?? 0,
                'shipping_amount' => $data['shipping_amount'] ?? 0,
                'other_amount' => $data['other_amount'] ?? 0,
                'total_amount' => $data['total_amount'] ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // --- Prepare details array ---
            $details = [];
            foreach ($data['product_id'] as $index => $productId) {
                $details[] = [
                    'purchase_order_master_id' => $purchaseOrder->id,
                    'product_id' => $productId,
                    'quantity' => $data['quantity'][$index],
                    'price' => $data['price'][$index],
                    'amount' => $data['amount'][$index] ?? ($data['quantity'][$index] * $data['price'][$index]),
                    'detail_remarks' => $data['detail_remarks'][$index] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            PurchaseOrderDetails::insert($details);

            return $purchaseOrder;
        });
    }

    public function getPurchaseOrderDetails($id)
    {
        return PurchaseOrderDetails::where('purchase_order_master_id', $id)
            ->whereNull('deleted_at')
            ->get();
    }

    public function update(PurchaseOrder $purchaseOrder, array $data): PurchaseOrder
    {

        return DB::transaction(function () use ($purchaseOrder, $data) {
            // --- Update master record ---
            $purchaseOrder->update([
                'date' => $data['date'],
                'project_id' => $data['project_id'],
                'party_id' => $data['party_id'],
                'detail_account_id' => $data['detail_account_id'],
                'contact_person' => $data['contact_person'] ?? null,
                'status' => $data['status'] ?? 'Unverified',
                'remarks' => $data['remarks'] ?? null,
                'gross_total' => $data['gross_total'] ?? 0,
                'tax_amount' => $data['tax_amount'] ?? 0,
                'shipping_amount' => $data['shipping_amount'] ?? 0,
                'other_amount' => $data['other_amount'] ?? 0,
                'total_amount' => $data['total_amount'] ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // --- Remove old details ---
            $purchaseOrder->purchaseOrderDetails()->delete();

            // --- Insert updated details ---
            $details = [];
            foreach ($data['product_id'] as $index => $productId) {
                $details[] = [
                    'purchase_order_master_id' => $purchaseOrder->id,
                    'product_id' => $productId,
                    'quantity' => $data['quantity'][$index],
                    'price' => $data['price'][$index],
                    'amount' => $data['amount'][$index] ?? ($data['quantity'][$index] * $data['price'][$index]),
                    'detail_remarks' => $data['detail_remarks'][$index] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            PurchaseOrderDetails::insert($details);

            return $purchaseOrder;
        });
    }

    public function delete($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        PurchaseOrderDetails::where('purchase_order_master_id', $id)->delete();

        //  delete the purchaseOrder
        return $purchaseOrder->delete();
    }

    public function getItemMeasurementUnit($id)
    {
        $itemMeasurementUnit = Item::where('id', $id)->value('measurement_unit_id');
        $field = App::getLocale() === 'ur' ? 'name_ur' : 'name_en';
        return Unit::where('id', $itemMeasurementUnit)->value($field);
    }
}
