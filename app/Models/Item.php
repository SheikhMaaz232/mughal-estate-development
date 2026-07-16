<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'main_head_id',
        'control_head_id',
        'sub_head_id',
        'sub_sub_head_id',
        'sub_sub_sub_head_id',
        'measurement_unit_id',
        'item_image',
        'name_en',
        'name_ur',
    ];

    // Relationships
    public function mainHead()
    {
        return $this->belongsTo(MainHead::class, 'main_head_id');
    }
    public function controlHead()
    {
        return $this->belongsTo(ControlHead::class, 'control_head_id');
    }
    public function subHead()
    {
        return $this->belongsTo(SubHead::class, 'sub_head_id');
    }
    public function subSubHead()
    {
        return $this->belongsTo(SubSubHead::class, 'sub_sub_head_id');
    }
    public function subSubSubHead()
    {
        return $this->belongsTo(SubSubSubHead::class, 'sub_sub_sub_head_id');
    }
    public function measurementUnit()
    {
        return $this->belongsTo(Unit::class, 'measurement_unit_id');
    }

    public function scopeSearch($query, $searchTerm = null, $request = null)
    {
        return $query
            ->when($searchTerm, function ($q) use ($searchTerm) {
                $q->where(function ($q2) use ($searchTerm) {
                    $q2->where('name_en', 'like', "%{$searchTerm}%")
                        ->orWhere('name_ur', 'like', "%{$searchTerm}%");
                });
            })
            ->when(isset($request['main_head_id']) && is_array($request['main_head_id']), function ($q) use ($request) {
                $q->whereIn('main_head_id', $request['main_head_id']);
            })
            ->when(isset($request['control_head_id']) && is_array($request['control_head_id']), function ($q) use ($request) {
                $q->whereIn('control_head_id', $request['control_head_id']);
            })
            ->when(isset($request['sub_head_id']) && is_array($request['sub_head_id']), function ($q) use ($request) {
                $q->whereIn('sub_head_id', $request['sub_head_id']);
            })
            ->when(isset($request['sub_sub_head_id']) && is_array($request['sub_sub_head_id']), function ($q) use ($request) {
                $q->whereIn('sub_sub_head_id', $request['sub_sub_head_id']);
            })
            ->when(isset($request['sub_sub_sub_head_id']) && is_array($request['sub_sub_sub_head_id']), function ($q) use ($request) {
                $q->whereIn('sub_sub_sub_head_id', $request['sub_sub_sub_head_id']);
            });
    }
}
