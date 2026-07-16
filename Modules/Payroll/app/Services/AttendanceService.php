<?php

namespace Modules\Payroll\App\Services;

use Carbon\Carbon;
use Modules\Payroll\App\Models\Attendance;
use Modules\Payroll\App\Models\AttendanceLog;
use Modules\Payroll\App\Models\Employee;
use Modules\Payroll\App\Models\Holiday;
use Modules\Payroll\App\Models\LeaveRequest;

class AttendanceService
{
    public function processDate($date)
    {
        $employees = Employee::with('shift')->get();

        foreach ($employees as $employee) {

            //  Skip if no device user id
            if (!$employee->device_id) {
                continue;
            }

            $attendance = Attendance::firstOrNew([
                'employee_id' => $employee->id,
                'date' => $date
            ]);

            //  Skip manual override
            if ($attendance->exists && $attendance->is_manual) {
                continue;
            }

            $logs = AttendanceLog::where('device_user_id', $employee->device_id)
                ->whereDate('punch_time', $date)
                ->orderBy('punch_time')
                ->get();

            $holiday = Holiday::whereDate('date', $date)->first();

            if ($holiday) {
                $attendance->fill([
                    'status' => 'holiday',
                    'late_minutes' => 0,
                    'early_leave_minutes' => 0,
                    'overtime_minutes' => 0,
                ])->save();

                continue;
            }

            $leave = LeaveRequest::where('employee_id', $employee->id)
                ->where('status', 'approved')
                ->whereDate('start_date', '<=', $date)
                ->whereDate('end_date', '>=', $date)
                ->first();

            if ($leave) {
                $attendance->fill([
                    'status' => 'leave',
                    'late_minutes' => 0,
                    'early_leave_minutes' => 0,
                    'overtime_minutes' => 0,
                ])->save();

                continue;
            }

            //  No logs → Absent
            if ($logs->isEmpty()) {
                $attendance->fill([
                    'status' => 'absent',
                    'check_in' => null,
                    'check_out' => null,
                    'late_minutes' => 0,
                    'early_leave_minutes' => 0,
                    'overtime_minutes' => 0,
                ])->save();

                continue;
            }

            $attendance->fill([
                'check_in' => $logs->first()->punch_time,
                'check_out' => $logs->last()->punch_time,
                'status' => 'present'
            ])->save();

            //  Apply advanced calculation
            $this->calculateTime($attendance);
        }
    }

    public function markManual(array $data)
    {
        foreach ($data['employees'] as $employeeId) {

            $attendance = Attendance::updateOrCreate(
                [
                    'employee_id' => $employeeId,
                    'date' => $data['date']
                ],
                [
                    'check_in' => $data['check_in'] ?? null,
                    'check_out' => $data['check_out'] ?? null,
                    'status' => $data['status'] ?? 'manual',
                    'is_manual' => true
                ]
            );

            //  Calculate after save
            $this->calculateTime($attendance);
        }
    }

    public function calculateTime($attendance)
    {


        //  Missing punches
        if (!$attendance->check_in || !$attendance->check_out) {
            $attendance->update([
                'late_minutes' => 0,
                'early_leave_minutes' => 0,
                'overtime_minutes' => 0,
            ]);
            return;
        }

        $employee = $attendance->employee;

        //  Safety check
        if (!$employee || !$employee->shift) {
            return;
        }

        $shift = $employee->shift;

        $shiftStart = Carbon::parse($shift->start_time);
        $shiftEnd   = Carbon::parse($shift->end_time);
        $grace      = $shift->grace_minutes ?? 0;

        $checkIn  = Carbon::parse($attendance->check_in);
        $checkOut = Carbon::parse($attendance->check_out);

        //  LATE (WITH GRACE)
        $late = 0;
        if ($checkIn->greaterThan($shiftStart->copy()->addMinutes($grace))) {
            $late = $shiftStart->diffInMinutes($checkIn);
        }

        //  EARLY LEAVE
        $earlyLeave = 0;
        if ($checkOut->lessThan($shiftEnd)) {
            $earlyLeave = $checkOut->diffInMinutes($shiftEnd);
        }

        //  OVERTIME
        $overtime = 0;
        if ($checkOut->greaterThan($shiftEnd)) {
            $overtime = $shiftEnd->diffInMinutes($checkOut);
        }

        //  STATUS LOGIC
        $status = 'present';

        if ($late > 0) {
            $status = 'late';
        }

        // Half-day condition
        if ($late > 240 || $earlyLeave > 240) {
            $status = 'half_day';
        }

        $attendance->update([
            'late_minutes' => $late,
            'early_leave_minutes' => $earlyLeave,
            'overtime_minutes' => $overtime,
            'status' => $status,
        ]);
    }
}
