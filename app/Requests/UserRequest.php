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
        $user = $request->user;

        $role_id = 0;

        if(!empty($user['role'])){
            $role_id = Role::where("code", $user['role'])->pluck('id')->first();
        } 

        return [
            'role' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email,null,id,role_id,' . $role_id,
            'mobile' => 'required|unique:users,mobile,null,id,role_id,' . $role_id,
            'isd_code' => 'nullable',
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

            unset($rules['user']['role_id']);
            unset($rules['user']['timezone_id']);
            unset($rules['user']['address']);
            unset($rules['user']['is_2fa']);
            unset($rules['user']['is_active']);

            $user = User::where('id', $this->route('id'))->first();

            // Edited Rules
            $rules['username'] = 'required|unique:users,username,' . $user->id . ',id,role_id,' . $user->role_id;
            $rules['email'] = 'required|unique:users,email,' . $user->id . ',id,role_id,' . $user->role_id;
            $rules['mobile'] = 'required|unique:users,mobile,' . $user->id . ',id,role_id,' . $user->role_id;

        }
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
