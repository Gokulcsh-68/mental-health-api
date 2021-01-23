<?php

namespace App\Requests;

use Pearl\RequestValidate\RequestAbstract;

class ActivityWellnessRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'act_catagory' => 'nullable',
            'act_date' => 'required',
            'act_duration' => 'nullable',
            'act_intake' => 'nullable',
            'act_intensity' => 'nullable',
            'act_time' => 'nullable',
            'act_type' => 'nullable',
            'patient_id' => 'required',
            'status' => 'required',
            'unit' => 'nullable'
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