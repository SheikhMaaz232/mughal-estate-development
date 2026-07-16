<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class RoadSpecification extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'road_category_id',
        'title_en',
        'title_ur'
    ];

    public function category()
    {
        return $this->belongsTo(RoadCategory::class, 'road_category_id', 'id');
    }
}
