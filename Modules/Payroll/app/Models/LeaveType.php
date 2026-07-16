<?php

namespace Modules\Payroll\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Payroll\App\Models\LeaveBalance;
// use Modules\Payroll\Database\Factories\LeaveTypeFactory;

class LeaveType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected $guarded = [];

    /**
     * Get leave balances for this leave type.
     */
    public function leaveBalances(): HasMany
    {
        return $this->hasMany(LeaveBalance::class);
    }

    // protected static function newFactory(): LeaveTypeFactory
    // {
    //     // return LeaveTypeFactory::new();
    // }
}
