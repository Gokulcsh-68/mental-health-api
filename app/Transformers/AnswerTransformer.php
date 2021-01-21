<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class AnswerTransformer extends JsonResource
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
            'id' =>  $this->id,
            'name' =>  $this->name,
            'is_active' =>  $this->is_active
        ];
    }
}