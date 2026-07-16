<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BOQDetail extends Model
{
    use SoftDeletes;

    protected $table = 'boq_details';

    protected $fillable = [
        'boq_master_id',
        'item_id',
        'quantity',
        'rate',
        'gross_amount',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'rate' => 'decimal:2',
        'gross_amount' => 'decimal:2',
    ];

    // Relationships
    public function boqMaster()
    {
        return $this->belongsTo(BOQMaster::class, 'boq_master_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    /**
     * Scope to filter by BOQ Master
     */
    public function scopeFilterByBOQMaster($query, $boqMasterId)
    {
        if ($boqMasterId) {
            return $query->where('boq_master_id', $boqMasterId);
        }
        return $query;
    }

    /**
     * Scope to filter by item
     */
    public function scopeFilterByItem($query, $itemId)
    {
        if ($itemId) {
            return $query->where('item_id', $itemId);
        }
        return $query;
    }

    /**
     * Get remaining quantity after work order allocation
     */
    public function getRemainingSortedQuantity($workOrderId = null)
    {
        $boqId = $this->boq_master_id;

        // Get total BOQ quantity for this item
        $boqQuantity = $this->quantity;

        // Get quantity used in work order items
        $usedInWorkOrder = WorkOrderItem::whereHas('workOrder', function ($q) use ($boqId) {
            $q->where('boq_id', $boqId);
        })
            ->where('boq_item_id', $this->id)
            ->sum('quantity');

        return max(0, $boqQuantity - $usedInWorkOrder);
    }
}
