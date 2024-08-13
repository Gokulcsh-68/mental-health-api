<?php

namespace App\Requests;

use Illuminate\Validation\Rule;
use Pearl\RequestValidate\RequestAbstract;

class NextAppointmentRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'patient_id' => [
                'required',
                Rule::exists('users', 'id')->where('role_id', 4)
            ],
            'provider_id' => [
                'required',
                Rule::exists('users', 'id')->where('role_id', 5)
            ],
            'date' => 'required|date_format:Y-m-d H:i:s',
            'reason' => 'nullable',
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