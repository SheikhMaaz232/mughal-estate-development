<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ControlHead extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = ['main_head_id', 'name_en', 'name_ur'];

    public function mainHead()
    {
        return $this->belongsTo(MainHead::class);
    }


    public function scopeSearch($query, $searchTerm = null, $request = null)
    {
        return $query->when($searchTerm, function ($q) use ($searchTerm) {
            $q->where(function ($q2) use ($searchTerm) {
                $q2->where('name_en', 'like', "%{$searchTerm}%")
                    ->orWhere('name_ur', 'like', "%{$searchTerm}%");
            });
        })
             ->when(isset($request['main_head_id']) && is_array($request['main_head_id']), function ($q) use ($request) {
                $q->whereIn('main_head_id', $request['main_head_id']);
            });
    }
}
