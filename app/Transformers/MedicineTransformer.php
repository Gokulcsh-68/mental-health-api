<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class MedicineTransformer extends JsonResource
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
            'user_id' =>  $this->user_id,
            'name' =>  $this->name,
            'type' =>  $this->type,
            'dosage' =>  $this->dosage,
            'generic_name' =>  $this->generic_name,
            'attributes' =>  $this->attributes
        ];
    }
}