<?php
// app/Http/Requests/UpdateLandRegistrationRequest.php

namespace App\Http\Requests\LandPurchase;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLandRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Change this based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'project_id' => 'required|exists:projects,id',
            'party_account_id' => 'required|exists:chart_of_accounts,id',
            'khawat_number' => 'nullable|string|max:100',
            'kanal' => 'required|numeric|min:0',
            'merla' => 'required|numeric|min:0',
            'square_feet' => 'required|numeric|min:0',
            'remarks' => 'nullable|string',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'project_id' => 'project',
            'party_account_id' => 'party account',
            'khawat_number' => 'khawat number',
            'kanal' => 'kanal',
            'merla' => 'merla',
            'square_feet' => 'square feet',
            'remarks' => 'remarks',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'project_id.required' => 'Please select a project.',
            'project_id.exists' => 'The selected project is invalid.',
            'party_account_id.required' => 'Please select a party account.',
            'party_account_id.exists' => 'The selected party account is invalid.',
            'kanal.required' => 'Kanal field is required.',
            'kanal.numeric' => 'Kanal must be a number.',
            'kanal.min' => 'Kanal must be at least 0.',
            'merla.required' => 'Merla field is required.',
            'merla.numeric' => 'Merla must be a number.',
            'merla.min' => 'Merla must be at least 0.',
            'square_feet.required' => 'Square feet field is required.',
            'square_feet.numeric' => 'Square feet must be a number.',
            'square_feet.min' => 'Square feet must be at least 0.',
        ];
    }
}
