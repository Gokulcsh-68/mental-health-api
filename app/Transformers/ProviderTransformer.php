<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ProviderTransformer extends JsonResource
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
            'user_id' =>  $this->user_id,
            'school_id' =>  $this->school_id,
            'practicing_since' =>  $this->practicing_since,
            'license_no' =>  $this->license_no,
            'specialities' =>  $this->specialities,
            'additional_info' =>  $this->additional_info
        ];
    }
}