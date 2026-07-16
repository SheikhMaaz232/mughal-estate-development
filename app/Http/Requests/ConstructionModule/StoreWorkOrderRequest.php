<?php

namespace App\Http\Requests\ConstructionModule;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkOrderRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'construction_site_id' => 'required|exists:construction_sites,id',
            'tender_id' => 'required|exists:tenders,id',
            'boq_id' => 'required|exists:boq_masters,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description_en' => 'nullable|string|max:1000',
            'description_ur' => 'nullable|string|max:1000',

            // Work Order Items validation
            'boq_item_id' => 'required|array|min:1',
            'boq_item_id.*' => 'required|exists:boq_details,item_id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric|min:0.0001',
            'rate' => 'required|array',
            'rate.*' => 'required|numeric|min:0',
        ];
    }

    /**
     * Get custom messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'construction_site_id.required' => __('messages.construction-site-required'),
            'construction_site_id.exists' => __('messages.construction-site-invalid'),
            'tender_id.required' => __('messages.tender-required'),
            'tender_id.exists' => __('messages.tender-invalid'),
            'boq_id.required' => __('messages.boq-required'),
            'boq_id.exists' => __('messages.boq-invalid'),
            'start_date.required' => __('messages.start-date-required'),
            'start_date.date' => __('messages.start-date-invalid'),
            'end_date.required' => __('messages.end-date-required'),
            'end_date.date' => __('messages.end-date-invalid'),
            'end_date.after_or_equal' => __('messages.end-date-after-start-date'),

            'boq_item_id.required' => __('messages.items-required'),
            'boq_item_id.array' => __('messages.items-must-be-array'),
            'boq_item_id.min' => __('messages.atleast-one-item-required'),
            'boq_item_id.*.required' => __('messages.item-required'),
            'boq_item_id.*.exists' => __('messages.item-invalid'),
            'quantity.required' => __('messages.quantity-required'),
            'quantity.array' => __('messages.quantity-must-be-array'),
            'quantity.*.required' => __('messages.quantity-required'),
            'quantity.*.numeric' => __('messages.quantity-numeric'),
            'quantity.*.min' => __('messages.quantity-min'),
            'rate.required' => __('messages.rate-required'),
            'rate.array' => __('messages.rate-must-be-array'),
            'rate.*.required' => __('messages.rate-required'),
            'rate.*.numeric' => __('messages.rate-numeric'),
            'rate.*.min' => __('messages.rate-min'),
        ];
    }
}
