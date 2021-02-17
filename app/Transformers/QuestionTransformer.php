<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionTransformer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
    */

    public function toArray($request): array
    {

        if($this->type == 'sub_question'){
            return [
                'id'            => $this->id,
                'parent_id'     => $this->parent_id,
                'name'          => $this->name,
                'type'          => $this->type,
                'is_active'     => $this->is_active,
                'sub_questions' => $this->subQuestions
            ];

        }else{
            return [
                'id'        =>  $this->id,
                // 'parent_id' =>  $this->parent_id,
                'name'      =>  $this->name,
                'type'      =>  $this->type,
                'is_active' =>  $this->is_active
            ];
        }
        
    }
}