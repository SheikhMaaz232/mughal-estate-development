<?php

namespace App\Services;

use App\Models\GoodsReceivedNoteDetail;
use App\Models\GoodsReceivedNoteMaster;
use App\Models\Item;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

class GRNService
{

    public function getById($id)
    {
        return GoodsReceivedNoteMaster::findOrFail($id);
    }

    public function create(array $data): GoodsReceivedNoteMaster
    {
        return DB::transaction(function () use ($data) {
            $goodsReceivedNoteMaster = GoodsReceivedNoteMaster::create([
                'date' => $data['date'],
                'purchase_order_no' => $data['purchase_order_no'],
                'project_id' => $data['project_id'],
                'party_id' => $data['party_id'],
                'detail_account_id' => $data['detail_account_id'],
                'fare' => $data['fare'] ?? null,
                'supplier_bill_no' => $data['supplier_bill_no'] ?? null,
                'status' => $data['status'] ?? 'Unverified',
                'remarks' => $data['remarks'] ?? null,
                'unloaded_by' => $data['unloaded_by'] ?? 0,
                'driver_name' => $data['driver_name'] ?? 0,
                'total_po_quantity' => $data['total_po_quantity'] ?? 0,
                'total_received_quantity' => $data['total_received_quantity'] ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // --- Prepare details array ---
            $details = [];
            foreach ($data['product_id'] as $index => $productId) {
                $details[] = [
                    'master_id' => $goodsReceivedNoteMaster->id,
                    'product_id' => $productId,
                    'po_quantity' => $data['po_quantity'][$index],
                    'received_qty' => $data['received_qty'][$index],
                    'balance' => $data['balance'][$index] ?? ($data['po_quantity'][$index] - $data['received_qty'][$index]),
                    'detail_remarks' => $data['detail_remarks'][$index] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            GoodsReceivedNoteDetail::insert($details);

            return $goodsReceivedNoteMaster;
        });
    }

    public function getGoodsReceivedNoteDetails($id)
    {
        return GoodsReceivedNoteDetail::where('master_id', $id)
            ->whereNull('deleted_at')
            ->get();
    }

    public function update($goodsReceivedNoteMaster, array $data)
    {
        return DB::transaction(function () use ($goodsReceivedNoteMaster, $data) {
            // --- Update master record ---
            $goodsReceivedNoteMaster->update([
                'date' => $data['date'],
                'purchase_order_no' => $data['purchase_order_no'],
                'project_id' => $data['project_id'],
                'party_id' => $data['party_id'],
                'detail_account_id' => $data['detail_account_id'],
                'fare' => $data['fare'] ?? null,
                'supplier_bill_no' => $data['supplier_bill_no'] ?? null,
                'status' => $data['status'],
                'remarks' => $data['remarks'] ?? null,
                'unloaded_by' => $data['unloaded_by'] ?? 0,
                'driver_name' => $data['driver_name'] ?? 0,
                'total_po_quantity' => $data['total_po_quantity'] ?? 0,
                'total_received_quantity' => $data['total_received_quantity'] ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // --- Remove old details ---
            GoodsReceivedNoteDetail::where('master_id',$goodsReceivedNoteMaster->id)->delete();

            // --- Insert updated details ---
            $details = [];
            foreach ($data['product_id'] as $index => $productId) {
                $details[] = [
                    'master_id' => $goodsReceivedNoteMaster->id,
                    'product_id' => $productId,
                    'po_quantity' => $data['po_quantity'][$index],
                    'received_qty' => $data['received_qty'][$index],
                    'balance' => $data['balance'][$index] ?? ($data['po_quantity'][$index] - $data['received_qty'][$index]),
                    'detail_remarks' => $data['detail_remarks'][$index] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            GoodsReceivedNoteDetail::insert($details);

            return $goodsReceivedNoteMaster;
        });
    }

    public function delete($id)
    {
        $goodsReceivedNoteMaster = GoodsReceivedNoteMaster::findOrFail($id);

        GoodsReceivedNoteDetail::where('master_id', $id)->delete();

        //  delete the goodsReceivedNoteMaster
        return $goodsReceivedNoteMaster->delete();
    }

    public function getItemMeasurementUnit($id)
    {
        $itemMeasurementUnit = Item::where('id', $id)->value('measurement_unit_id');
        $field = App::getLocale() === 'ur' ? 'name_ur' : 'name_en';
        return Unit::where('id', $itemMeasurementUnit)->value($field);
    }
}
