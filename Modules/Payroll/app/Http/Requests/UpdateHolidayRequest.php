<?php

namespace Modules\Payroll\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHolidayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_paid' => $this->has('is_paid'),
        ]);
    }

    public function rules(): array
    {
        return [
            'name_en' => 'required|string|max:255',
            'name_ur' => 'required|string|max:255',
            'date' => 'required|date',
            'holiday_type_id' => 'required|exists:holiday_types,id',
            'is_paid' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name_en.required' => __('payroll::validation.name_en_required'),
            'name_ur.required' => __('payroll::validation.name_ur_required'),
            'date.required' => __('payroll::messages.date-required'),
            'date.date' => __('payroll::messages.date-invalid'),
            'holiday_type_id.required' => __('payroll::messages.holiday-type-required'),
            'holiday_type_id.exists' => __('payroll::messages.holiday-type-invalid'),
        ];
    }
}
