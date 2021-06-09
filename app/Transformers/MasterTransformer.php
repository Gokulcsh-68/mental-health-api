<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class MasterTransformer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
    */

    public function toArray($request): array
    {
        $data =  [
            'id'                =>  $this->id,
            'attributes'        =>  $this->getResult($this->slug,$this->master_type_slug,$this->attributes),
            'master_type_slug'  =>  $this->master_type_slug,
            'name'              =>  $this->name,
            'slug'              =>  $this->slug,
            'is_active'         =>  $this->is_active,
        ];

        return $data;
    }
}