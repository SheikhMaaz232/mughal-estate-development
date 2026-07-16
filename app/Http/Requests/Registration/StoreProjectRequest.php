<?php

namespace App\Http\Requests\Registration;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'group_id' => 'required|integer|exists:groups,id',
            'company_id' => 'required|integer|exists:companies,id',
            'name_en' => 'required|string|max:255',
            'name_ur' => 'required|string|max:255',
            'square_feet' => 'required|numeric|min:1',
            'description_en' => 'required|string',
            'description_ur' => 'required|string|max:255',
            'phase_en' => 'nullable|string|max:255',
            'phase_ur' => 'nullable|string|max:255',
            'address_en' => 'required|string',
            'address_ur' => 'required|string|max:255',
            'project_map' => $this->isMethod('post')
                ? 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
                : 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'roads_area' => 'required|numeric|min:0',
            'park_area' => 'required|numeric|min:0',
            'cemetery_area' => 'required|numeric|min:0',
            'mosque_area' => 'required|numeric|min:0',
            'public_buildings_area' => 'required|numeric|min:0',
            'miscellaneous_area' => 'required|numeric|min:0',
            'social_waste_area' => 'required|numeric|min:0',
            'disposal_area' => 'required|numeric|min:0',
            'commercial_plots_area' => 'required|numeric|min:0',
            'residential_plots_area' => 'required|numeric|min:0',
            'total_area' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'group_id.required' => __('messages.group_id_required'),
            'company_id.required' => __('messages.company_id_required'),
            'name_en.required' => __('messages.name_en_required'),
            'name_ur.required' => __('messages.name_ur_required'),
            'square_feet.required' => __('messages.square_feet_required'),
            'description_en.required' => __('messages.description_en_required'),
            'description_ur.required' => __('messages.description_ur_required'),
            'address_en.required' => __('messages.address_en_required'),
            'address_ur.required' => __('messages.address_ur_required'),
            'total_area.required' => __('messages.total_area_required'),
            'roads_area.required' => __('messages.roads_area.required'),
            'roads_area.numeric' => __('messages.roads_area.numeric'),
            'roads_area.min' => __('messages.roads_area.min'),
            'park_area.required' => __('messages.park_area.required'),
            'park_area.numeric' => __('messages.park_area.numeric'),
            'park_area.min' => __('messages.park_area.min'),
            'cemetery_area.required' => __('messages.cemetery_area.required'),
            'cemetery_area.numeric' => __('messages.cemetery_area.numeric'),
            'cemetery_area.min' => __('messages.cemetery_area.min'),
            'mosque_area.required' => __('messages.mosque_area.required'),
            'mosque_area.numeric' => __('messages.mosque_area.numeric'),
            'mosque_area.min' => __('messages.mosque_area.min'),
            'social_waste_area.required' => __('messages.social_waste_area.required'),
            'social_waste_area.numeric' => __('messages.social_waste_area.numeric'),
            'social_waste_area.min' => __('messages.social_waste_area.min'),
            'disposal_area.required' => __('messages.disposable_area.required'),
            'disposal_area.numeric' => __('messages.disposable_area.numeric'),
            'disposal_area.min' => __('messages.disposable_area.min'),
            'commercial_plots_area.required' => __('messages.commercial_area.required'),
            'commercial_plots_area.numeric' => __('messages.commercial_area.numeric'),
            'commercial_plots_area.min' => __('messages.commercial_area.min'),

            'public_buildings_area.required' => __('messages.public_buildings_area.required'),
            'public_buildings_area.numeric' => __('messages.public_buildings_area.numeric'),
            'public_buildings_area.min' => __('messages.public_buildings_area.min'),

            'miscellaneous_area.required' => __('messages.miscellaneous_area.required'),
            'miscellaneous_area.numeric' => __('messages.miscellaneous_area.numeric'),
            'miscellaneous_area.min' => __('messages.miscellaneous_area.min'),


            'residential_plots_area.required' => __('messages.residential_area.required'),
            'residential_plots_area.numeric' => __('messages.residential_area.numeric'),
            'residential_plots_area.min' => __('messages.residential_area.min'),
            'total_area.required' => __('messages.total_area.required'),
            'total_area.numeric' => __('messages.total_area.numeric'),
            'total_area.min' => __('messages.total_area.min'),
            'project_map.required'   => __('messages.project_map.required'),
            'project_map.image'      => __('messages.project_map.image'),
            'project_map.mimes'      => __('messages.project_map.mimes'),
            'project_map.max'        => __('messages.project_map.max'),
        ];
    }
}
