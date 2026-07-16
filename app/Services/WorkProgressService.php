<?php

namespace App\Services;

use App\Models\WorkOrderItem;
use App\Models\WorkProgress;
use App\Models\WorkProgressDetail;
use Illuminate\Support\Facades\DB;

class WorkProgressService
{

    public function store($data)
    {
        DB::beginTransaction();

        try {
            //  1. Create main record
            $workProgress = WorkProgress::create([
                'work_order_id' => $data['work_order_id'],
                'date' => $data['date'],
                'description_en' => $data['description_en'] ?? null,
                'description_ur' => $data['description_ur'] ?? null,
            ]);

            //  2. Loop through each item
            foreach ($data['item_id'] as $index => $itemId) {

                $completedQty = $data['completed_qty'][$index];

                // Get already completed quantity from DETAILS table
                $totalCompleted = WorkProgressDetail::whereHas('workProgress', function ($q) use ($data) {
                    $q->where('work_order_id', $data['work_order_id']);
                })
                    ->where('item_id', $itemId)
                    ->sum('completed_qty');

                //  Get allowed quantity from work order
                $workOrderItem = WorkOrderItem::where('work_order_id', $data['work_order_id'])
                    ->where('boq_item_id', $itemId)
                    ->first();

                if (!$workOrderItem) {
                    throw new \Exception(__('messages.item_not_in_work_order'));
                }

                $remaining = $workOrderItem->quantity - $totalCompleted;

                // Validation
                if ($completedQty > $remaining) {
                    throw new \Exception(__('messages.qty_exceeded') . " (Max: {$remaining})");
                }

                //  3. Save detail record
                $workProgress->details()->create([
                    'item_id' => $itemId,
                    'completed_qty' => $completedQty,
                ]);
            }

            DB::commit();

            return $workProgress;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getAvailableWorkOrderItems(int $workOrderId)
    {
        return WorkOrderItem::where('work_order_id', $workOrderId)
            ->with('item')
            ->get()
            ->map(function ($item) use ($workOrderId) {
                $usedQuantity = WorkProgressDetail::whereHas('workProgress', function ($query) use ($workOrderId) {
                    $query->where('work_order_id', $workOrderId);
                })
                    ->where('item_id', $item->boq_item_id)
                    ->sum('completed_qty');
                return [
                    'id' => $item->item->id,
                    'item_name_en' => $item->item->name_en,
                    'item_name_ur' => $item->item->name_ur,
                    'total_quantity' => $item->quantity,
                    'used_quantity' => $usedQuantity,
                    'remaining_quantity' => $item->quantity - $usedQuantity,
                    'rate' => $item->rate,
                    'unit_en' => $item->item->measurementUnit->name_en ?? 'N/A',
                    'unit_ur' => $item->item->measurementUnit->name_ur ?? 'N/A',
                ];
            });
    }

    public function getAvailableWorkOrderItemsEditCase(int $workOrderId, int $workProgressId)
    {
        $progress = WorkProgress::with('details')
            ->findOrFail($workProgressId);

        return WorkOrderItem::where('work_order_id', $workOrderId)
            ->with('item')
            ->get()
            ->map(function ($item) use ($progress, $workOrderId) {

                // total used in ALL work progress except current one
                $totalUsed = WorkProgressDetail::whereHas('workProgress', function ($q) use ($workOrderId, $progress) {
                    $q->where('work_order_id', $workOrderId)
                        ->where('id', '!=', $progress->id); //  exclude current
                })
                    ->where('item_id', $item->item_id)
                    ->sum('completed_qty');

                // current progress qty (for edit form prefill)
                $currentQty = $progress->details
                    ->where('item_id', $item->item_id)
                    ->sum('completed_qty');

                return [
                    'id' => $item->item->id,
                    'item_name_en' => $item->item->name_en,
                    'item_name_ur' => $item->item->name_ur,
                    'total_quantity' => $item->quantity,

                    // REAL CORRECT LOGIC
                    'used_quantity' => $totalUsed,
                    'existing_qty' => $currentQty,
                    'rate' => $item->rate,
                    'unit_en' => $item->item->measurementUnit->name_en ?? 'N/A',
                    'unit_ur' => $item->item->measurementUnit->name_ur ?? 'N/A',

                    // IMPORTANT FIX
                    'remaining_quantity' => $item->quantity - $totalUsed + $currentQty,
                ];
            });
    }

    public function update($id, $data)
    {
        DB::beginTransaction();

        try {

            $progress = WorkProgress::with('details')->findOrFail($id);

            //  1. Update master record
            $progress->update([
                'work_order_id'  => $data['work_order_id'],
                'date'           => $data['date'],
                'description_en' => $data['description_en'] ?? null,
                'description_ur' => $data['description_ur'] ?? null,
            ]);

            // existing details keyed by item_id
            $existingDetails = $progress->details->keyBy('item_id');

            $submittedItems = $data['item_id'] ?? [];
            $submittedQtys  = $data['completed_qty'] ?? [];

            foreach ($submittedItems as $index => $itemId) {

                $qty = (float) $submittedQtys[$index];

                //  2. Work order item
                $workOrderItem = WorkOrderItem::where('work_order_id', $data['work_order_id'])
                    ->where('boq_item_id', $itemId)
                    ->first();

                if (!$workOrderItem) {
                    throw new \Exception(__('messages.item_not_in_work_order'));
                }

                //  3. total used excluding current progress
                $totalUsed = WorkProgressDetail::whereHas('workProgress', function ($q) use ($data) {
                    $q->where('work_order_id', $data['work_order_id']);
                })
                    ->where('item_id', $itemId)
                    ->where('work_progress_id', '!=', $progress->id)
                    ->sum('completed_qty');

                //  4. remaining quantity calculation
                $remaining = $workOrderItem->quantity - $totalUsed;

                if ($qty > $remaining) {
                    throw new \Exception(
                        __('messages.qty_exceeded') . " Max allowed: " . $remaining
                    );
                }

                //  5. update OR create
                if (isset($existingDetails[$itemId])) {

                    $existingDetails[$itemId]->update([
                        'completed_qty' => $qty,
                    ]);
                } else {

                    WorkProgressDetail::create([
                        'work_progress_id' => $progress->id,
                        'item_id'          => $itemId,
                        'completed_qty'    => $qty,
                    ]);
                }
            }

            //  6. delete removed rows
            $progress->details()
                ->whereNotIn('item_id', $submittedItems)
                ->delete();

            DB::commit();

            return $progress;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
