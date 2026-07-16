<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseReturnMaster extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'grn_no',
        'purchase_order_no',
        'purchase_invoice_no',
        'date',
        'project_id',
        'party_id',
        'status',
        'detail_account_id',
        'supplier_bill_no',
        'unloaded_by',
        'carriage',
        'gross_bill',
        'tax',
        'other_amount',
        'net_amount',
        'total_quantity',
        'remarks',
    ];

    public function details()
    {
        return $this->hasMany(PurchaseReturnDetail::class, 'purchase_return_master_id');
    }

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

    public function scopeSearch($query,  $request = null)
    {
        return $query

            ->when(!empty($request['purchase_order_no']), function ($q) use ($request) {
                $q->where('purchase_order_no', $request['purchase_order_no']);
            })

            ->when(!empty($request['grn_no']), function ($q) use ($request) {
                $q->where('grn_no', $request['grn_no']);
            })


            ->when(!empty($request['purchase_invoice_no']), function ($q) use ($request) {
                $q->where('purchase_invoice_no', $request['purchase_invoice_no']);
            })

            ->when(!empty($request['id']), function ($q) use ($request) {
                $q->where('id', $request['id']);
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
