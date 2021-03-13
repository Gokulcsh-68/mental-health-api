<?php

namespace App\Transformers;

use App\Transformers\UserTransformer;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffTransformer extends JsonResource
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
            'user_id' => $this->user_id,
            'school_name' => $this->school->name,
            'school_id' => $this->school_id,
            'is_admin' => $this->is_admin,
            'additional_info' => $this->additional_info,
            'user' => (new UserTransformer($this->user)),
        ];
    }
}
