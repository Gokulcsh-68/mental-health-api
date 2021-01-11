<?php

namespace App\Requests;

use Pearl\RequestValidate\RequestAbstract;

class VitalRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required',
            'consult_id' => 'nullable',
            'peripheral_id' => 'nullable',
            'slug' => 'nullable',
            'details' => 'required'
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