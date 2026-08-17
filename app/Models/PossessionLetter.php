<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class PossessionLetter extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $table = 'possession_letters';

    protected $fillable = [
        'file_no',
        'date',
        'project_id',
        'product_id',
        'party_id',
        'east_side',
        'east_bounded_by',
        'west_side',
        'status',
        'west_bounded_by',
        'south_side',
        'south_bounded_by',
        'north_side',
        'north_bounded_by',
        'kanal',
        'marla',
        'square_feet',
        'total_marla',
        'total_square_feet',
        'special_note',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    public function scopeSearch($query, $filters = [])
    {
        return $query

            ->when(!empty($filters['unit_no']), function ($q) use ($filters) {
                $q->whereHas('product', function ($q2) use ($filters) {
                    $q2->where('unit_no', $filters['unit_no']);
                });
            })

            ->when(!empty($filters['booking_application_no']), function ($q) use ($filters) {
                $q->where('file_no', $filters['booking_application_no']);
            })

            ->when(!empty($filters['date']), function ($q) use ($filters) {
                $q->where('date', $filters['date']);
            })

            ->when(!empty($filters['party_id']), function ($q) use ($filters) {
                $ids = is_array($filters['party_id'])
                    ? $filters['party_id']
                    : [$filters['party_id']];
                $q->whereIn('party_id', $ids);
            })

            ->when(!empty($filters['project_id']), function ($q) use ($filters) {
                $ids = is_array($filters['project_id'])
                    ? $filters['project_id']
                    : [$filters['project_id']];
                $q->whereIn('project_id', $ids);
            });
    }
}
