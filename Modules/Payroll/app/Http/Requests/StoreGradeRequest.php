<?php

namespace Modules\Payroll\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGradeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'title_en' => 'required|string|max:255|unique:grades,title_en',
            'title_ur' => 'required|string|max:255|unique:grades,title_ur',
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
