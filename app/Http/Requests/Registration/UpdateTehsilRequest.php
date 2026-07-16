<?php

namespace App\Http\Requests\Registration;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTehsilRequest extends FormRequest
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
    public function rules()
    {
        return [
            'city_id' => 'required|exists:cities,id',
            'name_en' => 'required|string|max:255',
            'name_ur' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'city_id.required' => 'The city is required.',
            'city_id.exists' => 'The selected city is invalid.',
            'name_en.required' => 'The English title is required.',
            'name_ur.required' => 'The Urdu title is required.',
        ];
    }
}
