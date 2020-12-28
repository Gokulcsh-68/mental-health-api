<?php

namespace App\Requests;
use App\Entities\Role;
use App\Entities\User;

use Pearl\RequestValidate\RequestAbstract;

class UserRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $request = app('request');


        $role_id = Role::where("code", $request['role'])->value('id');
      


        $rules = [
            'role' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'isd_code' => 'nullable',
            'mobile' => 'nullable',
            'username' => 'required|unique:users,username,null,id,role_id,' . $role_id,
            'password' => 'nullable',
            'profile_image' => 'nullable',
            'gender' => 'nullable',
            'dob' => 'nullable',
            'blood_group' => 'nullable',
            'timezone_id' => 'required',
            'address' => 'required',
            'country_iso' => 'nullable',
            'emergency_contact_info' => 'nullable',
            'is_2fa' => 'required',
            'is_active' => 'required',
        ];  
        

        if ($this->route('id')) {
           
            $role_id = app('request')
                ->attributes->get('entity')
                ->where('id', $this->route('id'))
                ->value('role_id');
     
              
            $rules['username'] = 'required|unique:users,username,'.$this->route('id').',id,role_id,' . $role_id;
        }


        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            //
        ];
    }
}
