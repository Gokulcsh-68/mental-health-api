<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class StaffTransformer extends JsonResource
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
            'is_admin' =>  $this->is_admin
        ];
    }
}