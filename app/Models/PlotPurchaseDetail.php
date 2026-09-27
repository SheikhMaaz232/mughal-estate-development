<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlotPurchaseDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'plot_purchase_master_id',
        'product_id',
        'size',
        'per_marla_rate',
        'amount',
        'detail_remarks_en',
        'detail_remarks_ur',
    ];

    protected $casts = [
        'size' => 'double',
        'per_marla_rate' => 'double',
        'amount' => 'double',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function master()
    {
        return $this->belongsTo(
            PlotPurchaseMaster::class,
            'plot_purchase_master_id'
        );
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}