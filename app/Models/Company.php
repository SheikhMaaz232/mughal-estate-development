<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Company extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'group_id',
        'name_en',
        'name_ur',
        'address_en',
        'address_ur',
        'description_en',
        'description_ur',
        'logo',
    ];

    public function getAuditData()
    {
        return [
            'extra_field' => 'value',
        ];
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
