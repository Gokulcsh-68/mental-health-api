<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientHealthTransformer extends JsonResource
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
            'slug'              => $this->slug,
            'patient_id'        =>  $this->patient_id,
            'consult_id'        =>  $this->consult_id,
            // 'slug-display-name' => $this->master->name,
            'values'            => $this->values,
            'created_at' => $this->created_at,
            'freeze' =>  $this->freeze
        ];

        // if($this->slug == 'condition') { // removed unnessary user data here. If needed then add a if condition and use it
        //     $data['user'] = new UserTransformer($this->user);
        // }

        if(!empty($this->consult)){
            $data['consult'] = (new ConsultTransformer($this->consult));
        }
        return $data;

    }
}
