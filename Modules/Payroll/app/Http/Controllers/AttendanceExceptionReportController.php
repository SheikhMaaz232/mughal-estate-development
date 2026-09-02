<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Payroll\App\Models\Attendance;
use Modules\Payroll\App\Models\Designation;
use Modules\Payroll\App\Models\Employee;
use Modules\Payroll\App\Models\Shift;

class AttendanceExceptionReportController extends Controller
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

        try {
            $from = Carbon::parse($fromDate);
            $to = Carbon::parse($toDate);

            if ($from->gt($to)) {
                [$from, $to] = [$to, $from];

                $fromDate = $from->format('Y-m-d');
                $toDate = $to->format('Y-m-d');
            }
        } catch (\Throwable $e) {
            $from = now()->startOfMonth();
            $to = now();

            $fromDate = $from->format('Y-m-d');
            $toDate = $to->format('Y-m-d');
        }

        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        $employeeId = $request->input('employee_id');
        $departmentId = $request->input('department_id');
        $designationId = $request->input('designation_id');
        $shiftId = $request->input('shift_id');

        $exceptionType = $request->input(
            'exception_type',
            'all'
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
        | Exception Filter
        |--------------------------------------------------------------------------
        */

        switch ($exceptionType) {

            case 'late':

                $query->where(
                    'late_minutes',
                    '>',
                    0
                );

                break;

            case 'early_leave':

                $query->where(
                    'early_leave_minutes',
                    '>',
                    0
                );

                break;

            case 'both':

                $query->where(
                    'late_minutes',
                    '>',
                    0
                )->where(
                    'early_leave_minutes',
                    '>',
                    0
                );

                break;

            case 'missing_check_in':

                $query->whereNull(
                    'check_in'
                );

                break;

            case 'missing_check_out':

                $query->whereNull(
                    'check_out'
                );

                break;

            case 'missing_punch':

                $query->where(function ($attendanceQuery) {

                    $attendanceQuery
                        ->whereNull('check_in')
                        ->orWhereNull('check_out');

                });

                break;

            case 'all':

            default:

                $query->where(function ($attendanceQuery) {

                    $attendanceQuery
                        ->where(
                            'late_minutes',
                            '>',
                            0
                        )
                        ->orWhere(
                            'early_leave_minutes',
                            '>',
                            0
                        )
                        ->orWhereNull('check_in')
                        ->orWhereNull('check_out');

                });

                break;
        }

        /*
        |--------------------------------------------------------------------------
        | Get Records
        |--------------------------------------------------------------------------
        */

        $attendances = $query
            ->orderBy('date')
            ->orderBy(
                Employee::select('first_name_en')
                    ->whereColumn(
                        'employees.id',
                        'attendances.employee_id'
                    )
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Classify Exception
        |--------------------------------------------------------------------------
        */

        $attendances->each(function ($attendance) {

            $late = (int) $attendance->late_minutes > 0;

            $early = (int) $attendance->early_leave_minutes > 0;

            $missingCheckIn = empty(
                $attendance->check_in
            );

            $missingCheckOut = empty(
                $attendance->check_out
            );

            if ($missingCheckIn && $missingCheckOut) {

                $attendance->exception_label =
                    'Missing Check-In & Check-Out';

                $attendance->exception_type =
                    'missing_punch';

            } elseif ($missingCheckIn) {

                $attendance->exception_label =
                    'Missing Check-In';

                $attendance->exception_type =
                    'missing_check_in';

            } elseif ($missingCheckOut) {

                $attendance->exception_label =
                    'Missing Check-Out';

                $attendance->exception_type =
                    'missing_check_out';

            } elseif ($late && $early) {

                $attendance->exception_label =
                    'Late & Early Leave';

                $attendance->exception_type =
                    'both';

            } elseif ($late) {

                $attendance->exception_label =
                    'Late Arrival';

                $attendance->exception_type =
                    'late';

            } elseif ($early) {

                $attendance->exception_label =
                    'Early Leave';

                $attendance->exception_type =
                    'early_leave';

            } else {

                $attendance->exception_label =
                    'Exception';

                $attendance->exception_type =
                    'exception';
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $totalRecords = $attendances->count();

        $lateRecords = $attendances
            ->filter(function ($attendance) {
                return (int) $attendance->late_minutes > 0;
            })
            ->count();

        $earlyLeaveRecords = $attendances
            ->filter(function ($attendance) {
                return (int) $attendance->early_leave_minutes > 0;
            })
            ->count();

        $bothRecords = $attendances
            ->filter(function ($attendance) {
                return
                    (int) $attendance->late_minutes > 0 &&
                    (int) $attendance->early_leave_minutes > 0;
            })
            ->count();

        $missingCheckInRecords = $attendances
            ->filter(function ($attendance) {
                return empty($attendance->check_in);
            })
            ->count();

        $missingCheckOutRecords = $attendances
            ->filter(function ($attendance) {
                return empty($attendance->check_out);
            })
            ->count();

        $missingPunchRecords = $attendances
            ->filter(function ($attendance) {
                return
                    empty($attendance->check_in) ||
                    empty($attendance->check_out);
            })
            ->count();

        $totalLateMinutes = $attendances->sum(
            'late_minutes'
        );

        $totalEarlyLeaveMinutes = $attendances->sum(
            'early_leave_minutes'
        );

        $totalExceptionMinutes =
            $totalLateMinutes +
            $totalEarlyLeaveMinutes;

        /*
        |--------------------------------------------------------------------------
        | Employee Summary
        |--------------------------------------------------------------------------
        */

        $employeeSummary = $attendances
            ->groupBy('employee_id')
            ->map(function ($records) {

                $employee = $records
                    ->first()
                    ->employee;

                return [

                    'employee' => $employee,

                    'records' => $records->count(),

                    'late_records' =>
                        $records
                            ->filter(function ($record) {
                                return
                                    (int) $record->late_minutes > 0;
                            })
                            ->count(),

                    'early_leave_records' =>
                        $records
                            ->filter(function ($record) {
                                return
                                    (int) $record->early_leave_minutes > 0;
                            })
                            ->count(),

                    'missing_check_in' =>
                        $records
                            ->filter(function ($record) {
                                return empty(
                                    $record->check_in
                                );
                            })
                            ->count(),

                    'missing_check_out' =>
                        $records
                            ->filter(function ($record) {
                                return empty(
                                    $record->check_out
                                );
                            })
                            ->count(),

                    'late_minutes' =>
                        (int) $records->sum(
                            'late_minutes'
                        ),

                    'early_leave_minutes' =>
                        (int) $records->sum(
                            'early_leave_minutes'
                        ),
                ];
            })
            ->sortBy(function ($item) {

                return $item['employee']
                    ? $item['employee']->first_name_en
                    : '';
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view(
            'payroll::reports.attendance-exception.index',
            compact(

                'attendances',

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

                'exceptionType',

                'totalRecords',

                'lateRecords',
                'earlyLeaveRecords',
                'bothRecords',

                'missingCheckInRecords',
                'missingCheckOutRecords',
                'missingPunchRecords',

                'totalLateMinutes',
                'totalEarlyLeaveMinutes',
                'totalExceptionMinutes'
            )
        );
    }
}
