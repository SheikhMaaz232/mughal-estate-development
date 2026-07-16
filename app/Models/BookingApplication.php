<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingApplication extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'form_no',
        'party_id',
        'detail_account_id',
        'receivable_dealer_id',
        'previous_booking_id',
        'project_id',
        'product_id',
        'transfer_charges',
        'dealer_id',
        'date',
        'transfer_charges_account_id',
        'expense_account_id',
        'discount_amount',
        'status',
        'care_off',
        'operating_start_date',
        'operating_charges',
        'operating_receivable_account',
        'condition',
        'case',
        'add_value',
        'discount',
        'commission',
        'total_amount',
        'grand_total_amount',
        'possession_fees',
        'possession_receivable_account',
        'proceeding_fees',
        'proceeding_receivable_account',
        'development_charges',
        'development_receivable_id',
        'gst',
        'gst_receivable_account_id',
        'sevenE_chalan',
        'sevenE_chalan_receivable_account',
    ];

    // Relationships
    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    public function detailAccount()
    {
        return $this->belongsTo(DetailAccount::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function dealer()
    {
        return $this->belongsTo(DetailAccount::class, 'dealer_id');
    }

    public function possessionFeesReceivableAccount()
    {
        return $this->belongsTo(DetailAccount::class, 'possession_receivable_account');
    }

    public function proceedingFeesReceivableAccount()
    {
        return $this->belongsTo(DetailAccount::class, 'proceeding_receivable_account');
    }

    public function developmentChargesReceivableAccount()
    {
        return $this->belongsTo(DetailAccount::class, 'development_receivable_id');
    }

    public function operatingChargesReceivableAccount()
    {
        return $this->belongsTo(DetailAccount::class, 'operating_receivable_account');
    }

    public function gstReceivableAccount()
    {
        return $this->belongsTo(DetailAccount::class, 'gst_receivable_account_id');
    }

    public function sevenEChalanReceivableAccount()
    {
        return $this->belongsTo(DetailAccount::class, 'sevenE_chalan_receivable_account');
    }

    public function transferChargesAccount()
    {
        return $this->belongsTo(DetailAccount::class, 'transfer_charges_account_id');
    }

    public function receivableDealer()
    {
        return $this->belongsTo(DetailAccount::class, 'receivable_dealer_id');
    }

    public function nominee()
    {
        return $this->belongsTo(BookingNomineeDetail::class);
    }

    public function registryOrder()
    {
        return $this->hasOne(RegistryOrder::class, 'booking_id');
    }

    public static function generateBookingNo()
    {
        $getBookingApplication = BookingApplication::max('id');

        $nextId = $getBookingApplication ? $getBookingApplication + 1 : 1;

        return 'BA-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    public static function bookingNo($id)
    {
        return 'BA-' . str_pad($id, 4, '0', STR_PAD_LEFT);
    }

    public function scopeSearch($query, $filters = [])
    {
        return $query

            ->when(!empty($filters['unit_no']), function ($q) use ($filters) {
                $q->whereHas('product', function ($q2) use ($filters) {
                    $q2->where('unit_no', $filters['unit_no']);
                });
            })

            ->when(!empty($filters['booking_application_no']), function ($q) use ($filters) {
                $q->where('id', $filters['booking_application_no']);
            })

            ->when(!empty($filters['date']), function ($q) use ($filters) {
                $q->where('date', $filters['date']);
            })

            ->when(!empty($filters['case']), function ($q) use ($filters) {
                $q->where('case', $filters['case']);
            })

            ->when(!empty($filters['party_id']), function ($q) use ($filters) {
                $ids = is_array($filters['party_id'])
                    ? $filters['party_id']
                    : [$filters['party_id']];
                $q->whereIn('party_id', $ids);
            })

            ->when(!empty($filters['project_id']), function ($q) use ($filters) {
                $ids = is_array($filters['project_id'])
                    ? $filters['project_id']
                    : [$filters['project_id']];
                $q->whereIn('project_id', $ids);
            });
    }
}
