<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class SchoolClassTransformer extends JsonResource
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
            'name' =>  $this->name,
            'school_id' =>  $this->school_id,
            'staff_id' =>  $this->staff_id
        ];
    }
}