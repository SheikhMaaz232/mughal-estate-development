<?php

namespace Modules\Payroll\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Payroll\App\Models\Employee;

class EmployeeContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'type',
        'phone',
        'email',
        'address'
    ];

    protected $casts = [
        'type' => 'string',
    ];

    /**
     * Get the employee that owns the contact.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
