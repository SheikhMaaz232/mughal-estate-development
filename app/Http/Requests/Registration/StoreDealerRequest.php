<?php

namespace App\Http\Requests\Registration;

use Illuminate\Foundation\Http\FormRequest;

class StoreDealerRequest extends FormRequest
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
            'name_en' => 'required|string|max:255',
            'name_ur' => 'required|string|max:255',
            'address_en' => 'required|string|max:255',
            'address_ur' => 'required|string|max:255',
            'mobile_number' => 'string|max:255',
            'phone_number' => 'string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB = 2048 KB
        ];
    }

    public function messages()
    {
        return [
            'name_en.required' => 'Please enter name in English',
            'name_ur.required' => 'Please enter name in Urdu',
            'address_en.required' => 'Please enter address in English',
            'address_ur.required' => 'Please enter address in Urdu',
            'photo.image' => 'The uploaded file must be an image.',
            'photo.mimes' => 'Only JPEG, PNG, JPG, and GIF images are allowed.',
            'photo.max' => 'The image size must not exceed 2 MB.',
        ];
    }
}
