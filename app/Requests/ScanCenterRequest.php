<?php

namespace App\Requests;

use App\Entities\ScanCenter;
use Pearl\RequestValidate\RequestAbstract;

class ScanCenterRequest extends RequestAbstract
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $error_rules = [];

        $rules['user'] = (new UserRequest())->rules();

        if ($this->route('id')) {
            unset($rules['user']['timezone_id']);
            unset($rules['user']['address']);
            unset($rules['user']['is_2fa']);
            unset($rules['user']['is_active']);

            $scan_centers = ScanCenter::where('id', $this->route('id'))->first();
            $scan_centers_request = app('request')->attributes->get('scan_centers');

            if (empty($scan_centers->user_id)) {
                $error_rules['unknown_scan_centers'] = 'required';
                return $error_rules;
            }   

            if($scan_centers_request){
                if (!$scan_centers_request->is_admin && $scan_centers->user_id != auth()->user()->id) {
                    $error_rules['scan_centers_auth'] = 'required';
                    return $error_rules;
                }

            }

            // Edited Rules
            $rules['user']['username'] = 'required|unique:users,username,' . $scan_centers->user_id . ',id,role_id,' . $scan_centers->user->role_id;


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