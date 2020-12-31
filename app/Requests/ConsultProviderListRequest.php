<?php

namespace App\Requests;

use Pearl\RequestValidate\RequestAbstract;

class ConsultProviderListRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'consult_date' => 'required_without_all:provider_id',
            'provider_id' => 'required_without_all:consult_date',
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
