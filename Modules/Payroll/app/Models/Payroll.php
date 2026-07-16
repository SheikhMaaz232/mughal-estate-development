<?php

namespace Modules\Payroll\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'days_in_month',
        'total_worked_days',
        'total_absent_days',
        'total_leave_days',
        'total_holiday_days',
        'total_late_minutes',
        'total_early_leave_minutes',
        'total_overtime_minutes',
        'basic_salary',
        'daily_rate',
        'minute_rate',
        'absence_deduction_amount',
        'late_early_deduction_amount',
        'overtime_amount',
        'allowance_adjustment',
        'deduction_adjustment',
        'gross_salary',
        'net_salary',
        'is_finalized',
        'finalized_at',
    ];

    protected $casts = [
        'employee_id' => 'integer',
        'year' => 'integer',
        'days_in_month' => 'integer',
        'total_worked_days' => 'integer',
        'total_absent_days' => 'integer',
        'total_leave_days' => 'integer',
        'total_holiday_days' => 'integer',
        'total_late_minutes' => 'integer',
        'total_early_leave_minutes' => 'integer',
        'total_overtime_minutes' => 'integer',
        'basic_salary' => 'decimal:2',
        'daily_rate' => 'decimal:2',
        'minute_rate' => 'decimal:4',
        'absence_deduction_amount' => 'decimal:2',
        'late_early_deduction_amount' => 'decimal:2',
        'overtime_amount' => 'decimal:2',
        'allowance_adjustment' => 'decimal:2',
        'deduction_adjustment' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'is_finalized' => 'boolean',
        'finalized_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
