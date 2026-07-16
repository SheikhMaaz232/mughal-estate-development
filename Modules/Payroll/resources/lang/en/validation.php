<?php
return [
    'name_en_required' => 'Urdu name is required',
    'name_ur_required' => 'Urdu name is required',
    'father_name_en_required' => 'Father name in english is required',
    'father_name_ur_required' => 'Father name in urdu is required',
    'email_required' => 'Email is required',
    'password_required' => 'Password is required',

     // Title field translations
    'title_en_required' => 'English title is required',
    'title_ur_required' => 'Urdu  title is required',
    'title_en_unique' => 'This english title has already been taken',
    'title_ur_unique' => 'This urdu title has already been taken',

    //Deduction
    'deduction_name_required' => 'Deduction name is required.',
    'deduction_name_unique' => 'This deduction name already exists.',
    'deduction_amount_required' => 'Deduction amount is required.',
    'deduction_amount_numeric' => 'Deduction amount must be a number.',
    'deduction_amount_min' => 'Deduction amount must be at least :min.',
    'deduction_type_required' => 'Deduction type is required.',

    //Allowance
    'allowance_name_required' => 'Allowance name is required.',
    'allowance_name_unique' => 'This allowance name already exists.',
    'allowance_amount_required' => 'Allowance amount is required.',
    'allowance_amount_numeric' => 'Allowance amount must be a number.',
    'allowance_amount_min' => 'Allowance amount must be at least :min.',
    'allowance_type_required' => 'Allowance type is required.',
    'required' => 'The :attribute field is required.',
    'string' => 'The :attribute must be a string.',
    'max' => [
        'string' => 'The :attribute may not be greater than :max characters.',
    ],
    'email' => 'The :attribute must be a valid email address.',
    'unique' => 'The :attribute has already been taken.',
    'confirmed' => 'The :attribute confirmation does not match.',

    //Employees
    'first_name_en_required' => 'First name is required',
    'first_name_ur_required' => 'First name is required',
    'cnic_required' => 'CNIC is required',
    'cnic_unique' => 'CNIC is already exists',
    'dob_required' => 'Date of Birth is required',
    'gender_required' => 'Gender is required',

    // Leave Requests
    'employee_required' => 'Employee is required.',
    'employee_invalid' => 'The selected employee is invalid.',
    'leave_type_required' => 'Leave type is required.',
    'leave_type_invalid' => 'The selected leave type is invalid.',
    'start_date_required' => 'Start date is required.',
    'start_date_invalid' => 'The start date is invalid.',
    'start_date_future' => 'The start date must be today or in the future.',
    'end_date_required' => 'End date is required.',
    'end_date_invalid' => 'The end date is invalid.',
    'end_date_after_start' => 'The end date must be after or equal to the start date.',
    'reason_max' => 'The reason may not be greater than 1000 characters.',
];
