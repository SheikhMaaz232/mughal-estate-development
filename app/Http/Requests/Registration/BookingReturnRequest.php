<?php

namespace App\Http\Requests\Registration;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class BookingReturnRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'booking_id' => [
                'required',
                'integer',
                'exists:booking_applications,id',
            ],

            'detail_account_id' => [
                'required',
                'integer',
                'exists:detail_accounts,id',
            ],

            'receivable_detail_account_id' => [
                'required',
                'integer',
                'exists:detail_accounts,id',
            ],

            'cancellation_charges_account_id' => [
                'required',
                'integer',
                'exists:detail_accounts,id',
            ],

            'cash_bank_account' => [
                'required',
                'integer',
                'exists:detail_accounts,id',
            ],

            'project_id' => 'nullable',

            'percentage_value' => [
                'required',
                'numeric',
                'max:255',
            ],

            'status' => [
                'required',
                'string',
                'max:255',
            ],

            'date' => [
                'required',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:500',
            ],
        ];
    }


    public function messages(): array
    {
        return [
            'booking_id.required' => __('messages.booking_id_required'),
            'booking_id.integer'  => __('messages.booking_id_integer'),
            'booking_id.exists'   => __('messages.booking_id_exists'),
            'booking_id.unique'   => __('messages.booking_id_unique'),
            'project_id.required'        => __('messages.project_required'),

            'detail_account_id.integer' => __('messages.detail_account_id_integer'),
            'detail_account_id.exists'  => __('messages.detail_account_id_exists'),

            'cash_bank_account.required' => __('messages.cash_bank_account_required'),
            'cash_bank_account.integer'  => __('messages.cash_bank_account_integer'),
            'cash_bank_account.exists'   => __('messages.cash_bank_account_exists'),

            'status.required' => __('messages.status_required'),
            'status.string'   => __('messages.status_string'),
            'status.max'      => __('messages.status_max'),

            'date.required' => __('messages.date_required'),
            'date.date'     => __('messages.date_date'),

            'percentage_value.required' => __('messages.percentage_required'),
            'percentage_value.numeric'  => __('messages.percentage_numeric'),

            'receivable_detail_account_id.required' => __('messages.receivable_detail_account_id.required'),
            'cancellation_charges_account_id.required' => __('messages.cancellation_charges_account_id.required'),
        ];
    }
}
