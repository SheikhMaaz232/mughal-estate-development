<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsReceivedNoteDetail extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'master_id',
        'product_id',
        'po_quantity',
        'received_qty',
        'balance',
        'detail_remarks',
    ];

    /**
     * Relationships
     */

    // A GRN Detail belongs to a GRN Master
    public function grn()
    {
        return $this->belongsTo(GoodsReceivedNoteMaster::class, 'master_id');
    }

    // A GRN Detail belongs to a Product (Item)
    public function product()
    {
        return $this->belongsTo(Item::class, 'product_id');
    }
}
