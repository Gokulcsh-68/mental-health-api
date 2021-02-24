<?php

namespace App\Services\CureselectApis;

use App\Services\CureselectApis\BaseService;
use App\Utils\Api;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TeleConsultApiService extends BaseService {

	use Api;

	/**
	 * @var string
	 */
	protected $endpoint_url;

	public static $default_virtual_service_provider = 'tokbox';

	public function __construct()
	{
		parent::__construct();
		$this->endpoint_url = $this->_base_url . 'v1/resource/consults';
	}

	public function create($payload)
    {
    	$validation = Validator::make($payload, [
    		'consult_date_time' => 'required|date_format:"Y-m-d H:i:s"',
			'consult_reason' => 'required',
			'consult_type' => [
				'required',
				Rule::in(['virtual', 'home', 'clinic'])
			],
			'service_provider' => [
				'nullable',
				Rule::in(['tokbox', 'jitsi'])
			],
			
			'provider.id' => 'required',
			'provider.name' => 'required',
			'provider.email' => 'required',
			'provider.phone' => 'required',
			'provider.gender' => 'nullable',
			'provider.profile_pic' => 'nullable',
			
			'patient.id' => 'required',
			'patient.name' => 'required',
			'patient.email' => 'required',
			'patient.phone' => 'required',
			'patient.gender' => 'nullable',
			'patient.profile_pic' => 'nullable',
        ]);

        if($validation->fails()) {
        	throw ValidationException::withMessages($validation->errors()->all());
        }

	    $provider_data = $payload['provider'];
	    $patient_data = $payload['patient'];
	    $consult_type = $payload['consult_type'];
	    $consult_reason = $payload['consult_reason'];
	    $consult_date_time = $payload['consult_date_time'];
	    $service_provider = $payload['service_provider'] ?? self::$default_virtual_service_provider;

    	try {

    		$url = $this->endpoint_url;

    		$provider = [
    			'role' => 'publisher',
	            'ref_number' => $provider_data['id'],
	            'participant_info' => [
	                'name' => $provider_data['name'],
	                'email' => $provider_data['email'],
	                'phone' => $provider_data['phone'],
	                'gender' => $provider_data['gender'],
	                'profile_pic' => $provider_data['profile_pic']
	            ]
    		];

    		$patient = [
    			'role' => 'subscriber',
	            'ref_number' => $patient_data['id'],
	            'participant_info' => [
	                'name' => $patient_data['name'],
	                'email' => $patient_data['email'],
	                'phone' => $patient_data['phone'],
	                'gender' => $patient_data['gender'],
	                'profile_pic' => $patient_data['profile_pic']
	            ]
    		];

    		$form_data = [
    			'scheduled_at' => date('Y-m-d H:i:s', strtotime($consult_date_time)),
    			"consult_type" => $consult_type,
			    'reason' => $consult_reason,
			    'virtual_service_provider' => $service_provider,
			    'participants' => [$provider, $patient],
    		];

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

    		$response = ['consult_id' => $api_response['data']['consults']['id']];

    	} 
    	catch(\Exception $e) {
			Log::error('Cureselect Email API ERROR ------- ', ['errorDetails' => $e->getMessage()]);

			throw BadRequestHttpException::withMessages($e->getMessage());
			$response = [ $e->getMessage() ];
		}

        return $response;
    }
}