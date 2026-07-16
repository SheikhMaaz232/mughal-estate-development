<?php

use Illuminate\Support\Facades\Route;
use Modules\Payroll\App\Http\Controllers\AllowanceController;
use Modules\Payroll\App\Http\Controllers\AttendanceController;
use Modules\Payroll\App\Http\Controllers\AttendanceDeviceController;
use Modules\Payroll\App\Http\Controllers\DeductionController;
use Modules\Payroll\App\Http\Controllers\DesignationController;
use Modules\Payroll\App\Http\Controllers\EmployeeController;
use Modules\Payroll\App\Http\Controllers\GradeController;
use Modules\Payroll\App\Http\Controllers\HolidayController;
use Modules\Payroll\App\Http\Controllers\HolidayTypeController;
use Modules\Payroll\App\Http\Controllers\LeaveRequestController;
use Modules\Payroll\App\Http\Controllers\LeaveTypeController;
use Modules\Payroll\App\Http\Controllers\PayrollController;
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

            // Leave Request approval routes
            Route::post('/leave-requests/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])->name('leave-requests.approve');
            Route::post('/leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('leave-requests.reject');

            Route::resource('holiday-types', HolidayTypeController::class)->names('holiday-types');
            Route::resource('holidays', HolidayController::class)->names('holidays');
            Route::resource('shifts', ShiftController::class)->names('shifts');
            Route::resource('deductions', DeductionController::class)->names('deductions');
            Route::resource('allowances', AllowanceController::class)->names('allowances');
            Route::resource('employees', EmployeeController::class)->names('employees');
            Route::resource('devices', AttendanceDeviceController::class)->names('devices');
            Route::prefix('attendance')->group(function () {

                Route::get('/', [AttendanceController::class, 'index'])->name('attendance.index');

                Route::post('/process', [AttendanceController::class, 'process'])->name('attendance.process');

                Route::get('/manual', [AttendanceController::class, 'manual'])->name('attendance.manual');
                Route::post('/manual', [AttendanceController::class, 'storeManual'])->name('attendance.manual.store');
            });
        });
});
