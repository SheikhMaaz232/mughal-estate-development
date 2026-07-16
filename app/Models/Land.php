<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Land extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_account_id',
        'buyer_account_id',
        'commission_account_id',
        'product_account',
        'total_murabba',
        'total_acre',
        'total_kanal',
        'total_wigha',
        'total_marla',
        'total_square_feet',
        'remarks',
        'project_id',
        'commission_amount',
        'land_amount',
        'terms_conditions_en',
        'terms_conditions_ur',
    ];

    public function details()
    {
        return $this->hasMany(LandDetail::class);
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function sellerAccount()
    {
        return $this->belongsTo(DetailAccount::class, 'seller_account_id');
    }

    public function buyerAccount()
    {
        return $this->belongsTo(DetailAccount::class, 'buyer_account_id');
    }

    public function commissionAccount()
    {
        return $this->belongsTo(DetailAccount::class, 'commission_account_id');
    }
    public function transfers()
    {
        return $this->hasMany(LandTransfer::class, 'land_id');
    }
}
