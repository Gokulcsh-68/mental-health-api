<?php

namespace App\Requests;

use App\Entities\Staff;
use Pearl\RequestValidate\RequestAbstract;

class HospitalRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|unique:hospitals,name',
            'group_id' => 'nullable',
            'reg_no' => 'required',
            'logo' => 'nullable',
            'additional_info' => 'nullable'
        ];

        $rules['user'] = (new UserRequest())->rules();

        if ($this->route('id')) {
            unset($rules['user']['role_id']);
            unset($rules['user']['timezone_id']);
            unset($rules['user']['address']);
            unset($rules['user']['is_2fa']);
            unset($rules['user']['is_active']);

            $staff = Staff::where('hospital_id', $this->route('id'))->first();

            // Edited Rules
            $rules['name'] = $rules['name'] . "," . $this->route('id');
            $rules['user']['username'] = 'required|unique:users,username,' . $staff->user_id . ',id,role_id,' . $staff->user->role_id;

        }

        return array_dot($rules);
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            "name.unique" => "Hospital name already taken",
            "user.username.unique" => "Username already taken",
        ];
    }
}