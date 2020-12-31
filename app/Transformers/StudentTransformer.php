<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentTransformer extends JsonResource
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
            'school_id' => $this->school_id,
            'class_id' => $this->class_id,
            'enroll_number' => $this->enroll_number,
            'additional_info' => $this->additional_info,
            'school_name' => $this->school->name,
            'class_name' => $this->getschoolclass->name,
            'user' => (new UserTransformer($this->user)),
            'class_in_charge' => (new UserTransformer($this->getschoolclass->staff->user)),
        ];
    }
}
