<?php

namespace Modules\Payroll\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Payroll\App\Models\HolidayType;
use OwenIt\Auditing\Contracts\Auditable;
// use Modules\Payroll\Database\Factories\HolidayFactory;

class Holiday extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name_en', 'name_ur', 'date', 'holiday_type_id', 'is_paid'];
    protected $casts = [
        'date' => 'date',
        'is_paid' => 'boolean',
    ];
    // protected static function newFactory(): HolidayFactory
    // {
    //     // return HolidayFactory::new();
    // }

    public function holidayType()
    {
        return $this->belongsTo(HolidayType::class);
    }
}
