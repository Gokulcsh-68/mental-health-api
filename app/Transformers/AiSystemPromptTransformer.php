<?php

namespace App\Transformers;

use App\Transformers\PetTransformer;
use App\Transformers\UserTransformer;
use Illuminate\Http\Resources\Json\JsonResource;

class AiSystemPromptTransformer extends JsonResource
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
            'prompt_text'   =>  $this->prompt_text,
            'pet'           =>  new PatientTransformer($this->pet),
            'pet_owner'     =>  new UserTransformer($this->pet->patient->user),
            'created_by'    =>  new UserTransformer($this->user),
            'created_at'    =>  $this->created_at,
            'updated_at'    =>  $this->updated_at,
            'deleted_at'    =>  $this->deleted_at,
        ];
    }
}