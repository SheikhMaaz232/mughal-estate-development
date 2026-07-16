<?php

namespace Modules\Payroll\App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\Payroll\App\Console\Commands\SyncAttendanceDevices;

class PayrollServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void {}

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }

    public function boot()
    {
        View::composer('payroll::*', function ($view) {
            $view->with('departments', \App\Models\Department::all());
        });
        if ($this->app->runningInConsole()) {
            $this->commands([
                SyncAttendanceDevices::class,
            ]);
        }
    }
}
