<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class FormSubmittedAnswerTransformer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
    */

    public function toArray($request): array
    {
        $data['name']       = $this->form->name;
        $data['score']      = $this->score;
        $data['answers']    = $this->answers;
        return [
            'form_id'       =>  $this->form_id,
            'patient_id'    =>  $this->patient_id,
            'answers'       =>  $this->answers,
            'score'         =>  $this->score,
            'created_at'    =>  date('Y-m-d',strtotime($this->created_at)),
            'message'       =>  $this->calculate_score($data)
        ];
    }
}