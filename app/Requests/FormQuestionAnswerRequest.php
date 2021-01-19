<?php

namespace App\Requests;

use Pearl\RequestValidate\RequestAbstract;

class FormQuestionAnswerRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'question_id' => 'required',
            'answer_id' => 'nullable',
            'jump_to_question_id' => 'nullable',
            'score' => 'nullable',
            'order' => 'nullable',
            'type' => 'required',
            'label' => 'nullable'
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