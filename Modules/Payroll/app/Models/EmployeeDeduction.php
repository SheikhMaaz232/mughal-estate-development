<?php

namespace Modules\Payroll\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Payroll\App\Models\Deduction;
use Modules\Payroll\App\Models\Employee;

// use Modules\Payroll\Database\Factories\EmployeeDeductionFactory;

class EmployeeDeduction extends Model
{

     /**
     * The attributes that are mass assignable.
     */

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'employee_id',
        'deduction_id',
        'amount',
        'start_date',
        'end_date',
        'total_installments',
        'remaining_installments',
        'is_recurring'
    ];

    protected $guarded = [];

    /**
     * Get the deduction that owns the employee deduction.
     */
    public function deduction(): BelongsTo
    {
        return $this->belongsTo(Deduction::class, 'deduction_id');
    }

    /**
     * Get the employee that owns the employee deduction.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
