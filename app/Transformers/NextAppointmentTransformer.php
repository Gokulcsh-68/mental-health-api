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
            'consult_id' =>  $this->consult_id,
            'patient_id' =>  $this->patient_id,
            'provider_id' =>  $this->provider_id,
            'date' =>  $this->date,
            'reason' =>  $this->reason,
            'provider' => (new UserTransformer($this->provider))->only('first_name', 'last_name'),
            'edit' => $edit
        ];
    }
}
