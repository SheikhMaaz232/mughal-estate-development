<?php

namespace Modules\Payroll\App\Services;


use Jmrashed\Zkteco\Lib\ZKTeco;
use Modules\Payroll\App\Models\AttendanceDevice;

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

    public function connect(): bool
    {
        return $this->zk->connect();
    }

    public function disconnect(): void
    {
        $this->zk->disconnect();
    }

    public function getDevice(): AttendanceDevice
    {
        return $this->device;
    }

    public function getClient(): ZKTeco
    {
        return $this->zk;
    }
}
