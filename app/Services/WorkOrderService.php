<?php

namespace App\Services;

use App\Models\BOQDetail;
use App\Models\WorkOrder;
use App\Models\WorkOrderItem;

class WorkOrderService
{
    /**
     * Create a new work order with items
     */
    public function create(array $data): WorkOrder
    {
        $workOrder = WorkOrder::create([
            'construction_site_id' => $data['construction_site_id'],
            'tender_id' => $data['tender_id'],
            'boq_id' => $data['boq_id'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'description_en' => $data['description_en'] ?? null,
            'description_ur' => $data['description_ur'] ?? null,
            'status' => 'pending',
        ]);

        // Validate and add items
        $boqItemIds = $data['boq_item_id'] ?? [];
        $quantities = $data['quantity'] ?? [];
        $rates = $data['rate'] ?? [];

        $totalAmount = 0;
        foreach ($boqItemIds as $index => $boqItemId) {
            if (!$boqItemId) {
                continue;
            }


            $quantity = $quantities[$index] ?? 0;
            $rate = $rates[$index] ?? 0;

            // Validate quantity doesn't exceed remaining BOQ quantity
            $this->validateQuantity($data['boq_id'], $boqItemId, $quantity);


            $amount = $quantity * $rate;
            WorkOrderItem::create([
                'work_order_id' => $workOrder->id,
                'boq_item_id' => $boqItemId,
                'quantity' => $quantity,
                'rate' => $rate,
                'amount' => $amount,
            ]);

            $totalAmount += $amount;
        }

        $workOrder->update(['total_amount' => $totalAmount]);
        return $workOrder->load('items.boqItem');
    }

    /**
     * Update an existing work order
     */
    public function update(array $data, int $id): WorkOrder
    {
        $workOrder = WorkOrder::findOrFail($id);

        $workOrder->update([
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'description_en' => $data['description_en'] ?? null,
            'description_ur' => $data['description_ur'] ?? null,
            'status' => $data['status'] ?? $workOrder->status,
        ]);

        // Get old items' quantities to subtract from used
        $oldItems = $workOrder->items()->get();
        $oldQuantities = $oldItems->pluck('quantity', 'boq_item_id')->toArray();

        // Delete old items
        $workOrder->items()->delete();

        // Validate and add new items
        $boqItemIds = $data['boq_item_id'] ?? [];
        $quantities = $data['quantity'] ?? [];
        $rates = $data['rate'] ?? [];

        $totalAmount = 0;
        foreach ($boqItemIds as $index => $boqItemId) {
            if (!$boqItemId) {
                continue;
            }

            $quantity = $quantities[$index] ?? 0;
            $rate = $rates[$index] ?? 0;

            // Validate quantity doesn't exceed remaining BOQ quantity
            $oldQuantity = $oldQuantities[$boqItemId] ?? 0;
            $this->validateQuantityForUpdate($data['boq_id'], $boqItemId, $quantity, $oldQuantity);

            $amount = $quantity * $rate;
            WorkOrderItem::create([
                'work_order_id' => $workOrder->id,
                'boq_item_id' => $boqItemId,
                'quantity' => $quantity,
                'rate' => $rate,
                'amount' => $amount,
            ]);

            $totalAmount += $amount;
        }

        $workOrder->update(['total_amount' => $totalAmount]);
        return $workOrder->load('items.boqItem');
    }

    /**
     * Delete a work order
     */
    public function delete(int $id): void
    {
        $workOrder = WorkOrder::findOrFail($id);
        $workOrder->items()->delete();
        $workOrder->delete();
    }

    /**
     * Validate that quantity doesn't exceed remaining BOQ quantity
     */
    public function validateQuantity(int $boqId, int $boqItemId, $quantity): void
    {
        $boqItem = BOQDetail::where('boq_master_id', $boqId)
            ->where('item_id', $boqItemId)
            ->first();

        if (!$boqItem) {
            throw new \Exception(__('messages.boq-item-not-found'));
        }

        // Calculate used quantity across all work orders for this BOQ
        $usedQuantity = WorkOrderItem::whereHas('workOrder', function ($query) use ($boqId) {
            $query->where('boq_id', $boqId);
        })
            ->where('boq_item_id', $boqItemId)
            ->sum('quantity');

        $remainingQuantity = $boqItem->quantity - $usedQuantity;
    }

    /**
     * Validate quantity for update (considering old quantity)
     */
    public function validateQuantityForUpdate(int $boqId, int $boqItemId, $quantity, $oldQuantity = 0): void
    {
        $boqItem = BOQDetail::where('boq_master_id', $boqId)
            ->where('item_id', $boqItemId)
            ->first();

        if (!$boqItem) {
            throw new \Exception(__('messages.boq-item-not-found'));
        }

        // Calculate used quantity across all work orders for this BOQ (excluding current item)
        $usedQuantity = WorkOrderItem::whereHas('workOrder', function ($query) use ($boqId) {
            $query->where('boq_id', $boqId);
        })
            ->where('boq_item_id', $boqItemId)
            ->sum('quantity');

        $availableQuantity = $boqItem->quantity - ($usedQuantity - $oldQuantity);

        if ($quantity > $availableQuantity) {
            throw new \Exception(__('messages.work-order-quantity-exceeds-boq', [
                'item' => $boqItem->item->name_en,
                'remaining' => $availableQuantity,
                'requested' => $quantity,
            ]));
        }
    }

    /**
     * Get available BOQ items for a specific BOQ
     */
    public function getAvailableBOQItems(int $boqId)
    {
        return BOQDetail::where('boq_master_id', $boqId)
            ->with('item')
            ->get()
            ->map(function ($item) use ($boqId) {
                $usedQuantity = WorkOrderItem::whereHas('workOrder', function ($query) use ($boqId) {
                    $query->where('boq_id', $boqId);
                })
                    ->where('boq_item_id', $item->id)
                    ->sum('quantity');

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
    /**
     * Get remaining quantity for a BOQ item
     */
    public function getRemainingQuantity(int $boqId, int $boqItemId)
    {
        $boqItem = BOQDetail::where('boq_master_id', $boqId)
            ->where('item_id', $boqItemId)
            ->first();

        if (!$boqItem) {
            return 0;
        }

        $usedQuantity = WorkOrderItem::whereHas('workOrder', function ($query) use ($boqId) {
            $query->where('boq_id', $boqId);
        })
            ->where('boq_item_id', $boqItemId)
            ->sum('quantity');

        return max(0, $boqItem->quantity - $usedQuantity);
    }
}
