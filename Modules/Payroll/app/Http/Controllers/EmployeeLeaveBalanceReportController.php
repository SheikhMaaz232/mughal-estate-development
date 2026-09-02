<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Modules\Payroll\App\Models\Designation;
use Modules\Payroll\App\Models\Employee;
use Modules\Payroll\App\Models\LeaveBalance;
use Modules\Payroll\App\Models\LeaveType;
use Modules\Payroll\App\Models\Shift;

class EmployeeLeaveBalanceReportController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        $employeeId = $request->input('employee_id');
        $departmentId = $request->input('department_id');
        $designationId = $request->input('designation_id');
        $shiftId = $request->input('shift_id');
        $leaveTypeId = $request->input('leave_type_id');

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

        $shifts = Shift::query()
            ->orderBy('shift_name_en')
            ->get();

        $leaveTypes = LeaveType::query()
            ->orderBy('title_en')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Leave Balance Query
        |--------------------------------------------------------------------------
        */

        $query = LeaveBalance::query()
            ->with([
                'employee.department',
                'employee.designation',
                'employee.shift',
                'leaveType',
            ]);

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
        | Shift Filter
        |--------------------------------------------------------------------------
        */

        if ($shiftId) {
            $query->whereHas(
                'employee',
                function ($employeeQuery) use ($shiftId) {
                    $employeeQuery->where(
                        'shift_id',
                        $shiftId
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
        | Get Records
        |--------------------------------------------------------------------------
        */

        $leaveBalances = $query
            ->orderBy(
                Employee::select('first_name_en')
                    ->whereColumn(
                        'employees.id',
                        'leave_balances.employee_id'
                    )
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Add Calculated Remaining Days
        |--------------------------------------------------------------------------
        */

        $leaveBalances->each(function ($balance) {

            $balance->remaining_days =
                max(
                    0,
                    (int) $balance->total_days -
                        (int) $balance->used_days
                );
        });

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $totalEmployees = $leaveBalances
            ->pluck('employee_id')
            ->unique()
            ->count();

        $totalLeaveTypes = $leaveBalances
            ->pluck('leave_type_id')
            ->unique()
            ->count();

        $totalDays = $leaveBalances
            ->sum('total_days');

        $totalUsedDays = $leaveBalances
            ->sum('used_days');

        $totalRemainingDays = $leaveBalances
            ->sum('remaining_days');

        /*
        |--------------------------------------------------------------------------
        | Employee-wise Summary
        |--------------------------------------------------------------------------
        */

        $employeeSummary = $leaveBalances
            ->groupBy('employee_id')
            ->map(function ($records) {

                $employee = $records
                    ->first()
                    ->employee;

                $totalDays = $records
                    ->sum('total_days');

                $usedDays = $records
                    ->sum('used_days');

                return [
                    'employee' => $employee,

                    'leave_types' =>
                    $records
                        ->pluck('leave_type_id')
                        ->unique()
                        ->count(),

                    'total_days' =>
                    $totalDays,

                    'used_days' =>
                    $usedDays,

                    'remaining_days' =>
                    max(
                        0,
                        $totalDays - $usedDays
                    ),
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

        $leaveTypeSummary = $leaveBalances
            ->groupBy('leave_type_id')
            ->map(function ($records) {

                $leaveType = $records
                    ->first()
                    ->leaveType;

                $totalDays = $records
                    ->sum('total_days');

                $usedDays = $records
                    ->sum('used_days');

                return [
                    'leave_type' => $leaveType,

                    'employees' =>
                    $records
                        ->pluck('employee_id')
                        ->unique()
                        ->count(),

                    'total_days' =>
                    $totalDays,

                    'used_days' =>
                    $usedDays,

                    'remaining_days' =>
                    max(
                        0,
                        $totalDays - $usedDays
                    ),
                ];
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view(
            'payroll::reports.employee-leave-balance.index',
            compact(
                'leaveBalances',
                'employeeSummary',
                'leaveTypeSummary',
                'employees',
                'departments',
                'designations',
                'shifts',
                'leaveTypes',
                'employeeId',
                'departmentId',
                'designationId',
                'shiftId',
                'leaveTypeId',
                'totalEmployees',
                'totalLeaveTypes',
                'totalDays',
                'totalUsedDays',
                'totalRemainingDays'
            )
        );
    }
}
