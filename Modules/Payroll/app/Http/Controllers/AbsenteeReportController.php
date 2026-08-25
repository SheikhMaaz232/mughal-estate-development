<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Modules\Payroll\App\Models\Attendance;
use Modules\Payroll\App\Models\Designation;
use Modules\Payroll\App\Models\Employee;
use Modules\Payroll\App\Models\Shift;

class AbsenteeReportController extends Controller
{
    /**
     * Absentee Report
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Date Filters
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
        | Other Filters
        |--------------------------------------------------------------------------
        */

        $employeeId = $request->input('employee_id');

        $departmentId = $request->input('department_id');

        $designationId = $request->input('designation_id');

        $shiftId = $request->input('shift_id');


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


        /*
        |--------------------------------------------------------------------------
        | Attendance Query
        |--------------------------------------------------------------------------
        */

        $query = Attendance::query()
            ->with([
                'employee.department',
                'employee.designation',
                'employee.shift',
            ])
            ->whereBetween('date', [
                $fromDate,
                $toDate,
            ])
            ->where('status', 'absent');


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
        | Get Attendance Records
        |--------------------------------------------------------------------------
        */

        $attendances = $query
            ->orderBy('date')
            ->orderBy('employee_id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $totalAbsentRecords = $attendances->count();

        $totalAbsentDays = $attendances->count();

        $totalEmployeesAbsent = $attendances
            ->pluck('employee_id')
            ->unique()
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Employee Wise Summary
        |--------------------------------------------------------------------------
        */

        $employeeSummary = $attendances
            ->groupBy('employee_id')
            ->map(function ($records) {

                $employee = $records->first()->employee;

                return [
                    'employee' => $employee,
                    'days' => $records->count(),
                    'first_absent_date' => $records
                        ->min('date'),
                    'last_absent_date' => $records
                        ->max('date'),
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
        | Date Wise Summary
        |--------------------------------------------------------------------------
        */

        $dateSummary = $attendances
            ->groupBy(function ($attendance) {

                return $attendance->date instanceof \Carbon\Carbon
                    ? $attendance->date->format('Y-m-d')
                    : $attendance->date;
            })
            ->map(function ($records, $date) {

                return [
                    'date' => $date,
                    'employees' => $records->count(),
                ];
            })
            ->sortBy('date')
            ->values();


        return view(
            'payroll::reports.absentee.index',
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

                'totalAbsentRecords',
                'totalAbsentDays',
                'totalEmployeesAbsent',

                'employeeSummary',
                'dateSummary'
            )
        );
    }
}
