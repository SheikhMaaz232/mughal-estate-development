<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingPartnerDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'booking_id',
        'partner_relation_id',
        'partner_name_en',
        'partner_name_ur',
        'partner_father_name_en',
        'partner_father_name_ur',
        'partner_cnic_no',
    ];

    public function booking()
    {
        return $this->belongsTo(BookingApplication::class, 'booking_id');
    }

    public function relation()
    {
        return $this->belongsTo(Relation::class, 'partner_relation_id');
    }
}
