<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'main_head_id',
        'control_head_id',
        'sub_head_id',
        'sub_sub_head_id',
        'sub_sub_sub_head_id',
        'company_id',
        'project_id',
        'road_id',
        'front_id',
        'amount_in_pkr',
        'front_width',
        'length',
        'block',
        'front_width2',
        'length2',
        'total_amount',
        'unit_no',
        'code',
        'kanal',
        'marla',
        'total_marla',
        'square_feet',
        'total_square_feet',
        'status',
        'image',
        'name_en',
        'name_ur',
        'termsAndConditions',
        'type',
    ];

    // Relationships
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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function road()
    {
        return $this->belongsTo(RoadCategory::class);
    }

    public function facing()
    {
        return $this->belongsTo(Facing::class, 'front_id');
    }

    public function scopeSearch($query, $filters = [])
    {
        return $query

            ->when(!empty($filters['search']), function ($q) use ($filters) {
                $q->where(function ($q2) use ($filters) {
                    $q2->where('name_en', 'like', "%{$filters['search']}%")
                        ->orWhere('name_ur', 'like', "%{$filters['search']}%");
                });
            })

            ->when(!empty($filters['unit_no']), function ($q) use ($filters) {
                $q->where('unit_no', $filters['unit_no']);
            })

            ->when(!empty($filters['project_id']), function ($q) use ($filters) {
                $ids = is_array($filters['project_id'])
                    ? $filters['project_id']
                    : [$filters['project_id']];
                $q->whereIn('project_id', $ids);
            })

            ->when(!empty($filters['main_head_id']), function ($q) use ($filters) {
                $ids = is_array($filters['main_head_id'])
                    ? $filters['main_head_id']
                    : [$filters['main_head_id']];
                $q->whereIn('main_head_id', $ids);
            })

            ->when(!empty($filters['control_head_id']), function ($q) use ($filters) {
                $ids = is_array($filters['control_head_id'])
                    ? $filters['control_head_id']
                    : [$filters['control_head_id']];
                $q->whereIn('control_head_id', $ids);
            })

            ->when(!empty($filters['sub_head_id']), function ($q) use ($filters) {
                $ids = is_array($filters['sub_head_id'])
                    ? $filters['sub_head_id']
                    : [$filters['sub_head_id']];
                $q->whereIn('sub_head_id', $ids);
            })

            ->when(!empty($filters['sub_sub_head_id']), function ($q) use ($filters) {
                $ids = is_array($filters['sub_sub_head_id'])
                    ? $filters['sub_sub_head_id']
                    : [$filters['sub_sub_head_id']];
                $q->whereIn('sub_sub_head_id', $ids);
            })

            ->when(!empty($filters['sub_sub_sub_head_id']), function ($q) use ($filters) {
                $ids = is_array($filters['sub_sub_sub_head_id'])
                    ? $filters['sub_sub_sub_head_id']
                    : [$filters['sub_sub_sub_head_id']];
                $q->whereIn('sub_sub_sub_head_id', $ids);
            });
    }
}
