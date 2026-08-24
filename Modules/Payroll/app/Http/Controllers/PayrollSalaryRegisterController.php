<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Payroll\App\Models\Employee;
use Modules\Payroll\App\Models\Payroll;

class PayrollSalaryRegisterController extends Controller
{
    /**
     * Monthly Payroll Salary Register
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        $year = (int) $request->input(
            'year',
            now()->year
        );

        $month = $request->input(
            'month',
            now()->format('m')
        );

        /*
        |--------------------------------------------------------------------------
        | Validate Year
        |--------------------------------------------------------------------------
        */

        if ($year < 2000 || $year > 2100) {
            $year = now()->year;
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Month
        |--------------------------------------------------------------------------
        */

        $month = str_pad(
            (int) $month,
            2,
            '0',
            STR_PAD_LEFT
        );

        if ((int) $month < 1 || (int) $month > 12) {
            $month = now()->format('m');
        }

        /*
        |--------------------------------------------------------------------------
        | Employee Filter
        |--------------------------------------------------------------------------
        */

        $employeeId = $request->input('employee_id');

        /*
        |--------------------------------------------------------------------------
        | Department Filter
        |--------------------------------------------------------------------------
        */

        $departmentId = $request->input('department_id');

        /*
        |--------------------------------------------------------------------------
        | Designation Filter
        |--------------------------------------------------------------------------
        */

        $designationId = $request->input('designation_id');

        /*
        |--------------------------------------------------------------------------
        | Finalized Filter
        |--------------------------------------------------------------------------
        */

        $finalized = $request->input('finalized');


        /*
        |--------------------------------------------------------------------------
        | Employees
        |--------------------------------------------------------------------------
        */

        $employees = Employee::query()
            ->orderBy('first_name_en')
            ->orderBy('last_name_en')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Departments
        |--------------------------------------------------------------------------
        */

        $departments = \App\Models\Department::query()
            ->orderBy('title_en')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Designations
        |--------------------------------------------------------------------------
        */

        $designations = \Modules\Payroll\App\Models\Designation::query()
            ->orderBy('title_en')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Payroll Query
        |--------------------------------------------------------------------------
        */

        $payrollQuery = Payroll::query()
            ->with([
                'employee.department',
                'employee.designation',
                'employee.shift',
            ])
            ->where('year', $year)
            ->where('month', $month);


        /*
        |--------------------------------------------------------------------------
        | Employee Filter
        |--------------------------------------------------------------------------
        */

        if ($employeeId) {

            $payrollQuery->where(
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

            $payrollQuery->whereHas(
                'employee',
                function ($query) use ($departmentId) {

                    $query->where(
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

            $payrollQuery->whereHas(
                'employee',
                function ($query) use ($designationId) {

                    $query->where(
                        'designation_id',
                        $designationId
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Finalized Filter
        |--------------------------------------------------------------------------
        */

        if ($finalized !== null && $finalized !== '') {

            $payrollQuery->where(
                'is_finalized',
                (bool) $finalized
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Get Payroll
        |--------------------------------------------------------------------------
        */

        $payrolls = $payrollQuery
            ->get()
            ->sortBy(function ($payroll) {

                return strtolower(
                    ($payroll->employee->first_name_en ?? '') .
                        ' ' .
                        ($payroll->employee->last_name_en ?? '')
                );
            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $summary = [

            'employees' => $payrolls->count(),

            'basic_salary' => $payrolls->sum(
                'basic_salary'
            ),

            'worked_days' => $payrolls->sum(
                'total_worked_days'
            ),

            'absent_days' => $payrolls->sum(
                'total_absent_days'
            ),

            'leave_days' => $payrolls->sum(
                'total_leave_days'
            ),

            'holiday_days' => $payrolls->sum(
                'total_holiday_days'
            ),

            'late_minutes' => $payrolls->sum(
                'total_late_minutes'
            ),

            'early_leave_minutes' => $payrolls->sum(
                'total_early_leave_minutes'
            ),

            'overtime_minutes' => $payrolls->sum(
                'total_overtime_minutes'
            ),

            'absence_deduction' => $payrolls->sum(
                'absence_deduction_amount'
            ),

            'late_early_deduction' => $payrolls->sum(
                'late_early_deduction_amount'
            ),

            'overtime_amount' => $payrolls->sum(
                'overtime_amount'
            ),

            'allowance_adjustment' => $payrolls->sum(
                'allowance_adjustment'
            ),

            'deduction_adjustment' => $payrolls->sum(
                'deduction_adjustment'
            ),

            'gross_salary' => $payrolls->sum(
                'gross_salary'
            ),

            'net_salary' => $payrolls->sum(
                'net_salary'
            ),

            'finalized' => $payrolls
                ->where('is_finalized', true)
                ->count(),

            'not_finalized' => $payrolls
                ->where('is_finalized', false)
                ->count(),
        ];


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'payroll::reports.salary-register.index',
            compact(
                'payrolls',
                'employees',
                'departments',
                'designations',
                'summary',
                'year',
                'month',
                'employeeId',
                'departmentId',
                'designationId',
                'finalized'
            )
        );
    }
}
