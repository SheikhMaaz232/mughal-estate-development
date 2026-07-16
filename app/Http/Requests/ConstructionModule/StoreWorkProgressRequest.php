<?php

namespace App\Http\Requests\ConstructionModule;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkProgressRequest extends FormRequest
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
    public function rules()
    {
        return [
            'work_order_id' => 'required|exists:work_orders,id',
            'item_id' => 'required|exists:items,id',
            'completed_qty' => 'required|array|min:1',
            'date' => 'required|date',
            'description_en' => 'nullable|string|max:1000',
            'description_ur' => 'nullable|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'work_order_id.required' => __('messages.work_order_required'),
            'item_id.required' => __('messages.item_required'),
            'completed_qty.required' => __('messages.qty_required'),
            'date.required' => __('messages.date_required'),
        ];
    }
}
