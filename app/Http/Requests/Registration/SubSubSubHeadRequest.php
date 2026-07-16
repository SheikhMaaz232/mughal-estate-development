<?php

namespace App\Http\Requests\Registration;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SubSubSubHeadRequest extends FormRequest
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
        $id = $this->route('sub_sub_sub_head');
        return [
            'main_head_id' => ['required', 'integer', 'exists:main_heads,id'],
            'control_head_id' => ['required', 'integer', 'exists:control_heads,id'],
            'sub_head_id' => ['required', 'integer', 'exists:sub_heads,id'],
            'sub_sub_head_id' => ['required', 'integer', 'exists:sub_sub_heads,id'],
            'project_id'        => 'required|exists:projects,id',
            'name_en' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sub_sub_sub_heads', 'name_en')->ignore($id)->whereNull('deleted_at'),
            ],
            'name_ur' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sub_sub_sub_heads', 'name_ur')->ignore($id)->whereNull('deleted_at'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'main_head_id.required' => __('messages.select-main-heads'),
            'control_head_id.required' => __('messages.select-control-heads'),
            'sub_head_id.required' => __('messages.select-sub-heads'),
            'sub_sub_head_id.required' => __('messages.select-sub-sub-heads'),
            'name_en.required' =>  __('messages.english-name-required'),
            'name_ur.required' =>  __('messages.urdu-name-required'),
            'name_en.unique' => __('messages.already-exists'),
            'name_ur.unique' => __('messages.already-exists'),
        ];
    }
}
