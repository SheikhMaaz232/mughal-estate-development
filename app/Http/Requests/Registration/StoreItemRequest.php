<?php

namespace App\Http\Requests\Registration;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {

        $id = $this->route('itemRegistration');
        $rules = [
            'main_head_id' => 'required|exists:main_heads,id',
            'control_head_id' => 'required|exists:control_heads,id',
            'sub_head_id' => 'required|exists:sub_heads,id',
            'sub_sub_head_id' => 'required|exists:sub_sub_heads,id',
            'measurement_unit_id' => 'nullable|exists:units,id',

            'name_en' => [
                'required',
                'string',
                'max:255',
                Rule::unique('items', 'name_en')
                    ->ignore($id)
                    ->whereNull('deleted_at'),
            ],
            'name_ur' => [
                'required',
                'string',
                'max:255',
                Rule::unique('items', 'name_ur')
                    ->ignore($id)
                    ->whereNull('deleted_at'),
            ],
            'item_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        // Dynamically validate `sub_sub_sub_head_id`
        if ($this->isMethod('post')) {
            // ✅ Create case → expects array
            $rules['sub_sub_sub_head_id'] = 'required|array';
            $rules['sub_sub_sub_head_id.*'] = 'exists:sub_sub_sub_heads,id';
        } else {
            // ✅ Update case → expects single value
            $rules['sub_sub_sub_head_id'] = 'required|exists:sub_sub_sub_heads,id';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'main_head_id.required' => __('messages.main_head_id_required'),
            'main_head_id.exists' => __('messages.main_head_id_exists'),
            'control_head_id.required' => __('messages.control_head_id_required'),
            'control_head_id.exists' => __('messages.control_head_id_exists'),
            'sub_head_id.required' => __('messages.sub_head_id_required'),
            'sub_head_id.exists' => __('messages.sub_head_id_exists'),
            'sub_sub_head_id.required' => __('messages.sub_sub_head_id_required'),
            'sub_sub_head_id.exists' => __('messages.sub_sub_head_id_exists'),
            'sub_sub_sub_head_id.required' => __('messages.sub_sub_sub_head_id_required'),
            'sub_sub_sub_head_id.exists' => __('messages.sub_sub_sub_head_id_exists'),
            'measurement_unit_id.exists' => __('messages.measurement_unit_id_exists'),
            'name_en.required' => __('messages.name_en_required'),
            'name_en.string' => __('messages.name_en_string'),
            'name_en.max' => __('messages.name_en_max'),
            'name_ur.required' => __('messages.name_ur_required'),
            'name_ur.string' => __('messages.name_ur_string'),
            'name_ur.max' => __('messages.name_ur_max'),
            'item_image.image' => __('messages.item_image_image'),
            'item_image.mimes' => __('messages.item_image_mimes'),
            'item_image.max' => __('messages.item_image_max'),
        ];
    }
}
