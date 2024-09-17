<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class NextAppointmentTransformer extends JsonResource
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
            'id' => $this->id,
            'patient_id' =>  $this->patient_id,
            'provider_id' =>  $this->provider_id,
            'date' =>  $this->date,
            'reason' =>  $this->reason,
            'provider' => (new UserTransformer($this->provider))->only('first_name', 'last_name')
        ];
    }
}