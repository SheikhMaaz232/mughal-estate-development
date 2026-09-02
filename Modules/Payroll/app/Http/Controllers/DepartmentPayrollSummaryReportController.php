<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Modules\Payroll\App\Models\Designation;
use Modules\Payroll\App\Models\Employee;
use Modules\Payroll\App\Models\Payroll;
use Modules\Payroll\App\Models\Shift;

class DepartmentPayrollSummaryReportController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Selected Month
        |--------------------------------------------------------------------------
        |
        | Database stores month as:
        | 2026-08
        |
        */

        $selectedMonth = $request->input(
            'month',
            now()->format('Y-m')
        );

        /*
        |--------------------------------------------------------------------------
        | Validate Month
        |--------------------------------------------------------------------------
        */

        if (!preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
            $selectedMonth = now()->format('Y-m');
        }

        /*
        |--------------------------------------------------------------------------
        | Year
        |--------------------------------------------------------------------------
        */

        $year = (int) substr(
            $selectedMonth,
            0,
            4
        );


        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        $employeeId = $request->input(
            'employee_id'
        );

        $departmentId = $request->input(
            'department_id'
        );

        $designationId = $request->input(
            'designation_id'
        );

        $shiftId = $request->input(
            'shift_id'
        );


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
        | Payroll Query
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | month column contains 2026-08
        |
        */

        $query = Payroll::query()
            ->with([
                'employee.department',
                'employee.designation',
                'employee.shift',
            ])
            ->where(
                'month',
                $selectedMonth
            )
            ->where(
                'year',
                $year
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
        | Get Payroll Records
        |--------------------------------------------------------------------------
        */

        $payrolls = $query
            ->orderBy('employee_id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Overall Totals
        |--------------------------------------------------------------------------
        */

        $totalEmployees = $payrolls
            ->pluck('employee_id')
            ->unique()
            ->count();

        $totalWorkedDays = $payrolls->sum(
            'total_worked_days'
        );

        $totalAbsentDays = $payrolls->sum(
            'total_absent_days'
        );

        $totalLeaveDays = $payrolls->sum(
            'total_leave_days'
        );

        $totalHolidayDays = $payrolls->sum(
            'total_holiday_days'
        );

        $totalLateMinutes = $payrolls->sum(
            'total_late_minutes'
        );

        $totalEarlyLeaveMinutes = $payrolls->sum(
            'total_early_leave_minutes'
        );

        $totalOvertimeMinutes = $payrolls->sum(
            'total_overtime_minutes'
        );

        $totalBasicSalary = $payrolls->sum(
            'basic_salary'
        );

        $totalAbsenceDeduction = $payrolls->sum(
            'absence_deduction_amount'
        );

        $totalLateEarlyDeduction = $payrolls->sum(
            'late_early_deduction_amount'
        );

        $totalOvertimeAmount = $payrolls->sum(
            'overtime_amount'
        );

        $totalAllowanceAdjustment = $payrolls->sum(
            'allowance_adjustment'
        );

        $totalDeductionAdjustment = $payrolls->sum(
            'deduction_adjustment'
        );

        $totalGrossSalary = $payrolls->sum(
            'gross_salary'
        );

        $totalNetSalary = $payrolls->sum(
            'net_salary'
        );


        /*
        |--------------------------------------------------------------------------
        | Overtime Hours
        |--------------------------------------------------------------------------
        */

        $totalOvertimeHours = intdiv(
            (int) $totalOvertimeMinutes,
            60
        );

        $remainingOvertimeMinutes =
            $totalOvertimeMinutes % 60;


        /*
        |--------------------------------------------------------------------------
        | Department Summary
        |--------------------------------------------------------------------------
        */

        $departmentSummary = $payrolls
            ->groupBy(function ($payroll) {

                return optional(
                    $payroll->employee
                )->department_id;
            })
            ->map(function ($records) {

                $employee = $records
                    ->first()
                    ->employee;

                $department = optional(
                    $employee
                )->department;

                return [

                    'department' => $department,

                    'employees' => $records
                        ->pluck('employee_id')
                        ->unique()
                        ->count(),

                    'worked_days' => $records->sum(
                        'total_worked_days'
                    ),

                    'absent_days' => $records->sum(
                        'total_absent_days'
                    ),

                    'leave_days' => $records->sum(
                        'total_leave_days'
                    ),

                    'holiday_days' => $records->sum(
                        'total_holiday_days'
                    ),

                    'late_minutes' => $records->sum(
                        'total_late_minutes'
                    ),

                    'early_leave_minutes' => $records->sum(
                        'total_early_leave_minutes'
                    ),

                    'overtime_minutes' => $records->sum(
                        'total_overtime_minutes'
                    ),

                    'basic_salary' => $records->sum(
                        'basic_salary'
                    ),

                    'absence_deduction' => $records->sum(
                        'absence_deduction_amount'
                    ),

                    'late_early_deduction' => $records->sum(
                        'late_early_deduction_amount'
                    ),

                    'overtime_amount' => $records->sum(
                        'overtime_amount'
                    ),

                    'allowance_adjustment' => $records->sum(
                        'allowance_adjustment'
                    ),

                    'deduction_adjustment' => $records->sum(
                        'deduction_adjustment'
                    ),

                    'gross_salary' => $records->sum(
                        'gross_salary'
                    ),

                    'net_salary' => $records->sum(
                        'net_salary'
                    ),
                ];
            })
            ->sortBy(function ($item) {

                return optional(
                    $item['department']
                )->title_en ?? '';
            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Month Name
        |--------------------------------------------------------------------------
        */

        $monthName = \Carbon\Carbon::createFromFormat(
            'Y-m',
            $selectedMonth
        )->format('F Y');


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'payroll::reports.department-payroll-summary.index',
            compact(
                'payrolls',
                'employees',
                'departments',
                'designations',
                'shifts',

                'departmentSummary',

                'selectedMonth',
                'year',
                'monthName',

                'employeeId',
                'departmentId',
                'designationId',
                'shiftId',

                'totalEmployees',

                'totalWorkedDays',
                'totalAbsentDays',
                'totalLeaveDays',
                'totalHolidayDays',

                'totalLateMinutes',
                'totalEarlyLeaveMinutes',

                'totalOvertimeMinutes',
                'totalOvertimeHours',
                'remainingOvertimeMinutes',

                'totalBasicSalary',
                'totalAbsenceDeduction',
                'totalLateEarlyDeduction',
                'totalOvertimeAmount',
                'totalAllowanceAdjustment',
                'totalDeductionAdjustment',

                'totalGrossSalary',
                'totalNetSalary'
            )
        );
    }
}
