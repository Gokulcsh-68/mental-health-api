<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class FormTransformer extends JsonResource
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
            'parent_id' =>  $this->parent_id,
            'slug' =>  $this->slug,
            'name' =>  $this->name,
            'desc' =>  $this->desc,
            'assessment_group' =>  $this->assessment_group,
            'type' =>  $this->type,
            'images' =>  $this->images,
            'is_active' =>  $this->is_active
        ];
    }
}