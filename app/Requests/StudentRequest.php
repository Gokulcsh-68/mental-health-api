<?php

namespace App\Requests;

use App\Entities\Student;
use Pearl\RequestValidate\RequestAbstract;

class StudentRequest extends RequestAbstract
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
            'class_id' => 'required',
            'enroll_number' => 'required',
            'additional_info' => 'nullable'
        ];

        $rules['user'] = (new UserRequest())->rules();

        if ($this->route('id')) {

            unset($rules['user']['role_id']);
            unset($rules['user']['timezone_id']);
            unset($rules['user']['address']);
            unset($rules['user']['is_2fa']);
            unset($rules['user']['is_active']);

            $Student = Student::where('id', $this->route('id'))->first();

            // Edited Rules
            $rules['user']['username'] = 'required|unique:users,username,' . $Student->user_id . ',id,role_id,' . $Student->user->role_id;

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
            //
        ];
    }
}
