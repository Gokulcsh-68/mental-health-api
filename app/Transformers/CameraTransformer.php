<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CameraTransformer extends JsonResource
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
            'hospital_id' =>  $this->hospital_id,
            'camera_name' =>  $this->camera_name,
            'camera_ip' =>  $this->camera_ip,
            'camera_type' =>  $this->camera_type
        ];
    }
}