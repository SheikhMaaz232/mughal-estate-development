<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Payroll\App\Models\Attendance;
use Modules\Payroll\App\Models\Employee;

class AttendanceDetailReportController extends Controller
{
    /**
     * Attendance Detail Report
     */
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
        | Validate Dates
        |--------------------------------------------------------------------------
        */

        try {
            $fromDateObject = Carbon::parse($fromDate)->startOfDay();
        } catch (\Throwable $e) {
            $fromDateObject = now()->startOfMonth()->startOfDay();
            $fromDate = $fromDateObject->format('Y-m-d');
        }


        try {
            $toDateObject = Carbon::parse($toDate)->endOfDay();
        } catch (\Throwable $e) {
            $toDateObject = now()->endOfDay();
            $toDate = $toDateObject->format('Y-m-d');
        }


        /*
        |--------------------------------------------------------------------------
        | If From Date > To Date
        |--------------------------------------------------------------------------
        */

        if ($fromDateObject->greaterThan($toDateObject)) {

            [$fromDateObject, $toDateObject] = [
                $toDateObject->copy()->startOfDay(),
                $fromDateObject->copy()->endOfDay(),
            ];

            $fromDate = $fromDateObject->format('Y-m-d');
            $toDate = $toDateObject->format('Y-m-d');
        }


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

        $departments = Department::query()
            ->orderBy('title_en')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Statuses
        |--------------------------------------------------------------------------
        */

        $statuses = [
            'present',
            'absent',
            'late',
            'half_day',
            'leave',
            'holiday',
            'manual',
        ];


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
            ]);


        /*
        |--------------------------------------------------------------------------
        | Employee Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('employee_id')) {

            $query->where(
                'employee_id',
                $request->integer('employee_id')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Department Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('department_id')) {

            $departmentId = $request->integer('department_id');

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
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->input('status')
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
        | Prepare Report
        |--------------------------------------------------------------------------
        */

        $report = $attendances->map(function ($attendance) {

            $workingMinutes = 0;

            /*
            |--------------------------------------------------------------------------
            | Check In / Check Out
            |--------------------------------------------------------------------------
            */

            if (
                !empty($attendance->check_in) &&
                !empty($attendance->check_out)
            ) {

                try {

                    $checkIn = Carbon::parse(
                        $attendance->check_in
                    );

                    $checkOut = Carbon::parse(
                        $attendance->check_out
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Overnight Shift
                    |--------------------------------------------------------------------------
                    */

                    if ($checkOut->lessThan($checkIn)) {
                        $checkOut->addDay();
                    }


                    $workingMinutes = $checkIn->diffInMinutes(
                        $checkOut
                    );
                } catch (\Throwable $e) {

                    $workingMinutes = 0;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Working Hours
            |--------------------------------------------------------------------------
            */

            $workingHours = intdiv(
                $workingMinutes,
                60
            );

            $remainingMinutes = $workingMinutes % 60;


            /*
            |--------------------------------------------------------------------------
            | Employee
            |--------------------------------------------------------------------------
            */

            $employee = $attendance->employee;


            return [

                'attendance' => $attendance,

                'employee' => $employee,

                'employee_id' => $attendance->employee_id,

                'date' => $attendance->date,

                'day' => Carbon::parse(
                    $attendance->date
                )->format('l'),

                'check_in' => $attendance->check_in,

                'check_out' => $attendance->check_out,

                'working_minutes' => $workingMinutes,

                'working_hours' => $workingHours,

                'remaining_minutes' => $remainingMinutes,

                'late_minutes' => (int) (
                    $attendance->late_minutes ?? 0
                ),

                'early_leave_minutes' => (int) (
                    $attendance->early_leave_minutes ?? 0
                ),

                'overtime_minutes' => (int) (
                    $attendance->overtime_minutes ?? 0
                ),

                'status' => $attendance->status,

                'is_manual' => (bool) (
                    $attendance->is_manual ?? false
                ),
            ];
        });


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $summary = [

            'total_records' => $report->count(),

            'present' => $report
                ->where('status', 'present')
                ->count(),

            'absent' => $report
                ->where('status', 'absent')
                ->count(),

            'leave' => $report
                ->where('status', 'leave')
                ->count(),

            'holiday' => $report
                ->where('status', 'holiday')
                ->count(),

            'half_day' => $report
                ->where('status', 'half_day')
                ->count(),

            'late_days' => $report
                ->filter(function ($row) {
                    return $row['late_minutes'] > 0;
                })
                ->count(),

            'early_leave_days' => $report
                ->filter(function ($row) {
                    return $row['early_leave_minutes'] > 0;
                })
                ->count(),

            'overtime_days' => $report
                ->filter(function ($row) {
                    return $row['overtime_minutes'] > 0;
                })
                ->count(),

            'late_minutes' => $report->sum(
                'late_minutes'
            ),

            'early_leave_minutes' => $report->sum(
                'early_leave_minutes'
            ),

            'overtime_minutes' => $report->sum(
                'overtime_minutes'
            ),

            'working_minutes' => $report->sum(
                'working_minutes'
            ),

            'manual_records' => $report
                ->where('is_manual', true)
                ->count(),
        ];


        /*
        |--------------------------------------------------------------------------
        | Summary Working Hours
        |--------------------------------------------------------------------------
        */

        $summary['working_hours'] = intdiv(
            $summary['working_minutes'],
            60
        );

        $summary['working_remaining_minutes'] =
            $summary['working_minutes'] % 60;


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'payroll::reports.attendance-detail.index',
            compact(
                'report',
                'summary',
                'employees',
                'departments',
                'statuses',
                'fromDate',
                'toDate'
            )
        );
    }
}
