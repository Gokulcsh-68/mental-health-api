<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class VitalTransformer extends JsonResource
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
            'user_id' =>  $this->user_id,
            'consult_id' =>  $this->consult_id,
            'peripheral_id' =>  $this->peripheral_id,
            'slug' =>  $this->slug,
            'details' =>  $this->details,
            'freeze' =>  $this->freeze
        ];
    }
}