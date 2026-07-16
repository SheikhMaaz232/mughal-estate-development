<?php

namespace Modules\Payroll\App\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Modules\Payroll\App\Models\Attendance;
use Modules\Payroll\App\Models\Employee;
use Modules\Payroll\App\Models\Payroll;

class PayrollService
{
    protected array $presentStatuses;
    protected array $leaveStatuses;
    protected array $holidayStatuses;
    protected int $defaultWorkingHours;

    public function __construct()
    {
        $this->presentStatuses = config('payroll.payroll_statuses.present', ['present', 'late', 'half_day', 'manual']);
        $this->leaveStatuses = config('payroll.include_leave_in_payroll', true)
            ? config('payroll.payroll_statuses.paid_leave', ['leave'])
            : [];
        $this->holidayStatuses = config('payroll.include_holiday_in_payroll', true)
            ? config('payroll.payroll_statuses.holiday', ['holiday'])
            : [];
        $this->defaultWorkingHours = config('payroll.working_hours_per_day', 8);
    }

    public function generatePayroll(string $month): Collection
    {
        $period = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endOfMonth = $period->copy()->endOfMonth();

        $employees = Employee::with('shift')
            ->where('status', 'active')
            ->where('basic_salary', '>', 0)
            ->get();

        return $employees->map(function (Employee $employee) use ($month, $period, $endOfMonth) {
            $existingPayroll = Payroll::where('employee_id', $employee->id)
                ->where('month', $month)
                ->first();

            if ($existingPayroll && $existingPayroll->is_finalized) {
                return $existingPayroll;
            }

            $attendanceRecords = Attendance::where('employee_id', $employee->id)
                ->whereBetween('date', [$period->toDateString(), $endOfMonth->toDateString()])
                ->get();

            $summary = [
                'total_worked_days' => $attendanceRecords->whereIn('status', $this->presentStatuses)->count(),
                'total_absent_days' => $attendanceRecords->where('status', 'absent')->count(),
                'total_leave_days' => $attendanceRecords->whereIn('status', $this->leaveStatuses)->count(),
                'total_holiday_days' => $attendanceRecords->whereIn('status', $this->holidayStatuses)->count(),
                'total_late_minutes' => $attendanceRecords->sum('late_minutes'),
                'total_early_leave_minutes' => $attendanceRecords->sum('early_leave_minutes'),
                'total_overtime_minutes' => $attendanceRecords->sum('overtime_minutes'),
            ];

            $values = $this->buildPayrollValues($employee, $month, $period, $summary, $existingPayroll);

            return Payroll::updateOrCreate(
                ['employee_id' => $employee->id, 'month' => $month],
                $values
            );
        });
    }

    public function updatePayroll(Payroll $payroll, array $data): Payroll
    {
        $payroll->allowance_adjustment = $data['allowance_adjustment'] ?? $payroll->allowance_adjustment;
        $payroll->deduction_adjustment = $data['deduction_adjustment'] ?? $payroll->deduction_adjustment;
        $payroll->is_finalized = (bool) ($data['is_finalized'] ?? $payroll->is_finalized);
        $payroll->finalized_at = $payroll->is_finalized
            ? ($payroll->finalized_at ?? Carbon::now())
            : null;

        $payableDays = $payroll->total_worked_days + $payroll->total_leave_days + $payroll->total_holiday_days;
        $payroll->gross_salary = $this->roundAmount(
            ($payroll->daily_rate * $payableDays) + $payroll->overtime_amount + $payroll->allowance_adjustment
        );

        $totalDeductions = $this->roundAmount(
            $payroll->absence_deduction_amount + $payroll->late_early_deduction_amount + $payroll->deduction_adjustment
        );

        $payroll->net_salary = $this->roundAmount($payroll->gross_salary - $totalDeductions);
        $payroll->save();

        return $payroll;
    }

    protected function buildPayrollValues(Employee $employee, string $month, Carbon $period, array $summary, ?Payroll $existingPayroll = null): array
    {
        $daysInMonth = $period->daysInMonth;
        $basicSalary = $employee->basic_salary;
        $dailyRate = $daysInMonth > 0 ? $basicSalary / $daysInMonth : 0;
        $minuteRate = $dailyRate / max($this->getWorkingHoursPerDay($employee) * 60, 1);
        $payableDays = $summary['total_worked_days'] + $summary['total_leave_days'] + $summary['total_holiday_days'];

        $absenceDeduction = $dailyRate * $summary['total_absent_days'];
        $lateEarlyDeduction = $minuteRate * ($summary['total_late_minutes'] + $summary['total_early_leave_minutes']);
        $overtimeAmount = $minuteRate * $summary['total_overtime_minutes'];
        $allowanceAdjustment = $existingPayroll->allowance_adjustment ?? 0;
        $deductionAdjustment = $existingPayroll->deduction_adjustment ?? 0;

        $grossSalary = $dailyRate * $payableDays + $overtimeAmount + $allowanceAdjustment;
        $netSalary = $grossSalary - ($absenceDeduction + $lateEarlyDeduction + $deductionAdjustment);

        return [
            'year' => $period->year,
            'days_in_month' => $daysInMonth,
            'total_worked_days' => $summary['total_worked_days'],
            'total_absent_days' => $summary['total_absent_days'],
            'total_leave_days' => $summary['total_leave_days'],
            'total_holiday_days' => $summary['total_holiday_days'],
            'total_late_minutes' => $summary['total_late_minutes'],
            'total_early_leave_minutes' => $summary['total_early_leave_minutes'],
            'total_overtime_minutes' => $summary['total_overtime_minutes'],
            'basic_salary' => $this->roundAmount($basicSalary),
            'daily_rate' => $this->roundAmount($dailyRate),
            'minute_rate' => round($minuteRate, 4),
            'absence_deduction_amount' => $this->roundAmount($absenceDeduction),
            'late_early_deduction_amount' => $this->roundAmount($lateEarlyDeduction),
            'overtime_amount' => $this->roundAmount($overtimeAmount),
            'allowance_adjustment' => $allowanceAdjustment,
            'deduction_adjustment' => $deductionAdjustment,
            'gross_salary' => $this->roundAmount($grossSalary),
            'net_salary' => $this->roundAmount($netSalary),
            'is_finalized' => $existingPayroll->is_finalized ?? false,
            'finalized_at' => $existingPayroll->finalized_at ?? null,
        ];
    }

    protected function getWorkingHoursPerDay(Employee $employee): float
    {
        if ($employee->shift && !empty($employee->shift->start_time) && !empty($employee->shift->end_time)) {
            $start = Carbon::parse($employee->shift->start_time);
            $end = Carbon::parse($employee->shift->end_time);

            return max(1, $start->diffInMinutes($end) / 60);
        }

        return $this->defaultWorkingHours;
    }

    protected function roundAmount(float $value): float
    {
        return round($value, 2);
    }
}
