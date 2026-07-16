<?php

namespace App\Http\Requests\Registration;

use Illuminate\Foundation\Http\FormRequest;

class PossessionLetterRequest extends FormRequest
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
            'file_no' => 'required|string|max:255',
            'date' => 'required|date',
            'project_id' => 'required|exists:projects,id',
            'product_id' => 'required|exists:products,id',
            'party_id' => 'required|exists:parties,id',
            'kanal' => 'required|numeric|min:0',
            'marla' => 'required|numeric|min:0',
            'square_feet' => 'required|numeric|min:0',
            'total_marla' => 'required|numeric|min:0',
            'total_square_feet' => 'required|numeric|min:0',
            'east_side' => 'nullable|string',
            'west_side' => 'nullable|string',
            'south_side' => 'nullable|string',
            'north_side' => 'nullable|string',
            'east_bounded_by' => 'nullable|string',
            'west_bounded_by' => 'nullable|string',
            'south_bounded_by' => 'nullable|string',
            'north_bounded_by' => 'nullable|string',
            'status' => 'required|string',
            'special_note' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'file_no.required' => __('messages.file_no_required'),
            'project_id.required' => __('messages.project_required'),
            'party_id.required' => __('messages.party_required'),
        ];
    }
}
