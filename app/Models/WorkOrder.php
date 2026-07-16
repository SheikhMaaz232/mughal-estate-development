<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'construction_site_id',
        'tender_id',
        'boq_id',
        'start_date',
        'end_date',
        'description_en',
        'description_ur',
        'total_amount',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function constructionSite()
    {
        return $this->belongsTo(ConstructionSite::class, 'construction_site_id');
    }

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

    public function boqMaster()
    {
        return $this->belongsTo(BOQMaster::class, 'boq_id');
    }

    public function items()
    {
        return $this->hasMany(WorkOrderItem::class);
    }

    /**
     * Get remaining quantity for a specific BOQ item across all work orders
     */
    public function getRemainingQuantity($boqItemId)
    {
        $boqItem = BOQDetail::find($boqItemId);
        if (!$boqItem) {
            return 0;
        }

        $usedQuantity = WorkOrderItem::whereHas('workOrder', function ($query) {
            $query->where('boq_id', $this->boq_id);
        })
            ->where('boq_item_id', $boqItemId)
            ->sum('quantity');

        return max(0, $boqItem->quantity - $usedQuantity);
    }

    /**
     * Scopes
     */
    public function scopeFilterByConstructionSite($query, $constructionSiteId)
    {
        if ($constructionSiteId) {
            return $query->where('construction_site_id', $constructionSiteId);
        }
        return $query;
    }

    public function scopeFilterByTender($query, $tenderId)
    {
        if ($tenderId) {
            return $query->where('tender_id', $tenderId);
        }
        return $query;
    }

    public function scopeFilterByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('description_en', 'like', "%{$search}%")
                ->orWhere('description_ur', 'like', "%{$search}%");
        }
        return $query;
    }
}
