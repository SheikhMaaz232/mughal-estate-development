<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Modules\Payroll\App\Models\Designation;
use Modules\Payroll\App\Models\Employee;
use Modules\Payroll\App\Models\Payroll;
use Modules\Payroll\App\Models\Shift;

class PayrollDeductionReportController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Selected Month
        |--------------------------------------------------------------------------
        |
        | Your payrolls.month column stores values like:
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
        | Only Employees With Deductions
        |--------------------------------------------------------------------------
        */

        $query->where(function ($payrollQuery) {

            $payrollQuery
                ->where(
                    'absence_deduction_amount',
                    '>',
                    0
                )
                ->orWhere(
                    'late_early_deduction_amount',
                    '>',
                    0
                )
                ->orWhere(
                    'deduction_adjustment',
                    '!=',
                    0
                );
        });


        /*
        |--------------------------------------------------------------------------
        | Get Records
        |--------------------------------------------------------------------------
        */

        $payrolls = $query
            ->orderBy('employee_id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Calculate Total Deduction Per Employee
        |--------------------------------------------------------------------------
        */

        $payrolls->each(function ($payroll) {

            $payroll->total_deduction =
                (float) $payroll->absence_deduction_amount
                +
                (float) $payroll->late_early_deduction_amount
                +
                (float) $payroll->deduction_adjustment;
        });


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $totalEmployees = $payrolls
            ->pluck('employee_id')
            ->unique()
            ->count();


        $totalBasicSalary = $payrolls->sum(
            'basic_salary'
        );


        $totalAbsenceDeduction = $payrolls->sum(
            'absence_deduction_amount'
        );


        $totalLateEarlyDeduction = $payrolls->sum(
            'late_early_deduction_amount'
        );


        $totalDeductionAdjustment = $payrolls->sum(
            'deduction_adjustment'
        );


        $totalDeductions = $payrolls->sum(
            'total_deduction'
        );


        $totalGrossSalary = $payrolls->sum(
            'gross_salary'
        );


        $totalNetSalary = $payrolls->sum(
            'net_salary'
        );


        /*
        |--------------------------------------------------------------------------
        | Deduction Records
        |--------------------------------------------------------------------------
        */

        $absenceDeductionRecords = $payrolls
            ->where(
                'absence_deduction_amount',
                '>',
                0
            )
            ->count();


        $lateEarlyDeductionRecords = $payrolls
            ->where(
                'late_early_deduction_amount',
                '>',
                0
            )
            ->count();


        $deductionAdjustmentRecords = $payrolls
            ->where(
                'deduction_adjustment',
                '!=',
                0
            )
            ->count();


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

                return [

                    'department' => optional(
                        $employee
                    )->department,

                    'employees' => $records
                        ->pluck('employee_id')
                        ->unique()
                        ->count(),

                    'basic_salary' => $records->sum(
                        'basic_salary'
                    ),

                    'absence_deduction' => $records->sum(
                        'absence_deduction_amount'
                    ),

                    'late_early_deduction' => $records->sum(
                        'late_early_deduction_amount'
                    ),

                    'deduction_adjustment' => $records->sum(
                        'deduction_adjustment'
                    ),

                    'total_deductions' => $records->sum(
                        'total_deduction'
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
            'payroll::reports.payroll-deduction.index',
            compact(

                'payrolls',

                'employees',
                'departments',
                'designations',
                'shifts',

                'selectedMonth',
                'year',
                'monthName',

                'employeeId',
                'departmentId',
                'designationId',
                'shiftId',

                'totalEmployees',

                'totalBasicSalary',

                'totalAbsenceDeduction',

                'totalLateEarlyDeduction',

                'totalDeductionAdjustment',

                'totalDeductions',

                'totalGrossSalary',

                'totalNetSalary',

                'absenceDeductionRecords',

                'lateEarlyDeductionRecords',

                'deductionAdjustmentRecords',

                'departmentSummary'
            )
        );
    }
}
