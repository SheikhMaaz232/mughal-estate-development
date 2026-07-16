<?php

namespace App\Http\Requests\Registration;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRelationRequest extends FormRequest
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
        // $id = $this->route('relation');
        $id = $this->route('relation') ?? $this->route('id');

        return [
            'name_en' => [
                'required',
                'string',
                'max:255',
                Rule::unique('relations', 'name_en')->ignore($id)->whereNull('deleted_at'),
            ],
            'name_ur' => [
                'required',
                'string',
                'max:255',
                Rule::unique('relations', 'name_ur')->ignore($id)->whereNull('deleted_at'),
            ],
        ];
    }


    public function messages()
    {
        return [
            'name_en.required' =>  __('messages.english-name-required'),
            'name_ur.required' =>  __('messages.urdu-name-required'),
            'name_en.unique' => __('messages.already-exists'),
            'name_ur.unique' => __('messages.already-exists'),
        ];
    }
}
