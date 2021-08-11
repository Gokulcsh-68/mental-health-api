<?php

namespace App\Services\CureselectApis;

use App\Services\CureselectApis\BaseService;
use App\Utils\Api;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PeripheralApiService extends BaseService {

	use Api;

	/**
	 * @var string
	 */
	protected $endpoint_url;

	public function __construct()
	{
		parent::__construct();
		$this->endpoint_url = $this->_base_url . 'v1/resource/peripheral-users';
	}

	protected function peripheralUserCreateValidate($payload) {
		$validation = Validator::make($payload, [
    		'username' => 'required|string',
    		'password' => 'required',
            'ref_number' => 'required|integer',
        ]);

        if($validation->fails()) {
        	throw ValidationException::withMessages($validation->errors()->all());
        }
	}

	public function create($payload)
    {
    	$this->peripheralUserCreateValidate($payload);
    	try {

    		$url = $this->endpoint_url;

    		$form_data = $payload;

    		$headers = [
			    'Authorization' => 'Bearer ' . $this->getToken(),
			    'Accept'        => 'application/json',
			    'Content-Type' => 'application/json',
			];

    		$options = [
    			'headers' => $headers,
    			'body' => json_encode($form_data),
    		];

    		$this->apiCall($url, $options, $method = "POST");
    		$api_response = $this->toGuzzleArray();

    		$response = ['peripheral_users_id' => $api_response['data']['peripheral_users']['id']];

    	} 
    	catch(\Exception $e) {
			Log::error('Peripheral User API ERROR ------- ', ['errorDetails' => $e->getMessage()]);

			$api_response = $this->toGuzzleArray();

			if($api_response) {
				if($api_response['code'] == 422) {
					throw ValidationException::withMessages($api_response['data']);
				}
			}

			throw new BadRequestHttpException($e->getMessage(), $e);
			$response = [ $e->getMessage() ];
		}

        return $response;
    }

    public function get($ref_number)
    {
    	try {

    		$url = $this->endpoint_url . '/first?ref_number='.$ref_number;

    		$headers = [
			    'Authorization' => 'Bearer ' . $this->getToken(),
			    'Accept'        => 'application/json',
			    'Content-Type' => 'application/json',
			];

    		$options = [
    			'headers' => $headers,
    		];

    		$this->apiCall($url, $options);
    		$api_response = $this->toGuzzleArray();

    		$response = $api_response['data']['peripheral_users'];

    	} 
    	catch(\Exception $e) {
			$response = [ $e->getMessage() ];
		}

        return $response;
    }

    public function patch($id, $payload)
    {
    	try {

    		$url = $this->endpoint_url.'/'.$id;


    		$form_data = $payload;

    		$headers = [
			    'Authorization' => 'Bearer ' . $this->getToken(),        
			    'Accept'        => 'application/json',
			    'Content-Type' => 'application/json',
			];

    		$options = [
    			'headers' => $headers,
    			'body' => json_encode($form_data),
    		];

    		$this->apiCall($url, $options, $method = "patch");
    		$api_response = $this->toGuzzleArray();
    		

    		$response = ['consult_id' => $api_response['data']['consults']['id']];

    	} 
    	catch(\Exception $e) {
			$response = [ $e->getMessage() ];
		}

        return $response;
    }
}