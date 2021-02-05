<?php

namespace App\Requests;

use Pearl\RequestValidate\RequestAbstract;

class ProviderUnavailabilityRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'available_status' => 'nullable',
            'available_type' => 'nullable',
            'from_date' => 'required',
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