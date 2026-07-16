<?php

namespace App\Http\Requests\ConstructionModule;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTenderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'construction_site_id' => ['required', 'exists:construction_sites,id'],
            'contractee_account_id' => ['required', 'exists:detail_accounts,id'],
            'contractor_account_id' => ['required', 'exists:detail_accounts,id'],
            'revenue_account_id' => ['required', 'exists:detail_accounts,id'],
            'expense_account_id' => ['required', 'exists:detail_accounts,id'],

            'title_en' => ['required', 'string', 'max:255'],
            'title_ur' => ['required', 'string', 'max:255'],

            'description_en' => ['nullable', 'string'],
            'description_ur' => ['nullable', 'string'],

            'work_type' => ['nullable', 'string', 'max:255'],
            'estimated_cost' => ['nullable', 'numeric', 'min:0'],

            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],

            'status' => ['required', 'in:draft,approved,in_progress,completed'],
        ];
    }

    public function messages(): array
    {
        return [
            'construction_site_id.required' => __('messages.construction_site_required'),
            'construction_site_id.exists' => __('messages.construction_site_invalid'),

            'contractee_account_id.required' => __('messages.contractee_account_required'),
            'contractee_account_id.exists' => __('messages.contractee_account_invalid'),

            'contractor_account_id.required' => __('messages.contractor_account_required'),
            'contractor_account_id.exists' => __('messages.contractor_account_invalid'),

            'revenue_account_id.required' => __('messages.revenue_account_required'),
            'revenue_account_id.exists' => __('messages.revenue_account_invalid'),

            'expense_account_id.required' => __('messages.expense_account_required'),
            'expense_account_id.exists' => __('messages.expense_account_invalid'),

            'title_en.required' => __('messages.title_en_required'),
            'title_en.string' => __('messages.title_en_string'),
            'title_en.max' => __('messages.title_en_max'),

            'title_ur.required' => __('messages.title_ur_required'),
            'title_ur.string' => __('messages.title_ur_string'),
            'title_ur.max' => __('messages.title_ur_max'),

            'description_en.string' => __('messages.description_en_string'),
            'description_ur.string' => __('messages.description_ur_string'),

            'work_type.string' => __('messages.work_type_string'),
            'work_type.max' => __('messages.work_type_max'),

            'estimated_cost.numeric' => __('messages.estimated_cost_numeric'),
            'estimated_cost.min' => __('messages.estimated_cost_min'),

            'start_date.date' => __('messages.start_date_date'),
            'end_date.date' => __('messages.end_date_date'),
            'end_date.after_or_equal' => __('messages.end_date_after'),

            'status.required' => __('messages.status_required'),
            'status.in' => __('messages.status_invalid'),
        ];
    }
}
