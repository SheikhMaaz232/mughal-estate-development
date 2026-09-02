<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Payroll\App\Models\Attendance;
use Modules\Payroll\App\Models\Designation;
use Modules\Payroll\App\Models\Employee;
use Modules\Payroll\App\Models\LeaveRequest;
use Modules\Payroll\App\Models\Shift;

class EmployeeAttendanceSummaryReportController extends Controller
{
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
        | Filters
        |--------------------------------------------------------------------------
        */

        $employeeId = $request->input('employee_id');

        $departmentId = $request->input('department_id');

        $designationId = $request->input('designation_id');

        $shiftId = $request->input('shift_id');


        /*
        |--------------------------------------------------------------------------
        | Validate Dates
        |--------------------------------------------------------------------------
        */

        try {
            $from = Carbon::parse($fromDate)->startOfDay();
            $to = Carbon::parse($toDate)->endOfDay();
        } catch (\Throwable $e) {

            $fromDate = now()
                ->startOfMonth()
                ->format('Y-m-d');

            $toDate = now()
                ->format('Y-m-d');

            $from = Carbon::parse($fromDate)->startOfDay();
            $to = Carbon::parse($toDate)->endOfDay();
        }


        /*
        |--------------------------------------------------------------------------
        | Report Days
        |--------------------------------------------------------------------------
        */

        $periodStart = Carbon::parse($fromDate);
        $periodEnd = Carbon::parse($toDate);

        $totalPeriodDays = $periodStart->diffInDays(
            $periodEnd
        ) + 1;


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
        | Employee Query
        |--------------------------------------------------------------------------
        */

        $employeeQuery = Employee::query()
            ->with([
                'department',
                'designation',
                'shift',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Employee Filter
        |--------------------------------------------------------------------------
        */

        if ($employeeId) {

            $employeeQuery->where(
                'id',
                $employeeId
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Department Filter
        |--------------------------------------------------------------------------
        */

        if ($departmentId) {

            $employeeQuery->where(
                'department_id',
                $departmentId
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Designation Filter
        |--------------------------------------------------------------------------
        */

        if ($designationId) {

            $employeeQuery->where(
                'designation_id',
                $designationId
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Shift Filter
        |--------------------------------------------------------------------------
        */

        if ($shiftId) {

            $employeeQuery->where(
                'shift_id',
                $shiftId
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Get Employees
        |--------------------------------------------------------------------------
        */

        $selectedEmployees = $employeeQuery
            ->orderBy('first_name_en')
            ->orderBy('last_name_en')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Attendance Data
        |--------------------------------------------------------------------------
        */

        $attendanceRecords = Attendance::query()
            ->whereBetween('date', [
                $fromDate,
                $toDate,
            ])
            ->whereIn(
                'employee_id',
                $selectedEmployees->pluck('id')
            )
            ->get()
            ->groupBy('employee_id');


        /*
        |--------------------------------------------------------------------------
        | Leave Data
        |--------------------------------------------------------------------------
        |
        | Only approved leave requests are counted.
        |
        */

        $leaveRequests = collect();

        if (class_exists(LeaveRequest::class)) {

            $leaveRequests = LeaveRequest::query()
                ->whereIn(
                    'employee_id',
                    $selectedEmployees->pluck('id')
                )
                ->where('status', 'approved')
                ->whereDate(
                    'end_date',
                    '>=',
                    $fromDate
                )
                ->whereDate(
                    'start_date',
                    '<=',
                    $toDate
                )
                ->get()
                ->groupBy('employee_id');
        }


        /*
        |--------------------------------------------------------------------------
        | Build Employee Summary
        |--------------------------------------------------------------------------
        */

        $employeeSummary = collect();


        foreach ($selectedEmployees as $employee) {

            $records = $attendanceRecords->get(
                $employee->id,
                collect()
            );


            /*
            |------------------------------------------------------------------
            | Present Days
            |------------------------------------------------------------------
            */

            $presentDays = $records
                ->filter(function ($attendance) {

                    return in_array(
                        strtolower(
                            (string) $attendance->status
                        ),
                        [
                            'present',
                            'late',
                            'early_leave',
                            'late_early',
                        ],
                        true
                    );
                })
                ->pluck('date')
                ->unique()
                ->count();


            /*
            |------------------------------------------------------------------
            | Absent Days
            |------------------------------------------------------------------
            */

            $absentDays = $records
                ->filter(function ($attendance) {

                    return strtolower(
                        (string) $attendance->status
                    ) === 'absent';
                })
                ->pluck('date')
                ->unique()
                ->count();


            /*
            |------------------------------------------------------------------
            | Leave Days
            |------------------------------------------------------------------
            */

            $leaveDays = 0;

            $employeeLeaves = $leaveRequests->get(
                $employee->id,
                collect()
            );


            foreach ($employeeLeaves as $leave) {

                $leaveStart = Carbon::parse(
                    $leave->start_date
                );

                $leaveEnd = Carbon::parse(
                    $leave->end_date
                );

                if ($leaveStart->lt($periodStart)) {
                    $leaveStart = $periodStart->copy();
                }

                if ($leaveEnd->gt($periodEnd)) {
                    $leaveEnd = $periodEnd->copy();
                }

                if ($leaveStart->lte($leaveEnd)) {

                    $leaveDays +=
                        $leaveStart->diffInDays(
                            $leaveEnd
                        ) + 1;
                }
            }


            /*
            |------------------------------------------------------------------
            | Holiday Days
            |------------------------------------------------------------------
            |
            | Your supplied migrations do not contain a holidays table.
            | Therefore holiday days cannot be calculated independently here.
            |
            | We use Attendance status = holiday where available.
            |
            */

            $holidayDays = $records
                ->filter(function ($attendance) {

                    return strtolower(
                        (string) $attendance->status
                    ) === 'holiday';
                })
                ->pluck('date')
                ->unique()
                ->count();


            /*
            |------------------------------------------------------------------
            | Late Information
            |------------------------------------------------------------------
            */

            $lateRecords = $records
                ->filter(function ($attendance) {

                    return (int) $attendance->late_minutes > 0;
                });

            $lateDays = $lateRecords
                ->pluck('date')
                ->unique()
                ->count();

            $totalLateMinutes = $records->sum(
                'late_minutes'
            );


            /*
            |------------------------------------------------------------------
            | Early Leave Information
            |------------------------------------------------------------------
            */

            $earlyRecords = $records
                ->filter(function ($attendance) {

                    return (int) $attendance->early_leave_minutes > 0;
                });

            $earlyLeaveDays = $earlyRecords
                ->pluck('date')
                ->unique()
                ->count();

            $totalEarlyLeaveMinutes = $records->sum(
                'early_leave_minutes'
            );


            /*
            |------------------------------------------------------------------
            | Overtime Information
            |------------------------------------------------------------------
            */

            $overtimeRecords = $records
                ->filter(function ($attendance) {

                    return (int) $attendance->overtime_minutes > 0;
                });

            $overtimeDays = $overtimeRecords
                ->pluck('date')
                ->unique()
                ->count();

            $totalOvertimeMinutes = $records->sum(
                'overtime_minutes'
            );


            /*
            |------------------------------------------------------------------
            | Total Exception Minutes
            |------------------------------------------------------------------
            */

            $totalExceptionMinutes =
                $totalLateMinutes
                +
                $totalEarlyLeaveMinutes;


            /*
            |------------------------------------------------------------------
            | Attendance Percentage
            |------------------------------------------------------------------
            */

            $attendancePercentage = 0;

            if ($totalPeriodDays > 0) {

                $attendancePercentage = round(
                    (
                        $presentDays
                        /
                        $totalPeriodDays
                    ) * 100,
                    2
                );
            }


            /*
            |------------------------------------------------------------------
            | Employee Name
            |------------------------------------------------------------------
            */

            $nameEn = trim(
                ($employee->first_name_en ?? '')
                    . ' ' .
                    ($employee->last_name_en ?? '')
            );

            $nameUr = trim(
                ($employee->first_name_ur ?? '')
                    . ' ' .
                    ($employee->last_name_ur ?? '')
            );


            /*
            |------------------------------------------------------------------
            | Summary Record
            |------------------------------------------------------------------
            */

            $employeeSummary->push([
                'employee' => $employee,

                'name_en' => $nameEn,
                'name_ur' => $nameUr,

                'total_period_days' =>
                $totalPeriodDays,

                'present_days' =>
                $presentDays,

                'absent_days' =>
                $absentDays,

                'leave_days' =>
                $leaveDays,

                'holiday_days' =>
                $holidayDays,

                'late_days' =>
                $lateDays,

                'total_late_minutes' =>
                $totalLateMinutes,

                'early_leave_days' =>
                $earlyLeaveDays,

                'total_early_leave_minutes' =>
                $totalEarlyLeaveMinutes,

                'overtime_days' =>
                $overtimeDays,

                'total_overtime_minutes' =>
                $totalOvertimeMinutes,

                'total_exception_minutes' =>
                $totalExceptionMinutes,

                'attendance_percentage' =>
                $attendancePercentage,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Overall Totals
        |--------------------------------------------------------------------------
        */

        $totalEmployees =
            $employeeSummary->count();

        $totalPresentDays =
            $employeeSummary->sum('present_days');

        $totalAbsentDays =
            $employeeSummary->sum('absent_days');

        $totalLeaveDays =
            $employeeSummary->sum('leave_days');

        $totalHolidayDays =
            $employeeSummary->sum('holiday_days');

        $totalLateDays =
            $employeeSummary->sum('late_days');

        $totalLateMinutes =
            $employeeSummary->sum(
                'total_late_minutes'
            );

        $totalEarlyLeaveDays =
            $employeeSummary->sum(
                'early_leave_days'
            );

        $totalEarlyLeaveMinutes =
            $employeeSummary->sum(
                'total_early_leave_minutes'
            );

        $totalOvertimeDays =
            $employeeSummary->sum(
                'overtime_days'
            );

        $totalOvertimeMinutes =
            $employeeSummary->sum(
                'total_overtime_minutes'
            );

        $totalExceptionMinutes =
            $employeeSummary->sum(
                'total_exception_minutes'
            );


        /*
        |--------------------------------------------------------------------------
        | Overall Attendance Percentage
        |--------------------------------------------------------------------------
        */

        $possibleAttendanceDays =
            $totalEmployees *
            $totalPeriodDays;

        $overallAttendancePercentage = 0;

        if ($possibleAttendanceDays > 0) {

            $overallAttendancePercentage = round(
                (
                    $totalPresentDays
                    /
                    $possibleAttendanceDays
                ) * 100,
                2
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'payroll::reports.employee-attendance-summary.index',
            compact(
                'employeeSummary',

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

                'totalPeriodDays',

                'totalEmployees',

                'totalPresentDays',
                'totalAbsentDays',
                'totalLeaveDays',
                'totalHolidayDays',

                'totalLateDays',
                'totalLateMinutes',

                'totalEarlyLeaveDays',
                'totalEarlyLeaveMinutes',

                'totalOvertimeDays',
                'totalOvertimeMinutes',

                'totalExceptionMinutes',

                'overallAttendancePercentage'
            )
        );
    }
}
