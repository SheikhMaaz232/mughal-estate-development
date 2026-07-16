<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Dealer extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name_en',
        'name_ur',
        'address_en',
        'address_ur',
        'mobile_number',
        'phone_number',
        'photo'
    ];

    // app/Models/Dealer.php

public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function($q) use ($searchTerm) {
            $q->where('name_en', 'like', "%{$searchTerm}%")
            ->orWhere('name_ur', 'like', "%{$searchTerm}%")
            ->orWhere('address_en', 'like', "%{$searchTerm}%")
            ->orWhere('address_ur', 'like', "%{$searchTerm}%")
            ->orWhere('mobile_number', 'like', "%{$searchTerm}%")
            ->orWhere('phone_number', 'like', "%{$searchTerm}%");
        });
    }
}
