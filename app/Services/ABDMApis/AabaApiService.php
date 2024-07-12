<?php

namespace App\Services\ABDMApis;

use App\Entities\Doc;
use App\Entities\Patient;
use App\Services\ABDMApis\BaseService;
use App\Services\ABDMApis\Rules\AadhaarNumber;
use App\Traits\S3;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class AabaApiService extends BaseService
{
	public function __construct()
	{
		parent::__construct();
	}

	public function requestOtpForEnrollViaAadhaar(Request $request)
	{

		$validation = Validator::make($request->all(), [
			'aadhaar_number' => ['required', new AadhaarNumber],
		]);

		if ($validation->fails()) {
			throw ValidationException::withMessages($validation->errors()->all());
		}

		try {

			$form_data = [
				'scope' => ['abha-enrol'],
				'loginHint' => 'aadhaar',
				'loginId' => $this->encryptData($request->input('aadhaar_number')),
				'otpSystem' => 'aadhaar',
			];

			$options = [
				'headers' => $this->getHeadersWithToken(),
				'body' => json_encode($form_data),
			];

			$this->apiCall($this->_urls['abha-request-otp-for-enroll'], $options, 'POST');
			$api_response = $this->toGuzzleArray();

			$response = [
				'transaction_id' => $api_response['txnId'], 
				'transaction_time' => $options['headers']['TIMESTAMP'], 
				'message' => $api_response['message']
			];
		} catch (\Exception $e) {
			$error = [
				'code' => $this->error->getCode(),
				'message' => $this->error->getMessage(),
				// 'trace' => $e->getTraceAsString(),
			];
			Log::error('ABDM API ENROLL VIA AADHAAR ERROR ------- ', $error);

			if($error['code'] == 400) {
				throw ValidationException::withMessages(['aadhaar_number' => [['Invalid aadhar number']]]);
			}

			throw new BadRequestHttpException($e->getMessage(), $e);
			$response = [ $e->getMessage() ];
		}

		return $response;
	}

	public function enrollViaAadhaar(Request $request)
	{
		$validation = Validator::make($request->all(), [
			'transaction_id' => 'required|string',
			'transaction_time' => 'required',
			'mobile' => 'required|digits:10',
			'otp' => 'required|digits:6',
		]);

		if ($validation->fails()) {
			throw ValidationException::withMessages([$validation->errors()]);
		}

		try {

			$form_data = [
				'authData' => [
					'authMethods' => ['otp'],
					'otp' => [
						'timeStamp' => (string) Carbon::createFromFormat('Y-m-d\TH:i:s.v\Z', $request->input('transaction_time'))->format('Y-m-d\TH:i:s'),
						'txnId' => $request->input('transaction_id'),
						'otpValue' => $this->encryptData($request->input('otp')),
						'mobile' => $request->input('mobile'),
					]
				],
				'consent' => [
					'code' => 'abha-enrollment',
					'version' => '1.4'
				],
			];

			Log::info('ABDM API VERIFY VIA AADHAAR INFO ------- ', $form_data);

			$options = [
				'headers' => $this->getHeadersWithToken(),
				'body' => json_encode($form_data),
			];

			$this->apiCall($this->_urls['abha-enroll-via-aadhaar-verification'], $options, 'POST');
			$api_response = $this->toGuzzleArray();
			$response = $api_response;

			// $response = ['transaction_id' => $api_response['txnId'], 'message' => $api_response['message']];
		} catch (\Exception $e) {
			$error = [
				'code' => $this->error->getCode(),
				'message' => $this->error->getMessage(),
				// 'trace' => $e->getTraceAsString(),
			];
			Log::error('ABDM API VERIFY VIA AADHAAR ERROR ------- ', $error);

			if($error['code'] == 422) {
				$api_response = $this->toGuzzleArray();
				// dd($this->error->getMessage(), $api_response);
				throw ValidationException::withMessages(['otp' => [[$api_response['error'] ]]]);
			}

			throw new BadRequestHttpException($e->getMessage(), $e);
			$response = [ $e->getMessage() ];
		}

		return $response;
	}
}
