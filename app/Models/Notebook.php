<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notebook extends Model
{
    protected $fillable = [
        'name_en',
        'name_ur',
        'description',
        'color',
        'user_id',
    ];
}
