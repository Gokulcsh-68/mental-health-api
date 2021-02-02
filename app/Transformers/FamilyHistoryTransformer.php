<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class FamilyHistoryTransformer extends JsonResource
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
            'slug' =>  $this->slug,
            'patient_id' =>  $this->patient_id,
            'details' =>  $this->details
        ];
    }
}