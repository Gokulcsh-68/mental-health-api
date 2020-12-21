<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientHealthTransformer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
    */

    public function toArray($request): array
    {
        if(!empty($this->consult)){
            return [
            // 'patient_id'        =>  $this->patient_id,
            // 'consult_id'        =>  $this->consult_id,
            "id"                => $this->id,
            'slug'              =>  $this->slug,
            'slug-display-name' => $this->master->name,
            'values'            =>  $this->values,
            'consult' => [
                'consult_type'      => $this->consult->consult_type,
                'consult_slot_type' => $this->consult->consult_slot_type,
                'consult_date_time' => $this->consult->consult_date_time,
                'consult_duration'  => $this->consult->consult_duration,
                'speciality'        => $this->consult->speciality,
                'consult_type'      => $this->consult->consult_type,
            ],
            'user' => [
                'first_name'    => $this->user->first_name,
                'last_name'     => $this->user->last_name,
                'email'         => $this->user->email,
                'isd_code'      => $this->user->isd_code,
                'mobile'        => $this->user->mobile,
                'username'      => $this->user->username,
                'profile_image' => $this->user->profile_image,
                'gender'        => $this->user->gender,
            ],

            ];
        }else{
            return [
            // 'patient_id'        =>  $this->patient_id,
            // 'consult_id'        =>  $this->consult_id,
            "id"                => $this->id,
            'slug'              =>  $this->slug,
            'slug-display-name' => $this->master->name,
            'values'            =>  $this->values,
            'user' => [
                'first_name'    => $this->user->first_name,
                'last_name'     => $this->user->last_name,
                'email'         => $this->user->email,
                'isd_code'      => $this->user->isd_code,
                'mobile'        => $this->user->mobile,
                'username'      => $this->user->username,
                'profile_image' => $this->user->profile_image,
                'gender'        => $this->user->gender,
            ],

        ];

        }
    }
}