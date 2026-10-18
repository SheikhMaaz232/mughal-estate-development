<?php

namespace Modules\Payroll\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Payroll\Database\Factories\QualificationFactory;

class Qualification extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected $guarded = [];
    // protected static function newFactory(): QualificationFactory
    // {
    //     // return QualificationFactory::new();
    // }
}
