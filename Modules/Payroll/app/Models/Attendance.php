<?php

namespace Modules\Payroll\App\Models;

use Illuminate\Database\Eloquent\Model;
// use Modules\Payroll\Database\Factories\AttendanceFactory;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'late_minutes',
        'early_leave_minutes',
        'overtime_minutes',
        'status',
        'is_manual'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
