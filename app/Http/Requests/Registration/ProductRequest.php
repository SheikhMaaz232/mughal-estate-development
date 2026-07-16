<?php

namespace App\Http\Requests\Registration;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
        // $id = $this->route('product');
        $id = $this->route('product');
        return [
            'main_head_id'        => 'required|exists:main_heads,id',
            'control_head_id'     => 'required|exists:control_heads,id',
            'sub_head_id'         => 'required|exists:sub_heads,id',
            'sub_sub_head_id'     => 'required|exists:sub_sub_heads,id',
            'sub_sub_sub_head_id' => 'required|exists:sub_sub_sub_heads,id',
            'company_id'          => 'required|exists:companies,id',
            'project_id'          => 'required|exists:projects,id',
            'road_id'             => 'required|exists:road_categories,id',
            'front_id'            => 'required|integer',
            'amount_in_pkr'       => 'required|numeric|min:0',

            // Unique unit_no per project
            'unit_no' => [
                'required',
                'string',
                Rule::unique('products', 'unit_no')
                    ->where(fn($q) => $q->where('project_id', $this->project_id)
                        ->whereNull('deleted_at'))
                    ->ignore($id),
            ],
            'kanal'               => 'required|numeric|min:0',
            'marla'               => 'required|numeric|min:0',
            'total_marla'         => 'required|numeric|min:0',
            'square_feet'         => 'required|numeric|min:0',
            'front_width'         => 'required|numeric|min:0',
            'length'              => 'required|numeric|min:0',
            'front_width2'        => 'nullable|string|max:255',
            'block'               => 'nullable|string|max:255',
            'length2'              => 'nullable|string|max:255',
            'status'              => 'required|string|max:255',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'name_en'             => 'required|string|max:255',
            'name_ur'             => 'required|string|max:255',
            'termsAndConditions'  => 'nullable|string',
            'type'                => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'main_head_id.required'        => __('messages.main_head_required'),
            'control_head_id.required'     => __('messages.control_head_required'),
            'sub_head_id.required'         => __('messages.sub_head_required'),
            'sub_sub_head_id.required'     => __('messages.sub_sub_head_required'),
            'sub_sub_sub_head_id.required' => __('messages.sub_sub_sub_head_required'),
            'company_id.required'          => __('messages.company_required'),
            'project_id.required'          => __('messages.project_required'),
            'road_id.required'             => __('messages.road_required'),
            'front_id.required'            => __('messages.front_required'),
            'amount_in_pkr.required'       => __('messages.amount_required'),
            'unit_no.required'             => __('messages.unit_no_required'),
            'unit_no.unique'               => __('messages.unit_no_unique'),
            'kanal.required'               => __('messages.kanal_required'),
            'marla.required'               => __('messages.marla_required'),
            'total_marla.required'         => __('messages.total_marla_required'),
            'square_feet.required'         => __('messages.square_feet_required'),
            'front_width.required'         => __('messages.front_width_required'),
            'length.required'               => __('messages.length_required'),
            'status.required'              => __('messages.status_required'),
            'name_en.required'             => __('messages.name_en_required'),
            'name_ur.required'             => __('messages.name_ur_required'),
            'type.required'                => __('messages.type_required'),
            'image.image'              => __('messages.image_image'),
            'image.mimes'              => __('messages.image_mimes'),
            'image.max'                => __('messages.image_max'),
        ];
    }
}
