<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class HospitalTransformer extends JsonResource
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
            'group_id' =>  $this->group_id,
            'reg_no' =>  $this->reg_no,
            'logo' =>  $this->logo,
            'additional_info' =>  $this->additional_info,
            'user' => (new UserTransformer($this->primaryStaff->user)),
        ];
    }
}