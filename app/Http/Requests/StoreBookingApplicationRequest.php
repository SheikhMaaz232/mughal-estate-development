<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        return [
            'form_no'           => 'required|string|max:255',
            'product_id'        => 'required|exists:products,id',
            'project_id'        => 'required|exists:projects,id',
            'party_id'          => 'required|exists:parties,id',
            'detail_account_id' => 'required|exists:detail_accounts,id',
            'transfer_charges_account_id' => 'nullable|exists:detail_accounts,id',
            'dealer_id'         => 'nullable',
            'receivable_dealer_id'         => 'nullable|exists:detail_accounts,id',
            'date'              => 'required|date',
            'previous_booking_id' => 'nullable',
            'care_off'          => 'nullable|string|max:255',
            'status'          => 'required|string|max:255',
            'case'          => 'required|string|max:255',

            'operating_start_date' => ['required', 'date'],
            'operating_charges'    => ['required', 'numeric', 'min:0'],
            'operating_receivable_account' => ['nullable', 'exists:detail_accounts,id'],
            'condition'            => ['required', 'in:allow,not_allow'],

            'expense_account_id' => ['nullable', 'exists:detail_accounts,id'],
            'discount_amount'   => ['nullable', 'numeric', 'min:0'],

            'add_value'         => 'nullable|numeric|min:0',
            'discount'          => 'nullable|numeric|min:0',
            'commission'        => 'nullable|numeric|min:0',
            'total_amount'      => 'required|numeric|min:0',
            'grand_total_amount' => 'required|numeric|min:0',

            'relation_id'       => 'nullable|array',
            'relation_id.*'     => 'nullable|exists:relations,id',
            'nominee_party_id'          => 'nullable|exists:parties,id',
            'schedule_type_id'        => 'nullable|array',
            'schedule_type_id.*'      => 'nullable|exists:schedule_types,id',
            'schedule_period_id'      => 'nullable|array',
            'schedule_period_id.*'    => 'nullable|exists:schedule_periods,id',
            'due_date'                => 'nullable|array',
            'due_date.*'              => 'nullable|date',
            'number'                  => 'nullable|array',
            'number.*'                => 'nullable|integer|min:1',
            'pay_amount'              => 'nullable|array',
            'pay_amount.*'            => 'nullable|numeric|min:0',
            'calculated_total_amount'   => 'nullable|array',
            'calculated_total_amount.*' => 'nullable|numeric|min:0',

            'possession_fees'      => 'required|numeric|min:0',
            'possession_receivable_account' => 'nullable|exists:detail_accounts,id',
            'proceeding_fees'      => 'required|numeric|min:0',
            'proceeding_receivable_account' => 'nullable|exists:detail_accounts,id',
            'development_charges'  => 'required|numeric|min:0',
            'development_receivable_id' => 'nullable|exists:detail_accounts,id',
            'gst'                  => 'required|numeric|min:0',
            'gst_receivable_account_id' => 'nullable|exists:detail_accounts,id',
            // 'sevenE_chalan'        => 'required|numeric|min:0',
            // 'sevenE_chalan_receivable_account' => 'nullable|exists:detail_accounts,id',
        ];
    }

    public function messages(): array
    {
        return [
            'form_no.required'           => __('messages.form_no_required'),
            'product_id.required'        => __('messages.product_required'),
            'project_id.required'        => __('messages.project_required'),
            'party_id.required'          => __('messages.party_required'),
            'detail_account_id.required' => __('messages.detail_account_required'),
            'dealer_id.required'         => __('messages.dealer_required'),
            'date.required'              => __('messages.date_required'),
            'total_amount.required'      => __('messages.total_amount_required'),
            'total_amount.numeric'       => __('messages.total_amount_numeric'),
            'relation_id.*.exists'       => __('messages.nominee_relation_invalid'),
            'schedule_type_id.*.exists'   => __('messages.schedule_type_invalid'),
            'schedule_period_id.*.exists' => __('messages.schedule_period_invalid'),
            'due_date.*.date'             => __('messages.schedule_due_date_invalid'),
            'number.*.integer'            => __('messages.schedule_number_invalid'),
            'pay_amount.*.numeric'        => __('messages.schedule_amount_invalid'),
            'calculated_total_amount.*.numeric' => __('messages.schedule_total_invalid'),
            'product_id.required' => __('messages.product_required.required'),
            'product_id.exists'   => __('validation.product_already_exists.exists'),
            'product_id.unique'   => __('validation.product_already_exists.unique'),
            'operating_start_date.required' => __('messages.operating_start_date_required'),
            'operating_start_date.date'     => __('messages.operating_start_date_date'),
            'operating_charges.required'    => __('messages.operating_charges_required'),
            'operating_charges.numeric'     => __('messages.operating_charges_numeric'),
            'operating_charges.min'         => __('messages.operating_charges_min'),
            'operating_receivable_account.exists' => __('messages.operating_charges_receivable_account_invalid'),
            'condition.required'            => __('messages.condition_required'),
            'condition.in'                  => __('messages.condition_invalid'),
            'possession_fees.required'      => __('messages.possession_fees_required'),
            'possession_receivable_account.exists' => __('messages.possession_fees_receivable_account_invalid'),
            'proceeding_fees.required'      => __('messages.proceeding_fees_required'),
            'proceeding_receivable_account.exists' => __('messages.proceeding_fees_receivable_account_invalid'),
            'development_charges.required'  => __('messages.development_charges_required'),
            'development_receivable_id.exists' => __('messages.development_charges_receivable_account_invalid'),
            'gst.required'                  => __('messages.gst_required'),
            'gst_receivable_account_id.exists' => __('messages.gst_receivable_account_invalid'),
            'sevenE_chalan.required'        => __('messages.sevenE_chalan_required'),
            'sevenE_chalan_receivable_account.exists' => __('messages.sevenE_chalan_receivable_account_invalid'),
            'transfer_charges_account_id.exists' => __('messages.transfer_charges_account_invalid'),
            'receivable_dealer_id.exists' => __('messages.receivable_dealer_invalid'),
            'numeric'                       => __('messages.numeric'),
            'min'                           => __('messages.min'),
        ];
    }
}
