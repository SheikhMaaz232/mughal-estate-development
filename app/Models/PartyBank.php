<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartyBank extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'party_id',
        'bank_id',
        'account_title',
        'account_number',
        'branch_code',
    ];

    // Optional: Define relationships if necessary
    public function party()
    {
        return $this->belongsTo(Party::class, 'party_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

}
