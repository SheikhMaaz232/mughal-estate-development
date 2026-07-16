<?php

namespace Modules\Payroll\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Payroll\Database\Factories\ShiftFactory;

class Shift extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['shift_name_en', 'shift_name_ur', 'start_time', 'end_time', 'description'];

    /**
     * The attributes that aren't mass assignable.
     */

    protected $guarded = [];

    // protected static function newFactory(): ShiftFactory
    // {
    //     // return ShiftFactory::new();
    // }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
