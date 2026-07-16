<?php

namespace Modules\Payroll\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGradeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
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
            'title_en.required' => 'The English title is required.',
            'title_ur.required' => 'The Urdu title is required.',
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
