<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class FormQuestionAnswerTransformer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
    */

    public function toArray($request): array
    {
        return [
            'question_id' =>  $this->question_id,
            'answer_id' =>  $this->answer_id,
            'jump_to_question_id' =>  $this->jump_to_question_id,
            'score' =>  $this->score,
            'order' =>  $this->order,
            'type' =>  $this->type,
            'label' =>  $this->label,
            'answer' =>  (new AnswerTransformer($this)),
        ];
    }
}