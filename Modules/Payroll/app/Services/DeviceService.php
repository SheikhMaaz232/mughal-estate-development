<?php

namespace Modules\Payroll\App\Services;

use Illuminate\Support\Facades\Log;
use Modules\Payroll\App\Models\AttendanceDevice;
use Modules\Payroll\App\Models\AttendanceLog;

class DeviceService
{
    public function syncAllDevices()
    {
        $devices = AttendanceDevice::where('is_active', true)->get();

        foreach ($devices as $device) {
            $this->syncDevice($device);
        }
    }

    public function syncDevice($device)
    {
        try {
            $zk = new \Rats\Zkteco\Lib\ZKTeco(
                $device->ip_address,
                $device->port
            );

            $zk->connect();

            $logs = $zk->getAttendance();

            foreach ($logs as $log) {

                AttendanceLog::updateOrCreate(
                    [
                        'device_id' => $device->id,
                        'device_user_id' => $log['id'],
                        'punch_time' => $log['timestamp'],
                    ],
                    [
                        'punch_type' => $log['type'] ?? null,
                        'raw_data' => json_encode($log),
                    ]
                );
            }

            $zk->disconnect();
        } catch (\Exception $e) {
            Log::error("Device Sync Failed: " . $device->name . ' ' . $e->getMessage());
        }
    }
}
