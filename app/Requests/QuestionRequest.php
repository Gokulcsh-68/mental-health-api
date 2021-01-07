<?php

namespace App\Requests;

use Pearl\RequestValidate\RequestAbstract;

class QuestionRequest extends RequestAbstract
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
            'name' => 'required',
            'type' => 'required',
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