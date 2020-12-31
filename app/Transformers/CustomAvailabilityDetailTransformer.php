<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomAvailabilityDetailTransformer extends JsonResource
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
            'provider_id' =>  $this->provider_id,
            'from_date' =>  $this->from_date,
            'to_date' =>  $this->to_date,
            'timing' =>  $this->timing
        ];
    }
}