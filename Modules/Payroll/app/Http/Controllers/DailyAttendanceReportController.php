<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Modules\Payroll\App\Models\Attendance;
use Modules\Payroll\App\Models\Employee;

class DailyAttendanceReportController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));

        $employeeId = $request->input('employee_id');
        $departmentId = $request->input('department_id');
        $status = $request->input('status');

        $attendances = Attendance::query()
            ->with([
                'employee.department',
                'employee.designation',
                'employee.shift',
            ])
            ->whereDate('date', $date)

            ->when($employeeId, function ($query) use ($employeeId) {
                $query->where('employee_id', $employeeId);
            })

            ->when($departmentId, function ($query) use ($departmentId) {
                $query->whereHas('employee', function ($query) use ($departmentId) {
                    $query->where('department_id', $departmentId);
                });
            })

            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })

            ->orderBy('employee_id')
            ->paginate(25)
            ->withQueryString();

        $employees = Employee::query()
            ->where('status', 'active')
            ->orderBy('first_name_en')
            ->get();

        $departments = Department::query()
            ->orderBy('title_en')
            ->get();

        $statuses = [
            'present',
            'absent',
            'late',
            'half_day',
            'leave',
            'holiday',
            'manual',
        ];

        $summaryQuery = Attendance::query()
            ->whereDate('date', $date)

            ->when($employeeId, function ($query) use ($employeeId) {
                $query->where('employee_id', $employeeId);
            })

            ->when($departmentId, function ($query) use ($departmentId) {
                $query->whereHas('employee', function ($query) use ($departmentId) {
                    $query->where('department_id', $departmentId);
                });
            })

            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            });

        $summary = [
            'total' => (clone $summaryQuery)->count(),

            'present' => (clone $summaryQuery)
                ->where('status', 'present')
                ->count(),

            'absent' => (clone $summaryQuery)
                ->where('status', 'absent')
                ->count(),

            'late' => (clone $summaryQuery)
                ->where('status', 'late')
                ->count(),

            'half_day' => (clone $summaryQuery)
                ->where('status', 'half_day')
                ->count(),

            'leave' => (clone $summaryQuery)
                ->where('status', 'leave')
                ->count(),

            'holiday' => (clone $summaryQuery)
                ->where('status', 'holiday')
                ->count(),

            'manual' => (clone $summaryQuery)
                ->where('is_manual', true)
                ->count(),

            'late_minutes' => (clone $summaryQuery)->sum('late_minutes'),

            'early_leave_minutes' => (clone $summaryQuery)
                ->sum('early_leave_minutes'),

            'overtime_minutes' => (clone $summaryQuery)
                ->sum('overtime_minutes'),
        ];

        return view(
            'payroll::reports.daily-attendance.index',
            compact(
                'attendances',
                'employees',
                'departments',
                'statuses',
                'summary',
                'date',
                'employeeId',
                'departmentId',
                'status'
            )
        );
    }
}
