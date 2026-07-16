<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContractorBillRequest extends FormRequest
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
            'tender_id' => 'required|integer|exists:tenders,id',
            'work_order_id' => 'required|integer|exists:work_orders,id',
            'contractor_account_id' => 'required|integer|exists:detail_accounts,id',
            'bill_date' => 'required|date',
            'remarks' => 'nullable|string|max:1000',

            // Items validation
            'items' => 'required|array|min:1',
            'items.*.boq_item_id' => 'required|integer|exists:boq_details,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.rate' => 'required|numeric|min:0',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'tender_id.required' => 'Tender is required',
            'work_order_id.required' => 'Work Order is required',
            'contractor_account_id.required' => 'Contractor Account is required',
            'bill_date.required' => 'Bill Date is required',
            'items.required' => 'At least one item is required',
            'items.*.quantity.min' => 'Quantity must be greater than 0',
            'items.*.rate.min' => 'Rate must be 0 or greater',
        ];
    }
}
