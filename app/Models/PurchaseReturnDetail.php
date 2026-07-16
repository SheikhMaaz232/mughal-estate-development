<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseReturnDetail extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'purchase_return_master_id',
        'product_id',
        'quantity',
        'price',
        'amount',
        'detail_remarks'
    ];

    public function master()
    {
        return $this->belongsTo(PurchaseReturnMaster::class, 'purchase_return_master_id');
    }

    public function product()
    {
        return $this->belongsTo(Item::class, 'product_id');
    }
}
