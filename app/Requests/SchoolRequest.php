<?php

namespace App\Requests;

use Pearl\RequestValidate\RequestAbstract;

class SchoolRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'reg_no' => 'required',
            'name' => 'required|unique:schools,name',
            'logo' => 'nullable',
            'additional_info' => 'nullable',
        ];

        // $rules += (new StaffRequest())->rules();

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
            "name.unique" => "School name already taken"
        ];
    }
}