<?php

namespace App\Transformers;

use App\Services\CureselectApis\PeripheralApiService;
use Illuminate\Support\Facades\Cache;
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

        $return_val = ['id' =>  $this->id,
            'role_id' =>  $this->role_id,
            'first_name' =>  $this->first_name,
            'last_name' =>  $this->last_name,
            'email' =>  $this->email,
            'isd_code' =>  $this->isd_code,
            'mobile' =>  $this->mobile,
            'username' =>  $this->username,
            'secret' =>  $this->secret,
            'profile_image' =>  $this->profile_image_url,
            'gender' =>  $this->gender,
            'dob' =>  $this->dob,
            'blood_group' =>  $this->blood_group,
            'timezone' =>  $this->timezone,
            'address' =>  $this->address,
            'country_iso' =>  $this->country_iso,
            'emergency_contact_info' =>  $this->emergency_contact_info,
            'is_2fa' =>  $this->is_2fa,
            'is_active' =>  $this->is_active,
            'communication_channel' =>  $this->communication_channel];

        if($request->user()){


            if($request->user()->role->code == 'hospitalgroup'){
                $return_val['hospital_group_name'] = $request->get('staff')->hospitalgroup->name;
                $return_val['hospital_group_address'] = $request->get('staff')->hospitalgroup;
            }


            if($request->user()->role->code == 'hospital'){
                $return_val['hospital_name'] = $request->get('staff')->hospital->name;
                $return_val['hospital_address'] = $request->get('staff')->hospital;

                if($this->patient){
                    $peripheral_credentials = Cache::rememberForever('PERIPHERAL_CREDENTIALS_USER_' . $this->user_id, function() {
                        return (new PeripheralApiService)->get($this->user_id);
                    });
                    $return_val['patient'] = $this->patient;
                    $return_val['peripheral_credentials'] = [
                        'username' => $peripheral_credentials['username'] ?? '',
                        'otp' => $peripheral_credentials['otp'] ?? '',
                        'salt_key' => $peripheral_credentials['access_secret'] ?? '',
                    ];
                    
                }
            }
        }

        return $return_val;
    }
}