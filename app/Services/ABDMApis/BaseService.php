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
		return Cache::remember('ABDM_API_AUTH_TOKEN', $minutes = 30, function () {
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

public function getPublicKey(): void
{
    try {
        $url = $this->_urls['get-public-key'];
        
        // Use the same authenticated headers as other endpoints
        $options = [
            'headers' => $this->getHeadersWithToken(),
        ];
        
        Log::info('ABDM Public Key Request', [
            'url' => $url,
            'headers' => array_merge(
                $options['headers'],
                ['Authorization' => 'Bearer ' . substr($this->getToken(), 0, 20) . '...'] // Log partial token
            )
        ]);
        
        $this->apiCall($url, $options, 'GET');
        $responseData = $this->toGuzzleArray();
        
        Log::info('ABDM Public Key Response', [
            'response' => $responseData,
            'url' => $url
        ]);
        
        // Get the base64 encoded public key
        $publicKeyBase64 = $responseData['publicKey'] ?? null;
        
        if (empty($publicKeyBase64)) {
            throw new \Exception('Public key not found in response: ' . json_encode($responseData));
        }
        
        // Convert base64 to PEM format
        $publicKeyPem = $this->convertBase64ToPem($publicKeyBase64);
        
        // Store the PEM formatted public key
        Storage::put('abdm-public-key', $publicKeyPem);
        
        Log::info('ABDM Public Key stored successfully', [
            'key_length' => strlen($publicKeyPem),
            'encryption_algorithm' => $responseData['encryptionAlgorithm'] ?? 'unknown'
        ]);
        
    } catch (\Exception $e) {
        Log::error('ABDM PUBLIC KEY FETCH FAILED', [
            'message' => $e->getMessage(),
            'url' => $url ?? 'unknown',
            'code' => $e->getCode(),
        ]);
        throw $e;
    }
}

/**
 * Convert base64 encoded public key to PEM format
 */
private function convertBase64ToPem(string $base64Key): string
{
    $pem = "-----BEGIN PUBLIC KEY-----\n";
    $pem .= chunk_split($base64Key, 64, "\n");
    $pem .= "-----END PUBLIC KEY-----";
    return $pem;
}

	private function authenticate()
	{
		if (!$this->_client_id || !$this->_client_secret) {
			throw new \Exception('Missing ABDM_API_CLIENT_ID or ABDM_API_CLIENT_SECRET in .env');
		}

		$url = 'https://dev.abdm.gov.in/api/hiecm/gateway/v3/sessions';

		$headers = $this->getHeaders(); // Accept + Content-Type
		$headers['REQUEST-ID'] = (string) Str::uuid();
		$headers['TIMESTAMP'] = Carbon::now()->format('Y-m-d\TH:i:s.v\Z');
		$headers['X-CM-ID'] = 'sbx';  // ← Critical for sandbox!

		$form_data = [
			'clientId'     => $this->_client_id,
			'clientSecret' => $this->_client_secret,
			'grantType'    => 'client_credentials',
		];

		$options = [
			'headers' => $headers,
			'body'    => json_encode($form_data),
		];

		try {
			$this->apiCall($url, $options, "POST");
			$api_response = $this->toGuzzleArray();
			$this->_token = $api_response['accessToken'] ?? null;

			if (empty($this->_token)) {
				throw new \Exception('No access token in response');
			}

			Log::info('ABDM token obtained successfully', ['token_length' => strlen($this->_token)]);
		} catch (\Exception $e) {
			$error = [
				'code'    => $e->getCode() ?: 500,
				'message' => $e->getMessage(),
			];
			Log::error('ABDM API LOGIN ERROR', $error);
			return false;
		}

		return true;
	}

protected function encryptData($string): string
{
    $keyPath = Storage::path('abdm-public-key');
    
    // Fetch key if it doesn't exist
    if (!Storage::exists('abdm-public-key')) {
        $this->getPublicKey();
    }
    
    // Use OpenSSL for encryption with OAEP padding (as required by ABDM)
    $publicKeyContent = Storage::get('abdm-public-key');
    $publicKey = openssl_pkey_get_public($publicKeyContent);
    
    if (!$publicKey) {
        throw new \Exception('Failed to load public key: ' . openssl_error_string());
    }
    
    $encrypted = '';
    $result = openssl_public_encrypt(
        $string,
        $encrypted,
        $publicKey,
        OPENSSL_PKCS1_OAEP_PADDING
    );
    
    if (!$result) {
        throw new \Exception('Encryption failed: ' . openssl_error_string());
    }
    
    openssl_free_key($publicKey);
    
    return base64_encode($encrypted);
}
}
