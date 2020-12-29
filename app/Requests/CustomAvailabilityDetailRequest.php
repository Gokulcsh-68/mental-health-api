<?php

namespace App\Requests;

use App\Entities\CustomAvailabilityDetail;
use Pearl\RequestValidate\RequestAbstract;

class CustomAvailabilityDetailRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        
        $request = app('request');
        $errorRules = [
            "from_date" => "required",
            "to_date" => "required",
        ];

        // Edited Rules
        if ($this->route('id')) {

            $available = CustomAvailabilityDetail::editCustomAvailabilityCheck($request);

            if ($available['error_type'] == 'Already') {
                $errorRules['date'] = 'required';
            } elseif ($available['error_type'] == 'Unauthorized') {
                $errorRules['authorized'] = 'required';
            }
        } else {
            $available = CustomAvailabilityDetail::customAvailabilityCheck($request);
            
            if ($available['error_type'] == 'Already') {
                $errorRules['date'] = 'required';
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
            'from_date.required' => 'Frome date required',
            'to_date.required' => 'To date required',
            'date.required' => 'Date already exists',
            'authorized.required' => 'Unauthorized access!',
        ];
    }
}