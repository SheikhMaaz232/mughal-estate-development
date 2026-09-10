<?php

namespace Modules\Payroll\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Payroll\App\Models\Employee;
// use Modules\Payroll\Database\Factories\EmployeeWeeklyHolidayFactory;

class EmployeeWeeklyHoliday extends Model
{
    use SoftDeletes;
    protected $table = 'employee_weekly_holidays';

    protected $fillable = [
        'employee_id',
        'day_of_week',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
