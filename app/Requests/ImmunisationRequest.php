<?php

namespace App\Requests;

use Pearl\RequestValidate\RequestAbstract;

class ImmunisationRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'details' => 'required',
            'patient_id' => 'required',
            'slug' => 'required'
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