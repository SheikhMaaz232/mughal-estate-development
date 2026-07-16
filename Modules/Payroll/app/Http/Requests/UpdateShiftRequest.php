<?php

namespace Modules\Payroll\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShiftRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
     public function rules()
    {
        return [
            'shift_name_en' => 'required|string|max:200',
            'shift_name_ur' => 'required|string|max:200',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'description' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'shift_name_en.required' => 'The shift name in urdu is required.',
            'shift_name_en.max' => 'The shift name in urdu  may not be greater than 200 characters.',
            'shift_name_ur.required' => 'The shift in urdu name is required.',
            'shift_name_ur.max' => 'The shift name may not be greater than 200 characters.',
            'start_time.required' => 'The start time is required.',
            'start_time.date_format' => 'The start time must be in HH:MM format.',
            'end_time.required' => 'The end time is required.',
            'end_time.date_format' => 'The end time must be in HH:MM format.',
            'end_time.after' => 'The end time must be after the start time.',
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
