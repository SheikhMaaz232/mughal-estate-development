<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Payroll\App\Models\Attendance;
use Modules\Payroll\App\Models\Employee;

class MonthlyAttendanceReportController extends Controller
{
    /**
     * Monthly Attendance Report
     */
    public function index(Request $request)
    {
        $year = (int) $request->input('year', now()->year);

        $month = (int) $request->input('month', now()->month);

        /*
        |--------------------------------------------------------------------------
        | Validate Month
        |--------------------------------------------------------------------------
        */

        if ($month < 1 || $month > 12) {
            $month = now()->month;
        }

        /*
        |--------------------------------------------------------------------------
        | Date Range
        |--------------------------------------------------------------------------
        */

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();

        $endDate = $startDate->copy()->endOfMonth();

        /*
        |--------------------------------------------------------------------------
        | Employees
        |--------------------------------------------------------------------------
        */

        $employees = Employee::query()
            ->with([
                'department',
                'designation',
                'shift',
            ])
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

        $attendanceQuery = Attendance::query()
            ->with([
                'employee.department',
                'employee.designation',
                'employee.shift',
            ])
            ->whereBetween('date', [
                $startDate->format('Y-m-d'),
                $endDate->format('Y-m-d'),
            ]);


        /*
        |--------------------------------------------------------------------------
        | Employee Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('employee_id')) {

            $attendanceQuery->where(
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

            $attendanceQuery->whereHas(
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
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $attendanceQuery->where(
                'status',
                $request->input('status')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Get Attendance
        |--------------------------------------------------------------------------
        */

        $attendanceRecords = $attendanceQuery
            ->orderBy('employee_id')
            ->orderBy('date')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Group Attendance By Employee
        |--------------------------------------------------------------------------
        */

        $groupedAttendance = $attendanceRecords
            ->groupBy('employee_id');


        /*
        |--------------------------------------------------------------------------
        | Monthly Report Rows
        |--------------------------------------------------------------------------
        */

        $report = collect();


        foreach ($groupedAttendance as $employeeId => $records) {

            $employee = $records->first()->employee;

            if (!$employee) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Basic Counters
            |--------------------------------------------------------------------------
            */

            $totalRecords = $records->count();

            $presentDays = $records
                ->where('status', 'present')
                ->count();

            $absentDays = $records
                ->where('status', 'absent')
                ->count();

            $leaveDays = $records
                ->where('status', 'leave')
                ->count();

            $holidayDays = $records
                ->where('status', 'holiday')
                ->count();

            $halfDays = $records
                ->where('status', 'half_day')
                ->count();

            $lateDays = $records
                ->filter(function ($attendance) {
                    return (int) $attendance->late_minutes > 0;
                })
                ->count();

            $earlyLeaveDays = $records
                ->filter(function ($attendance) {
                    return (int) $attendance->early_leave_minutes > 0;
                })
                ->count();

            $overtimeDays = $records
                ->filter(function ($attendance) {
                    return (int) $attendance->overtime_minutes > 0;
                })
                ->count();


            /*
            |--------------------------------------------------------------------------
            | Minutes
            |--------------------------------------------------------------------------
            */

            $lateMinutes = (int) $records->sum('late_minutes');

            $earlyLeaveMinutes = (int) $records->sum(
                'early_leave_minutes'
            );

            $overtimeMinutes = (int) $records->sum(
                'overtime_minutes'
            );


            /*
            |--------------------------------------------------------------------------
            | Working Minutes
            |--------------------------------------------------------------------------
            */

            $workingMinutes = 0;


            foreach ($records as $attendance) {

                if (
                    empty($attendance->check_in) ||
                    empty($attendance->check_out)
                ) {
                    continue;
                }

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

                    $workingMinutes += $checkIn->diffInMinutes(
                        $checkOut
                    );
                } catch (\Throwable $e) {

                    continue;
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

            $workingRemainingMinutes = $workingMinutes % 60;


            /*
            |--------------------------------------------------------------------------
            | Attendance Percentage
            |--------------------------------------------------------------------------
            */

            $attendancePercentage = 0;

            if ($totalRecords > 0) {

                $attendancePercentage = round(
                    (
                        ($presentDays + ($halfDays * 0.5))
                        / $totalRecords
                    ) * 100,
                    2
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Add Report Row
            |--------------------------------------------------------------------------
            */

            $report->push([

                'employee' => $employee,

                'employee_id' => $employee->id,

                'total_records' => $totalRecords,

                'present_days' => $presentDays,

                'absent_days' => $absentDays,

                'leave_days' => $leaveDays,

                'holiday_days' => $holidayDays,

                'half_days' => $halfDays,

                'late_days' => $lateDays,

                'late_minutes' => $lateMinutes,

                'early_leave_days' => $earlyLeaveDays,

                'early_leave_minutes' => $earlyLeaveMinutes,

                'overtime_days' => $overtimeDays,

                'overtime_minutes' => $overtimeMinutes,

                'working_minutes' => $workingMinutes,

                'working_hours' => $workingHours,

                'working_remaining_minutes' => $workingRemainingMinutes,

                'attendance_percentage' => $attendancePercentage,

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $summary = [

            'employees' => $report->count(),

            'total_records' => $report->sum(
                'total_records'
            ),

            'present_days' => $report->sum(
                'present_days'
            ),

            'absent_days' => $report->sum(
                'absent_days'
            ),

            'leave_days' => $report->sum(
                'leave_days'
            ),

            'holiday_days' => $report->sum(
                'holiday_days'
            ),

            'half_days' => $report->sum(
                'half_days'
            ),

            'late_days' => $report->sum(
                'late_days'
            ),

            'late_minutes' => $report->sum(
                'late_minutes'
            ),

            'early_leave_days' => $report->sum(
                'early_leave_days'
            ),

            'early_leave_minutes' => $report->sum(
                'early_leave_minutes'
            ),

            'overtime_days' => $report->sum(
                'overtime_days'
            ),

            'overtime_minutes' => $report->sum(
                'overtime_minutes'
            ),

            'working_minutes' => $report->sum(
                'working_minutes'
            ),
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
            'payroll::reports.monthly-attendance.index',
            compact(
                'report',
                'summary',
                'employees',
                'departments',
                'statuses',
                'year',
                'month',
                'startDate',
                'endDate'
            )
        );
    }
}
