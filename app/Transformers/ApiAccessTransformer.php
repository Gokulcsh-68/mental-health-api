<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ApiAccessTransformer extends JsonResource
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
            'username' =>  $this->username,
            'token' =>  $this->token,
            'active' =>  $this->active,
            'expiry_date' =>  $this->expiry_date
        ];
    }
}