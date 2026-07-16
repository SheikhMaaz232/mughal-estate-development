<?php

namespace Modules\Payroll\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveRequestRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'employee_id.required' => __('payroll::validation.employee_required'),
            'employee_id.exists' => __('payroll::validation.employee_invalid'),
            'leave_type_id.required' => __('payroll::validation.leave_type_required'),
            'leave_type_id.exists' => __('payroll::validation.leave_type_invalid'),
            'start_date.required' => __('payroll::validation.start_date_required'),
            'start_date.date' => __('payroll::validation.start_date_invalid'),
            'start_date.after_or_equal' => __('payroll::validation.start_date_future'),
            'end_date.required' => __('payroll::validation.end_date_required'),
            'end_date.date' => __('payroll::validation.end_date_invalid'),
            'end_date.after_or_equal' => __('payroll::validation.end_date_after_start'),
            'reason.max' => __('payroll::validation.reason_max'),
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
