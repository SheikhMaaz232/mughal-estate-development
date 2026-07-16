<?php

namespace App\Http\Requests\PurchaseModule;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseReturnRequest extends FormRequest
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
            'grn_no'              => 'required|integer',
            'purchase_order_no'   => 'required|integer',
            'purchase_invoice_no'   => 'required|integer',
            'date'                => 'required|date',
            'project_id'          => 'required|exists:projects,id',
            'party_id'            => 'required|exists:parties,id',
            'detail_account_id'   => 'required|exists:detail_accounts,id',
            'supplier_bill_no'    => 'required|string|max:20',
            'status'         => 'required|string',
            'unloaded_by'         => 'required|string',
            'carriage'            => 'nullable|numeric',
            'gross_bill'          => 'required|numeric',
            'other_amount'          => 'nullable|numeric',
            'tax'                 => 'nullable|numeric',
            'net_amount'          => 'required|numeric',
            'total_quantity'      => 'required|numeric',
            'remarks'             => 'nullable|string',
            'product_id'             => 'required|array|min:1',
            'product_id.*'           => 'required|exists:items,id',
            'quantity'               => 'required|array',
            'quantity.*'             => 'required|numeric|min:0',
            'price'                  => 'required|array',
            'price.*'                => 'required|numeric|min:0',
            'amount'                 => 'required|array',
            'amount.*'               => 'required|numeric|min:0',
            'detail_remarks'         => 'nullable|array',
            'detail_remarks.*'       => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [

            'grn_no.required'              => __('messages.grn_no_required'),
            'purchase_order_no.required'   => __('messages.purchase_order_required'),
            'date.required'                => __('messages.date_required'),
            'project_id.required'          => __('messages.project_required'),
            'party_id.required'            => __('messages.party_required'),
            'detail_account_id.required'   => __('messages.detail_account_required'),
            'supplier_bill_no.required'    => __('messages.supplier_bill_no_required'),
            'unloaded_by.required'         => __('messages.unloaded_by_required'),
            'gross_bill.required'          => __('messages.gross_bill_required'),
            'net_amount.required'          => __('messages.net_amount_required'),
            'total_quantity.required'      => __('messages.total_quantity_required'),

            'product_id.required' => __('messages.product_required'),
            'quantity.required'  => __('messages.quantity_required'),
            'price.required'     => __('messages.price_required'),
            'amount.required'    => __('messages.amount_required'),
        ];
    }
}
