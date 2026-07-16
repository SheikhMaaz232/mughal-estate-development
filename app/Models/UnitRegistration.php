<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitRegistration extends Model
{
    use SoftDeletes;

   protected $fillable = [
        'company_id',
        'project_id',
        'product_id',
        'road_id',
        'front_id',
        'volume_unit',
        'covering_unit',
        'volume',
        'covering',
        'actual_volume',
        'actual_covering',
        'phase',
        'unit_no',
        'unit_name_en',
        'unit_name_ur',
        'kanal',
        'marla',
        'total_marla',
        'yard',
        'status',
        'image',
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function road()
    {
        return $this->belongsTo(RoadCategory::class, 'road_id');
    }

    public function volumeUnit()
    {
        return $this->belongsTo(Unit::class, 'volume_unit');
    }

    public function coveringUnit()
    {
        return $this->belongsTo(Unit::class, 'covering_unit');
    }
}
