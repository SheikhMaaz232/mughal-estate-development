<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistryOrder extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $table = 'registry_orders';

    protected $fillable = [
        'date',
        'booking_id',
        'party_id',
        'fard_id',
        'relation',
        'registry_fees',
        'registry_status',
        'registry_fees_receivable_account',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    public function booking()
    {
        return $this->belongsTo(BookingApplication::class, 'booking_id');
    }

    public function scopeSearch($query, $filters = [])
    {
        return $query

            ->when(!empty($filters['unit_no']), function ($q) use ($filters) {
                $q->whereHas('booking.product', function ($q2) use ($filters) {
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
                $q->whereIn('party_id', $ids);
            })


            ->when(!empty($filters['project_id']), function ($q) use ($filters) {
                $ids = is_array($filters['project_id'])
                    ? $filters['project_id']
                    : [$filters['project_id']];

                $q->whereHas('booking.project', function ($bookingQuery) use ($ids) {
                    $bookingQuery->whereIn('project_id', $ids);
                });
            });
    }
}
