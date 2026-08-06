<?php

use Illuminate	esting\Concerns\InteractsWithAuthentication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Payroll\App\Models\Employee;
use Modules\Payroll\App\Models\Designation;
use Modules\Payroll\App\Models\Shift;
use App\Models\Department;
use App\Models\User;

uses(RefreshDatabase::class);

describe('payroll employee contract', function () {
    it('downloads a contract pdf for an employee', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $department = Department::create([
            'title_en' => 'Engineering',
            'title_ur' => 'انجینئرنگ',
            'department_type' => Department::TYPE_OPERATING,
        ]);

        $designation = Designation::create([
            'title_en' => 'Developer',
            'title_ur' => 'ڈیولپر',
        ]);

        $shift = Shift::create([
            'shift_name_en' => 'Day',
            'shift_name_ur' => 'دن',
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
        ]);

        $employee = Employee::create([
            'first_name_en' => 'Ali',
            'last_name_en' => 'Khan',
            'first_name_ur' => 'علی',
            'last_name_ur' => 'خان',
            'father_name_en' => 'Ahmed',
            'father_name_ur' => 'احمد',
            'cnic' => '12345-6789012-3',
            'dob' => '1990-01-01',
            'basic_salary' => 50000,
            'department_id' => $department->id,
            'designation_id' => $designation->id,
            'shift_id' => $shift->id,
            'joining_date' => now()->toDateString(),
        ]);

        $response = $this->get(route('payroll.employees.contract', $employee));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $response->assertHeader('content-disposition', fn ($value) => str_contains($value, 'employee-contract-' . $employee->id . '.pdf'));
    });
});
