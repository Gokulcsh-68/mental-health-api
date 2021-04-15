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
        
        return [
            'from_date' => 'required',
            'to_date' => 'required',
            'provider_id' => 'nullable',
            'timing' => 'nullable'
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