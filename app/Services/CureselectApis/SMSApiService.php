<?php

namespace App\Services\CureselectApis;

use App\Services\CureselectApis\BaseService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;

class SMSApiService extends BaseService {

	/**
	 * @var string
	 */
	protected $endpoint_url;

	public function __construct()
	{
		parent::__construct();
		$this->endpoint_url = $this->_base_url . 'v1/communication/send/sms';
	}

	/**
	 * Send SMS
	 * 
	 * @param array $to 
	 * @param string $subject 
	 * @param string $message 
	 * @param string $iso_code 
	 * @return mixed
	 */
	public function send(string $mobile, string $isd_code, string $message, string $iso_code)
    {
    	try {
    		$authorization = "Authorization: Bearer " . $this->getToken();

	        $data = array(
	            'country_code' => $iso_code,
	            'message' => $message,
	            'phone' => [
                    'number' => $mobile,
                    'code' => $isd_code
                ]
	        );

	        $payload = json_encode($data);

	        $ch = curl_init($this->endpoint_url);
	        curl_setopt($ch, CURLOPT_HEADER, 1);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	        curl_setopt($ch, CURLOPT_POST, true);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	            'Content-Type: application/json',
	            $authorization,
	            'Content-Length: ' . strlen($payload))
	        );

	        $result = curl_exec($ch);

	        $header = $this->get_headers_from_curl_response($result);
	        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	        $body = substr($result, $header_size);

	        curl_close($ch);

	        $response = ['header' => $header, 'body' => json_decode($body)];
    	} 
    	catch(\Exception $e) {
			Log::error('Cureselect SMS API ERROR ------- ', ['errorDetails' => $e->getMessage()]);
			$response = [ $e->getMessage() ];
		}

        return $response;
    }
}