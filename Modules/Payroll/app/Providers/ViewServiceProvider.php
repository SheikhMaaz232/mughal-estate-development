<?php

namespace Modules\Payroll\App\Providers;

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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\Payroll\App\Models\Allowance;
use Modules\Payroll\App\Models\Deduction;
use Modules\Payroll\App\Models\Designation;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {

        View::composer('payroll::*', function ($view) {

            $view->with('groups', Cache::rememberForever('payroll_groups', function () {
                return Group::select('id', 'name_en', 'name_ur')->get();
            }));

            $view->with('companies', Cache::rememberForever('payroll_companies', function () {
                return Company::select('id', 'name_en', 'name_ur')->get();
            }));

            $view->with('cities', Cache::rememberForever('payroll_cities', function () {
                return City::select('id', 'name_en', 'name_ur')->get();
            }));

            $view->with('tehsils', Cache::rememberForever('payroll_tehsils', function () {
                return Tehsil::select('id', 'name_en', 'name_ur')->get();
            }));

            $view->with('departmentTypes', Cache::rememberForever('payroll_department_types', function () {
                return Department::getDepartmentTypes();
            }));

            $view->with('roadCategories', Cache::rememberForever('payroll_road_categories', function () {
                return RoadCategory::select('id', 'title_en', 'title_ur')->get();
            }));

            $view->with('mainHeads', Cache::rememberForever('payroll_main_heads', function () {
                return MainHead::select('id', 'name_en', 'name_ur')->get();
            }));

            $view->with('units', Cache::rememberForever('payroll_units', function () {
                return Unit::select('id', 'name_en', 'name_ur')->get();
            }));

            $view->with('projects', Cache::rememberForever('payroll_projects', function () {
                return Project::select('id', 'name_en', 'name_ur')->get();
            }));

            $view->with('productsData', Cache::rememberForever('payroll_products', function () {
                return Product::select('id', 'name_en', 'name_ur')->get();
            }));

            $view->with('designations', Cache::rememberForever('payroll_designations', function () {
                return Designation::select('id', 'title_en', 'title_ur')->get();
            }));

            $view->with('departments', Cache::rememberForever('payroll_departments', function () {
                return Department::select('id', 'name_en', 'name_ur')->get();
            }));

            $view->with('banks', Cache::rememberForever('payroll_banks', function () {
                return Bank::select('id', 'name_en', 'name_ur')->get();
            }));

            $view->with('allowancesList', Cache::rememberForever('payroll_allowances', function () {
                return Allowance::select('id', 'title_en', 'title_ur')->get();
            }));

            $view->with('deductionsList', Cache::rememberForever('payroll_deductions', function () {
                return Deduction::select('id', 'title_en', 'title_ur')->get();
            }));
        });
    }
}