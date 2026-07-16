<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConstructionSite extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'project_id',
        'party_id',
        'name_en',
        'name_ur',
        'description_en',
        'description_ur',
        'address_en',
        'address_ur',
        'estimated_start_date',
        'estimated_end_date',
        'status',
    ];

    protected $casts = [
        'estimated_start_date' => 'date',
        'estimated_end_date' => 'date',
    ];


    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    public function tenders()
    {
        return $this->hasMany(Tender::class);
    }

    /**
     * Scope to apply all filters at once using request()->all()
     */
    public function scopeApplyFilters($query, $filters = [])
    {
        return $query
            ->when($filters['company_id'] ?? null, function ($q, $companyId) {
                return $q->where('company_id', $companyId);
            })
            ->when($filters['project_id'] ?? null, function ($q, $projectId) {
                return $q->where('project_id', $projectId);
            })
            ->when($filters['status'] ?? null, function ($q, $status) {
                return $q->where('status', $status);
            })
            ->when($filters['search'] ?? null, function ($q, $searchTerm) {
                return $q->where(function ($subQ) use ($searchTerm) {
                    $subQ->where('name_en', 'like', '%' . $searchTerm . '%')
                          ->orWhere('name_ur', 'like', '%' . $searchTerm . '%')
                          ->orWhere('address_en', 'like', '%' . $searchTerm . '%')
                          ->orWhere('address_ur', 'like', '%' . $searchTerm . '%');
                });
            });
    }
}
