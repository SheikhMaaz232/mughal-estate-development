<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Payroll\App\Models\Employee;
use Modules\Payroll\App\Models\Payroll;

class EmployeePayslipController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) $request->input('year', now()->year);

        $month = str_pad(
            (int) $request->input('month', now()->month),
            2,
            '0',
            STR_PAD_LEFT
        );

        $employeeId = $request->input('employee_id');

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
        | Selected Payroll
        |--------------------------------------------------------------------------
        */

        $payroll = null;

        if ($employeeId) {

            $payroll = Payroll::query()
                ->with([
                    'employee.department',
                    'employee.designation',
                    'employee.shift',
                ])
                ->where('employee_id', $employeeId)
                ->where('year', $year)
                ->where('month', $month)
                ->first();
        }


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'payroll::reports.employee-payslip.index',
            compact(
                'employees',
                'payroll',
                'year',
                'month',
                'employeeId'
            )
        );
    }
}
