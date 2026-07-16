<?php

namespace Modules\Payroll\App\Services;

use Modules\Payroll\App\Models\Employee;
use Modules\Payroll\App\Models\EmployeeContact;
use Modules\Payroll\App\Models\EmployeeBank;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Payroll\App\Models\EmployeeAllowance;
use Modules\Payroll\App\Models\EmployeeDeduction;
use Modules\Payroll\App\Models\LeaveBalance;

class EmployeeService
{
    /**
     * Create a new employee with related data
     */
    public function createEmployee(array $data): Employee
    {
        return DB::transaction(function () use ($data) {
            // Handle profile picture upload
            if (isset($data['profile_picture']) && $data['profile_picture']->isValid()) {
                $data['profile_picture'] = $data['profile_picture']->store('employee-profile-pictures', 'public');
            }

            // Create the employee
            $employee = Employee::create([
                'first_name_en' => $data['first_name_en'],
                'first_name_ur' => $data['first_name_ur'],
                'last_name_en' => $data['last_name_en'] ?? null,
                'last_name_ur' => $data['last_name_ur'] ?? null,
                'father_name_en' => $data['father_name_en'] ?? null,
                'father_name_ur' => $data['father_name_ur'] ?? null,
                'cnic' => $data['cnic'],
                'dob' => $data['dob'] ?? null,
                'gender' => $data['gender'] ?? null,
                'marital_status' => $data['marital_status'] ?? null,
                'shift_id' => $data['shift_id'] ?? null,
                'device_id' => '1' ?? null,
                'department_id' => $data['department_id'] ?? null,
                'designation_id' => $data['designation_id'] ?? null,
                'joining_date' => $data['joining_date'] ?? null,
                'basic_salary' => $data['basic_salary'] ?? 0,
                'profile_picture' => $data['profile_picture'] ?? null,
                'status' => isset($data['status']) && $data['status'] === 'active' ? 'active' : 'inactive',
            ]);

            // Create contact information
            if (!empty($data['contacts'])) {
                $this->createContacts($employee, $data['contacts']);
            }

            // Create bank information
            if (!empty($data['banks'])) {
                $this->saveBanks($employee, $data['banks']);
            }

            // Create deductions information
            if (!empty($data['deductions'])) {
                $this->saveDeductions($employee, $data['deductions']);
            }

            // Create allowances information
            if (!empty($data['allowances'])) {
                $this->saveAllowances($employee, $data['allowances']);
            }

            // Create leave balance information
            if (!empty($data['leave_balances'])) {
                $this->saveLeaveBalances($employee, $data['leave_balances']);
            }

            return $employee->load(['contacts', 'banks', 'allowances', 'deductions', 'leaveBalances']);
        });
    }

    /**
     * Update an existing employee with related data
     */
    public function updateEmployee(Employee $employee, array $data): Employee
    {
        return DB::transaction(function () use ($employee, $data) {
            // Handle profile picture upload
            if (isset($data['profile_picture']) && $data['profile_picture']->isValid()) {
                // Delete old profile picture if exists
                if ($employee->profile_picture) {
                    Storage::disk('public')->delete($employee->profile_picture);
                }

                $data['profile_picture'] = $data['profile_picture']->store('employee-profile-pictures', 'public');
            } else {
                // Keep the existing profile picture if not updating
                $data['profile_picture'] = $employee->profile_picture;
            }

            // Update the employee
            $employee->update([
                'first_name_en' => $data['first_name_en'],
                'first_name_ur' => $data['first_name_ur'],
                'last_name_en' => $data['last_name_en'] ?? null,
                'last_name_ur' => $data['last_name_ur'] ?? null,
                'father_name_en' => $data['father_name_en'] ?? null,
                'father_name_ur' => $data['father_name_ur'] ?? null,
                'cnic' => $data['cnic'],
                'dob' => $data['dob'] ?? null,
                'gender' => $data['gender'] ?? null,
                'marital_status' => $data['marital_status'] ?? null,
                'shift_id' => $data['shift_id'] ?? null,
                'device_id' => $data['device_id'] ?? null,
                'department_id' => $data['department_id'] ?? null,
                'designation_id' => $data['designation_id'] ?? null,
                'joining_date' => $data['joining_date'] ?? null,
                'basic_salary' => $data['basic_salary'] ?? 0,
                'profile_picture' => $data['profile_picture'] ?? null,
                'status' => isset($data['status']) && $data['status'] === 'active' ? 'active' : 'inactive',
            ]);

            // Update contact information
            if (isset($data['contacts'])) {
                $this->updateContacts($employee, $data['contacts']);
            }

            // Update bank information
            if (isset($data['banks'])) {
                $this->updateBanks($employee, $data['banks']);
            }

            // Update deductions information
            if (isset($data['deductions'])) {
                $this->updateDeductions($employee, $data['deductions']);
            }

            // Update allowances information
            if (isset($data['allowances'])) {
                $this->updateAllowances($employee, $data['allowances']);
            }

            // Update leave balance information
            if (isset($data['leave_balances'])) {
                $this->updateLeaveBalances($employee, $data['leave_balances']);
            }

            return $employee->load(['contacts', 'banks', 'allowances', 'deductions', 'leaveBalances']);
        });
    }

    /**
     * Create contact information for an employee
     */
    protected function createContacts(Employee $employee, array $contacts): void
    {
        $contactData = [];

        foreach ($contacts as $contact) {
            if (!empty($contact['phone']) || !empty($contact['email'])) {
                $contactData[] = [
                    'employee_id' => $employee->id,
                    'type' => $contact['type'] ?? 'personal',
                    'phone' => $contact['phone'] ?? null,
                    'email' => $contact['email'] ?? null,
                    'address' => $contact['address'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (!empty($contactData)) {
            EmployeeContact::insert($contactData);
        }
    }

    /**
     * Update contact information for an employee
     */
    protected function updateContacts(Employee $employee, array $contacts): void
    {
        // First, remove all existing contacts
        $employee->contacts()->delete();

        // Then create new ones from the submitted data
        $this->createContacts($employee, $contacts);
    }

    /**
     * Create bank information for an employee
     */
    protected function saveBanks(Employee $employee, array $banks): void
    {
        $bankData = [];

        foreach ($banks as $bank) {
            if (!empty($bank['bank_id']) || !empty($bank['account_number'])) {
                $bankData[] = [
                    'employee_id' => $employee->id,
                    'bank_id' => $bank['bank_id'] ?? null,
                    'account_number' => $bank['account_number'] ?? null,
                    'account_title' => $bank['account_title'] ?? null,
                    'iban' => $bank['iban'] ?? null,
                    'branch_code' => $bank['branch_code'] ?? null,
                    'type' => $bank['type'] ?? 'savings',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (!empty($bankData)) {
            EmployeeBank::insert($bankData);
        }
    }

    /**
     * Update bank information for an employee
     */
    protected function updateBanks(Employee $employee, array $banks): void
    {
        // First, remove all existing banks
        $employee->banks()->delete();

        // Then create new ones from the submitted data
        $this->saveBanks($employee, $banks);
    }

    /**
     * Create deductions information for an employee
     */
    protected function saveDeductions(Employee $employee, array $deductions): void
    {
        $deductionData = [];

        foreach ($deductions as $deduction) {
            if (!empty($deduction['deduction_id']) || !empty($deduction['amount'])) {
                $deductionData[] = [
                    'employee_id' => $employee->id,
                    'deduction_id' => $deduction['deduction_id'] ?? null,
                    'amount' => $deduction['amount'] ?? 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (!empty($deductionData)) {
            EmployeeDeduction::insert($deductionData);
        }
    }

    /**
     * Update deductions information for an employee
     */
    protected function updateDeductions(Employee $employee, array $deductions): void
    {
        // First, remove all existing deductions
        $employee->deductions()->delete();

        // Then create new ones from the submitted data
        $this->saveDeductions($employee, $deductions);
    }

    /**
     * Create allowances information for an employee
     */
    protected function saveAllowances(Employee $employee, array $allowances): void
    {
        $allowanceData = [];

        foreach ($allowances as $allowance) {
            if (!empty($allowance['allowance_id']) || !empty($allowance['amount'])) {
                $allowanceData[] = [
                    'employee_id' => $employee->id,
                    'allowance_id' => $allowance['allowance_id'] ?? null,
                    'amount' => $allowance['amount'] ?? 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (!empty($allowanceData)) {
            EmployeeAllowance::insert($allowanceData);
        }
    }

    /**
     * Update allowances information for an employee
     */
    protected function updateAllowances(Employee $employee, array $allowances): void
    {
        // First, remove all existing allowances
        $employee->allowances()->delete();

        // Then create new ones from the submitted data
        $this->saveAllowances($employee, $allowances);
    }

    /**
     * Create leave balance information for an employee
     */
    protected function saveLeaveBalances(Employee $employee, array $leaveBalances): void
    {
        $balanceData = [];

        foreach ($leaveBalances as $balance) {
            if (empty($balance['leave_type_id'])) {
                continue;
            }

            $balanceData[] = [
                'employee_id' => $employee->id,
                'leave_type_id' => $balance['leave_type_id'],
                'total_days' => isset($balance['total_days']) ? (int) $balance['total_days'] : 0,
                'used_days' => isset($balance['used_days']) ? (int) $balance['used_days'] : 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($balanceData)) {
            LeaveBalance::insert($balanceData);
        }
    }

    /**
     * Update leave balance information for an employee
     */
    protected function updateLeaveBalances(Employee $employee, array $leaveBalances): void
    {
        $employee->leaveBalances()->delete();
        $this->saveLeaveBalances($employee, $leaveBalances);
    }

    /**
     * Delete an employee and related data
     */
    public function deleteEmployee(Employee $employee): void
    {
        DB::transaction(function () use ($employee) {
            // Delete profile picture if exists
            if ($employee->profile_picture) {
                Storage::disk('public')->delete($employee->profile_picture);
            }

            // Delete related records
            $employee->contacts()->delete();
            $employee->banks()->delete();
            $employee->allowances()->delete();
            $employee->deductions()->delete();
            $employee->leaveBalances()->delete();

            // Delete the employee
            $employee->delete();
        });
    }
}
