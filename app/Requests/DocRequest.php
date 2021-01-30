<?php

namespace App\Requests;

use Pearl\RequestValidate\RequestAbstract;

class DocRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'addition_info' => 'nullable',
            'consult_id' => 'nullable',
            'document_source' => 'nullable',
            'properties' => 'nullable',
            'user_id' => 'required'
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