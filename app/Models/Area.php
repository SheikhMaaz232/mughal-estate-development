<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Area extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'city_id',
        'tehsil_id',
        'name_en',
        'name_ur',
        'area_code'
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function tehsil()
    {
        return $this->belongsTo(Tehsil::class);
    }
}
