<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ContractorBill extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'tender_id',
        'work_order_id',
        'contractor_account_id',
        'bill_no',
        'bill_date',
        'total_amount',
        'remarks',
        'status',
        'voucher_id',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'bill_date' => 'date',
        'verified_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /**
     * RELATIONSHIPS
     */

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function contractorAccount()
    {
        return $this->belongsTo(DetailAccount::class, 'contractor_account_id');
    }

    public function items()
    {
        return $this->hasMany(ContractorBillItem::class);
    }

    public function payments()
    {
        return $this->hasMany(ContractorBillPayment::class);
    }

    public function journalVoucher()
    {
        return $this->belongsTo(JournalVoucher::class, 'voucher_id');
    }

    public function verifiedByUser()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * SCOPES
     */

    public function scopeSearch($query, $search)
    {
        if (!$search) return $query;

        return $query->where(function ($q) use ($search) {
            $q->where('bill_no', 'like', "%$search%")
                ->orWhere('remarks', 'like', "%$search%")
                ->orWhereHas('tender', function ($tq) use ($search) {
                    $tq->where('title_en', 'like', "%$search%");
                })
                ->orWhereHas('contractorAccount', function ($cq) use ($search) {
                    $cq->where('name', 'like', "%$search%");
                });
        });
    }

    public function scopeFilterByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function scopeFilterByTender($query, $tenderId)
    {
        if ($tenderId) {
            return $query->where('tender_id', $tenderId);
        }
        return $query;
    }

    public function scopeFilterByDate($query, $fromDate, $toDate)
    {
        if ($fromDate) {
            $query->whereDate('bill_date', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('bill_date', '<=', $toDate);
        }
        return $query;
    }

    /**
     * UTILITY METHODS
     */

    public function isVerified()
    {
        return $this->status !== 'draft' && $this->voucher_id !== null;
    }

    public function canEdit()
    {
        return $this->status === 'draft';
    }

    /**
     * Get total paid amount for this bill
     */
    public function getPaidAmount()
    {
        return $this->payments()
            ->whereIn('status', ['pending', 'posted'])
            ->sum('amount');
    }

    /**
     * Get outstanding amount for this bill
     */
    public function getOutstandingAmount()
    {
        return max(0, $this->total_amount - $this->getPaidAmount());
    }

    /**
     * Get remaining amount (alias for getOutstandingAmount)
     */
    public function getRemainigAmount()
    {
        return $this->getOutstandingAmount();
    }

    /**
     * Get posted payments only
     */
    public function getPostedPayments()
    {
        return $this->payments()
            ->where('status', 'posted')
            ->sum('amount');
    }

    /**
     * Get pending payments only
     */
    public function getPendingPayments()
    {
        return $this->payments()
            ->where('status', 'pending')
            ->sum('amount');
    }

    /**
     * Update bill payment status based on payments
     */
    public function updatePaymentStatus()
    {
        // Only update status if bill is verified
        if (!$this->isVerified()) {
            return;
        }

        $paidAmount = $this->getPostedPayments();
        $outstanding = $this->getOutstandingAmount();

        if ($outstanding == 0) {
            $this->update(['status' => 'paid']);
        } elseif ($paidAmount > 0) {
            $this->update(['status' => 'partial_paid']);
        } elseif ($this->status !== 'verified') {
            $this->update(['status' => 'verified']);
        }
    }

    /**
     * Update bill status (legacy method - kept for backward compatibility)
     */
    public function updateBillStatus()
    {
        $this->updatePaymentStatus();
    }

    /**
     * Check if bill can accept payment
     */
    public function canAcceptPayment()
    {
        return $this->isVerified() && $this->getOutstandingAmount() > 0;
    }

    /**
     * Get payment history
     */
    public function getPaymentHistory()
    {
        return $this->payments()
            ->orderBy('payment_date', 'desc')
            ->get();
    }

    /**
     * Calculate items total amount
     */
    public function calculateTotalAmount()
    {
        return $this->items()->sum('amount');
    }

    /**
     * Get status label
     */
    public function getStatusLabel()
    {
        return match($this->status) {
            'draft' => __('messages.draft'),
            'verified' => __('messages.verified'),
            'partial_paid' => __('messages.partial-paid'),
            'paid' => __('messages.paid'),
            'cancelled' => __('messages.cancelled'),
            default => $this->status,
        };
    }

    /**
     * Get contractor details for payment reference
     */
    public function getPaymentReference()
    {
        return [
            'bill_no' => $this->bill_no,
            'contractor' => $this->contractorAccount->name ?? 'N/A',
            'tender' => $this->tender->title_en ?? 'N/A',
            'amount' => $this->total_amount,
            'outstanding' => $this->getOutstandingAmount(),
        ];
    }
}
