<?php

namespace Modules\Payroll\App\Models;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Payroll\App\Models\Attendance;
use Modules\Payroll\App\Models\EmployeeAllowance;
use Modules\Payroll\App\Models\EmployeeContact;
use Modules\Payroll\App\Models\EmployeeDeduction;
use Modules\Payroll\App\Models\LeaveBalance;
use Modules\Payroll\App\Models\Payroll;

class Employee extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['first_name_en', 'last_name_en', 'first_name_ur','profile_picture', 'last_name_ur', 'department_id', 'designation_id', 'joining_date', 'father_name_en', 'father_name_ur', 'cnic', 'dob', 'basic_salary', 'device_id', 'shift_id', 'gender', 'marital_status'];

    /**
     * The attributes that aren't mass assignable.
     */

    protected $guarded = [];

    /**
     * Get the contacts for the employee.
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(EmployeeContact::class);
    }

    /**
     * Get the bank accounts for the employee.
     */
    public function banks(): HasMany
    {
        return $this->hasMany(EmployeeBank::class);
    }

    /**
     * Get the department that owns the employee.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the designation that owns the employee.
     */
    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class);
    }

    /**
     * Get the allowances for the employee.
     */
    public function allowances(): HasMany
    {
        return $this->hasMany(EmployeeAllowance::class);
    }

    /**
     * Get the deductions for the employee.
     */
    public function deductions(): HasMany
    {
        return $this->hasMany(EmployeeDeduction::class);
    }

    /**
     * Get the leave balances for the employee.
     */
    public function leaveBalances(): HasMany
    {
        return $this->hasMany(LeaveBalance::class);
    }

    /**
     * Get the attendance records for the employee.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the payroll records for the employee.
     */
    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
