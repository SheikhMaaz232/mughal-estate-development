<?php

namespace Modules\Payroll\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePayrollRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'allowance_adjustment' => ['nullable', 'numeric'],
            'deduction_adjustment' => ['nullable', 'numeric'],
            'is_finalized' => ['nullable', 'boolean'],
        ];

    }
}
