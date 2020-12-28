<?php

namespace App\Requests;

use App\Entities\Staff;
use Pearl\RequestValidate\RequestAbstract;

class StaffRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            // 'user_id' => 'required',
            // 'school_id' => 'required',
            // 'is_admin'  => 'required'
        ];

        $error_rules = [];

        $rules['user'] = (new UserRequest())->rules();

        if ($this->route('id')) {
            unset($rules['user']['timezone_id']);
            unset($rules['user']['address']);
            unset($rules['user']['is_2fa']);
            unset($rules['user']['is_active']);

            $staff = Staff::where('id', $this->route('id'))->first();
            $staff_request = app('request')->attributes->get('staff');

            if (empty($staff->user_id)) {
                $error_rules['unknown_staff'] = 'required';
                return $error_rules;
            }

            if (!$staff_request->is_admin && $staff->user_id != auth()->user()->id) {
                $error_rules['staff_auth'] = 'required';
                return $error_rules;
            }

            // Edited Rules
            $rules['user']['username'] = 'required|unique:users,username,' . $staff->user_id . ',id,role_id,' . $staff->user->role_id;

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
            'staff_auth.required' => 'You are not allowed to modify the data.',
            'unknown_staff.required' => 'Unauthorized action',
        ];
    }
}
