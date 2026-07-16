<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    const TYPE_OPERATING = 'OPERATING';
    const TYPE_ADMINISTRATIVE = 'ADMINISTRATIVE';

    protected $fillable = [
        'department_type',
        'title_en',
        'title_ur'
    ];

     public static function getDepartmentTypes()
        {
            return [
                self::TYPE_OPERATING => 'Operating',
                self::TYPE_ADMINISTRATIVE => 'Administrative',
            ];
        }
}
