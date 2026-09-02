<?php

use Illuminate\Support\Facades\Route;
use Modules\Payroll\App\Http\Controllers\AbsenteeReportController;
use Modules\Payroll\App\Http\Controllers\AllowanceController;
use Modules\Payroll\App\Http\Controllers\AttendanceController;
use Modules\Payroll\App\Http\Controllers\AttendanceDetailReportController;
use Modules\Payroll\App\Http\Controllers\AttendanceDeviceController;
use Modules\Payroll\App\Http\Controllers\AttendanceExceptionReportController;
use Modules\Payroll\App\Http\Controllers\ContractController;
use Modules\Payroll\App\Http\Controllers\DailyAttendanceReportController;
use Modules\Payroll\App\Http\Controllers\DeductionController;
use Modules\Payroll\App\Http\Controllers\DepartmentAttendanceSummaryReportController;
use Modules\Payroll\App\Http\Controllers\DepartmentPayrollSummaryReportController;
use Modules\Payroll\App\Http\Controllers\DepartmentSalarySummaryReportController;
use Modules\Payroll\App\Http\Controllers\DesignationController;
use Modules\Payroll\App\Http\Controllers\EmployeeAttendanceCardController;
use Modules\Payroll\App\Http\Controllers\EmployeeAttendanceSummaryReportController;
use Modules\Payroll\App\Http\Controllers\EmployeeController;
use Modules\Payroll\App\Http\Controllers\EmployeeLeaveBalanceReportController;
use Modules\Payroll\App\Http\Controllers\EmployeePayslipController;
use Modules\Payroll\App\Http\Controllers\GradeController;
use Modules\Payroll\App\Http\Controllers\HolidayController;
use Modules\Payroll\App\Http\Controllers\HolidayTypeController;
use Modules\Payroll\App\Http\Controllers\LateEarlyLeaveReportController;
use Modules\Payroll\App\Http\Controllers\LeaveReportController;
use Modules\Payroll\App\Http\Controllers\LeaveRequestController;
use Modules\Payroll\App\Http\Controllers\LeaveTypeController;
use Modules\Payroll\App\Http\Controllers\MonthlyAttendanceReportController;
use Modules\Payroll\App\Http\Controllers\OvertimeReportController;
use Modules\Payroll\App\Http\Controllers\PayrollController;
use Modules\Payroll\App\Http\Controllers\PayrollDeductionReportController;
use Modules\Payroll\App\Http\Controllers\PayrollSalaryRegisterController;
use Modules\Payroll\App\Http\Controllers\PayrollTypeController;
use Modules\Payroll\App\Http\Controllers\QualificationController;
use Modules\Payroll\App\Http\Controllers\ShiftController;

Route::get('/payroll', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified', 'web'])->group(function () {

    Route::prefix('/payroll')
        ->name('payroll.')
        ->middleware(['auth', 'verified', 'web'])
        ->group(function () {
            Route::resource('payrolls', PayrollController::class);
            Route::resource('qualifications', QualificationController::class);
            Route::resource('grades', GradeController::class)->names('grades');
            Route::resource('payroll-types', PayrollTypeController::class)->names('payroll-types');
            Route::resource('designations', DesignationController::class)->names('designations');
            Route::resource('leave-types', LeaveTypeController::class)->names('leave-types');
            Route::resource('leave-requests', LeaveRequestController::class)->names('leave-requests');

            Route::get(
                'reports/daily-attendance',
                [DailyAttendanceReportController::class, 'index']
            )->name('reports.daily-attendance');

            Route::get(
                '/reports/monthly-attendance',
                [MonthlyAttendanceReportController::class, 'index']
            )->name('reports.monthly-attendance');

            Route::get(
                '/reports/attendance-detail',
                [AttendanceDetailReportController::class, 'index']
            )->name('reports.attendance-detail');

            Route::get(
                '/reports/employee-attendance-card',
                [EmployeeAttendanceCardController::class, 'index']
            )->name('reports.employee-attendance-card');

            Route::get(
                '/reports/payroll-salary-register',
                [PayrollSalaryRegisterController::class, 'index']
            )->name('reports.salary-register');

            Route::get(
                '/reports/employee-payslip',
                [EmployeePayslipController::class, 'index']
            )->name('reports.employee-payslip');

            Route::get(
                '/reports/late-early-leave',
                [LateEarlyLeaveReportController::class, 'index']
            )->name('reports.late-early-leave');

            Route::get(
                '/reports/overtime',
                [OvertimeReportController::class, 'index']
            )->name('reports.overtime');

            Route::get(
                '/reports/absentee',
                [AbsenteeReportController::class, 'index']
            )->name('reports.absentee');

            Route::get(
                '/reports/leave',
                [LeaveReportController::class, 'index']
            )->name('reports.leave');

            Route::get(
                '/reports/employee-attendance-summary',
                [EmployeeAttendanceSummaryReportController::class, 'index']
            )->name('reports.employee-attendance-summary');

            Route::get(
                '/reports/department-attendance-summary',
                [DepartmentAttendanceSummaryReportController::class, 'index']
            )->name('reports.department-attendance-summary');

            Route::get(
                '/reports/attendance-exception',
                [AttendanceExceptionReportController::class, 'index']
            )->name('reports.attendance-exception');

            Route::get(
                '/reports/employee-leave-balance',
                [
                    EmployeeLeaveBalanceReportController::class,
                    'index'
                ]
            )->name(
                'reports.employee-leave-balance'
            );

            Route::get(
                '/reports/department-payroll-summary',
                [
                    DepartmentPayrollSummaryReportController::class,
                    'index'
                ]
            )->name(
                'reports.department-payroll-summary'
            );

            Route::get(
                '/reports/payroll-deduction',
                [PayrollDeductionReportController::class, 'index']
            )->name('reports.payroll-deduction');

            Route::get(
                '/payroll/reports/department-salary-summary',
                [DepartmentSalarySummaryReportController::class, 'index']
            )->name(
                'reports.department-salary-summary'
            );


            // Leave Request approval routes
            Route::post('/leave-requests/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])->name('leave-requests.approve');
            Route::post('/leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('leave-requests.reject');

            Route::resource('holiday-types', HolidayTypeController::class)->names('holiday-types');
            Route::resource('holidays', HolidayController::class)->names('holidays');
            Route::resource('shifts', ShiftController::class)->names('shifts');
            Route::resource('deductions', DeductionController::class)->names('deductions');
            Route::resource('allowances', AllowanceController::class)->names('allowances');
            Route::resource('employees', EmployeeController::class)->names('employees');
            Route::get('/employees/{employee}/contract', [ContractController::class, 'view'])->name('employees.contract');
            Route::resource('devices', AttendanceDeviceController::class)->names('devices');
            Route::prefix('attendance')->group(function () {

                Route::get('/', [AttendanceController::class, 'index'])->name('attendance.index');

                Route::post('/process', [AttendanceController::class, 'process'])->name('attendance.process');

                Route::get('/manual', [AttendanceController::class, 'manual'])->name('attendance.manual');
                Route::post('/manual', [AttendanceController::class, 'storeManual'])->name('attendance.manual.store');
            });
        });
});
