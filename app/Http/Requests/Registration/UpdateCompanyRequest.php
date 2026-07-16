<?php

namespace App\Http\Requests\Registration;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'group_id' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'name_ur' => 'required|string|max:255',
            'address_en' => 'required|string',
            'address_ur' => 'required|string',
            'description_en' => 'required|string',
            'description_ur' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'group_id.required' => 'The group id is required',
            'name_en.required' => 'The company english name is required',
            'name_ur.required' => 'The company urdu name is required',
            'address_en.required' => 'The address in english is required',
            'address_ur.required' => 'The address in urdu is required',
            'description_en.required' => 'The description in english is required',
            'description_ur.required' => 'The description in urdu is required',
            'logo.image' => 'The logo must be an image file',
            'logo.max' => 'The logo size should not exceed 2MB',
        ];
    }
}