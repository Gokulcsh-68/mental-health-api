<?php

namespace App\Transformers;

use App\Services\CureselectApis\PeripheralApiService;
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
        $peripheral_credentials = (new PeripheralApiService)->get($this->user_id);
        return [
            'id' => $this->id,
            'user_id' =>  $this->user_id,
            'hospital_id' =>  $this->hospital_id,
            'additional_info' =>  $this->additional_info,
            'user' => (new UserTransformer($this->user)),
            'peripheral_credentials' => [
                'username' => $peripheral_credentials['username'] ?? '',
                'salt_key' => $peripheral_credentials['access_secret'] ?? ''

            ],
        ];
    }
}