<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Payroll\App\Models\Attendance;
use Modules\Payroll\App\Models\Designation;
use Modules\Payroll\App\Models\Employee;
use Modules\Payroll\App\Models\Shift;

class LateEarlyLeaveReportController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Default Dates
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


        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        $employeeId = $request->input('employee_id');
        $departmentId = $request->input('department_id');
        $designationId = $request->input('designation_id');
        $shiftId = $request->input('shift_id');

        $exceptionType = $request->input(
            'exception_type',
            'all'
        );


        /*
        |--------------------------------------------------------------------------
        | Filter Dropdown Data
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


        /*
        |--------------------------------------------------------------------------
        | Report Query
        |--------------------------------------------------------------------------
        */

        $query = Attendance::query()
            ->with([
                'employee.department',
                'employee.designation',
                'employee.shift',
            ])
            ->whereBetween('date', [
                Carbon::parse($fromDate)->startOfDay(),
                Carbon::parse($toDate)->endOfDay(),
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
        | Late / Early Leave Filter
        |--------------------------------------------------------------------------
        */

        if ($exceptionType === 'late') {

            $query->where(
                'late_minutes',
                '>',
                0
            );
        }

        elseif ($exceptionType === 'early_leave') {

            $query->where(
                'early_leave_minutes',
                '>',
                0
            );
        }

        elseif ($exceptionType === 'both') {

            $query->where(
                'late_minutes',
                '>',
                0
            )
            ->where(
                'early_leave_minutes',
                '>',
                0
            );
        }

        else {

            $query->where(function ($attendanceQuery) {

                $attendanceQuery
                    ->where(
                        'late_minutes',
                        '>',
                        0
                    )
                    ->orWhere(
                        'early_leave_minutes',
                        '>',
                        0
                    );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Get Report
        |--------------------------------------------------------------------------
        */

        $attendances = $query
            ->orderBy('date')
            ->orderBy(
                Employee::select('first_name_en')
                    ->whereColumn(
                        'employees.id',
                        'attendances.employee_id'
                    )
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Totals
        |--------------------------------------------------------------------------
        */

        $totalRecords = $attendances->count();

        $totalLateMinutes = $attendances->sum(
            'late_minutes'
        );

        $totalEarlyLeaveMinutes = $attendances->sum(
            'early_leave_minutes'
        );

        $totalExceptionMinutes =
            $totalLateMinutes
            +
            $totalEarlyLeaveMinutes;

        $lateRecords = $attendances
            ->where('late_minutes', '>', 0)
            ->count();

        $earlyLeaveRecords = $attendances
            ->where('early_leave_minutes', '>', 0)
            ->count();


        return view(
            'payroll::reports.late-early-leave.index',
            compact(
                'attendances',
                'employees',
                'departments',
                'designations',
                'shifts',
                'fromDate',
                'toDate',
                'employeeId',
                'departmentId',
                'designationId',
                'shiftId',
                'exceptionType',
                'totalRecords',
                'totalLateMinutes',
                'totalEarlyLeaveMinutes',
                'totalExceptionMinutes',
                'lateRecords',
                'earlyLeaveRecords'
            )
        );
    }
}