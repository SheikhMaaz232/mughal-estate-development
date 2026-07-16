<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingNomineeDetail extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'booking_id',
        'relation_id',
        'nominee_party_id',
        'created_at',
        'updated_at',
    ];

    public function booking()
    {
        return $this->belongsTo(BookingApplication::class, 'booking_id');
    }

    public function relation()
    {
        return $this->belongsTo(Relation::class, 'relation_id');
    }

    public function nomineeParty()
    {
        return $this->belongsTo(Party::class, 'nominee_party_id');
    }
}
