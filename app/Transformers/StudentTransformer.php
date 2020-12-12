<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentTransformer extends JsonResource
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
            'user_id' =>  $this->user_id,
            'school_id' =>  $this->school_id,
            'class_id' =>  $this->class_id
        ];
    }
}