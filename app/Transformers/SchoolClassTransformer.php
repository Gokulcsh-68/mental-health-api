<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class SchoolClassTransformer extends JsonResource
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
            'name' => $this->name,
            'school_id' => $this->school_id,
            'is_active' => $this->is_active,
            'staff_id' => $this->staff_id,
            'school_name' => $this->school->name,
            'staff' => (new UserTransformer($this->staff->user)),
        ];
    }
}
