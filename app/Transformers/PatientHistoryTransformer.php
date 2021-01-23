<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientHistoryTransformer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
    */

    public function toArray($request): array
    {
        $data = [
            "id"                => $this->id,
            'patient_id' =>  $this->patient_id,
            'consult_id' =>  $this->consult_id,
            'slug' =>  $this->slug,
            'values' =>  $this->values
        ];

        if(!empty($this->consult)){
            $data['consult'] = (new ConsultTransformer($this->consult));
        }

        return $data;
    }
}