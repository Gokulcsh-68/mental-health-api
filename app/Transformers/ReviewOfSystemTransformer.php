<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewOfSystemTransformer extends JsonResource
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
            'id'            =>  $this->id,
            'patient_id'    =>  $this->patient_id,
            'consult_id'    =>  $this->consult_id,
            'name'          =>  $this->name,
            'slug'          =>  $this->slug,
            'created_at'    =>  $this->created_at,
            'status'        =>  $this->status,
            'values'        =>  $this->values,
            'freeze'        =>  $this->freeze
        ];
    }
}