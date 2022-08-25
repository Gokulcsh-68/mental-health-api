<?php

namespace App\Requests;

use Pearl\RequestValidate\RequestAbstract;

class MedicineRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'user_id' => 'nullable',
            'name' => 'required',
            'type' => 'required',
            'dosage' => 'nullable',
            'generic_name' => 'nullable',
            'attributes' => 'nullable'
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