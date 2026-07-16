<?php

namespace App\Http\Requests\PurchaseModule;

use Illuminate\Foundation\Http\FormRequest;

class GRNRequest extends FormRequest
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
            'purchase_order_no' => 'required|string|max:255',
            'date' => 'required|date',
            'project_id' => 'required|exists:projects,id',
            'party_id' => 'required|exists:parties,id',
            'detail_account_id' => 'required|exists:detail_accounts,id',
            'fare' => 'required|numeric|min:0',
            'unloaded_by' => 'required|string|max:255',
            'status' => 'required|string|max:250',
            'driver_name' => 'required|string|max:250',
            'supplier_bill_no' => 'nullable|string|max:250',
            'remarks' => 'nullable|string|max:250',
            'total_po_quantity' => 'required|numeric|min:0',
            'total_received_quantity' => 'required|numeric|min:0',
            'product_id' => ['required', 'array', 'min:1'],
            'product_id.*' => ['required', 'exists:items,id'],
            'po_quantity' => ['required', 'array'],
            'po_quantity.*' => ['required', 'numeric', 'min:1'],
            'received_qty' => ['required', 'array'],
            'received_qty.*' => ['required', 'numeric', 'min:0'],
            'balance' => ['required', 'array'],
            'balance.*' => ['required', 'numeric', 'min:0'],
            'detail_remarks' => ['nullable', 'array'],
            'detail_remarks.*' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'party_id.required'          => __('messages.party_required'),
            'project_id.required'        => __('messages.project_required'),
            'detail_account_id.required' => __('messages.detail_account_required'),
            'fare.required'              => __('messages.fare_required'),
            'unloaded_by.required'       => __('messages.unloaded_by_required'),
            'status.required'            => __('messages.status_required'),
            'driver_name.required'       => __('messages.driver_name_required'),
            'total_po_quantity.required'    => __('messages.total_quantity_required'),
            'total_received_quantity.required'    => __('messages.total_quantity_required'),
            'product_id.required' => __('messages.product_required'),
            'product_id.*.required' => __('messages.product_required'),
            'po_quantity.*.required' => __('messages.quantity_is_required'),
            'po_quantity.*.required' => __('messages.quantity_is_required'),
            'received_qty.*.required' => __('messages.quantity_is_required'),
            'received_qty.*.required' => __('messages.quantity_is_required'),
            'balance.required'     => __('messages.balance_required'),
        ];
    }
}
