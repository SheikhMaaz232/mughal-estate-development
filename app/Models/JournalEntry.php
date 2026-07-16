<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class JournalEntry extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'journal_voucher_id',
        'credit_detail_account_id',
        'debit_detail_account_id',
        'detail_description_en',
        'detail_description_ur',
        'debit',
        'credit',
    ];

    public function debitAccount()
    {
        return $this->belongsTo(DetailAccount::class, 'debit_detail_account_id');
    }

    public function creditAccount()
    {
        return $this->belongsTo(DetailAccount::class, 'credit_detail_account_id');
    }
}
