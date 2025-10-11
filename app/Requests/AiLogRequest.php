<?php

namespace App\Requests;

use Pearl\RequestValidate\RequestAbstract;

class AiLogRequest extends RequestAbstract
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
            'data' => 'nullable',
            'status' => 'required'
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