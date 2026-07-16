<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'land_id',
        'khawat_no',
        'fard_id_no',
        'registry_no',
        'moza',
        'murabba',
        'acre',
        'kanal',
        'wigha',
        'marla',
        'square_feet',
        'remarks',
    ];

    public function land()
    {
        return $this->belongsTo(Land::class);
    }

    public function transfers()
    {
        return $this->hasMany(LandTransfer::class, 'land_id', 'land_id')
                    ->where('khawat_no', $this->khawat_no);
    }
}
