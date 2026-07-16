<?php

namespace App\Http\Requests\LandRegistration;

use Illuminate\Foundation\Http\FormRequest;

class StoreLandRequest extends FormRequest
{
    public function authorize()
    {
        return true; // adjust auth logic as needed
    }

    public function rules()
    {
        return [
            'seller_account_id' => 'nullable|string|max:255',
            'buyer_account_id' => 'nullable|string|max:255',
            'commission_account_id' => 'nullable|string|max:255',
            'product_account' => 'nullable|string|max:255',
            'total_murabba' => 'nullable|numeric',
            'total_acre' => 'nullable|numeric',
            'total_kanal' => 'nullable|numeric',
            'total_wigha' => 'nullable|numeric',
            'total_marla' => 'nullable|numeric',
            'total_square_feet' => 'nullable|numeric',
            'remarks' => 'nullable|string',
            'project_id' => 'nullable|string|max:255',
            'commission_amount' => 'nullable|numeric',
            'land_amount' => 'nullable|numeric',
            'terms_conditions_en' => 'nullable|string',
            'terms_conditions_ur' => 'nullable|string',

            'details' => 'nullable|array',
            'details.*.khawat_no' => 'nullable|string|max:255',
            'details.*.fard_id_no' => 'nullable|string|max:255',
            'details.*.registry_no' => 'nullable|string|max:255',
            'details.*.moza' => 'nullable|string|max:255',
            'details.*.murabba' => 'nullable|numeric',
            'details.*.acre' => 'nullable|numeric',
            'details.*.kanal' => 'nullable|numeric',
            'details.*.wigha' => 'nullable|numeric',
            'details.*.marla' => 'nullable|numeric',
            'details.*.square_feet' => 'nullable|numeric',
            'details.*.remarks' => 'nullable|string',
        ];
    }
}
