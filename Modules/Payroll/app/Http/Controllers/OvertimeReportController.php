<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Modules\Payroll\App\Models\Attendance;
use Modules\Payroll\App\Models\Designation;
use Modules\Payroll\App\Models\Employee;
use Modules\Payroll\App\Models\Shift;

class OvertimeReportController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Dates
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
            ->where('overtime_minutes', '>', 0);


        /*
        |--------------------------------------------------------------------------
        | Employee
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
        | Department
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
        | Designation
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
        | Shift
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
        | Get Records
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

        $totalRecords = $attendances->count();

        $totalOvertimeMinutes = $attendances->sum(
            'overtime_minutes'
        );


        /*
        |--------------------------------------------------------------------------
        | Convert Minutes To Hours
        |--------------------------------------------------------------------------
        */

        $totalOvertimeHours = intdiv(
            (int) $totalOvertimeMinutes,
            60
        );

        $remainingMinutes = $totalOvertimeMinutes % 60;


        /*
        |--------------------------------------------------------------------------
        | Employee Summary
        |--------------------------------------------------------------------------
        |
        | Useful when one employee has multiple overtime records.
        |
        */

        $employeeSummary = $attendances
            ->groupBy('employee_id')
            ->map(function ($records) {

                $employee = $records->first()->employee;

                $minutes = $records->sum(
                    'overtime_minutes'
                );

                return [
                    'employee' => $employee,
                    'days' => $records->count(),
                    'minutes' => $minutes,
                    'hours' => intdiv(
                        (int) $minutes,
                        60
                    ),
                    'remaining_minutes' => $minutes % 60,
                ];
            })
            ->sortBy(function ($item) {

                return $item['employee']
                    ? $item['employee']->first_name_en
                    : '';
            })
            ->values();


        return view(
            'payroll::reports.overtime.index',
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
                'totalRecords',
                'totalOvertimeMinutes',
                'totalOvertimeHours',
                'remainingMinutes',
                'employeeSummary'
            )
        );
    }
}
