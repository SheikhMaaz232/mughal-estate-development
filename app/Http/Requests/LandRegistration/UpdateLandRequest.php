<?php

namespace App\Http\Requests\LandRegistration;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // same as store for now
        return (new StoreLandRequest())->rules();
    }
}
