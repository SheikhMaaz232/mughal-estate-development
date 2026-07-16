<?php

namespace App\Http\Requests\Registration;

use Illuminate\Foundation\Http\FormRequest;

class BankReceiptVoucherRequest extends FormRequest
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
            'date'              => 'required|date',
            'project_id'        => 'required|exists:projects,id',
            'detail_account_id' => 'required|exists:detail_accounts,id',
            'bank_id'           => 'required|exists:detail_accounts,id',
            'description_en'       => 'nullable|string|max:500',
            'description_ur'       => 'nullable|string|max:500',
            'total_amount'      => 'required|numeric|min:0',
            'transaction_type'       => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'date.required'              => __('messages.date_required'),
            'date.date'                  => __('messages.date_invalid'),
            'project_id.required'        => __('messages.project_required'),
            'detail_account_id.required' => __('messages.detail_account_required'),
            'bank_id.required'           => __('messages.bank_required'),
            'description_en.string'         => __('messages.description_string'),
            'description_en.max'            => __('messages.description_max'),
            'description_ur.string'         => __('messages.description_string'),
            'description_ur.max'            => __('messages.description_max'),
            'total_amount.required'      => __('messages.total_amount_required'),
            'total_amount.numeric'       => __('messages.total_amount_numeric'),
            'total_amount.min'           => __('messages.total_amount_min'),
            'attachment.image'           => __('messages.attachment_image'),
            'attachment.mimes'           => __('messages.attachment_mimes'),
            'attachment.max'             => __('messages.attachment_max'),
        ];
    }
}
