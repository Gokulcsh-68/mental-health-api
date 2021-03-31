<?php

namespace App\Requests;

use App\Entities\Patient;
use Pearl\RequestValidate\RequestAbstract;

class PatientRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        // return [
        //     'user_id' => 'required',
        //     'hospital_id' => 'required',
        //     'additional_info' => 'nullable'
        // ];

        $rules['user'] = (new UserRequest())->rules();

        if ($this->route('id')) {

            unset($rules['user']['role_id']);
            unset($rules['user']['timezone_id']);
            unset($rules['user']['address']);
            unset($rules['user']['is_2fa']);
            unset($rules['user']['is_active']);

            $Patient = Patient::where('id', $this->route('id'))->first();

            // Edited Rules
            $rules['user']['username'] = 'required|unique:users,username,' . $Patient->user_id . ',id,role_id,' . $Patient->user->role_id;

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