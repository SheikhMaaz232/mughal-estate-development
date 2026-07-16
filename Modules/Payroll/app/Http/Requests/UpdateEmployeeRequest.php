<?php

namespace Modules\Payroll\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $employeeId = $this->route('employee')->id;

        return [
            'first_name_en' => 'required|string|max:255',
            'first_name_ur' => 'required|string|max:255',
            'last_name_en' => 'nullable|string|max:255',
            'last_name_ur' => 'nullable|string|max:255',
            'father_name_en' => 'nullable|string|max:255',
            'father_name_ur' => 'nullable|string|max:255',
            'cnic' => [
                'required',
                'string',
                'max:20',
                Rule::unique('employees')->ignore($employeeId)
            ],
            'dob' => 'nullable|date',
            'gender' => 'required|in:male,female,other',
            'marital_status' => 'nullable|in:single,married,divorced',
            'department_id' => 'nullable|exists:departments,id',
            'designation_id' => 'nullable|exists:designations,id',
            'joining_date' => 'nullable|date',
            'basic_salary' => 'nullable|numeric|min:0',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'nullable|string',

            // Contacts validation
            'contacts' => 'nullable|array',
            'contacts.*.type' => 'nullable|in:personal,work,emergency,home', // Changed 'other' to 'home'
            'contacts.*.phone' => 'nullable|string|max:20',
            'contacts.*.email' => 'nullable|email|max:255',
            'contacts.*.address' => 'nullable|string',

            // Banks validation - fixed field names to match form
            'banks' => 'nullable|array',
            'banks.*.bank_id' => 'nullable|exists:banks,id', // Changed from 'name' to 'bank_id'
            'banks.*.account_number' => 'nullable|string|max:50',
            'banks.*.account_title' => 'nullable|string|max:255',
            'banks.*.iban' => 'nullable|string|max:34',
            'banks.*.branch_code' => 'nullable|string|max:20',
            'banks.*.type' => 'nullable|in:savings,current,salary',

            // Allowances validation - added missing section
            'allowances' => 'nullable|array',
            'allowances.*.allowance_id' => 'nullable|exists:allowances,id',
            'allowances.*.amount' => 'nullable|numeric|min:0',

            // Deductions validation - added missing section
            'deductions' => 'nullable|array',
            'deductions.*.deduction_id' => 'nullable|exists:deductions,id',
            'deductions.*.amount' => 'nullable|numeric|min:0',

            // Leave balances validation
            'leave_balances' => 'nullable|array',
            'leave_balances.*.leave_type_id' => 'required|exists:leave_types,id',
            'leave_balances.*.total_days' => 'nullable|integer|min:0',
            'leave_balances.*.used_days' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'first_name_en.required' => __('payroll::validation.first_name_en_required'),
            'first_name_ur.required' => __('payroll::validation.first_name_ur_required'),
            'cnic.unique' => __('payroll::validation.cnic_unique'),
            'cnic.required' => __('payroll::validation.cnic_required'),
            'dob.required' => __('payroll::validation.dob_required'),
            'gender.required' => __('payroll::validation.gender_required'),
            'contacts.*.email.email' => __('The contact email must be a valid email address.'),
            'banks.*.iban.max' => __('The IBAN must not be greater than 34 characters.'),
            'allowances.*.allowance_id.exists' => __('The selected allowance is invalid.'),
            'deductions.*.deduction_id.exists' => __('The selected deduction is invalid.'),
        ];
    }

    /**
     * Prepare the data for validation.
     * This helps handle empty arrays for dynamic sections
     */
    protected function prepareForValidation()
    {
        // Ensure contacts, banks, allowances, deductions, and leave balances are always arrays
        $this->merge([
            'contacts' => $this->contacts ?? [],
            'banks' => $this->banks ?? [],
            'allowances' => $this->allowances ?? [],
            'deductions' => $this->deductions ?? [],
            'leave_balances' => $this->leave_balances ?? [],
        ]);

        // Filter out completely empty sections
        $this->merge([
            'contacts' => array_filter($this->contacts, function($contact) {
                return !empty(array_filter($contact));
            }),
            'banks' => array_filter($this->banks, function($bank) {
                return !empty(array_filter($bank));
            }),
            'allowances' => array_filter($this->allowances, function($allowance) {
                return !empty(array_filter($allowance));
            }),
            'deductions' => array_filter($this->deductions, function($deduction) {
                return !empty(array_filter($deduction));
            }),
            'leave_balances' => array_filter($this->leave_balances, function($balance) {
                return isset($balance['leave_type_id']) || isset($balance['total_days']) || isset($balance['used_days']);
            }),
        ]);
    }
}
