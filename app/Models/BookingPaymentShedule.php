<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingPaymentShedule extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'booking_id',
        'schedule_type_id',
        'schedule_period_id',
        'due_date',
        'number',
        'pay_amount',
        'calculated_total_amount',
        'created_at',
        'updated_at',

    ];

    public function booking()
    {
        return $this->belongsTo(BookingApplication::class, 'booking_id');
    }

    public function scheduleType()
    {
        return $this->belongsTo(ScheduleType::class, 'schedule_type_id');
    }

    public function schedulePeriod()
    {
        return $this->belongsTo(SchedulePeriod::class, 'schedule_period_id');
    }
}
