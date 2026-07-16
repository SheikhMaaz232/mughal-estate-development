<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'date',
        'project_id',
        'party_id',
        'detail_account_id',
        'contact_person',
        'status',
        'remarks',
        'gross_total',
        'tax_amount',
        'shipping_amount',
        'other_amount',
        'total_amount',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    public function detailAccount()
    {
        return $this->belongsTo(DetailAccount::class);
    }

    public function purchaseOrderDetails()
    {
        return $this->hasMany(purchaseOrderDetails::class, 'purchase_order_master_id');
    }

    public function scopeSearch($query,  $request = null)
    {
        return $query

            ->when(!empty($request['purchase_order_no']), function ($q) use ($request) {
                $q->where('id', $request['purchase_order_no']);
            })

            ->when(!empty($request['date']), function ($q) use ($request) {
                $q->where('date', $request['date']);
            })

            ->when(isset($request['detail_account_id']) && is_array($request['detail_account_id']), function ($q) use ($request) {
                $q->whereIn('detail_account_id', $request['detail_account_id']);
            })
            ->when(isset($request['party_id']) && is_array($request['party_id']), function ($q) use ($request) {
                $q->whereIn('party_id', $request['party_id']);
            });
    }
}
