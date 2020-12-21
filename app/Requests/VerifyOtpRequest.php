<?php

namespace App\Requests;

use Pearl\RequestValidate\RequestAbstract;

class VerifyOtpRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            "otp" => "required|string",
            "verify_mode" => "required|in:email,phone"
        ];

        $rules = (new GeneralLoginRequest())->rules();

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

        ];
    }
}