<?php

namespace App\Http\Requests\Registration;

use Illuminate\Foundation\Http\FormRequest;

class RegistryOrderRequest extends FormRequest
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
            'date'       => ['required', 'date'],
            'booking_id' => ['required', 'exists:booking_applications,id'],
            'party_id'   => ['required', 'exists:parties,id'],
            'fard_id'    => 'nullable',
            'relation'   => ['required', 'string', 'max:255'],
            'registry_fees' => ['required', 'numeric', 'min:0'],
            'registry_fees_receivable_account' => ['required', 'exists:detail_accounts,id'],
            'registry_status' => ['required', 'in:pending,completed,underprocess'],
        ];
    }

    public function messages(): array
    {
        return [
            'date.required'       => __('messages.date_required'),
            'date.date'           => __('messages.date_invalid'),

            'booking_id.required' => __('messages.booking_required'),
            'booking_id.exists'   => __('messages.booking_invalid'),

            'party_id.required'   => __('messages.party_required'),
            'party_id.exists'     => __('messages.party_invalid'),

            'fard_id.required'    => __('messages.fard_required'),
            'fard_id.string'      => __('messages.fard_string'),
            'fard_id.max'         => __('messages.fard_max'),

            'relation.required'   => __('messages.relation_required'),
            'relation.string'     => __('messages.relation_string'),
            'relation.max'        => __('messages.relation_max'),
        ];
    }
}
