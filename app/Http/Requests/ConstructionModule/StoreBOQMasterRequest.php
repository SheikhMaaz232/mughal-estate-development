<?php

namespace App\Http\Requests\ConstructionModule;

use Illuminate\Foundation\Http\FormRequest;

class StoreBOQMasterRequest extends FormRequest
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
            'tender_id' => 'required|exists:tenders,id|unique:boq_masters,tender_id',
            'title_en' => 'required|string|max:255',
            'title_ur' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0',

            // BOQ Details validation
            'item_id' => 'required|array|min:1',
            'item_id.*' => 'required|exists:items,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric|min:0.0001',
            'rate' => 'required|array',
            'rate.*' => 'required|numeric|min:0',
            'gross_amount' => 'required|array',
            'gross_amount.*' => 'required|numeric|min:0',
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
            'tender_id.unique' => __('messages.boq-exists-for-tender'),
            'title_en.required' => __('messages.title-required'),
            'title_ur.required' => __('messages.title_ur-required'),
            'total_amount.required' => __('messages.total-amount-required'),
            'total_amount.numeric' => __('messages.total-amount-numeric'),
            'total_amount.min' => __('messages.total-amount-min'),

            // BOQ Details messages
            'item_id.required' => __('messages.items-required'),
            'item_id.array' => __('messages.items-must-be-array'),
            'item_id.min' => __('messages.atleast-one-item-required'),
            'item_id.*.required' => __('messages.item-required'),
            'item_id.*.exists' => __('messages.item-invalid'),
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
            'gross_amount.required' => __('messages.gross-amount-required'),
            'gross_amount.array' => __('messages.gross-amount-must-be-array'),
            'gross_amount.*.required' => __('messages.gross-amount-required'),
            'gross_amount.*.numeric' => __('messages.gross-amount-numeric'),
            'gross_amount.*.min' => __('messages.gross-amount-min'),
        ];
    }
}
