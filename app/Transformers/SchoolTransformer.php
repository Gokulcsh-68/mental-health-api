<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class SchoolTransformer extends JsonResource
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
            'reg_no' =>  $this->reg_no,
            'logo' =>  $this->logo,
            'additional_info' =>  $this->additional_info
        ];
    }
}