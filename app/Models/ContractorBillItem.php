<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractorBillItem extends Model
{
    protected $fillable = [
        'contractor_bill_id',
        'boq_item_id',
        'quantity',
        'rate',
        'amount',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'rate' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    /**
     * RELATIONSHIPS
     */

    public function contractorBill()
    {
        return $this->belongsTo(ContractorBill::class);
    }

    public function boqItem()
    {
        return $this->belongsTo(BOQDetail::class, 'boq_item_id');
    }

    /**
     * UTILITY METHODS
     */

    /**
     * Get total completed quantity from work progress for this BOQ item
     */
    public function getCompletedQuantity()
    {
        return WorkProgressDetail::where('item_id', $this->boqItem->item_id)
            ->whereHas('workProgress', function ($q) {
                $q->where('work_order_id', $this->contractorBill->work_order_id);
            })
            ->sum('completed_qty');
    }

    /**
     * Check if bill quantity exceeds completed quantity
     */
    public function validateQuantity()
    {
        $completedQty = $this->getCompletedQuantity();
        $billedQty = ContractorBillItem::where('boq_item_id', $this->boq_item_id)
            ->whereHas('contractorBill', function ($q) {
                $q->where('work_order_id', $this->contractorBill->work_order_id);
                if ($this->id) {
                    $q->where('id', '!=', $this->contractorBill->id);
                }
            })
            ->sum('quantity');

        return ($billedQty + $this->quantity) <= $completedQty;
    }

    public function getRemainigCompletedQuantity()
    {
        $completedQty = $this->getCompletedQuantity();
        $billedQty = ContractorBillItem::where('boq_item_id', $this->boq_item_id)
            ->whereHas('contractorBill', function ($q) {
                $q->where('work_order_id', $this->contractorBill->work_order_id);
                if ($this->id) {
                    $q->where('id', '!=', $this->contractorBill->id);
                }
            })
            ->sum('quantity');

        return max(0, $completedQty - $billedQty);
    }
}
