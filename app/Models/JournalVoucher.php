<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class JournalVoucher extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = ['voucher_no', 'voucher_date', 'description', 'total_debit', 'total_credit'];

    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function scopeSearch($query, $filters)
    {
        return $query
            ->when($filters['voucher_no'] ?? null, function ($query, $voucherNo) {
                $query->where('voucher_no', 'like', "%{$voucherNo}%");
            })
            ->when($filters['debit_account_id'] ?? null, function ($query, $debitAccountId) {
                $query->whereHas('journalEntries', function ($q) use ($debitAccountId) {
                    $q->where('debit_detail_account_id', $debitAccountId);
                });
            })

            ->when($filters['credit_account_id'] ?? null, function ($query, $creditAccountId) {
                $query->whereHas('journalEntries', function ($q) use ($creditAccountId) {
                    $q->where('credit_detail_account_id', $creditAccountId);
                });
            });
    }
}
