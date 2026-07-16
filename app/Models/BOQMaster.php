<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BOQMaster extends Model
{
    use SoftDeletes;

    protected $table = 'boq_masters';

    protected $fillable = [
        'construction_site_id',
        'tender_id',
        'title_en',
        'title_ur',
        'total_amount',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    // Relationships
    public function constructionSite()
    {
        return $this->belongsTo(ConstructionSite::class);
    }

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

    public function details()
    {
        return $this->hasMany(BOQDetail::class, 'boq_master_id');
    }

    /**
     * Scope to filter by construction site
     */
    public function scopeFilterByConstructionSite($query, $siteId)
    {
        if ($siteId) {
            return $query->where('construction_site_id', $siteId);
        }
        return $query;
    }

    /**
     * Scope to filter by tender
     */
    public function scopeFilterByTender($query, $tenderId)
    {
        if ($tenderId) {
            return $query->where('tender_id', $tenderId);
        }
        return $query;
    }

    /**
     * Scope to search by title
     */
    public function scopeSearch($query, $searchTerm)
    {
        if ($searchTerm) {
            return $query->where(function ($q) use ($searchTerm) {
                $q->where('title_en', 'like', '%' . $searchTerm . '%')
                  ->orWhere('title_ur', 'like', '%' . $searchTerm . '%');
            });
        }
        return $query;
    }

    /**
     * Scope to apply all filters at once
     */
    public function scopeApplyFilters($query, $filters = [])
    {
        return $query
            ->when($filters['construction_site_id'] ?? null, function ($q, $siteId) {
                return $q->where('construction_site_id', $siteId);
            })
            ->when($filters['tender_id'] ?? null, function ($q, $tenderId) {
                return $q->where('tender_id', $tenderId);
            })
            ->when($filters['search'] ?? null, function ($q, $searchTerm) {
                return $q->where(function ($subQ) use ($searchTerm) {
                    $subQ->where('title_en', 'like', '%' . $searchTerm . '%')
                          ->orWhere('title_ur', 'like', '%' . $searchTerm . '%');
                });
            });
    }
}
