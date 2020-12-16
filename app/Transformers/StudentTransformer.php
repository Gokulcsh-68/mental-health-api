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
        // dd($this->getschoolclass);
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'school_id' => $this->school_id,
            'class_id' => $this->class_id,
            'school_name' => $this->school->name,
            'class_name' => $this->getschoolclass->name,
            'staff_in_charge' => [
                'first_name' => $this->getschoolclass->staff->user->first_name,
                'last_name' => $this->getschoolclass->staff->user->last_name,
                'email' => $this->getschoolclass->staff->user->email,
                'isd_code' => $this->getschoolclass->staff->user->isd_code,
                'mobile' => $this->getschoolclass->staff->user->mobile,
                'username' => $this->getschoolclass->staff->user->username,
                'profile_image' => $this->getschoolclass->staff->user->profile_image,
            ],
            'user' => [
                'first_name' => $this->user->first_name,
                'last_name' => $this->user->last_name,
                'email' => $this->user->email,
                'isd_code' => $this->user->isd_code,
                'mobile' => $this->user->mobile,
                'username' => $this->user->username,
                'profile_image' => $this->user->profile_image,
                'gender' => $this->user->gender,
                'dob' => $this->user->dob,
                'blood_group' => $this->user->blood_group,
                'timezone_id' => $this->user->timezone_id,
                'address' => $this->user->address,
                'country_iso' => $this->user->country_iso,
                'emergency_contact_info' => $this->user->emergency_contact_info,
            ],
        ];
    }
}
