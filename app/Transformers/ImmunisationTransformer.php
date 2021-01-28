<?php

namespace App\Transformers;

use App\Services\MasterService;
use Illuminate\Http\Resources\Json\JsonResource;

class ImmunisationTransformer extends JsonResource
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
            'details' => $this->details,
            'patient_id' =>  $this->patient_id,
            'slug' =>  $this->slug
        ];
    }
}