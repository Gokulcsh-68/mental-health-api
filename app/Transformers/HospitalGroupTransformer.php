<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class HospitalGroupTransformer extends JsonResource
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
            'name' =>  $this->name,
            'reg_no' =>  $this->reg_no,
            'additional_info' =>  $this->additional_info,
            'user' => (new UserTransformer($this->primaryStaff->user)),
        ];
    }
}