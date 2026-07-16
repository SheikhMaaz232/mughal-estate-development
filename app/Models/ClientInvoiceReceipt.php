<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ClientInvoiceReceipt extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'client_invoice_id',
        'voucher_id',
        'amount',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function clientInvoice()
    {
        return $this->belongsTo(ClientInvoice::class);
    }

    public function voucher()
    {
        return $this->belongsTo(JournalVoucher::class, 'voucher_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scopes
     */
    public function scopeForInvoice($query, $invoiceId)
    {
        return $query->where('client_invoice_id', $invoiceId);
    }

    /**
     * Accessors
     */
    public function getVoucherTypeAttribute()
    {
        if (!$this->voucher) {
            return null;
        }

        // Check if it's a BRV or CRV by looking at the voucher type or entries
        $firstEntry = $this->voucher->journalEntries()->first();

        if ($firstEntry && $firstEntry->debit_account_id) {
            $debitAccount = DetailAccount::find($firstEntry->debit_account_id);
            if ($debitAccount && str_contains(strtolower($debitAccount->name_en), 'bank')) {
                return 'BRV';
            }
            if ($debitAccount && str_contains(strtolower($debitAccount->name_en), 'cash')) {
                return 'CRV';
            }
        }

        return 'JV'; // Fallback
    }
}
