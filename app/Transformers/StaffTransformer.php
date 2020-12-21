<?php

namespace App\Transformers;

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
                'timezone' => $this->user->timezone,
                'address' => $this->user->address,
                'country_iso' => $this->user->country_iso,
                'emergency_contact_info' => $this->user->emergency_contact_info,
            ],
        ];
    }
}
