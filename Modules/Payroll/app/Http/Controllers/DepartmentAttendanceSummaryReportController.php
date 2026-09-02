<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Payroll\App\Models\Attendance;
use Modules\Payroll\App\Models\Employee;

class DepartmentAttendanceSummaryReportController extends Controller
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

        $departmentId = $request->input('department_id');


        /*
        |--------------------------------------------------------------------------
        | Validate Dates
        |--------------------------------------------------------------------------
        */

        try {
            $from = Carbon::parse($fromDate);
            $to = Carbon::parse($toDate);

            if ($from->gt($to)) {
                $temp = $from;

                $from = $to;
                $to = $temp;

                $fromDate = $from->format('Y-m-d');
                $toDate = $to->format('Y-m-d');
            }
        } catch (\Throwable $e) {

            $fromDate = now()
                ->startOfMonth()
                ->format('Y-m-d');

            $toDate = now()
                ->format('Y-m-d');

            $from = Carbon::parse($fromDate);
            $to = Carbon::parse($toDate);
        }


        /*
        |--------------------------------------------------------------------------
        | Period
        |--------------------------------------------------------------------------
        */

        $totalPeriodDays = $from->diffInDays($to) + 1;


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
        | Employee Query
        |--------------------------------------------------------------------------
        */

        $employeeQuery = Employee::query();


        if ($departmentId) {
            $employeeQuery->where(
                'department_id',
                $departmentId
            );
        }


        $employees = $employeeQuery
            ->orderBy('first_name_en')
            ->orderBy('last_name_en')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Attendance
        |--------------------------------------------------------------------------
        */

        $attendances = Attendance::query()
            ->whereBetween('date', [
                $fromDate,
                $toDate,
            ])
            ->whereIn(
                'employee_id',
                $employees->pluck('id')
            )
            ->get()
            ->groupBy('employee_id');


        /*
        |--------------------------------------------------------------------------
        | Department Summary
        |--------------------------------------------------------------------------
        */

        $departmentSummary = collect();


        foreach ($departments as $department) {

            /*
            |------------------------------------------------------------------
            | Employees in Department
            |------------------------------------------------------------------
            */

            $departmentEmployees = $employees->where(
                'department_id',
                $department->id
            );


            if ($departmentEmployees->isEmpty()) {
                continue;
            }


            /*
            |------------------------------------------------------------------
            | Employee IDs
            |------------------------------------------------------------------
            */

            $employeeIds = $departmentEmployees
                ->pluck('id');


            /*
            |------------------------------------------------------------------
            | Department Attendance
            |------------------------------------------------------------------
            */

            $records = $attendances
                ->filter(
                    function ($records, $employeeId) use ($employeeIds) {
                        return $employeeIds->contains(
                            $employeeId
                        );
                    }
                )
                ->flatten();


            /*
            |------------------------------------------------------------------
            | Present
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
                ->groupBy('employee_id')
                ->sum(function ($employeeRecords) {

                    return $employeeRecords
                        ->pluck('date')
                        ->unique()
                        ->count();
                });


            /*
            |------------------------------------------------------------------
            | Absent
            |------------------------------------------------------------------
            */

            $absentDays = $records
                ->filter(function ($attendance) {

                    return strtolower(
                        (string) $attendance->status
                    ) === 'absent';
                })
                ->groupBy('employee_id')
                ->sum(function ($employeeRecords) {

                    return $employeeRecords
                        ->pluck('date')
                        ->unique()
                        ->count();
                });


            /*
            |------------------------------------------------------------------
            | Leave
            |------------------------------------------------------------------
            */

            $leaveDays = $records
                ->filter(function ($attendance) {

                    return strtolower(
                        (string) $attendance->status
                    ) === 'leave';
                })
                ->groupBy('employee_id')
                ->sum(function ($employeeRecords) {

                    return $employeeRecords
                        ->pluck('date')
                        ->unique()
                        ->count();
                });


            /*
            |------------------------------------------------------------------
            | Holiday
            |------------------------------------------------------------------
            */

            $holidayDays = $records
                ->filter(function ($attendance) {

                    return strtolower(
                        (string) $attendance->status
                    ) === 'holiday';
                })
                ->groupBy('employee_id')
                ->sum(function ($employeeRecords) {

                    return $employeeRecords
                        ->pluck('date')
                        ->unique()
                        ->count();
                });


            /*
            |------------------------------------------------------------------
            | Late
            |------------------------------------------------------------------
            */

            $lateRecords = $records->filter(function ($attendance) {

                return (int) $attendance->late_minutes > 0;
            });


            $lateDays = $lateRecords
                ->groupBy('employee_id')
                ->sum(function ($employeeRecords) {

                    return $employeeRecords
                        ->pluck('date')
                        ->unique()
                        ->count();
                });


            $totalLateMinutes = $records->sum(
                'late_minutes'
            );


            /*
            |------------------------------------------------------------------
            | Early Leave
            |------------------------------------------------------------------
            */

            $earlyLeaveRecords = $records->filter(
                function ($attendance) {

                    return (int) $attendance->early_leave_minutes > 0;
                }
            );


            $earlyLeaveDays = $earlyLeaveRecords
                ->groupBy('employee_id')
                ->sum(function ($employeeRecords) {

                    return $employeeRecords
                        ->pluck('date')
                        ->unique()
                        ->count();
                });


            $totalEarlyLeaveMinutes = $records->sum(
                'early_leave_minutes'
            );


            /*
            |------------------------------------------------------------------
            | Overtime
            |------------------------------------------------------------------
            */

            $overtimeRecords = $records->filter(
                function ($attendance) {

                    return (int) $attendance->overtime_minutes > 0;
                }
            );


            $overtimeDays = $overtimeRecords
                ->groupBy('employee_id')
                ->sum(function ($employeeRecords) {

                    return $employeeRecords
                        ->pluck('date')
                        ->unique()
                        ->count();
                });


            $totalOvertimeMinutes = $records->sum(
                'overtime_minutes'
            );


            /*
            |------------------------------------------------------------------
            | Employee Count
            |------------------------------------------------------------------
            */

            $employeeCount = $departmentEmployees->count();


            /*
            |------------------------------------------------------------------
            | Possible Attendance Days
            |------------------------------------------------------------------
            */

            $possibleAttendanceDays =
                $employeeCount *
                $totalPeriodDays;


            /*
            |------------------------------------------------------------------
            | Attendance Percentage
            |------------------------------------------------------------------
            */

            $attendancePercentage = 0;

            if ($possibleAttendanceDays > 0) {

                $attendancePercentage = round(
                    (
                        $presentDays /
                        $possibleAttendanceDays
                    ) * 100,
                    2
                );
            }


            /*
            |------------------------------------------------------------------
            | Total Exception Minutes
            |------------------------------------------------------------------
            */

            $totalExceptionMinutes =
                $totalLateMinutes +
                $totalEarlyLeaveMinutes;


            /*
            |------------------------------------------------------------------
            | Department Summary
            |------------------------------------------------------------------
            */

            $departmentSummary->push([

                'department' => $department,

                'employee_count' =>
                $employeeCount,

                'period_days' =>
                $totalPeriodDays,

                'possible_attendance_days' =>
                $possibleAttendanceDays,

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

        $totalDepartments =
            $departmentSummary->count();

        $totalEmployees =
            $departmentSummary->sum(
                'employee_count'
            );

        $totalPossibleAttendanceDays =
            $departmentSummary->sum(
                'possible_attendance_days'
            );

        $totalPresentDays =
            $departmentSummary->sum(
                'present_days'
            );

        $totalAbsentDays =
            $departmentSummary->sum(
                'absent_days'
            );

        $totalLeaveDays =
            $departmentSummary->sum(
                'leave_days'
            );

        $totalHolidayDays =
            $departmentSummary->sum(
                'holiday_days'
            );

        $totalLateDays =
            $departmentSummary->sum(
                'late_days'
            );

        $totalLateMinutes =
            $departmentSummary->sum(
                'total_late_minutes'
            );

        $totalEarlyLeaveDays =
            $departmentSummary->sum(
                'early_leave_days'
            );

        $totalEarlyLeaveMinutes =
            $departmentSummary->sum(
                'total_early_leave_minutes'
            );

        $totalOvertimeDays =
            $departmentSummary->sum(
                'overtime_days'
            );

        $totalOvertimeMinutes =
            $departmentSummary->sum(
                'total_overtime_minutes'
            );

        $totalExceptionMinutes =
            $departmentSummary->sum(
                'total_exception_minutes'
            );


        /*
        |--------------------------------------------------------------------------
        | Overall Percentage
        |--------------------------------------------------------------------------
        */

        $overallAttendancePercentage = 0;

        if ($totalPossibleAttendanceDays > 0) {

            $overallAttendancePercentage = round(
                (
                    $totalPresentDays /
                    $totalPossibleAttendanceDays
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
            'payroll::reports.department-attendance-summary.index',
            compact(
                'departmentSummary',

                'departments',

                'fromDate',
                'toDate',

                'departmentId',

                'totalPeriodDays',

                'totalDepartments',
                'totalEmployees',

                'totalPossibleAttendanceDays',

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
