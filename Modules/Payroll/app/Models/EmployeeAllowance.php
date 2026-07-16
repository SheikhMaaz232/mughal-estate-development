<?php

namespace Modules\Payroll\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// use Modules\Payroll\Database\Factories\EmployeeAllowanceFactory;

class EmployeeAllowance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['employee_id',
        'allowance_id',
        'amount',
        'start_date',
        'end_date',
        'is_recurring'];

    protected $guarded = [];

     /**
     * Get the allowance that owns the employee allowance.
     */
    public function allowance(): BelongsTo
    {
        return $this->belongsTo(Allowance::class, 'allowance_id');
    }

    /**
     * Get the employee that owns the employee allowance.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
