<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingReturn extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $table = 'booking_returns';

    protected $fillable = [
        'booking_id',
        'detail_account_id',
        'receivable_detail_account_id',
        'cancellation_charges_account_id',
        'percentage_value',
        'cash_bank_account',
        'status',
        'date',
        'project_id',
        'remarks'
    ];

    /**
     * Relationship: BookingReturn belongs to BookingApplication
     */
    public function bookingApplication()
    {
        return $this->belongsTo(BookingApplication::class, 'booking_id');
    }

    public function detailAccount()
    {
        return $this->belongsTo(DetailAccount::class, 'detail_account_id');
    }

    public function receivableDetailAccount()
    {
        return $this->belongsTo(DetailAccount::class, 'receivable_detail_account_id');
    }

    public function cancellationChargesAccount()
    {
        return $this->belongsTo(DetailAccount::class, 'cancellation_charges_account_id');
    }

    public function cashBank()
    {
        return $this->belongsTo(DetailAccount::class, 'cash_bank_account');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function scopeSearch($query, $filters = [])
    {
        return $query

            ->when(!empty($filters['unit_no']), function ($q) use ($filters) {
                $q->whereHas('bookingApplication.product', function ($q2) use ($filters) {
                    $q2->where('unit_no', $filters['unit_no']);
                });
            })

            ->when(!empty($filters['booking_application_no']), function ($q) use ($filters) {
                $q->where('booking_id', $filters['booking_application_no']);
            })

            ->when(!empty($filters['date']), function ($q) use ($filters) {
                $q->where('date', $filters['date']);
            })

            ->when(!empty($filters['party_id']), function ($q) use ($filters) {
                $ids = is_array($filters['party_id'])
                    ? $filters['party_id']
                    : [$filters['party_id']];

                $q->whereHas('bookingApplication', function ($q2) use ($ids) {
                    $q2->whereIn('party_id', $ids);
                });
            })

            ->when(!empty($filters['project_id']), function ($q) use ($filters) {
                $ids = is_array($filters['project_id'])
                    ? $filters['project_id']
                    : [$filters['project_id']];
                $q->whereIn('project_id', $ids);
            });
    }
}
