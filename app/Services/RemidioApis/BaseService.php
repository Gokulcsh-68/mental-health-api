<?php

namespace App\Services\RemidioApis;

use App\Utils\Api;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BaseService
{
	use Api;

	public $_base_url;
	private $_client_id;
	private $_client_secret;
	private $_client_name;
	private $_client_identification_token;

	private $_token = null;
	private $_client_auth_token = null;

	public function __construct()
	{
		$this->_base_url = env('REMIDIO_API_ENDPOINT', false);
		$this->_client_id = env('REMIDIO_CLIENT_ID', false);
		$this->_client_secret = env('REMIDIO_CLIENT_SECRET', false);
		$this->_client_name = env('REMIDIO_API_CLIENT_NAME', false);
		$this->_client_identification_token = env('REMIDIO_API_CLIENT_IDENTIFICATION_TOKEN', false);
	}

	public function getToken()
	{
		// Cache::forget('REMIDIO_API_TOKEN');
		return Cache::remember('REMIDIO_API_TOKEN', $minutes = 10, function() {
			$this->authenticate();
			return $this->_token;
    	});
	}

	public function getClientAuthToken()
	{
		// Cache::forget('REMIDIO_API_CLIENT_AUTH_TOKEN');
		return Cache::rememberForever('REMIDIO_API_CLIENT_AUTH_TOKEN', function() {
			$this->createClientAuthToken();
			return $this->_client_auth_token;
    	});
	}

	public function getHeaders(): array
	{
		return [
			'Accept'        => 'application/json',
			'Content-Type' => 'application/json',
			'clientName' => $this->_client_name,
			'clientIdentificationToken' => $this->_client_identification_token,
		];
	}

	public function getHeadersWithClientToken(): array
	{
		return [
			'Accept'        => 'application/json',
			'Content-Type' => 'application/json',
			'clientName' => $this->_client_name,
			'clientIdentificationToken' => $this->_client_identification_token,
			'clientAuthToken' => $this->getClientAuthToken(),
		];
	}

	private function authenticate()
	{
		if (!$this->_base_url || !$this->_client_id || !$this->_client_secret) {
			$error_message = 'Please check values are assigned to following variables in env file. The variables are REMIDIO_API_ENDPOINT, 
			REMIDIO_CLIENT_ID, REMIDIO_CLIENT_SECRET';
			throw new \Exception($error_message);
		}

		$url = $this->_base_url . '/api/user/loginUser';

		$headers = $this->getHeaders();

		$form_data = [
			'emailAddress' => $this->_client_id,
			'password' => $this->_client_secret,
		];

		$options = [
			'headers' => $headers,
			'body' => json_encode($form_data),
		];

		try {
			$this->apiCall($url, $options, "POST");

			$api_response = $this->toGuzzleArray();
			return $this->_token = $api_response['data'];
		} catch (\Exception $e) {
			$error = [
				'code' => $this->error->getCode(),
				'message' => $this->error->getMessage(),
				// 'trace' => $e->getTraceAsString(),
			];
			Log::error('REMIDIO API ERROR ------- ', $error);
		}

		return false;
	}

	private function createClientAuthToken()
	{
		$url = $this->_base_url . '/api/gateway/getAuthToken';

		$headers = $this->getHeaders() + ['Authorization' => 'Bearer ' . $this->getToken()];

		$options = [
			'headers' => $headers,
		];

		try {
			$this->apiCall($url, $options);

			$api_response = $this->toGuzzleArray();
			return $this->_client_auth_token = $api_response['data'];
		} catch (\Exception $e) {
			$error = [
				'code' => $this->error->getCode(),
				'message' => $this->error->getMessage(),
				// 'trace' => $e->getTraceAsString(),
			];
			Log::error('REMIDIO API ERROR ------- ', $error);
		}

		return false;
	}
}
