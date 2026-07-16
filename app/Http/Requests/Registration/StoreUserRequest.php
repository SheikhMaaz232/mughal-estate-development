<?php

namespace App\Http\Requests\Registration;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Ensure you're already using auth middleware
    }

    public function rules(): array
    {
        return [
            'name_en' => ['required', 'string', 'max:255'],
            'name_ur' => ['required', 'string', 'max:255'],
            'father_name_en' => ['required', 'string', 'max:255'],
            'father_name_ur' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'avatar' =>  'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'name_en.required' => __('validation.name_en_required'),
            'name_ur.required' => __('validation.name_ur_required'),
            'father_name_en.required' => __('validation.father_name_en_required'),
            'father_name_ur.required' => __('validation.father_name_ur_required'),
            'email.required' => __('validation.email_required'),
            'password.required' => __('validation.password_required'),
            'avatar.max' => 'The image size must not exceed 2 MB.',
        ];
    }
}
