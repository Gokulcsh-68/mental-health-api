<?php

namespace App\Requests;

use Pearl\RequestValidate\RequestAbstract;

class FormRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'parent_id' => 'nullable',
            'slug' => 'required',
            'name' => 'required',
            'desc' => 'required',
            'assessment_group' => 'nullable',
            'type' => 'required',
            'images' => 'nullable',
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