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
            'id' => $this->id,
            'user_id' => $this->user_id,
            'practicing_since' => $this->practicing_since,
            'license_no' => $this->license_no,
            'specialities' => $this->specialities,
            'additional_info' => $this->additional_info,
            'user' => (new UserTransformer($this->user)),
            'provider_speciality' => $this->providerSpeciality,
        ];
    }
}
