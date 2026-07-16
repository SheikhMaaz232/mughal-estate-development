<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\User;
use App\Observers\AuditLogObserver;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Spatie\Permission\Models\Permission;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Console\Commands\ApplyOperatingCharges::class,
                \App\Console\Commands\TestLandRegistrationPerformance::class,
                \App\Console\Commands\TestDatabasePerformance::class,
            ]);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(
            AuditLogObserver::class);
        //app()->setLocale(session('locale', config('app.locale')));
        App::setLocale(session('locale', config('app.locale')));

        View::composer('layouts.backend', function ($view) {
            $companyId = session('selected_company_id');
            $company = $companyId ? Company::find($companyId) : null;
            $view->with('selectedCompany', $company);
        });

        if (PHP_OS_FAMILY === 'Windows') {
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
            \Illuminate\Support\Facades\Artisan::call('view:clear');
        }

        Paginator::useBootstrapFive();

        // if (PHP_OS_FAMILY === 'Windows') {
        //     config(['view.compiled' => realpath(storage_path('framework/views'))]);
        // }

        Permission::created(function ($permission) {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        });

        Permission::updated(function ($permission) {
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        });

        Permission::deleted(function ($permission) {
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        });
    }
}
