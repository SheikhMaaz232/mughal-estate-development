<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LandTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'land_id',
        'transfer_date',
        'registry_type_id',
        'purchaser_account_id',
        'seller_account_id',
        'fard_no',
        'khawat_no',
        'khatoni_no',
        'mushtarqa_khata',
        'makhsoos_raqba',
        'qitaat',
        'saalam_khata',
        'hissa_mutaliqa',
        'raqba_muntaqila',
        'value',
        'attachment_1',
        'attachment_2',
        'attachment_3',
    ];

    protected $casts = [
        'transfer_date' => 'date',
        'value' => 'decimal:2',
    ];

    /**
     * Get the land being transferred
     */
    public function land(): BelongsTo
    {
        return $this->belongsTo(Land::class);
    }

    /**
     * Get the registry type
     */
    public function registryType(): BelongsTo
    {
        return $this->belongsTo(RegistryType::class);
    }

    /**
     * Get the purchaser account
     */
    public function purchaserAccount(): BelongsTo
    {
        return $this->belongsTo(DetailAccount::class, 'purchaser_account_id');
    }

    /**
     * Get the seller account
     */
    public function sellerAccount(): BelongsTo
    {
        return $this->belongsTo(DetailAccount::class, 'seller_account_id');
    }
}