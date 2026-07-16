<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Party extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name_en',
        'name_ur',
        'father_name_en',
        'father_name_ur',
        'cnic_no',
        'ntn_no',
        'gst_no',
        'cast_id',
        'residential_status',
        'occupation_id',
        'cnic_front_image',
        'cnic_back_image',
        'profile_image',
        'business_name_en',
        'business_name_ur',
        'business_address_en',
        'business_address_ur',
        'home_address_en',
        'home_address_ur',
        'remarks',
        'contact_number_1',
        'contact_number_2',
        'whatsApp_no',
    ];

    // Optional: Define relationships if necessary
    public function cast()
    {
        return $this->belongsTo(Cast::class, 'cast_id');
    }

    public function residentialStatus()
    {
        return $this->belongsTo(Residential::class, 'residential_status');
    }

    public function occupation()
    {
        return $this->belongsTo(OccupationType::class, 'occupation_id');
    }

    public function banks()
    {
        return $this->hasMany(PartyBank::class, 'party_id');
    }

    public function scopeSearch($query, $searchTerm = null, $request = null)
    {
        return $query
            ->when($searchTerm, function ($q) use ($searchTerm) {
                $q->where(function ($q2) use ($searchTerm) {
                    $q2->where('name_en', 'like', "%{$searchTerm}%")
                        ->orWhere('name_ur', 'like', "%{$searchTerm}%")
                        ->orWhere('cnic_no', 'like', "%{$searchTerm}%")
                        ->orWhere('whatsApp_no', 'like', "%{$searchTerm}%");
                });
            })
            ->when(isset($request['cast_id']) && is_array($request['cast_id']), function ($q) use ($request) {
                $q->whereIn('cast_id', $request['cast_id']);
            })
            ->when(isset($request['residential_status']) && is_array($request['residential_status']), function ($q) use ($request) {
                $q->whereIn('residential_status', $request['residential_status']);
            })
            ->when(isset($request['occupation_id']) && is_array($request['occupation_id']), function ($q) use ($request) {
                $q->whereIn('occupation_id', $request['occupation_id']);
            });
    }
}
