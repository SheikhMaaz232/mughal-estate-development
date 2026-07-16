<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkProgress extends Model
{
    use SoftDeletes;

    protected $table = 'work_progress';

    protected $fillable = [
        'work_order_id',
        'date',
        'description_en',
        'description_ur',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function details()
    {
        return $this->hasMany(WorkProgressDetail::class);
    }

    public function scopeSearch($query, $search)
    {
        if (!$search) return $query;

        return $query->whereHas('workOrder', function ($q) use ($search) {
            $q->where('id', 'like', "%$search%");
        })
            ->orWhereHas('item', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            })
            ->orWhere('completed_qty', 'like', "%$search%")
            ->orWhere('date', 'like', "%$search%");
    }
}
