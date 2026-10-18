<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleInvoiceDetail extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'sale_invoice_master_id',
        'product_id',
        'quantity',
        'price',
        'amount',
        'detail_remarks'
    ];

    public function master()
    {
        return $this->belongsTo(SaleInvoice::class, 'sale_invoice_master_id');
    }

    public function product()
    {
        return $this->belongsTo(Item::class, 'product_id');
    }
}
