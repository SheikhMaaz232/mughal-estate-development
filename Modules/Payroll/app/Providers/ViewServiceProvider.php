<?php

namespace Modules\Payroll\app\Providers;

use App\Models\Bank;
use App\Models\City;
use App\Models\Company;
use App\Models\Department;
use App\Models\Group;
use App\Models\MainHead;
use App\Models\Product;
use App\Models\Project;
use App\Models\RoadCategory;
use App\Models\Tehsil;
use App\Models\Unit;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\Payroll\App\Models\Allowance;
use Modules\Payroll\App\Models\Deduction;
use Modules\Payroll\App\Models\Designation;
use Modules\Payroll\App\Models\Shift;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer(['payroll::.*'], function ($view) {
            Log::info('Payroll view composer called for: ' . $view->getName());
            $view->with('groups', Group::all());
            $view->with('companies', Company::all());
            $view->with('cities', City::all());
            $view->with('tehsils', Tehsil::all());
            $view->with('departmentTypes', Department::getDepartmentTypes());
            $view->with('roadCategories', RoadCategory::all());
            $view->with('mainHeads', MainHead::all());
            $view->with('units', Unit::all());
            $view->with('projects', Project::all());
            $view->with('productsData', Product::all());
            $view->with('designations', Designation::all());
            $view->with('departments', cache()->remember('departments_list', 3600, function () {
                return Department::all();
            }));
            $view->with('banks', cache()->remember('banks_list', 3600, function () {
                return Bank::all();
            }));
            $view->with('allowancesList', cache()->remember('allowances_list', 3600, function () {
                return Allowance::all();
            }));
            $view->with('deductionsList', cache()->remember('deductions_list', 3600, function () {
                return Deduction::all();
            }));
        });
    }
}
