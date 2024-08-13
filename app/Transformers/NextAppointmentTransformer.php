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
            'date' =>  $this->is_admin,
            'reason' =>  $this->additional_info,
            'provider' => (new UserTransformer($this->provider))->only('first_name', 'last_name')
        ];
    }
}