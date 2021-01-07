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
        return [
            'parent_id' =>  $this->parent_id,
            'name' =>  $this->name,
            'type' =>  $this->type,
            'is_active' =>  $this->is_active
        ];
    }
}