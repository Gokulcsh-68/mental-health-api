<?php

namespace App\Requests;

use Pearl\RequestValidate\RequestAbstract;

class PatientHistoryRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'patient_id' => 'required',
            'consult_id' => 'nullable',
            'slug' => 'required',
            'values' => 'required'
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