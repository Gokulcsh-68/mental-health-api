<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ConsultTransformer extends JsonResource
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
            'patient_id' =>  $this->patient_id,
            'provider_id' =>  $this->provider_id,
            'school_id' =>  $this->school_id,
            'class_id' =>  $this->class_id,
            'consult_type' =>  $this->consult_type,
            'consult_slot_type' =>  $this->consult_slot_type,
            'consult_date_time' =>  $this->consult_date_time,
            'consult_duration' =>  $this->consult_duration,
            'speciality' =>  $this->speciality
        ];
    }
}