<?php

namespace App\Requests;

use App\Entities\Consult;
use Pearl\RequestValidate\RequestAbstract;

class ConsultRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $request = app('request');
        $errorRules = [];

        $rules = [];

        // Edited Rules
        if ($this->route('id')) {

        } else {

            $rules = [
                'patient_id' => 'required',
                'provider_id' => 'required',
                'class_id' => 'required',
                'consult_type' => 'required',
                'consult_slot_type' => 'required',
                'consult_date_time' => 'required',
                'speciality' => 'required'
            ];

            if (empty($errorRules)) {
                $booked = Consult::bookedSlotChecked($request);
                if (empty($booked['status'])) {
                    $errorRules['booked'] = 'required';
                    return $errorRules;
                }
            }
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
            'booked.required' => 'Slots are booked already!'
        ];
    }
}