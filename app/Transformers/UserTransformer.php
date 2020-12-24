<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class UserTransformer extends JsonResource
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
            'id' =>  $this->id,
            'role_id' =>  $this->role_id,
            'first_name' =>  $this->first_name,
            'last_name' =>  $this->last_name,
            'email' =>  $this->email,
            'isd_code' =>  $this->isd_code,
            'mobile' =>  $this->mobile,
            'username' =>  $this->username,
            'secret' =>  $this->secret,
            'profile_image' =>  $this->profile_image,
            'gender' =>  $this->gender,
            'dob' =>  $this->dob,
            'blood_group' =>  $this->blood_group,
            'timezone' =>  $this->timezone,
            'address' =>  $this->address,
            'country_iso' =>  $this->country_iso,
            'emergency_contact_info' =>  $this->emergency_contact_info,
            'is_2fa' =>  $this->is_2fa,
            'is_active' =>  $this->is_active
        ];
    }
}