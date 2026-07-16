<?php

namespace Modules\Payroll\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Payroll\App\Models\AttendanceLog;
// use Modules\Payroll\Database\Factories\AttendanceDeviceFactory;

class AttendanceDevice extends Model
{
    protected $fillable = ['name_en', 'name_ur', 'ip_address', 'port', 'is_active'];

    public function logs()
    {
        return $this->hasMany(AttendanceLog::class, 'device_id');
    }
}
