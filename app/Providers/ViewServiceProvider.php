<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

use App\Models\Bank;
use App\Models\Cast;
use App\Models\City;
use App\Models\Company;
use App\Models\ConstructionSite;
use App\Models\ControlHead;
use App\Models\Department;
use App\Models\DetailAccount;
use App\Models\Facing;
use App\Models\Group;
use App\Models\Item;
use App\Models\MainHead;
use App\Models\OccupationType;
use App\Models\Party;
use App\Models\Product;
use App\Models\Project;
use App\Models\Relation;
use App\Models\Residential;
use App\Models\RoadCategory;
use App\Models\SchedulePeriod;
use App\Models\ScheduleType;
use App\Models\SubHead;
use App\Models\SubSubHead;
use App\Models\SubSubSubHead;
use App\Models\Tehsil;
use App\Models\Tender;
use App\Models\Unit;
use App\Models\WorkOrder;

use Modules\Payroll\App\Models\Allowance;
use Modules\Payroll\App\Models\Deduction;
use Modules\Payroll\App\Models\LeaveType;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        /**
         * Don't execute on authentication/public pages
         */
        if (
            Request::is('login') ||
            Request::is('forgot-password') ||
            Request::is('reset-password*') ||
            Request::is('register')
        ) {
            return;
        }

        View::composer('*', function ($view) {

            $view->with('allowances', Cache::rememberForever('allowances', fn() => Allowance::all()));

            $view->with('deductions', Cache::rememberForever('deductions', fn() => Deduction::all()));

            $view->with('leaveTypesList', Cache::rememberForever('leave_types', fn() => LeaveType::all()));

            $view->with('groups', Cache::rememberForever('groups', fn() =>
                Group::select('id','name_en','name_ur')->get()));

            $view->with('companies', Cache::rememberForever('companies', fn() =>
                Company::select('id','name_en','name_ur')->get()));

            $view->with('cities', Cache::rememberForever('cities', fn() =>
                City::select('id','name_en','name_ur')->get()));

            $view->with('tehsils', Cache::rememberForever('tehsils', fn() =>
                Tehsil::select('id','name_en','name_ur')->get()));

            $view->with('departmentTypes', Cache::rememberForever('department_types', fn() =>
                Department::getDepartmentTypes()));

            $view->with('roadCategories', Cache::rememberForever('road_categories', fn() =>
                RoadCategory::select('id','title_en','title_ur')->get()));

            $view->with('mainHeads', Cache::rememberForever('main_heads', fn() =>
                MainHead::select('id','name_en','name_ur')->get()));

            $view->with('searchControlHeads', Cache::rememberForever('control_heads', fn() =>
                ControlHead::select('id','name_en','name_ur')->get()));

            $view->with('searchSubHeads', Cache::rememberForever('sub_heads', fn() =>
                SubHead::select('id','name_en','name_ur')->get()));

            $view->with('searchSubSubHeads', Cache::rememberForever('sub_sub_heads', fn() =>
                SubSubHead::select('id','name_en','name_ur')->get()));

            $view->with('searchSubSubSubHeads', Cache::rememberForever('sub_sub_sub_heads', fn() =>
                SubSubSubHead::select('id','name_en','name_ur')->get()));

            $view->with('units', Cache::rememberForever('units', fn() =>
                Unit::select('id','name_en','name_ur')->get()));

            $view->with('projects', Cache::rememberForever('projects', fn() =>
                Project::select('id','name_en','name_ur')->get()));

            $view->with('productsData', Cache::rememberForever('products', fn() =>
                Product::select('id','name_en','name_ur')->get()));

            $view->with('casts', Cache::rememberForever('casts', fn() =>
                Cast::select('id','title_en','title_ur')->get()));

            $view->with('occupations', Cache::rememberForever('occupations', fn() =>
                OccupationType::select('id','title_en','title_ur')->get()));

            $view->with('residentialStatus', Cache::rememberForever('residential_status', fn() =>
                Residential::select('id','title_en','title_ur')->get()));

            $view->with('banks', Cache::rememberForever('banks', fn() =>
                Bank::select('id','name_en','name_ur')->get()));

            $view->with('facings', Cache::rememberForever('facings', fn() =>
                Facing::select('id','name_en','name_ur')->get()));

            $view->with('relations', Cache::rememberForever('relations', fn() =>
                Relation::select('id','name_en','name_ur')->get()));

            $view->with('scheduleTypes', Cache::rememberForever('schedule_types', fn() =>
                ScheduleType::select('id','title_en','title_ur')->get()));

            $view->with('schedulePeriods', Cache::rememberForever('schedule_periods', fn() =>
                SchedulePeriod::select('id','title_en','title_ur')->get()));

            $view->with('coaDealers', Cache::rememberForever('coa_dealers', fn() =>
                DetailAccount::select('id','name_en','name_ur')
                    ->where('sub_sub_head_id',39)
                    ->get()));

            $view->with('coaBanks', Cache::rememberForever('coa_banks', fn() =>
                DetailAccount::select('id','name_en','name_ur')
                    ->where('sub_sub_head_id',19)
                    ->get()));

            $view->with('coaPayables', Cache::rememberForever('coa_payables', fn() =>
                DetailAccount::select('id','name_en','name_ur')
                    ->where('main_head_id',2)
                    ->get()));

            $view->with('coaReceivables', Cache::rememberForever('coa_receivables', fn() =>
                DetailAccount::select('id','name_en','name_ur')
                    ->where('sub_head_id',1)
                    ->get()));

            $view->with('coaCashAccounts', Cache::rememberForever('coa_cash_accounts', fn() =>
                DetailAccount::select('id','name_en','name_ur')
                    ->where('sub_sub_head_id',18)
                    ->get()));

            $view->with('items', Cache::rememberForever('items', fn() =>
                Item::select('id','name_en','name_ur')->get()));

            $view->with('constructionSites', Cache::rememberForever('construction_sites', fn() =>
                ConstructionSite::select('id','name_en','name_ur')->get()));

            $view->with('tenders', Cache::rememberForever('tenders', fn() =>
                Tender::select('id','title_en','title_ur')->get()));

            $view->with('workOrders', Cache::rememberForever('work_orders', fn() =>
                WorkOrder::select('id','description_en','description_ur')->get()));

            /**
             * Don't cache this if parties change frequently.
             * Consider AJAX loading if this table is large.
             */
            $view->with('searchParties',
                Cache::remember('search_parties', now()->addHours(12), function () {
                    return Party::with('cast')
                        ->select(
                            'id',
                            'name_en',
                            'name_ur',
                            'cnic_no',
                            'contact_number_1',
                            'cast_id'
                        )->get();
                })
            );
        });
    }
}