<?php

namespace App\Services\ABDMApis;

use App\Utils\Api;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BaseService
{
	use Api;

	// public $_base_url;
	private $_client_id;
	private $_client_secret;

	private $_token = null;
	protected $_urls = [];

	public function __construct()
	{
		// $this->_base_url = env('CURESELECT_API_ENDPOINT', false);
		$this->_client_id = env('ABDM_API_CLIENT_ID', false);
		$this->_client_secret = env('ABDM_API_CLIENT_SECRET', false);
		$this->_urls = config('abdm');
	}

	public function getToken()
	{
		// Cache::forget('ABDM_API_AUTH_TOKEN');
		return Cache::remember('ABDM_API_AUTH_TOKEN', $minutes = 30, function() {
			$this->authenticate();
			return $this->_token;
    	});
	}

	public function getHeaders(): array
	{
		return [
			'Accept'        => 'application/json',
			'Content-Type' => 'application/json',
		];
	}

	public function getHeadersWithToken(): array
	{
		return $this->getHeaders() + [
			'Authorization' => 'Bearer ' . $this->getToken(),
			'REQUEST-ID' => (string) Str::uuid(),
			'TIMESTAMP' => Carbon::now()->format('Y-m-d\TH:i:s.v\Z'),
		];
	}

	public function getPublicKey() : void {
		try {
			$url = $this->_urls['get-public-key'];
			$this->apiCall($url);
			Storage::put('abdm-public-key', $this->apiResponse);
		} catch (\Exception $e) {
			$error = [
				'code' => $this->error->getCode(),
				'message' => $this->error->getMessage(),
			];
			Log::error('ABDM API PUBLIC KEY GET ERROR ------- ', $error);
		}
	}

	private function authenticate()
	{
		if (!$this->_client_id || !$this->_client_secret) {
			$error_message = 'Please check values are assigned to following variables in env file. The variables are  
			ABDM_API_CLIENT_ID, ABDM_API_CLIENT_SECRET';
			throw new \Exception($error_message);
		}

		$url = 'https://dev.abdm.gov.in/gateway/v0.5/sessions';

		$headers = $this->getHeaders();

		$form_data = [
			'clientId' => $this->_client_id,
			'clientSecret' => $this->_client_secret,
			'grantType' => 'client_credentials',
		];

		$options = [
			'headers' => $headers,
			'body' => json_encode($form_data),
		];

		try {
			$this->apiCall($url, $options, "POST");

			$api_response = $this->toGuzzleArray();
			return $this->_token = $api_response['accessToken'];
		} catch (\Exception $e) {
			$error = [
				'code' => $this->error->getCode(),
				'message' => $this->error->getMessage(),
				// 'trace' => $e->getTraceAsString(),
			];
			Log::error('ABDM API LOGIN ERROR ------- ', $error);
		}

		return false;
	}

	protected function encryptData($string): string {
		// $public_key = Storage::put('abdm-public-key', $this->apiResponse);
		$publicKey = \Spatie\Crypto\Rsa\PublicKey::fromFile(Storage::path('abdm-public-key'));
		$encryptedData = $publicKey->encrypt($string);
		return (base64_encode($encryptedData));
	}
}
