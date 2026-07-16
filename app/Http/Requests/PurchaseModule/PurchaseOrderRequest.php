<?php

namespace App\Http\Requests\PurchaseModule;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseOrderRequest extends FormRequest
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
            'date' => ['required', 'date'],
            'project_id' => ['required', 'exists:projects,id'],
            'party_id' => ['required', 'exists:parties,id'],
            'detail_account_id' => ['required', 'exists:detail_accounts,id'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:Unverified,Verified'],
            'product_id' => ['required', 'array', 'min:1'],
            'product_id.*' => ['required', 'exists:items,id'],
            'quantity' => ['required', 'array'],
            'quantity.*' => ['required', 'numeric', 'min:1'],
            'price' => ['required', 'array'],
            'price.*' => ['required', 'numeric', 'min:0'],
            'amount' => ['required', 'array'],
            'amount.*' => ['required', 'numeric', 'min:0'],
            'detail_remarks' => ['nullable', 'array'],
            'detail_remarks.*' => ['nullable', 'string', 'max:500'],
            'gross_total' => ['nullable', 'numeric', 'min:0'],
            'tax_amount' => ['nullable', 'numeric', 'min:0'],
            'shipping_amount' => ['nullable', 'numeric', 'min:0'],
            'other_amount' => ['nullable', 'numeric', 'min:0'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],

        ];
    }

    public function messages(): array
    {
        return [
            'party_id.required' => __('messages.party_required'),
            'project_id.required'        => __('messages.project_required'),
            'detail_account_id.required' => __('messages.detail_account_required'),
            'contact_person' => __('messages.contact_person'),
            'status' => __('messages.status'),
            'product_id.required' => __('messages.product_required'),
            'product_id.*.required' => __('messages.product_required'),
            'quantity.*.required' => __('messages.quantity_is_required'),
            'price.*.required' => __('messages.price_is_required'),
        ];
    }
}
