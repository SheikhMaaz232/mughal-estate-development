<?php

namespace Modules\Payroll\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
// use Modules\Payroll\Database\Factories\GradsFactory;

class Grade extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected $guarded = [];

    // protected static function newFactory(): GradsFactory
    // {
    //     // return GradsFactory::new();
    // }
}
