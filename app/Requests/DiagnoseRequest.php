<?php

namespace App\Requests;

use Pearl\RequestValidate\RequestAbstract;

class DiagnoseRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'patient_id' => 'required|integer|exists:patients,id', // Pet ID (if applicable)
            'description' => 'required|string|min:10',
            'operation'   => 'nullable|string',
            'lang'        => 'nullable|string|in:en,fr,es,de,it', // Add supported languages
            'timezone'    => 'nullable|string',
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
            'description.required' => 'The description is required.',
            'description.min'      => 'The description must be at least 10 characters.',
            'lang.in'              => 'The selected language is not supported.',
            'timezone.timezone'    => 'The timezone format is invalid.',
        ];
    }
}
