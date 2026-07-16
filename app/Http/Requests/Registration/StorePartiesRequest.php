<?php

namespace App\Http\Requests\Registration;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StorePartiesRequest extends FormRequest
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
        $id = $this->route('party') ?? $this->route('id');

        return [
            'name_en' => 'required|string|max:255',
            'name_ur' => 'required|string|max:255',
            'father_name_en' => 'required|string|max:255',
            'father_name_ur' => 'required|string|max:255',
            'cnic_no' => [
                'required',
                'string',
                'max:255',
                Rule::unique('parties', 'cnic_no')->ignore($id)->whereNull('deleted_at'),
            ],
            'ntn_no' => 'nullable|string|max:50',
            'gst_no' => 'nullable|string|max:50',
            'cast_id' => 'required|exists:casts,id',
            'residential_status' => 'required|exists:residentials,id',
            'occupation_id' => 'required|exists:occupation_types,id',
            'business_name_en' => 'nullable|string|max:255',
            'business_name_ur' => 'nullable|string|max:255',
            'business_address_en' => 'nullable|string|max:255',
            'business_address_ur' => 'nullable|string|max:255',
            'home_address_en' => 'required|string|max:255',
            'home_address_ur' => 'required|string|max:255',
            'remarks' => 'nullable|string|max:255',
            'contact_number_1' => 'required|string|max:15',
            'contact_number_2' => 'nullable|string|max:15',
            'whatsApp_no' => 'required|string|max:15'
        ];
    }

    public function messages(): array
    {
        return [
            'name_en.required' => __('messages.english-name-required'),
            'name_en.unique' => __('messages.already-exists'),
            'name_ur.required' => __('messages.urdu-name-required'),
            'name_ur.unique' => __('messages.already-exists'),

            'father_name_en.required' => __('messages.english-father-name-required'),
            'father_name_ur.required' => __('messages.urdu-father-name-required'),

            'cnic_no.required' => __('messages.cnic-required'),
            'cnic_no.unique' => __('messages.cnic-already-exists'),

            'cast_id.required' => __('messages.select-caste'),
            'residential_status.required' => __('messages.select-residential-status'),
            'occupation_id.required' => __('messages.select-occupation'),

            'home_address_en.required' => __('messages.english-home-address-required'),
            'home_address_ur.required' => __('messages.urdu-home-address-required'),

            'contact_number_1.required' => __('messages.contact-number-1-required'),
            'whatsApp_no.required' => __('messages.whatsapp-number-required'),
            'cnic_front_image.image' => __('messages.invalid-image'),
            'cnic_back_image.image' => __('messages.invalid-image'),
            'profile_image.image' => __('messages.invalid-image'),

        ];
    }
}
