<?php

namespace Modules\Payroll\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Payroll\Database\Factories\PayrollTypeFactory;

class PayrollType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected $guarded = [];
    // protected static function newFactory(): PayrollTypeFactory
    // {
    //     // return PayrollTypeFactory::new();
    // }
}
