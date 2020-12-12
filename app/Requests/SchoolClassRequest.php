<?php

namespace App\Requests;

use Pearl\RequestValidate\RequestAbstract;

class SchoolClassRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $request = app('request');

        return [
            #'name' => 'required|unique:,school_id' . $request->get('staff')->school_id,
            'name' => 'required|unique:school_classes,name,null,id,school_id,' . $request->get('staff')->school_id,
            // 'school_id' => 'required',
            'staff_id' => 'required',
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
