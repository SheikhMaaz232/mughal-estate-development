<?php

namespace App\Providers;

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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\Payroll\App\Models\Allowance;
use Modules\Payroll\App\Models\Deduction;
use Modules\Payroll\App\Models\LeaveType;

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
        // $start = microtime(true);


        // View::composer([
        //     'accounts.*',
        //     'payroll.*',
        // ], function ($view) {
        //     $view->with('allowances', cache()->remember('allowances_list', 3600, function () {
        //         return Allowance::all();
        //     }));
        //     $view->with('deductions', cache()->remember('deductions_list', 3600, function () {
        //         return Deduction::all();
        //     }));
        //     $view->with('leaveTypesList', cache()->remember('leave_types_list', 3600, function () {
        //         return LeaveType::all();
        //     }));
        //     // $view->with('groups', Group::select('id','name_en', 'name_ur')->get());
        //     $view->with('groups', Cache::remember('groups_data', 3600, function () {
        //         return Group::select('id', 'name_en', 'name_ur')->get();
        //     }));

        //     $view->with('companies', Cache::remember('companies_data', 3600, function () {
        //         return Company::select('id', 'name_en', 'name_ur')->get();
        //     }));

        //     $view->with('cities', Cache::remember('cities_data', 3600, function () {
        //         return City::select('id', 'name_en', 'name_ur')->get();
        //     }));

        //     $view->with('tehsils', Cache::remember('tehsils_data', 3600, function () {
        //         return Tehsil::select('id', 'name_en', 'name_ur')->get();
        //     }));

        //     $view->with('departmentTypes', Cache::remember('departmentTypes_data', 3600, function () {
        //         return Department::getDepartmentTypes();
        //     }));

        //     $view->with('roadCategories', Cache::remember('roadCategories_data', 3600, function () {
        //         return RoadCategory::select('id', 'title_en', 'title_ur')->get();
        //     }));

        //     $view->with('mainHeads', Cache::remember('mainHeads_data', 3600, function () {
        //         return MainHead::select('id', 'name_en', 'name_ur')->get();
        //     }));

        //     $view->with('searchControlHeads', Cache::remember('controlHeads_data', 3600, function () {
        //         return ControlHead::select('id', 'name_en', 'name_ur')->get();
        //     }));

        //     $view->with('searchSubHeads', Cache::remember('subHeads_data', 3600, function () {
        //         return SubHead::select('id', 'name_en', 'name_ur')->get();
        //     }));

        //     $view->with('searchSubSubHeads', Cache::remember('subSubHeads_data', 3600, function () {
        //         return SubSubHead::select('id', 'name_en', 'name_ur')->get();
        //     }));

        //     $view->with('searchSubSubSubHeads', Cache::remember('subSubSubHeads_data', 3600, function () {
        //         return SubSubSubHead::select('id', 'name_en', 'name_ur')->get();
        //     }));

        //     $view->with('units', Cache::remember('units_data', 3600, function () {
        //         return Unit::select('id', 'name_en', 'name_ur')->get();
        //     }));

        //     $view->with('projects', Cache::remember('projects_data', 3600, function () {
        //         return Project::select('id', 'name_en', 'name_ur')->get();
        //     }));

        //     $view->with('productsData', Cache::remember('productsData_data', 3600, function () {
        //         return Product::select('id', 'name_en', 'name_ur')->get();
        //     }));

        //     $view->with('casts', Cache::remember('casts_data', 3600, function () {
        //         return Cast::select('id', 'title_en', 'title_ur')->get();
        //     }));

        //     $view->with('occupations', Cache::remember('occupations_data', 3600, function () {
        //         return OccupationType::select('id', 'title_en', 'title_ur')->get();
        //     }));

        //     $view->with('residentialStatus', Cache::remember('residentialStatus_data', 3600, function () {
        //         return Residential::select('id', 'title_en', 'title_ur')->get();
        //     }));

        //     $view->with('banks', Cache::remember('banks_data', 3600, function () {
        //         return Bank::select('id', 'name_en', 'name_ur')->get();
        //     }));

        //     $view->with('facings', Cache::remember('facings_data', 3600, function () {
        //         return Facing::select('id', 'name_en', 'name_ur')->get();
        //     }));

        //     $view->with('searchParties', Cache::remember('searchParty', 3600, function () {
        //         return Party::with('cast')->select('id', 'name_en', 'name_ur', 'cnic_no', 'contact_number_1', 'cast_id')->get();
        //     }));

        //     $view->with('detailAccounts', Cache::remember('detailAccount', 3600, function () {
        //         return DetailAccount::select('id', 'name_en', 'name_ur')->get();
        //     }));
        //     $view->with('relations', Cache::remember('relation', 3600, function () {
        //         return Relation::select('id', 'name_en', 'name_ur')->get();
        //     }));
        //     $view->with('scheduleTypes', Cache::remember('scheduleType', 3600, function () {
        //         return ScheduleType::select('id', 'title_en', 'title_ur')->get();
        //     }));
        //     $view->with('schedulePeriods', Cache::remember('schedulePeriod', 3600, function () {
        //         return SchedulePeriod::select('id', 'title_en', 'title_ur')->get();
        //     }));
        //     $view->with('coaDealers', Cache::remember('coaDealer', 3600, function () {
        //         return DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 39)->get();
        //     }));
        //     $view->with('coaBanks', Cache::remember('coaBank', 3600, function () {
        //         return DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 19)->get();
        //     }));
        //     $view->with('coaPayables', Cache::remember('coaPayable', 3600, function () {
        //         return DetailAccount::select('id', 'name_en', 'name_ur')->where('main_head_id', 2)->get();
        //     }));
        //     $view->with('coaReceivables', Cache::remember('coaReceivable', 3600, function () {
        //         return DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_head_id', 1)->get();
        //     }));
        //     $view->with('coaCashAccounts', Cache::remember('coaCashAccount', 3600, function () {
        //         return DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 18)->get();
        //     }));
        //     $view->with('items', Cache::remember('item', 3600, function () {
        //         return Item::select('id', 'name_en', 'name_ur')->get();
        //     }));
        //     $view->with('constructionSites', Cache::remember('constructionSites', 3600, function () {
        //         return ConstructionSite::select('id', 'name_en', 'name_ur')->get();
        //     }));
        //     $view->with('tenders', Cache::remember('tenders', 3600, function () {
        //         return Tender::select('id', 'title_en', 'title_ur')->get();
        //     }));
        //     $view->with('workOrders', Cache::remember('workOrder', 3600, function () {
        //         return WorkOrder::select('id', 'description_en', 'description_ur')->get();
        //     }));

        //     // $view->with('companies', Company::select('id','name_en', 'name_ur')->get());
        //     // $view->with('cities', City::select('id','name_en', 'name_ur')->get());
        //     // $view->with('tehsils', Tehsil::select('id','name_en', 'name_ur')->get());
        //     // $view->with('departmentTypes', Department::getDepartmentTypes());
        //     // $view->with('roadCategories', RoadCategory::select('id','title_en', 'title_ur')->get());
        //     // $view->with('mainHeads', MainHead::select('id','name_en', 'name_ur')->get());
        //     // $view->with('searchControlHeads', ControlHead::select('id','name_en', 'name_ur')->get());
        //     // $view->with('searchSubHeads', SubHead::select('id','name_en', 'name_ur')->get());
        //     // $view->with('searchSubSubHeads', SubSubHead::select('id','name_en', 'name_ur')->get());
        //     // $view->with('searchSubSubSubHeads', SubSubSubHead::select('id','name_en', 'name_ur')->get());
        //     // $view->with('units', Unit::select('id','name_en', 'name_ur')->get());
        //     // $view->with('projects', Project::select('id','name_en', 'name_ur')->get());
        //     // $view->with('productsData', Product::select('id','name_en', 'name_ur')->get());
        //     // $view->with('casts', Cast::select('id','title_en', 'title_ur')->get());
        //     // $view->with('occupations', OccupationType::select('id','title_en', 'title_ur')->get());
        //     // $view->with('residentialStatus', Residential::select('id','title_en', 'title_ur')->get());
        //     // $view->with('banks', Bank::select('id','name_en', 'name_ur')->get());

        // });
        // logger()->info(
        //     'ViewServiceProvider boot END => ' .
        //         round((microtime(true) - $start) * 1000, 2) . ' ms'
        // );
    }
}
