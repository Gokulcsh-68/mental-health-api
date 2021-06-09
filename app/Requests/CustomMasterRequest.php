<?php

namespace App\Requests;

use App\Entities\Provider;
use Pearl\RequestValidate\RequestAbstract;
use Illuminate\Support\Facades\Log;

class CustomMasterRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $request = app('request');

        // $user_id = $request->user()->id;

        $provider_details = Provider::where('user_id', $request->user()->id)->first();

        $unique_id = null;

        if(!empty($provider_details)){
            $unique_id = (!empty($provider_details['primary_provider_id']) ? $provider_details['primary_provider_id'] : $provider_details['id']);
        }

        Log::info($request['master_type_slug']);

        $return = [
            'provider_id' => 'nullable',
            'master_type_slug' => 'required',
            'name' => 'required|unique:custom_masters,name,null,id,provider_id,' . $unique_id.',master_type_slug,'.$request['master_type_slug'],
             'slug' => 'required|unique:custom_masters,slug,null,id,provider_id,' . $unique_id.',master_type_slug,'.$request['master_type_slug'],
            'attributes' => 'nullable',
            'is_active' => 'required'
        ];

        return $return;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.unique' => 'name already available',
            'slug.unique' => 'slug already available'
        ];
    }
}