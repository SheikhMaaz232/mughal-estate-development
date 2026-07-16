<?php

namespace App\Http\Requests\Registration;

use Illuminate\Foundation\Http\FormRequest;

class StoreUnitRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id'        => 'required|exists:companies,id',
            'project_id'        => 'required|exists:projects,id',
            'product_id'        => 'required|exists:products,id',
            'road_id'           => 'required|exists:road_categories,id',
            'front_id'          => 'required|integer',
            'volume_unit'       => 'required|exists:units,id',
            'covering_unit'     => 'required|exists:units,id',
            'volume'            => 'required|string',
            'covering'          => 'required|string',
            'actual_volume'     => 'required|string',
            'actual_covering'   => 'required|string',
            'phase'             => 'required|string',
            'unit_no'           => 'required|string',
            'unit_name_en'      => 'required|string',
            'unit_name_ur'      => 'required|string',
            'kanal'             => 'required|numeric|min:0',
            'marla'             => 'required|numeric|min:0',
            'yard'              => 'required|numeric|min:0',
            'total_marla'       => 'required|numeric|min:0',
            'status'            => 'required|string',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.required'      => __('messages.company_required'),
            'company_id.exists'        => __('messages.company_exists'),

            'project_id.required'      => __('messages.project_required'),
            'project_id.exists'        => __('messages.project_exists'),

            'product_id.required'      => __('messages.product_required'),
            'product_id.exists'        => __('messages.product_exists'),

            'road_id.required'         => __('messages.road_required'),
            'road_id.exists'           => __('messages.road_exists'),

            'front_id.required'        => __('messages.front_required'),

            'volume_unit.required'     => __('messages.volume_unit_required'),
            'volume_unit.exists'       => __('messages.volume_unit_exists'),

            'covering_unit.required'   => __('messages.covering_unit_required'),
            'covering_unit.exists'     => __('messages.covering_unit_exists'),

            'volume.required'          => __('messages.volume_required'),
            'covering.required'        => __('messages.covering_required'),

            'actual_volume.required'   => __('messages.actual_volume_required'),
            'actual_covering.required' => __('messages.actual_covering_required'),

            'phase.required'           => __('messages.phase_required'),
            'unit_no.required'         => __('messages.unit_no_required'),
            'unit_name_en.required'    => __('messages.unit_name_en_required'),
            'unit_name_ur.required'    => __('messages.unit_name_ur_required'),

            'kanal.required'           => __('messages.kanal_required'),
            'marla.required'           => __('messages.marla_required'),
            'yard.required'            => __('messages.yard_required'),
            'total_marla.required'     => __('messages.total_marla_required'),

            'status.required'          => __('messages.status_required'),
            'image.image'              => __('messages.image_image'),
            'image.mimes'              => __('messages.image_mimes'),
            'image.max'                => __('messages.image_max'),
        ];
    }
}
