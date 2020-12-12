<?php

namespace App\Requests;

use Pearl\RequestValidate\RequestAbstract;

class ProviderSpecialityRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'provider_id' => 'required',
            'speciality' => 'required',
            'school_id' => 'required'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            //
        ];
    }
}