<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankReceiptVoucher extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'date',
        'project_id',
        'detail_account_id',
        'bank_id',
        'description_en',
        'description_ur',
        'total_amount',
        'attachment',
        'transaction_type'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function detailAccount()
    {
        return $this->belongsTo(DetailAccount::class, 'detail_account_id');
    }

    public function bank()
    {
        return $this->belongsTo(DetailAccount::class, 'bank_id');
    }

    public function scopeSearch($query,  $request = null)
    {
        return $query

            ->when(isset($request['detail_account_id']) && is_array($request['detail_account_id']), function ($q) use ($request) {
                $q->whereIn('detail_account_id', $request['detail_account_id']);
            })
            ->when(isset($request['bank_id']) && is_array($request['bank_id']), function ($q) use ($request) {
                $q->whereIn('bank_id', $request['bank_id']);
            });
    }
}
