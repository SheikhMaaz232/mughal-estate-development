<?php

namespace Modules\Payroll\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQualificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'title_en' => 'required|string|max:255',
            'title_ur' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'title_en.required' => 'The english title is required.',
            'title_ur.required' => 'The urdu title is required.',
        ];
    }
}
