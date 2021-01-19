<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class FormQuestionTransformer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
    */

    public function toArray($request): array
    {
        // dd($this->formQuestionAnswers);

        return [
            // 'form_id' =>  $this->form_id,
           // 'question_id' =>  $this->question_id,
            'order' =>  $this->order,
            'question' =>  (new QuestionTransformer($this)),
            'answers' =>   FormQuestionAnswerTransformer::collection($this->formQuestionAnswers),
        ];
    }
}