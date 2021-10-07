<?php

namespace App\Services\CureselectApis;

use App\Services\CureselectApis\BaseService;
use App\Utils\Api;
use Illuminate\Http\Request;
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
	protected $teleconsult_url;

	public static $default_virtual_service_provider = 'tokbox';

	public function __construct()
	{
		parent::__construct();
		$this->endpoint_url = $this->_base_url . 'v1/resource/consults';
		$this->teleconsult_url = $this->_base_url . 'v1/consults/token-validate';
	}

	protected function consultCreateValidate($payload) {
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
			'patient.email' => 'required_if:patient.phone,',
			'patient.phone' => 'required_if:patient.email,',
			'patient.gender' => 'nullable',
			'patient.profile_pic' => 'nullable',
        ]);

        if($validation->fails()) {
        	throw ValidationException::withMessages($validation->errors()->all());
        }
	}

	private function processUserData($data, $role) {
		$user = [
			'role' => $role,
            'ref_number' => (string) $data['id'],
            'participant_info' => [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'gender' => $data['gender'],
                'profile_pic' => $data['profile_pic']
            ]
		];

		if(isset($data['additional_info'])) {
			$user['participant_info']['additional_info'] = $data['additional_info'];
		}

		return $user;
	}

	public function create($payload)
    {
    	$this->consultCreateValidate($payload);

	    $provider_data = $payload['provider'];
	    $patient_data = $payload['patient'];
	    $consult_type = $payload['consult_type'];
	    $consult_reason = $payload['consult_reason'];
	    $consult_date_time = $payload['consult_date_time'];
	    $service_provider = $payload['service_provider'] ?? self::$default_virtual_service_provider;
	    $consult_additional_info = $payload['additional_info'] ?? null;

    	try {

    		$url = $this->endpoint_url;

    		$form_data = [
    			'scheduled_at' => date('Y-m-d H:i:s', strtotime($consult_date_time)),
    			"consult_type" => $consult_type,
			    'reason' => $consult_reason,
			    'virtual_service_provider' => $service_provider,
			    'participants' => [
			    	$this->processUserData($provider_data, 'publisher'), 
			    	$this->processUserData($patient_data, 'subscriber'),
			    ],
			    'additional_info' => $consult_additional_info,
    		];

    		if(isset($payload['consult_status'])) {
    			$form_data['consult_status'] = $payload['consult_status'];
    		}

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
			Log::error('Cureselect Teleconsult API ERROR ------- ', ['errorDetails' => $e->getMessage()]);

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

	public function fetch($params = [], $per_page = 10, $page_number = 1)
	{
		// dd($params);
		$params = array_only($params, ['participant_ref_number', 'consult_status_id', 'consult_type', 'consult_status', 'scheduled_from_date', 'scheduled_to_date', 'consult_id']);

		$validation = Validator::make($params, [
    		'scheduled_from_date' => 'nullable|date_format:"Y-m-d H:i:s"',
    		'scheduled_to_date' => 'required_unless:scheduled_from_date,null|date_format:"Y-m-d H:i:s"',
			'consult_type' => [
				'nullable',
				Rule::in(['virtual', 'home', 'clinic'])
			],
			'consult_status' => 'nullable',
        ]);

        if($validation->fails()) {
        	throw ValidationException::withMessages($validation->errors()->all());
        }

		$params = $params + ['limit' => $per_page, 'page' => $page_number];

		$headers = [
			'Authorization' => 'Bearer ' . $this->getToken(),        
		];

		$options = [
			'headers' => $headers,
			'query' => $params
		];

		

		try {
			$url = $this->endpoint_url;
			$this->apiCall($url, $options, $method = "GET");
			$response = $this->toGuzzleArray();
		}
		catch(\Exception $e) {
			Log::error('Cureselect Teleconsult API ERROR ------- ', ['errorDetails' => $e->getMessage()]);

			throw new BadRequestHttpException($e->getMessage(), $e);
			$response = [ $e->getMessage() ];
		}

		return $response;
	}


	public function fetchById($params = [], $consult_id)
	{
		$params = array_only($params, ['participant_ref_number', 'consult_status_id', 'consult_type', 'consult_status', 'scheduled_from_date', 'scheduled_to_date', 'consult_id']);

		$validation = Validator::make($params, [
    		'scheduled_from_date' => 'nullable|date_format:"Y-m-d H:i:s"',
    		'scheduled_to_date' => 'required_unless:scheduled_from_date,null|date_format:"Y-m-d H:i:s"',
			'consult_type' => [
				'nullable',
				Rule::in(['virtual', 'home', 'clinic'])
			],
			'consult_status' => 'nullable',
        ]);

        if($validation->fails()) {
        	throw ValidationException::withMessages($validation->errors()->all());
        }

		
		$headers = [
			'Authorization' => 'Bearer ' . $this->getToken(),        
		];

		$options = [
			'headers' => $headers,
			'query' => $params
		];
	

		try {
			$url = $this->endpoint_url.'/'.$consult_id;
			$this->apiCall($url, $options, $method = "GET");
			$response = $this->toGuzzleArray();
		}
		catch(\Exception $e) {
			Log::error('Cureselect Teleconsult API ERROR ------- ', ['errorDetails' => $e->getMessage()]);

			throw new BadRequestHttpException($e->getMessage(), $e);
			$response = [ $e->getMessage() ];
		}

		return $response;
	}

	public function patch(Request $request)
    {


    	try {

    		$url = $this->endpoint_url.'/'.$request->get('id');


    		$form_data = [
    			"consult_status" => $request->get('status'),
    			"additional_info" => $request->get('additional_info') ?? [],
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

    		$this->apiCall($url, $options, $method = "patch");
    		$api_response = $this->toGuzzleArray();
    		

    		$response = ['consult_id' => $api_response['data']['consults']['id']];

    	} 
    	catch(\Exception $e) {
			Log::error('Cureselect Teleconsult API ERROR ------- ', ['errorDetails' => $e->getMessage()]);

			throw new BadRequestHttpException($e->getMessage(), $e);
			$response = [ $e->getMessage() ];
		}

        return $response;
    }




	public function consultDetails($request)
	{
		
		$headers = [
			'Authorization' => 'Bearer ' . $this->getToken(),     
		];

		$options = [
			'headers' => $headers
		];

		

		try {
			$url = $this->teleconsult_url.'?token='.$request->get('token');
			$this->apiCall($url, $options, $method = "GET");
			$response = $this->toGuzzleArray();
		}
		catch(\Exception $e) {
			Log::error('Cureselect Teleconsult API ERROR ------- ', ['errorDetails' => $e->getMessage()]);

			throw new BadRequestHttpException($e->getMessage(), $e);
			$response = [ $e->getMessage() ];
		}

		return $response;
	}
}