<?php

namespace Modules\Payroll\App\Models;

use Illuminate\Database\Eloquent\Model;
// use Modules\Payroll\Database\Factories\AttendanceLogFactory;

class AttendanceLog extends Model
{
    protected $fillable = [
        'device_id',
        'device_user_id',
        'punch_time',
        'punch_type',
        'raw_data'
    ];

    protected $casts = [
        'punch_time' => 'datetime'
    ];

    public function device()
    {
        return $this->belongsTo(AttendanceDevice::class);
    }
}
