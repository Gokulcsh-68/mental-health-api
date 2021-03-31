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
            'unique_id' =>  $this->unique_id,
            'patient_in_room' =>  $this->patient_in_room,
            'provider_in_room' =>  $this->provider_in_room,
            'patient_id' =>  $this->patient_id,
            'provider_id' =>  $this->provider_id,
            'hospital_id' =>  $this->hospital_id,
            'consult_type' =>  $this->consult_type,
            'consult_slot_type' =>  $this->consult_slot_type,
            'consult_date_time' =>  $this->consult_date_time,
            'consult_duration' =>  $this->consult_duration,
            'speciality' =>  $this->speciality,
            'unit' =>  $this->unit,
            'slots' =>  $this->slots,
            'started_date_time' =>  $this->started_date_time,
            'ended_date_time' =>  $this->ended_date_time,
            'consent' =>  $this->consent,
            'camera_id' =>  $this->camera_id,
            'consult_notes' =>  $this->consult_notes,
            'Addendum_notes' =>  $this->Addendum_notes,
            'reason_for_consult' =>  $this->reason_for_consult,
            'status' =>  $this->status
        ];
    }
}