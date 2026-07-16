<?php

namespace App\Http\Requests\Registration;

use Illuminate\Foundation\Http\FormRequest;

class StoreOccupationTypeRequest extends FormRequest
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
}
