<?php

namespace App\Requests;

use Pearl\RequestValidate\RequestAbstract;

class StaffRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            // 'user_id' => 'required',
            // 'school_id' => 'required',
            'is_admin' => 'required'
        ];

        $rules['user'] = (new UserRequest())->rules();

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