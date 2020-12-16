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

        $rules = [
            #'name' => 'required|unique:,school_id' . $request->get('staff')->school_id,
            'name' => 'required|unique:school_classes,name,null,id,school_id,' . $request->get('staff')->school_id,
            // 'school_id' => 'required',
            'staff_id' => 'required',
        ];

        if ($this->route('id')) {

            $rules['name'] = 'required|unique:school_classes,name,' . $this->route('id') . ',id,school_id,' . $request->get('staff')->school_id;
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
            "name.unique" => "Class name is already taken",
        ];
    }
}
