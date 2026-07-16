<?php

namespace Modules\Payroll\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHolidayTypesRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'title_en' => 'required|unique:holiday_types,title_en|string|max:255',
            'title_ur' => 'required|string|max:255|unique:holiday_types,title_ur',
        ];
    }

    public function messages()
    {
        return [
            'title_en.required' => __('payroll::validation.title_en_required'),
            'title_en.unique' => __('payroll::validation.title_en_unique'),
            'title_ur.required' => __('payroll::validation.title_ur_required'),
            'title_ur.unique' => __('payroll::validation.title_ur_unique'),
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
