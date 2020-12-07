<?php

namespace App\Utils;

use Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

trait Api
{
	public $client;

	public $apiResponse;

	public $error;

	public function getGuzzleClient()
	{
		if (is_null($this->client)) {
			$this->client = app()->make(Client::class);
		}

		return $this->client;
	}

	public function apiCall($url, $options = [], $method = "GET")
	{
		unset($this->apiResponse);
		unset($this->error);
		try {
			$response = $this->getGuzzleClient()->request($method, $url, $options);
			$this->apiResponse = $response->getBody()->getContents();

			return $this;
		} catch(ClientException $e) {
			exceptionLogger($url, $e);
			$this->error = $e;
			$this->apiResponse = (string) $e->getResponse()->getBody();

			throw $e;
		}

		return $this;
	}

	public function toGuzzleArray()
	{
		return json_decode($this->apiResponse, true);
	}
}