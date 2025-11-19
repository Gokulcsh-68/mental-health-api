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
            'id'          => $this->id,
            'prompt_text' => $this->prompt_text,
            'pet'         => $this->pet ? new PatientTransformer($this->pet) : null,
            'pet_owner'   => $this->pet?->patient?->user ? new UserTransformer($this->pet->patient->user) : null,
            'created_by'  => $this->user ? new UserTransformer($this->user) : null,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
            'deleted_at'  => $this->deleted_at,
        ];
    }
}
