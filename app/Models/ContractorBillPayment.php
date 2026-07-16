<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ContractorBillPayment extends Model
{
    use SoftDeletes;

    protected $table = 'contractor_bill_payments';

    protected $fillable = [
        'contractor_bill_id',
        'voucher_id',
        'voucher_type',
        'amount',
        'payment_date',
        'payment_method',
        'remarks',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * RELATIONSHIPS
     */

    public function contractorBill()
    {
        return $this->belongsTo(ContractorBill::class);
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
     * Get the payment voucher (BPV or CPV) - Polymorphic pattern
     */
    public function getVoucherDetails()
    {
        if (!$this->voucher_id || !$this->voucher_type) {
            return null;
        }

        if ($this->voucher_type === 'BPV') {
            return BankPaymentVoucher::find($this->voucher_id);
        } elseif ($this->voucher_type === 'CPV') {
            return CashPaymentVoucher::find($this->voucher_id);
        }
        return null;
    }

    /**
     * QUERY SCOPES
     */

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePosted($query)
    {
        return $query->where('status', 'posted');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeForBill($query, $billId)
    {
        return $query->where('contractor_bill_id', $billId);
    }

    public function scopeByVoucherType($query, $type)
    {
        return $query->where('voucher_type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('payment_date', 'desc');
    }

    /**
     * OBSERVERS/HOOKS
     */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
                $model->updated_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });
    }

    /**
     * HELPER METHODS
     */

    public function canCancel()
    {
        return $this->status === 'pending';
    }

    public function canPost()
    {
        return $this->status === 'pending' && $this->voucher_id;
    }

    public function markAsPosted()
    {
        if (!$this->canPost()) {
            throw new \Exception('Payment cannot be posted. Status: ' . $this->status);
        }
        $this->update([
            'status' => 'posted',
            'payment_date' => $this->payment_date ?? now(),
        ]);
        return $this;
    }

    public function markAsCancelled()
    {
        if (!$this->canCancel()) {
            throw new \Exception('Cannot cancel a posted payment');
        }
        $this->update(['status' => 'cancelled']);
        return $this;
    }

    /**
     * Get human-readable status label
     */
    public function getStatusLabel()
    {
        return match($this->status) {
            'pending' => __('messages.pending'),
            'posted' => __('messages.posted'),
            'cancelled' => __('messages.cancelled'),
            default => $this->status,
        };
    }

    /**
     * Get voucher type label
     */
    public function getVoucherTypeLabel()
    {
        return match($this->voucher_type) {
            'BPV' => __('messages.bank-payment-voucher'),
            'CPV' => __('messages.cash-payment-voucher'),
            default => $this->voucher_type ?? 'N/A',
        };
    }
}
