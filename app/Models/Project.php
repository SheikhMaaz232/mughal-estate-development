<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Project extends Model implements Auditable
{
    use SoftDeletes, HasFactory, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'group_id',
        'company_id',
        'name_en',
        'name_ur',
        'square_feet',
        'description_en',
        'description_ur',
        'phase_en',
        'phase_ur',
        'address_en',
        'address_ur',
        'project_map',
        'roads_area',
        'public_buildings_area',
        'park_area',
        'cemetery_area',
        'mosque_area',
        'social_waste_area',
        'miscellaneous_area',
        'disposal_area',
        'commercial_plots_area',
        'residential_plots_area',
        'total_area',
    ];

    protected $casts = [
        'group_id' => 'integer',
        'company_id' => 'integer',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function scopeSearch($query, $searchTerm = null)
    {
        return $query->when($searchTerm, function ($q) use ($searchTerm) {
            $q->where(function ($q2) use ($searchTerm) {
                $q2->where('name_en', 'like', "%{$searchTerm}%")
                    ->orWhere('name_ur', 'like', "%{$searchTerm}%");
            });
        });
    }
}
