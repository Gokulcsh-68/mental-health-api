<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class DocTransformer extends JsonResource
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
            'id' =>  $this->id,
            'addition_info' =>  $this->addition_info,
            'consult_id' =>  $this->consult_id,
            'document_source' =>  $this->document_source,
            'properties' =>  $this->properties,
            'user_id' =>  $this->user_id
        ];
    }
}