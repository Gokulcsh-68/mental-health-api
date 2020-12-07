<?php

namespace App\Requests;

use Pearl\RequestValidate\RequestAbstract;

class ApiAccessRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'username' => 'required',
            'token' => 'required',
            'active' => 'required',
            'expiry_date' => 'required'
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