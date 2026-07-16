<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tender extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'construction_site_id',
        'contractee_account_id',
        'contractor_account_id',
        'revenue_account_id',
        'expense_account_id',
        'title_en',
        'title_ur',
        'description_en',
        'description_ur',
        'work_type',
        'estimated_cost',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'estimated_cost' => 'decimal:2',
    ];

    // Relationships
    public function constructionSite()
    {
        return $this->belongsTo(ConstructionSite::class, 'construction_site_id');
    }

    public function contracteeAccount()
    {
        return $this->belongsTo(DetailAccount::class, 'contractee_account_id');
    }

    public function contractorAccount()
    {
        return $this->belongsTo(DetailAccount::class, 'contractor_account_id');
    }

    public function revenueAccount()
    {
        return $this->belongsTo(DetailAccount::class, 'revenue_account_id');
    }

    public function expenseAccount()
    {
        return $this->belongsTo(DetailAccount::class, 'expense_account_id');
    }

    /**
     * Scope to apply all filters at once
     */
 

    public function scopeSearch($query, $filters = [])
    {
        return $query

            ->when(!empty($filters['construction_site_id']), function ($q) use ($filters) {
                $q->where('construction_site_id', $filters['construction_site_id']);
            })

            ->when(!empty($filters['status']), function ($q) use ($filters) {
                $q->where('status', $filters['status']);
            })

            ->when(!empty($filters['search']), function ($q) use ($filters) {
                $searchTerm = $filters['search'];
                $q->where(function ($subQ) use ($searchTerm) {
                    $subQ->where('title_en', 'like', '%' . $searchTerm . '%')
                        ->orWhere('title_ur', 'like', '%' . $searchTerm . '%')
                        ->orWhere('work_type', 'like', '%' . $searchTerm . '%');
                });
            });
    }
}
