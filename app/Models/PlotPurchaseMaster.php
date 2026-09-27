<?php

namespace App\Models;
;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class PlotPurchaseMaster extends Model implements Auditable
{
    use SoftDeletes,  \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'date',
        'project_id',
        'party_id',
        'detail_account_id',
        'status',
        'gross_bill',
        'total_quantity',
        'remarks_en',
        'remarks_ur',
    ];

    protected $casts = [
        'date' => 'date',
        'gross_bill' => 'double',
        'total_quantity' => 'double',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

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

    public function details()
    {
        return $this->hasMany(
            PlotPurchaseDetail::class,
            'plot_purchase_master_id'
        );
    }

    public function scopeSearch($query,  $request = null)
    {
        return $query

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
