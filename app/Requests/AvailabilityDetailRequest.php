<?php

namespace App\Requests;

use App\Entities\AvailabilityDetail;
use Pearl\RequestValidate\RequestAbstract;

class AvailabilityDetailRequest extends RequestAbstract
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

        // Edited Rules
        if ($this->route('id')) {

            if (empty($request->timing)) {
                $errorRules['timing'] = 'required';   
            }

            if (empty($errorRules)) {
                $available = AvailabilityDetail::editAvailabilityCheck($request);
                if ($available['error_type'] == 'Already') {
                    $errorRules['day'] = 'required';
                } elseif ($available['error_type'] == 'Unauthorized') {
                    $errorRules['authorized'] = 'required';
                }
            }

        } else {
            $available = AvailabilityDetail::availabilityCheck($request);
            
            if ($available['error_type'] == 'Already') {
                $errorRules['day'] = 'required';
            } elseif ($available['error_type'] == 'Unauthorized') {
                $errorRules['authorized'] = 'required';
            }
        }

        return $errorRules;

    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'availability_details.required' => 'Availability detail required',
            'timing.required' => 'Timing required',
            'day.required' => 'Day already exists!',
            'authorized.required' => 'Unauthorized access!',
        ];
    }
}


