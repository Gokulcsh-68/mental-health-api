<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientTransformer extends JsonResource
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
            'user_id' =>  $this->user_id,
            'hospital_id' =>  $this->hospital_id,
            'additional_info' =>  $this->additional_info,
            'user' => (new UserTransformer($this->user))
        ];
    }
}