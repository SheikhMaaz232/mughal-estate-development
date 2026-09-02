<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Modules\Payroll\App\Models\Designation;
use Modules\Payroll\App\Models\Employee;
use Modules\Payroll\App\Models\Payroll;

class DepartmentSalarySummaryReportController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Default Month / Year
        |--------------------------------------------------------------------------
        */

        $month = $request->input(
            'month',
            now()->format('Y-m')
        );

        $year = $request->input(
            'year',
            now()->year
        );


        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        $departmentId = $request->input('department_id');

        $designationId = $request->input('designation_id');

        $employeeId = $request->input('employee_id');

        $finalized = $request->input(
            'finalized',
            'all'
        );


        /*
        |--------------------------------------------------------------------------
        | Dropdown Data
        |--------------------------------------------------------------------------
        */

        $departments = Department::query()
            ->orderBy('title_en')
            ->get();

        $designations = Designation::query()
            ->orderBy('title_en')
            ->get();

        $employees = Employee::query()
            ->orderBy('first_name_en')
            ->orderBy('last_name_en')
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
            ])
            ->where('month', $month);


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
        | Finalized Filter
        |--------------------------------------------------------------------------
        */

        if ($finalized === 'finalized') {

            $query->where(
                'is_finalized',
                true
            );
        } elseif ($finalized === 'unfinalized') {

            $query->where(
                'is_finalized',
                false
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Get Payroll Records
        |--------------------------------------------------------------------------
        */

        $payrolls = $query
            ->orderBy(
                Employee::select('first_name_en')
                    ->whereColumn(
                        'employees.id',
                        'payrolls.employee_id'
                    )
            )
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Department Summary
        |--------------------------------------------------------------------------
        */

        $departmentSummary = $payrolls
            ->groupBy(function ($payroll) {

                return optional(
                    $payroll->employee
                )->department_id ?? 0;
            })
            ->map(function ($records) {

                $employee = $records->first()->employee;

                return [
                    'department' => optional(
                        $employee
                    )->department,

                    'employee_count' => $records->count(),

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
                )->title_en ?? 'ZZZ';
            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Overall Totals
        |--------------------------------------------------------------------------
        */

        $totalEmployees = $payrolls->count();

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
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'payroll::reports.department-salary-summary.index',
            compact(
                'payrolls',
                'departmentSummary',
                'departments',
                'designations',
                'employees',

                'month',
                'year',

                'departmentId',
                'designationId',
                'employeeId',
                'finalized',

                'totalEmployees',
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
