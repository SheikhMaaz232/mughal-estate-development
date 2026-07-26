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
        $start = microtime(true);

        logger()->info('ViewServiceProvider boot START');
        View::composer('*', function ($view) {
            $view->with('allowances', Allowance::all());

            $view->with('deductions', Deduction::all());

            $view->with('leaveTypesList', LeaveType::all());

            $view->with('groups', Group::select('id', 'name_en', 'name_ur')->get());

            $view->with('companies', Company::select('id', 'name_en', 'name_ur')->get());

            $view->with('cities', City::select('id', 'name_en', 'name_ur')->get());

            $view->with('tehsils', Tehsil::select('id', 'name_en', 'name_ur')->get());

            $view->with('departmentTypes', Department::getDepartmentTypes());

            $view->with('roadCategories', RoadCategory::select('id', 'title_en', 'title_ur')->get());

            $view->with('mainHeads', MainHead::select('id', 'name_en', 'name_ur')->get());

            $view->with('searchControlHeads', ControlHead::select('id', 'name_en', 'name_ur')->get());

            $view->with('searchSubHeads', SubHead::select('id', 'name_en', 'name_ur')->get());

            $view->with('searchSubSubHeads', SubSubHead::select('id', 'name_en', 'name_ur')->get());

            $view->with('searchSubSubSubHeads', SubSubSubHead::select('id', 'name_en', 'name_ur')->get());

            $view->with('units', Unit::select('id', 'name_en', 'name_ur')->get());

            $view->with('projects', Project::select('id', 'name_en', 'name_ur')->get());

            $view->with('productsData', Product::select('id', 'name_en', 'name_ur')->get());

            $view->with('casts', Cast::select('id', 'title_en', 'title_ur')->get());

            $view->with('occupations', OccupationType::select('id', 'title_en', 'title_ur')->get());

            $view->with('residentialStatus', Residential::select('id', 'title_en', 'title_ur')->get());

            $view->with('banks', Bank::select('id', 'name_en', 'name_ur')->get());

            $view->with('facings', Facing::select('id', 'name_en', 'name_ur')->get());

            $view->with('searchParties', Party::with('cast')
                ->select('id', 'name_en', 'name_ur', 'cnic_no', 'contact_number_1', 'cast_id')
                ->get());

            $view->with('detailAccounts', DetailAccount::select('id', 'name_en', 'name_ur')->get());

            $view->with('relations', Relation::select('id', 'name_en', 'name_ur')->get());

            $view->with('scheduleTypes', ScheduleType::select('id', 'title_en', 'title_ur')->get());

            $view->with('schedulePeriods', SchedulePeriod::select('id', 'title_en', 'title_ur')->get());

            $view->with('coaDealers', DetailAccount::select('id', 'name_en', 'name_ur')
                ->where('sub_sub_head_id', 39)
                ->get());

            $view->with('coaBanks', DetailAccount::select('id', 'name_en', 'name_ur')
                ->where('sub_sub_head_id', 19)
                ->get());

            $view->with('coaPayables', DetailAccount::select('id', 'name_en', 'name_ur')
                ->where('main_head_id', 2)
                ->get());

            $view->with('coaReceivables', DetailAccount::select('id', 'name_en', 'name_ur')
                ->where('sub_head_id', 1)
                ->get());

            $view->with('coaCashAccounts', DetailAccount::select('id', 'name_en', 'name_ur')
                ->where('sub_sub_head_id', 18)
                ->get());

            $view->with('items', Item::select('id', 'name_en', 'name_ur')->get());

            $view->with('constructionSites', ConstructionSite::select('id', 'name_en', 'name_ur')->get());

            $view->with('tenders', Tender::select('id', 'title_en', 'title_ur')->get());

            $view->with('workOrders', WorkOrder::select('id', 'description_en', 'description_ur')->get());
            // $view->with('companies', Company::select('id','name_en', 'name_ur')->get());
            // $view->with('cities', City::select('id','name_en', 'name_ur')->get());
            // $view->with('tehsils', Tehsil::select('id','name_en', 'name_ur')->get());
            // $view->with('departmentTypes', Department::getDepartmentTypes());
            // $view->with('roadCategories', RoadCategory::select('id','title_en', 'title_ur')->get());
            // $view->with('mainHeads', MainHead::select('id','name_en', 'name_ur')->get());
            // $view->with('searchControlHeads', ControlHead::select('id','name_en', 'name_ur')->get());
            // $view->with('searchSubHeads', SubHead::select('id','name_en', 'name_ur')->get());
            // $view->with('searchSubSubHeads', SubSubHead::select('id','name_en', 'name_ur')->get());
            // $view->with('searchSubSubSubHeads', SubSubSubHead::select('id','name_en', 'name_ur')->get());
            // $view->with('units', Unit::select('id','name_en', 'name_ur')->get());
            // $view->with('projects', Project::select('id','name_en', 'name_ur')->get());
            // $view->with('productsData', Product::select('id','name_en', 'name_ur')->get());
            // $view->with('casts', Cast::select('id','title_en', 'title_ur')->get());
            // $view->with('occupations', OccupationType::select('id','title_en', 'title_ur')->get());
            // $view->with('residentialStatus', Residential::select('id','title_en', 'title_ur')->get());
            // $view->with('banks', Bank::select('id','name_en', 'name_ur')->get());

        });
        logger()->info(
            'ViewServiceProvider boot END => ' .
                round((microtime(true) - $start) * 1000, 2) . ' ms'
        );
    }
}
