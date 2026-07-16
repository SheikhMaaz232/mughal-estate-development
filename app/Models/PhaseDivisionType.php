<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class PhaseDivisionType extends Model implements Auditable
{
    use SoftDeletes, HasFactory, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name_en',
        'name_ur',
    ];
}