<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class SchoolTransformer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request): array
    {
        // $user = $this->primaryStaff->user->load('timezone')->toArray();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'reg_no' => $this->reg_no,
            'logo' => $this->logo,
            'additional_info' => $this->additional_info,
            'user' => (new UserTransformer($this->primaryStaff->user)),
            /*'user' => [
                'first_name' => $this->primaryStaff->user->first_name,
                'last_name' => $this->primaryStaff->user->last_name,
                'email' => $this->primaryStaff->user->email,
                'isd_code' => $this->primaryStaff->user->isd_code,
                'mobile' => $this->primaryStaff->user->mobile,
                'username' => $this->primaryStaff->user->username,
                'profile_image' => $this->primaryStaff->user->profile_image,
                'gender' => $this->primaryStaff->user->gender,
                'dob' => $this->primaryStaff->user->dob,
                'blood_group' => $this->primaryStaff->user->blood_group,
                'address' => $this->primaryStaff->user->address,
                'timezone' => $this->primaryStaff->user->timezone,
                'country_iso' => $this->primaryStaff->user->country_iso,
                'is_active' => $this->primaryStaff->user->is_active,
                'emergency_contact_info' => $this->primaryStaff->user->emergency_contact_info,
            ],*/
        ];
    }
}
