<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class AvailabilityDetailTransformer extends JsonResource
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
            'provider_id' =>  $this->provider_id,
            'from_date_time' =>  $this->from_date_time,
            'to_date_time' =>  $this->to_date_time,
            'duration' =>  $this->duration,
            'slot_group' =>  $this->slot_group,
            'available_type' =>  $this->available_type,
            'slot_type' =>  $this->slot_type,
            'slot_status' =>  $this->slot_status,
            'available_status' =>  $this->available_status
        ];
    }
}