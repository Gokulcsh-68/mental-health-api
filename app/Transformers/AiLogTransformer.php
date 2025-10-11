<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class AiLogTransformer extends JsonResource
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
            'id'        =>  $this->id,
            'patient_id' =>  $this->patient_id,
            'data' =>  $this->data,
            'status' =>  $this->status,
            'user' => (new UserTransformer($this->primaryStaff->user)),
        ];
    }
}