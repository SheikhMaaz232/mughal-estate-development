<?php

namespace Modules\Payroll\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Payroll\Database\Factories\RelationFactory;

class Relation extends Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): RelationFactory
    // {
    //     // return RelationFactory::new();
    // }
}
