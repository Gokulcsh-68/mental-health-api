<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ProviderUnavailabilityTransformer extends JsonResource
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
            'available_status' =>  $this->available_status,
            'available_type' =>  $this->available_type,
            'from_date' =>  $this->from_date,
            'provider_id' =>  $this->provider_id,
            'timing' =>  $this->timing
        ];
    }
}