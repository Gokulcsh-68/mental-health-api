<?php

namespace App\Requests;

use Pearl\RequestValidate\RequestAbstract;

class CameraRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'hospital_id' => 'nullable',
            'camera_name' => 'required',
            'camera_ip' => 'required',
            'camera_type' => 'required'
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