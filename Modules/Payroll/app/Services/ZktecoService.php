<?php

namespace Modules\Payroll\App\Services;

use Jmrashed\Zkteco\Lib\ZKTeco;
use Modules\Payroll\App\Models\AttendanceDevice;
use Modules\Payroll\App\Models\AttendanceLog;

class ZktecoService
{
    protected AttendanceDevice $device;

    protected ZKTeco $zk;

    public function __construct(AttendanceDevice $device)
    {
        $this->device = $device;

        $this->zk = new ZKTeco(
            $device->ip_address,
            (int) $device->port
        );
    }

    /**
     * Connect to ZKTeco device.
     */
    public function connect(): bool
    {
        return $this->zk->connect();
    }

    /**
     * Disconnect from ZKTeco device.
     */
    public function disconnect(): void
    {
        try {
            $this->zk->disconnect();
        } catch (\Throwable $e) {
            // Ignore disconnect errors
        }
    }

    /**
     * Get device.
     */
    public function getDevice(): AttendanceDevice
    {
        return $this->device;
    }

    /**
     * Get ZKTeco client.
     */
    public function getClient(): ZKTeco
    {
        return $this->zk;
    }

    /**
     * Fetch attendance from device.
     */
    public function fetchAttendance(): array
    {
        if (!$this->connect()) {
            throw new \RuntimeException(
                "Unable to connect to ZKTeco device {$this->device->ip_address}:{$this->device->port}"
            );
        }

        try {
            return $this->zk->getAttendance();
        } finally {
            $this->disconnect();
        }
    }

    /**
     * Import attendance logs from ZKTeco.
     */
    public function importAttendance(): int
    {
        if (!$this->connect()) {
            throw new \RuntimeException(
                "Unable to connect to ZKTeco device {$this->device->ip_address}:{$this->device->port}"
            );
        }

        try {

            $records = $this->zk->getAttendance();

            $imported = 0;

            foreach ($records as $record) {

                if (
                    !isset($record['id']) ||
                    !isset($record['timestamp'])
                ) {
                    continue;
                }

                $log = AttendanceLog::firstOrCreate(
                    [
                        'device_id' => $this->device->id,
                        'device_user_id' => (string) $record['id'],
                        'punch_time' => $record['timestamp'],
                    ],
                    [
                        'punch_type' => $record['type'] ?? null,
                        'raw_data' => json_encode(
                            $record,
                            JSON_UNESCAPED_UNICODE
                        ),
                    ]
                );

                if ($log->wasRecentlyCreated) {
                    $imported++;
                }
            }

            return $imported;
        } finally {

            $this->disconnect();
        }
    }
}
