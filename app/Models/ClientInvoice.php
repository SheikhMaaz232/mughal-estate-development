<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ClientInvoice extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'tender_id',
        'client_id',
        'invoice_no',
        'invoice_date',
        'amount',
        'remarks_en',
        'remarks_ur',
        'status',
        'journal_voucher_id',
        'verified_by',
        'verified_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'verified_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

    public function client()
    {
        return $this->belongsTo(DetailAccount::class, 'client_id');
    }

    public function journalVoucher()
    {
        return $this->belongsTo(JournalVoucher::class);
    }

    public function receipts()
    {
        return $this->hasMany(ClientInvoiceReceipt::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scopes
     */
    public function scopeSearch($query, $filters = [])
    {
        return $query
            ->when(!empty($filters['search']), function ($q) use ($filters) {
                $search = $filters['search'];
                $q->where(function ($subQ) use ($search) {
                    $subQ->where('invoice_no', 'like', "%{$search}%")
                        ->orWhere('remarks', 'like', "%{$search}%");
                });
            })
            ->when(!empty($filters['tender_id']), function ($q) use ($filters) {
                $q->where('tender_id', $filters['tender_id']);
            })
            ->when(!empty($filters['client_id']), function ($q) use ($filters) {
                $q->where('client_id', $filters['client_id']);
            })
            ->when(!empty($filters['status']), function ($q) use ($filters) {
                $q->where('status', $filters['status']);
            })
            ->when(!empty($filters['from_date']), function ($q) use ($filters) {
                $q->whereDate('invoice_date', '>=', $filters['from_date']);
            })
            ->when(!empty($filters['to_date']), function ($q) use ($filters) {
                $q->whereDate('invoice_date', '<=', $filters['to_date']);
            });
    }

    /**
     * Check if invoice can be edited
     */
    public function canBeEdited(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if invoice can be verified
     */
    public function canBeVerified(): bool
    {
        return $this->status === 'draft' && $this->journal_voucher_id === null;
    }

    /**
     * Check if invoice is already verified
     */
    public function isVerified(): bool
    {
        return $this->status !== 'draft';
    }

    /**
     * Check if JV is posted
     */
    public function isJVPosted(): bool
    {
        return $this->journal_voucher_id !== null;
    }

    /**
     * Get total received amount
     */
    public function getTotalReceivedAttribute(): float
    {
        return $this->receipts()->sum('amount');
    }

    /**
     * Get outstanding receivable amount
     */
    public function getOutstandingReceivableAttribute(): float
    {
        return $this->amount - $this->total_received;
    }

    /**
     * Update status based on received amount
     */
    public function updateStatusBasedOnReceipts(): void
    {
        $totalReceived = $this->total_received;

        if ($totalReceived == 0) {
            $this->status = 'verified';
        } elseif ($totalReceived < $this->amount) {
            $this->status = 'partial_received';
        } else {
            $this->status = 'received';
        }

        $this->save();
    }

    /**
     * Check if invoice is fully received
     */
    public function isFullyReceived(): bool
    {
        return $this->total_received >= $this->amount;
    }

    /**
     * Check if invoice has partial receipts
     */
    public function hasPartialReceipts(): bool
    {
        return $this->total_received > 0 && $this->total_received < $this->amount;
    }
}
