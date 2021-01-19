<?php

namespace App\Requests;

use Pearl\RequestValidate\RequestAbstract;

class FormQuestionRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'form_id' => 'required',
            'question_id' => 'required',
            'order' => 'nullable'
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