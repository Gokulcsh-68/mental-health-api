<?php

namespace App\Utils;

use App\Traits\Api;
use Twilio\Rest\Client;
use Illuminate\Http\JsonResponse;

class Communication
{
	use Api;

	public function sendEmail($templateData)
	{
		$config = config('api.elasticMail');
	  	$param['api_key'] = $config['apiKey'];
	  	$param['username'] = $config['username']; 
	  	$param['from'] = $config['from'];
	  	$param['from_name'] = $config['fromName']; 
	  	$param['subject'] = $templateData['subject'];
	  	$param['body_html'] = $templateData['message'];
	  	$param['to'] = $templateData['email'];
		$this->apiCall($config['url'], ['form_params' => $param], "POST");
		logInfo($this->apiResponse);

		return $this->apiResponse;
	}

	public function sendSms($templateData)
	{
		$config = config('api.twilio');  
	  	$client = new Client($config['accountSid'], $config['authToken']);

	  	$result = $client->messages->create($templateData['mobile'], [
	        	'from' => $config['fromNumber'],
	        	'body' => $templateData['message']
	      	]
	  	);
	  	logInfo(json_encode($result->toArray()));
		
		return $result;
	}
}