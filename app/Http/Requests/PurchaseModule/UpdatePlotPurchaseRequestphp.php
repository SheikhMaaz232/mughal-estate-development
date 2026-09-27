<?php

namespace App\Http\Requests\PurchaseModule;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePlotPurchaseRequestphp extends FormRequest
{
    /** * Determine if the user is authorized to make this request. */ public function authorize(): bool
    {
        return true;
    }
    /** * Get the validation rules that apply to the request. */ public function rules(): array
    {
        return [
            'date' => ['required', 'date',], 
            'project_id' => ['required', 'integer', 'exists:projects,id',], 
            'party_id' => ['required', 'integer', 'exists:parties,id',], 
            'detail_account_id' => ['required', 'integer', 'exists:detail_accounts,id',], 
            'status' => ['required', 'string', 'max:250',], 
            'gross_bill' => ['required', 'numeric', 'min:0',], 
            'total_quantity' => ['required', 'numeric', 'min:0',], 
            'remarks_en' => ['nullable', 'string',], 
            'remarks_ur' => ['nullable', 'string',], 
            'details' => ['required', 'array', 'min:1',], 
            'details.*.id' => ['nullable', 'integer', 'exists:plot_purchase_details,id',], 
            'details.*.product_id' => ['required', 'integer', 'exists:products,id',], 
            'details.*.size' => ['required', 'numeric', 'min:0',], 
            'details.*.per_marla_rate' => ['required', 'numeric', 'min:0',], 
            'details.*.amount' => ['required', 'numeric', 'min:0',], 
            'details.*.detail_remarks_en' => ['nullable', 'string',], 
            'details.*.detail_remarks_ur' => ['nullable', 'string',],
            ];
    }
    /** * Get the custom validation messages. */ public function messages(): array
    {
        return [ 
            'date.required' => __('messages..date_required'), 
            'date.date' => __('messages..date_date'), 
            'project_id.required' => __('messages..project_id_required'), 
            'project_id.integer' => __('messages..project_id_integer'), 
            'project_id.exists' => __('messages..project_id_exists'), 
            'party_id.required' => __('messages..party_id_required'), 
            'party_id.integer' => __('messages..party_id_integer'), 
            'party_id.exists' => __('messages..party_id_exists'), 
            'detail_account_id.required' => __('messages..detail_account_id_required'), 
            'detail_account_id.integer' => __('messages..detail_account_id_integer'), 
            'detail_account_id.exists' => __('messages..detail_account_id_exists'), 
            'status.required' => __('messages..status_required'), 
            'status.string' => __('messages..status_string'), 
            'status.max' => __('messages..status_max'), 
            'gross_bill.required' => __('messages..gross_bill_required'), 
            'gross_bill.numeric' => __('messages..gross_bill_numeric'), 
            'gross_bill.min' => __('messages..gross_bill_min'), 
            'total_quantity.required' => __('messages..total_quantity_required'), 
            'total_quantity.numeric' => __('messages..total_quantity_numeric'), 
            'total_quantity.min' => __('messages..total_quantity_min'), 
            'remarks_en.string' => __('messages..remarks_en_string'), 
            'remarks_ur.string' => __('messages..remarks_ur_string'), 
            'details.required' => __('messages..details_required'), 
            'details.array' => __('messages..details_array'), 
            'details.min' => __('messages..details_min'), 
            'details.*.id.required' => __('messages..details_id_required'), 
            'details.*.id.integer' => __('messages..details_id_integer'), 
            'details.*.id.exists' => __('messages..details_id_exists'), 
            'details.*.product_id.required' => __('messages..details_product_id_required'), 
            'details.*.product_id.integer' => __('messages..details_product_id_integer'), 
            'details.*.product_id.exists' => __('messages..details_product_id_exists'), 
            'details.*.size.required' => __('messages..details_size_required'), 
            'details.*.size.numeric' => __('messages..details_size_numeric'), 
            'details.*.size.min' => __('messages..details_size_min'), 
            'details.*.per_marla_rate.required' => __('messages..details_per_marla_rate_required'), 
            'details.*.per_marla_rate.numeric' => __('messages..details_per_marla_rate_numeric'), 
            'details.*.per_marla_rate.min' => __('messages..details_per_marla_rate_min'), 
            'details.*.amount.required' => __('messages..details_amount_required'), 
            'details.*.amount.numeric' => __('messages..details_amount_numeric'), 
            'details.*.amount.min' => __('messages..details_amount_min'), 
            'details.*.detail_remarks_en.string' => __('messages..details_detail_remarks_en_string'), 
            'details.*.detail_remarks_ur.string' => __('messages..details_detail_remarks_ur_string'),
            ];
    }
}
