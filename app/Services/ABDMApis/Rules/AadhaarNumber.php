<?php

namespace App\Services\ABDMApis\Rules;

use Illuminate\Contracts\Validation\Rule;

class AadhaarNumber implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Check if the value is a 12-digit number
        return preg_match('/^\d{12}$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a valid 12-digit Aadhaar number.';
    }
}