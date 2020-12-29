<?php

namespace App\Requests;

use Pearl\RequestValidate\RequestAbstract;

class ProviderRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {

        $rules = [
            'practicing_since' => 'nullable',
            'license_no' => 'required',
            'specialities' => 'nullable',
            'additional_info' => 'nullable'
        ];

        $rules['user'] = (new UserRequest())->rules();

        // Edited Rules
        if ($this->route('id')) {
            $rules['user'] = array_except($rules['user'], ['role_id', 'timezone_id', 'address', 'is_2fa', 'is_active', 'role_type']);
            $provider = app('request')->attributes->get('entity')->where('id', $this->route('id'))
                ->firstOrFail(['user_id']);
            
            $rules['user']['username'] = 'required|unique:users,username,' . $provider->user_id . ',id,role_id,' . $provider->user->role_id;
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
            //
        ];
    }
}