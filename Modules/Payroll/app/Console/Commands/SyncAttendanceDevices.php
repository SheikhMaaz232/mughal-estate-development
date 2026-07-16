<?php

namespace Modules\Payroll\App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Payroll\App\Services\DeviceService;

class SyncAttendanceDevices extends Command
{
    protected $signature = 'attendance:sync';
    protected $description = 'Sync attendance from all ZKTeco devices';

    public function handle(DeviceService $deviceService)
    {
        $this->info('Starting Attendance Device Sync...');

        try {
            $deviceService->syncAllDevices();

            $this->info('Attendance Sync Completed Successfully');
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
