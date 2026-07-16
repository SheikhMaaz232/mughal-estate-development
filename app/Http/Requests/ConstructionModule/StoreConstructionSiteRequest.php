<?php

namespace App\Http\Requests\ConstructionModule;

use Illuminate\Foundation\Http\FormRequest;

class StoreConstructionSiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => ['required', 'exists:companies,id'],
            'project_id' => ['required', 'exists:projects,id'],
            'party_id' => ['nullable', 'exists:parties,id'],

            'name_en' => ['required', 'string', 'max:255'],
            'name_ur' => ['required', 'string', 'max:255'],

            'description_en' => ['nullable', 'string'],
            'description_ur' => ['nullable', 'string'],

            'address_en' => ['required', 'string'],
            'address_ur' => ['required', 'string'],

            'estimated_start_date' => ['nullable', 'date'],
            'estimated_end_date' => ['nullable', 'date', 'after_or_equal:estimated_start_date'],

            'status' => ['required', 'in:pending,ongoing,completed,on-hold'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.required' => __('messages.company_required'),
            'company_id.exists'   => __('messages.company_invalid'),

            'project_id.required' => __('messages.project_required'),
            'project_id.exists'   => __('messages.project_invalid'),

            'party_id.exists'     => __('messages.party_invalid'),

            'name_en.required'    => __('messages.name_en_required'),
            'name_en.string'      => __('messages.name_en_string'),
            'name_en.max'         => __('messages.name_en_max'),

            'name_ur.required'    => __('messages.name_ur_required'),
            'name_ur.string'      => __('messages.name_ur_string'),
            'name_ur.max'         => __('messages.name_ur_max'),

            'description_en.string' => __('messages.description_en_string'),
            'description_ur.string' => __('messages.description_ur_string'),

            'address_en.required' => __('messages.address_en_required'),
            'address_en.string'   => __('messages.address_en_string'),

            'address_ur.required' => __('messages.address_ur_required'),
            'address_ur.string'   => __('messages.address_ur_string'),

            'estimated_start_date.date' => __('messages.estimated_start_date_date'),
            'estimated_end_date.date'   => __('messages.estimated_end_date_date'),
            'estimated_end_date.after_or_equal' => __('messages.estimated_end_date_after'),

            'status.required' => __('messages.status_required'),
            'status.in'       => __('messages.status_invalid'),
        ];
    }
}
