<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkProgressDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'work_progress_id',
        'item_id',
        'completed_qty',
    ];

    /**
     * work_progress
     */
    public function workProgress()
    {
        return $this->belongsTo(WorkProgress::class);
    }

    /**
     * item
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
