<?php

namespace App\Requests;

use Pearl\RequestValidate\RequestAbstract;

class MasterRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'master_type_slug' => 'required',
            'name' => 'required',
            'slug' => 'required',
            'attributes' => 'nullable',
            'is_active' => 'required'
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