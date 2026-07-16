<?php

namespace App\Http\Requests\Registration;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMainHeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name_en' => ['required', 'string', 'max:255'],
            'name_ur' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name_en.required' => 'The English name is required.',
            'name_ur.required' => 'The Urdu name is required.',
        ];
    }
}
