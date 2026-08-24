<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Payroll\App\Models\Attendance;
use Modules\Payroll\App\Models\Employee;

class EmployeeAttendanceCardController extends Controller
{
    /**
     * Employee Attendance Card
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Selected Employee
        |--------------------------------------------------------------------------
        */

        $employeeId = $request->input('employee_id');


        /*
        |--------------------------------------------------------------------------
        | Selected Month / Year
        |--------------------------------------------------------------------------
        */

        $year = (int) $request->input(
            'year',
            now()->year
        );

        $month = (int) $request->input(
            'month',
            now()->month
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

        if ($month < 1 || $month > 12) {
            $month = now()->month;
        }


        /*
        |--------------------------------------------------------------------------
        | Month Date Range
        |--------------------------------------------------------------------------
        */

        $startDate = Carbon::create(
            $year,
            $month,
            1
        )->startOfMonth();

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
        | Selected Employee
        |--------------------------------------------------------------------------
        */

        $employee = null;

        if ($employeeId) {

            $employee = Employee::query()
                ->with([
                    'department',
                    'designation',
                    'shift',
                ])
                ->find($employeeId);
        }


        /*
        |--------------------------------------------------------------------------
        | Attendance
        |--------------------------------------------------------------------------
        */

        $attendanceRecords = collect();


        if ($employee) {

            $attendanceRecords = Attendance::query()
                ->where('employee_id', $employee->id)
                ->whereBetween('date', [
                    $startDate->format('Y-m-d'),
                    $endDate->format('Y-m-d'),
                ])
                ->orderBy('date')
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | Group By Date
        |--------------------------------------------------------------------------
        */

        $attendanceByDate = $attendanceRecords
            ->keyBy(function ($attendance) {

                return Carbon::parse(
                    $attendance->date
                )->format('Y-m-d');
            });


        /*
        |--------------------------------------------------------------------------
        | Calendar Days
        |--------------------------------------------------------------------------
        */

        $days = collect();

        $currentDate = $startDate->copy();


        while ($currentDate->lessThanOrEqualTo($endDate)) {

            $dateKey = $currentDate->format('Y-m-d');

            $attendance = $attendanceByDate->get(
                $dateKey
            );


            /*
            |--------------------------------------------------------------------------
            | Working Minutes
            |--------------------------------------------------------------------------
            */

            $workingMinutes = 0;


            if (
                $attendance &&
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
            | Status
            |--------------------------------------------------------------------------
            */

            $status = $attendance
                ? $attendance->status
                : 'not_recorded';


            /*
            |--------------------------------------------------------------------------
            | Is Weekend
            |--------------------------------------------------------------------------
            */

            $isWeekend = $currentDate->isWeekend();


            /*
            |--------------------------------------------------------------------------
            | Day Record
            |--------------------------------------------------------------------------
            */

            $days->push([

                'date' => $currentDate->copy(),

                'date_key' => $dateKey,

                'day_name' => $currentDate->format('l'),

                'day_number' => $currentDate->day,

                'attendance' => $attendance,

                'check_in' => $attendance
                    ? $attendance->check_in
                    : null,

                'check_out' => $attendance
                    ? $attendance->check_out
                    : null,

                'working_minutes' => $workingMinutes,

                'working_hours' => $workingHours,

                'remaining_minutes' => $remainingMinutes,

                'late_minutes' => $attendance
                    ? (int) ($attendance->late_minutes ?? 0)
                    : 0,

                'early_leave_minutes' => $attendance
                    ? (int) ($attendance->early_leave_minutes ?? 0)
                    : 0,

                'overtime_minutes' => $attendance
                    ? (int) ($attendance->overtime_minutes ?? 0)
                    : 0,

                'status' => $status,

                'is_manual' => $attendance
                    ? (bool) ($attendance->is_manual ?? false)
                    : false,

                'is_weekend' => $isWeekend,
            ]);


            $currentDate->addDay();
        }


        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $summary = [

            'calendar_days' => $days->count(),

            'present_days' => $days
                ->where('status', 'present')
                ->count(),

            'absent_days' => $days
                ->where('status', 'absent')
                ->count(),

            'leave_days' => $days
                ->where('status', 'leave')
                ->count(),

            'holiday_days' => $days
                ->where('status', 'holiday')
                ->count(),

            'half_days' => $days
                ->where('status', 'half_day')
                ->count(),

            'late_days' => $days
                ->filter(function ($day) {

                    return $day['late_minutes'] > 0;
                })
                ->count(),

            'early_leave_days' => $days
                ->filter(function ($day) {

                    return $day['early_leave_minutes'] > 0;
                })
                ->count(),

            'overtime_days' => $days
                ->filter(function ($day) {

                    return $day['overtime_minutes'] > 0;
                })
                ->count(),

            'late_minutes' => $days->sum(
                'late_minutes'
            ),

            'early_leave_minutes' => $days->sum(
                'early_leave_minutes'
            ),

            'overtime_minutes' => $days->sum(
                'overtime_minutes'
            ),

            'working_minutes' => $days->sum(
                'working_minutes'
            ),

            'weekend_days' => $days
                ->where('is_weekend', true)
                ->count(),

            'not_recorded_days' => $days
                ->where('status', 'not_recorded')
                ->count(),

            'manual_days' => $days
                ->where('is_manual', true)
                ->count(),
        ];


        /*
        |--------------------------------------------------------------------------
        | Working Hours
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
        | Attendance Percentage
        |--------------------------------------------------------------------------
        */

        $attendanceApplicableDays =
            $summary['calendar_days']
            - $summary['weekend_days'];


        if ($attendanceApplicableDays > 0) {

            $summary['attendance_percentage'] = round(

                (
                    $summary['present_days']
                    + ($summary['half_days'] * 0.5)
                )
                    / $attendanceApplicableDays
                    * 100,

                2

            );
        } else {

            $summary['attendance_percentage'] = 0;
        }


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'payroll::reports.employee-attendance-card.index',
            compact(
                'employee',
                'employees',
                'days',
                'summary',
                'year',
                'month',
                'startDate',
                'endDate'
            )
        );
    }
}
