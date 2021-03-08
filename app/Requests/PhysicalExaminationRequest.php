<?php

namespace App\Requests;

use Pearl\RequestValidate\RequestAbstract;

class PhysicalExaminationRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'consult_id' => 'nullable',
            'name' => 'required',
            'patient_id' => 'required',
            'slug' => 'required',
            'status' => 'required',
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