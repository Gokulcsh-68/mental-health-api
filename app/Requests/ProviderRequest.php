<?php

namespace App\Requests;

use Pearl\RequestValidate\RequestAbstract;

class ProviderRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'school_id' => 'required',
            'practicing_since' => 'nullable',
            'license_no' => 'required',
            'specialities' => 'nullable',
            'additional_info' => 'nullable'
        ];

        $rules['user'] = (new UserRequest())->rules();

        return array_dot($rules);
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