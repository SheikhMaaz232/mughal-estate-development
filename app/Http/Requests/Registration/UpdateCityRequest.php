<?php

namespace App\Http\Requests\Registration;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name_en' => 'required|string|max:255',
            'name_ur' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name_en.required' => 'The English name is required.',
            'name_ur.required' => 'The Urdu name is required.',
        ];
    }
}
