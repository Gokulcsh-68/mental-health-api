<?php

namespace App\Requests;

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
        return [
            'unique_id' => 'required',
            'patient_in_room' => 'required',
            'provider_in_room' => 'required',
            'patient_id' => 'required',
            'provider_id' => 'required',
            'hospital_id' => 'required',
            'consult_type' => 'required',
            'consult_slot_type' => 'required',
            'consult_date_time' => 'required',
            'consult_duration' => 'required',
            'speciality' => 'required',
            'unit' => 'required',
            'slots' => 'required',
            'started_date_time' => 'required',
            'ended_date_time' => 'required',
            'consent' => 'nullable',
            'camera_id' => 'nullable',
            'consult_notes' => 'required',
            'Addendum_notes' => 'required',
            'reason_for_consult' => 'required',
            'status' => 'required'
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