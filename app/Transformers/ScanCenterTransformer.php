<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ScanCenterTransformer extends JsonResource
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
            'primary_scan_centers_id' =>  $this->primary_scan_centers_id,
            'hospital_id' =>  $this->hospital_id,
            'is_admin' =>  $this->is_admin,
            'additional_info' =>  $this->additional_info,
            'user' => (new UserTransformer($this->user))
        ];
    }
}