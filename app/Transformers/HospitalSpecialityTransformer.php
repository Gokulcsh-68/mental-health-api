<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class HospitalSpecialityTransformer extends JsonResource
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
            // 'hospital_id' =>  $this->hospital_id,
            'speciality' =>  $this->speciality
        ];
    }
}