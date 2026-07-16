<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\BOQDetail;

class WorkOrderItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'work_order_id',
        'boq_item_id',
        'quantity',
        'rate',
        'amount',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'rate' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function boqItem()
    {
        return $this->belongsTo(BOQDetail::class, 'boq_item_id', 'item_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'boq_item_id');
    }


    /**
     * Mutators
     */
    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = $this->quantity * $this->rate;
    }
}
