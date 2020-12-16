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
            'staff_id' => $this->staff_id,
            'school_name' => $this->school->name,
            'staff' => [
                'first_name' => $this->staff->user->first_name,
                'last_name' => $this->staff->user->last_name,
                'email' => $this->staff->user->email,
                'isd_code' => $this->staff->user->isd_code,
                'mobile' => $this->staff->user->mobile,
                'username' => $this->staff->user->username,
                'profile_image' => $this->staff->user->profile_image,
                'gender' => $this->staff->user->gender,
                'dob' => $this->staff->user->dob,
                'blood_group' => $this->staff->user->blood_group,
                'timezone_id' => $this->staff->user->timezone_id,
                'address' => $this->staff->user->address,
                'country_iso' => $this->staff->user->country_iso,
                'emergency_contact_info' => $this->staff->user->emergency_contact_info,
            ],
        ];
    }
}
