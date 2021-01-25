<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityWellnessTransformer extends JsonResource
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
            'act_catagory' =>  $this->act_catagory,
            'act_date' =>  $this->act_date,
            'act_duration' =>  $this->act_duration,
            'act_intake' =>  $this->act_intake,
            'act_intensity' =>  $this->act_intensity,
            'act_time' =>  $this->act_time,
            'act_type' =>  $this->act_type,
            'patient_id' =>  $this->patient_id,
            'status' =>  $this->status,
            'unit' =>  $this->unit
        ];
    }
}