<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientInvoiceRequest extends FormRequest
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
            'tender_id' => 'required|exists:tenders,id',
            'client_id' => 'required|exists:parties,id',
            'invoice_no' => 'required|string|unique:client_invoices,invoice_no|max:50',
            'invoice_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'remarks_en' => 'nullable|string|max:500',
            'remarks_ur' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'tender_id.required' => __('messages.tender-required'),
            'tender_id.exists' => __('messages.tender-invalid'),
            'client_id.required' => __('messages.client-required'),
            'client_id.exists' => __('messages.client-invalid'),
            'invoice_no.required' => __('messages.invoice-no-required'),
            'invoice_no.unique' => __('messages.invoice-no-must-be-unique'),
            'invoice_date.required' => __('messages.invoice-date-required'),
            'invoice_date.date' => __('messages.invoice-date-must-be-valid-date'),
            'amount.required' => __('messages.amount-required'),
            'amount.numeric' => __('messages.amount-must-be-numeric'),
        ];
    }
}
