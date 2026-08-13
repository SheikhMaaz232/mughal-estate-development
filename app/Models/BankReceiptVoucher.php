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
        'transaction_type',
        'status'
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

    public function scopeSearch($query, $filters)
    {
        if (!empty($filters['detail_account_id'])) {

            $detailAccountIds = (array) $filters['detail_account_id'];

            $query->whereIn(
                'detail_account_id',
                $detailAccountIds
            );
        }

        if (!empty($filters['bank_id'])) {

            $bankIds = (array) $filters['bank_id'];

            $query->whereIn(
                'bank_id',
                $bankIds
            );
        }

        if (!empty($filters['project_id'])) {

            $projectIds = (array) $filters['project_id'];

            $query->whereIn(
                'project_id',
                $projectIds
            );
        }

        if (!empty($filters['voucher_no'])) {

            $query->where(
                'id',
                $filters['voucher_no']
            );
        }

        return $query;
    }
}
