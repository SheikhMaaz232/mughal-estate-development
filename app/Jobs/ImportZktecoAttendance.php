<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Modules\Payroll\App\Models\AttendanceDevice;
use Modules\Payroll\App\Services\ZktecoService;

class ImportZktecoAttendance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        Log::info('ZKTeco attendance job started');

        $devices = AttendanceDevice::where('is_active', true)->get();

        Log::info('Active attendance devices found', [
            'count' => $devices->count(),
        ]);

        foreach ($devices as $device) {

            try {

                Log::info('Connecting to ZKTeco device', [
                    'device_id' => $device->id,
                    'ip' => $device->ip_address,
                    'port' => $device->port,
                ]);

                $service = new ZktecoService($device);

                $imported = $service->importAttendance();

                Log::info('ZKTeco attendance import completed', [
                    'device_id' => $device->id,
                    'imported' => $imported,
                ]);

            } catch (\Throwable $e) {

                Log::error('ZKTeco attendance import failed', [
                    'device_id' => $device->id,
                    'device_ip' => $device->ip_address,
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);
            }
        }

        Log::info('ZKTeco attendance job finished');
    }
}