<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsReceivedNoteMaster extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'purchase_order_no',
        'date',
        'project_id',
        'party_id',
        'detail_account_id',
        'fare',
        'supplier_bill_no',
        'unloaded_by',
        'status',
        'driver_name',
        'total_po_quantity',
        'total_received_quantity',
        'remarks',
    ];

    /**
     * Relationships
     */

    // A GRN Master belongs to a Project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // A GRN Master belongs to a Party
    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    // A GRN Master belongs to a Detail Account
    public function detailAccount()
    {
        return $this->belongsTo(DetailAccount::class);
    }

    // A GRN Master has many GRN Details
    public function grnDetails()
    {
        return $this->hasMany(GoodsReceivedNoteDetail::class, 'master_id');
    }

    public function scopeSearch($query,  $request = null)
    {
        return $query

            ->when(!empty($request['purchase_order_no']), function ($q) use ($request) {
                $q->where('purchase_order_no', $request['purchase_order_no']);
            })

            ->when(!empty($request['grn_no']), function ($q) use ($request) {
                $q->where('id', $request['grn_no']);
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
