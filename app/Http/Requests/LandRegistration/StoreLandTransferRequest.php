<?php

namespace App\Http\Requests\LandRegistration;

use Illuminate\Foundation\Http\FormRequest;

class StoreLandTransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'land_id' => 'required|exists:lands,id',
            'transfer_date' => 'required|date',
            'registry_type_id' => 'required|exists:registry_types,id',
            'purchaser_account_id' => 'required|exists:detail_accounts,id',
            'seller_account_id' => 'required|exists:detail_accounts,id',
            'fard_no' => 'required|string|max:255',
            'khawat_no' => 'required|string|max:255',
            'khatoni_no' => 'nullable|string|max:255',
            'mushtarqa_khata' => 'nullable|string|max:255',
            'makhsoos_raqba' => 'nullable|string|max:255',
            'qitaat' => 'nullable|string|max:255',
            'saalam_khata' => 'nullable|string|max:255',
            'hissa_mutaliqa' => 'nullable|string|max:255',
            'raqba_muntaqila' => 'nullable|string|max:255',
            'value' => 'required|numeric|min:0',
            'attachment_1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attachment_2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attachment_3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // For update, make images optional
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['attachment_1'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
            $rules['attachment_2'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
            $rules['attachment_3'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'transfer_date.required' => __('messages.transfer_date_required'),
            'registry_type_id.required' => __('messages.registry_type_id_required'),
            'purchaser_account_id.required' => __('messages.purchaser_account_id_required'),
            'seller_account_id.required' => __('messages.seller_account_id_required'),
            'fard_no.required' => __('messages.fard_no_required'),
            'khawat_no.required' => __('messages.khawat_no_required'),
            'value.required' => __('messages.value_required'),
        ];
    }
}
