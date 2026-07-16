<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Apply operating charges daily for complete months
        $schedule->command('charges:apply-operating')
            ->daily()
            ->timezone('Asia/Karachi');
    }

    /**
     * Register the commands for the application.
     *
     * Note: In Laravel 12, commands are registered via AppServiceProvider
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
