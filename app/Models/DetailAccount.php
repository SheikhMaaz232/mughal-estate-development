<?php

namespace App\Models;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailAccount extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'main_head_id',
        'control_head_id',
        'sub_head_id',
        'sub_sub_head_id',
        'sub_sub_sub_head_id',
        'party_id',
        'project_id',
        'name_en',
        'name_ur',

    ];

    // Optional: Define relationships if necessary
    public function mainHead()
    {
        return $this->belongsTo(MainHead::class);
    }

    public function controlHead()
    {
        return $this->belongsTo(ControlHead::class);
    }

    public function subHead()
    {
        return $this->belongsTo(SubHead::class);
    }

    public function subSubHead()
    {
        return $this->belongsTo(SubSubHead::class);
    }

    public function subSubSubHead()
    {
        return $this->belongsTo(SubSubSubHead::class);
    }

    public function party()
    {
        return $this->belongsTo(Party::class, 'party_id');
    }

    public function projects()
    {
        return $this->belongsTo(Project::class, 'project_id');
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
            })
            ->when(isset($request['party_id']) && is_array($request['party_id']), function ($q) use ($request) {
                $q->whereIn('party_id', $request['party_id']);
            });
    }
}
