<?php

namespace App\Requests;

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
        $user = $request->user;

        return [
            'role_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email,null,id,role_id,' . $user['role_id'],
            'isd_code' => 'nullable',
            'mobile' => 'nullable|unique:users,mobile',
            'username' => 'required|unique:users,username,null,id,role_id,' . $user['role_id'],
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
