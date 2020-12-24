<?php

namespace App\Requests;
use App\Entities\Role;

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

        $user = $request->user();
        $role_id = $user->role_id;

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
            
            $rules['username'] = 'required|unique:users,username,'.$user->id.',id,role_id,' . $role_id;
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
