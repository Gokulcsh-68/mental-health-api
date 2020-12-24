<?php

namespace App\Requests;

use Pearl\RequestValidate\RequestAbstract;

class ResendOtpRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $request = app('request');
        $rules = [
            "role" => "required",
            "action" => "required",
        ];

        if ($request->action == '2faAuthentication') {
            $rules += [
                "username" => "required|string",
                "password" => "required"
            ];
        }

        if ($request->action == 'forgotPassword') {
            $rules += [
                "email" => "required|string",
            ];
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

        ];
    }
}