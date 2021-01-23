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
            'master_type_slug' =>  $this->master_type_slug,
            'name' =>  $this->name,
            'slug' =>  $this->slug,
            'attributes' =>  $this->attributes,
            'is_active' =>  $this->is_active,
        ];

        return $data;
    }
}