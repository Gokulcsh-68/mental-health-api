<?php

namespace App\Services\CureselectApis;

use Illuminate\Support\Facades\Cache;
// use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;

class BaseService {
	public $_base_url;
	private $_client_id;
	private $_client_secret;

	private $_token = null;

	public function __construct()
	{
		$this->_base_url = env('CURESELECT_API_ENDPOINT', false);
		$this->_client_id = env('CURESELECT_API_CLIENT_ID', false);
		$this->_client_secret = env('CURESELECT_API_CLIENT_SECRET', false);
	}

	public function getToken()
	{
		Cache::forget('CURESELECT_API_TOKEN');
		return Cache::remember('CURESELECT_API_TOKEN', $minutes = 1380, function() {
			$this->authenticate();
			return $this->_token;
    	});
	}

	private function authenticate()
	{
		if(!$this->_base_url || !$this->_client_id || !$this->_client_secret) {
			$error_message = 'Please check values are assigned to following variables in env file. The variables are CURESELECT_API_ENDPOINT, CURESELECT_API_CLIENT_ID, CURESELECT_API_CLIENT_SECRET';
			throw new \Exception($error_message);
		}

		$url = $this->_base_url . 'v1/users/authenticate/api';

      	$data = array(
		    'client_id' => $this->_client_id,
		    'client_secret' => $this->_client_secret
		);
		 
		$payload = json_encode($data);

      	$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    'Content-Type: application/json',
		    'Content-Length: ' . strlen($payload))
		);
		$result = curl_exec($ch);

		$header = $this->get_headers_from_curl_response($result);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$body = substr($result, $header_size);

		curl_close($ch);

		$res = json_decode($body);
	    $resdata = json_decode(json_encode($res),true);


	    try {
	    	if($resdata['code'] == '200') {
		    	return $this->_token = $header['Authorization'];
		    }
	    } catch(\Exception $e) {
	    	Log::error('CURESELECT API ERROR ------- ', ['errorDetails' => $e->getMessage()]);
	    }

	    return false;
	}

	public function get_headers_from_curl_response($response)
	{
	    $headers = array();

	    $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));

	    foreach (explode("\r\n", $header_text) as $i => $line) {
	    	if ($i === 0) {
	            $headers['http_code'] = $line;
	    	}
	        else
	        {
	            list ($key, $value) = explode(': ', $line);
	            $headers[$key] = $value;
	        }
	    }
	    return $headers;
	}
}