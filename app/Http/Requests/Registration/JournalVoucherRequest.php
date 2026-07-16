<?php

namespace App\Http\Requests\Registration;

use Illuminate\Foundation\Http\FormRequest;

class JournalVoucherRequest extends FormRequest
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
            'voucher_date' => 'required|date',
            'description'  => 'nullable|string',
            'total_debit'  => 'required|numeric|min:0',
            'total_credit' => 'required|numeric|min:0',

            'entries' => 'required|array|min:1',

            'entries.*.credit_detail_account_id' => 'required|exists:detail_accounts,id',
            'entries.*.debit_detail_account_id' => 'required|exists:detail_accounts,id',
            'entries.*.detail_description' => 'nullable|string',
            'entries.*.debit' => 'required|numeric|min:0',
            'entries.*.credit' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [

            // Voucher level
            'voucher_date.required' => __('messages.voucher_date_required'),
            'voucher_date.date'     => __('messages.voucher_date_invalid'),

            'description.string' => __('messages.voucher_description_string'),

            // Entries array
            'entries.required' => __('messages.entries_required'),
            'entries.array'    => __('messages.entries_array'),
            'entries.min'      => __('messages.entries_min'),

            // Entry level (row wise)
            'entries.*.account_id.required' => __('messages.entry_account_required'),
            'entries.*.account_id.exists'   => __('messages.entry_account_invalid'),

            'entries.*.detail_description.string' => __('messages.entry_description_string'),

            'entries.*.debit.numeric' => __('messages.entry_debit_numeric'),
            'entries.*.debit.min'     => __('messages.entry_debit_min'),

            'entries.*.credit.numeric' => __('messages.entry_credit_numeric'),
            'entries.*.credit.min'     => __('messages.entry_credit_min'),
        ];
    }
}
