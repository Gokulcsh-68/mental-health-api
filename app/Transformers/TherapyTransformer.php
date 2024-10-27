<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class TherapyTransformer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
    */

    public function toArray($request): array
    {

        $dateA = date('Y-m-d H:i:s',strtotime($this->created_at));
        if(strtotime($dateA) <= strtotime('-24 hours')){
            $edit =  false;
        }
        else
        {
            $edit =  true;
        }

        return [
            'id' => $this->id,
            'patient_id' =>  $this->patient_id,
            'date' =>  $this->date,
            'type' =>  $this->type,
            'therapy_name' =>  $this->therapy_name,
            'therapist_name' =>  $this->therapist_name,
            'duration' =>  $this->duration,
            'notes' =>  $this->notes,
            'created_at' =>  $this->created_at,
            'edit' => $edit
        ];
    }
}
