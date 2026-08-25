<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Modules\Payroll\App\Models\Designation;
use Modules\Payroll\App\Models\Employee;
use Modules\Payroll\App\Models\LeaveRequest;
use Modules\Payroll\App\Models\LeaveType;

class LeaveReportController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        $fromDate = $request->input(
            'from_date',
            now()->startOfMonth()->format('Y-m-d')
        );

        $toDate = $request->input(
            'to_date',
            now()->format('Y-m-d')
        );

        $employeeId = $request->input('employee_id');

        $departmentId = $request->input('department_id');

        $designationId = $request->input('designation_id');

        $leaveTypeId = $request->input('leave_type_id');

        $status = $request->input('status');


        /*
        |--------------------------------------------------------------------------
        | Dropdown Data
        |--------------------------------------------------------------------------
        */

        $employees = Employee::query()
            ->orderBy('first_name_en')
            ->orderBy('last_name_en')
            ->get();

        $departments = Department::query()
            ->orderBy('title_en')
            ->get();

        $designations = Designation::query()
            ->orderBy('title_en')
            ->get();

        $leaveTypes = LeaveType::query()
            ->orderBy('title_en')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Leave Request Query
        |--------------------------------------------------------------------------
        |
        | We select leaves which overlap the selected date range.
        |
        */

        $query = LeaveRequest::query()
            ->with([
                'employee.department',
                'employee.designation',
                'leaveType',
                'approver',
            ])
            ->whereDate(
                'start_date',
                '<=',
                $toDate
            )
            ->whereDate(
                'end_date',
                '>=',
                $fromDate
            );


        /*
        |--------------------------------------------------------------------------
        | Employee Filter
        |--------------------------------------------------------------------------
        */

        if ($employeeId) {
            $query->where(
                'employee_id',
                $employeeId
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Department Filter
        |--------------------------------------------------------------------------
        */

        if ($departmentId) {

            $query->whereHas(
                'employee',
                function ($employeeQuery) use ($departmentId) {

                    $employeeQuery->where(
                        'department_id',
                        $departmentId
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Designation Filter
        |--------------------------------------------------------------------------
        */

        if ($designationId) {

            $query->whereHas(
                'employee',
                function ($employeeQuery) use ($designationId) {

                    $employeeQuery->where(
                        'designation_id',
                        $designationId
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Leave Type Filter
        |--------------------------------------------------------------------------
        */

        if ($leaveTypeId) {

            $query->where(
                'leave_type_id',
                $leaveTypeId
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($status) {

            $query->where(
                'status',
                $status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Get Records
        |--------------------------------------------------------------------------
        */

        $leaveRequests = $query
            ->orderBy('start_date')
            ->orderBy('employee_id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Calculate Days
        |--------------------------------------------------------------------------
        |
        | The calculation considers only the part of the leave that
        | falls inside the selected report period.
        |
        */

        $leaveRequests->each(function ($leave) use (
            $fromDate,
            $toDate
        ) {

            $start = $leave->start_date->copy();

            $end = $leave->end_date->copy();

            $reportStart = \Carbon\Carbon::parse(
                $fromDate
            );

            $reportEnd = \Carbon\Carbon::parse(
                $toDate
            );


            if ($start->lt($reportStart)) {
                $start = $reportStart->copy();
            }

            if ($end->gt($reportEnd)) {
                $end = $reportEnd->copy();
            }


            $leave->report_days = $start->lte($end)
                ? $start->diffInDays($end) + 1
                : 0;
        });


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $totalRequests = $leaveRequests->count();

        $totalLeaveDays = $leaveRequests->sum(
            'report_days'
        );

        $totalEmployees = $leaveRequests
            ->pluck('employee_id')
            ->unique()
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Status Summary
        |--------------------------------------------------------------------------
        */

        $pendingRequests = $leaveRequests
            ->where('status', 'pending')
            ->count();

        $approvedRequests = $leaveRequests
            ->where('status', 'approved')
            ->count();

        $rejectedRequests = $leaveRequests
            ->where('status', 'rejected')
            ->count();


        $pendingDays = $leaveRequests
            ->where('status', 'pending')
            ->sum('report_days');

        $approvedDays = $leaveRequests
            ->where('status', 'approved')
            ->sum('report_days');

        $rejectedDays = $leaveRequests
            ->where('status', 'rejected')
            ->sum('report_days');


        /*
        |--------------------------------------------------------------------------
        | Employee-wise Summary
        |--------------------------------------------------------------------------
        */

        $employeeSummary = $leaveRequests
            ->groupBy('employee_id')
            ->map(function ($records) {

                $employee = $records
                    ->first()
                    ->employee;

                return [
                    'employee' => $employee,

                    'requests' => $records->count(),

                    'days' => $records->sum(
                        'report_days'
                    ),

                    'approved_days' => $records
                        ->where('status', 'approved')
                        ->sum('report_days'),

                    'pending_days' => $records
                        ->where('status', 'pending')
                        ->sum('report_days'),

                    'rejected_days' => $records
                        ->where('status', 'rejected')
                        ->sum('report_days'),
                ];
            })
            ->sortBy(function ($item) {

                return $item['employee']
                    ? $item['employee']->first_name_en
                    : '';
            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Leave Type Summary
        |--------------------------------------------------------------------------
        */

        $leaveTypeSummary = $leaveRequests
            ->groupBy('leave_type_id')
            ->map(function ($records) {

                $leaveType = $records
                    ->first()
                    ->leaveType;

                return [
                    'leave_type' => $leaveType,

                    'requests' => $records->count(),

                    'days' => $records->sum(
                        'report_days'
                    ),

                    'approved_days' => $records
                        ->where('status', 'approved')
                        ->sum('report_days'),

                    'pending_days' => $records
                        ->where('status', 'pending')
                        ->sum('report_days'),

                    'rejected_days' => $records
                        ->where('status', 'rejected')
                        ->sum('report_days'),
                ];
            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'payroll::reports.leave.index',
            compact(
                'leaveRequests',

                'employees',
                'departments',
                'designations',
                'leaveTypes',

                'fromDate',
                'toDate',

                'employeeId',
                'departmentId',
                'designationId',
                'leaveTypeId',
                'status',

                'totalRequests',
                'totalLeaveDays',
                'totalEmployees',

                'pendingRequests',
                'approvedRequests',
                'rejectedRequests',

                'pendingDays',
                'approvedDays',
                'rejectedDays',

                'employeeSummary',
                'leaveTypeSummary'
            )
        );
    }
}
