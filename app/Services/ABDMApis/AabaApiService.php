<?php
namespace App\Services\ABDMApis;

use App\Entities\Patient;
use App\Services\ABDMApis\BaseService;
use App\Services\ABDMApis\Rules\AadhaarNumber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
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

            Log::info('ABDM OTP REQUEST', [
                'endpoint' => $this->_urls['abha-request-otp-for-enroll'],
                'headers' => array_merge(
                    $options['headers'],
                    ['Authorization' => 'Bearer ' . substr($this->getToken(), 0, 20) . '...']
                )
            ]);

            $this->apiCall($this->_urls['abha-request-otp-for-enroll'], $options, 'POST');
            $api_response = $this->toGuzzleArray();

            Log::info('ABDM OTP REQUEST SUCCESS', $api_response);

            $response = [
                'transaction_id' => $api_response['txnId'],
                'transaction_time' => $options['headers']['TIMESTAMP'], // Keep original format
                'message' => $api_response['message'] ?? 'OTP sent successfully',
                'expires_at' => Carbon::now()->addMinutes(10)->toIso8601String() // Add expiry info
            ];

        } catch (\Exception $e) {
            $error = [
                'code' => $this->error->getCode(),
                'message' => $this->error->getMessage(),
            ];
            
            Log::error('ABDM API ENROLL VIA AADHAAR ERROR', $error);

            if ($error['code'] == 400) {
                throw ValidationException::withMessages(['aadhaar_number' => ['Invalid Aadhaar number']]);
            }

            throw new BadRequestHttpException($e->getMessage(), $e);
        }

        return $response;
    }

    public function enrollViaAadhaar(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'transaction_id' => 'required|string',
            'transaction_time' => 'required|string',
            'mobile' => 'required|digits:10',
            'otp' => 'required|digits:6',
        ]);

        if ($validation->fails()) {
            throw ValidationException::withMessages($validation->errors()->all());
        }

        try {
            // Validate transaction hasn't expired (10 minutes window)
            $transactionTime = Carbon::createFromFormat('Y-m-d\TH:i:s.v\Z', $request->input('transaction_time'));
            $minutesSinceTransaction = $transactionTime->diffInMinutes(Carbon::now());

            if ($minutesSinceTransaction > 10) {
                throw ValidationException::withMessages([
                    'transaction_id' => ['Transaction has expired. Please request a new OTP.']
                ]);
            }

            Log::info('ABDM ENROLLMENT ATTEMPT', [
                'transaction_id' => $request->input('transaction_id'),
                'transaction_age_minutes' => $minutesSinceTransaction,
                'mobile' => $request->input('mobile')
            ]);

            $patient_id = $request->input('patient_id');

            // CRITICAL FIX: Use the EXACT timestamp format from the OTP request
            $form_data = [
                'authData' => [
                    'authMethods' => ['otp'],
                    'otp' => [
                        'timeStamp' => $request->input('transaction_time'), // Use original format
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

            Log::info('ABDM API VERIFY VIA AADHAAR REQUEST', [
                'form_data' => array_merge(
                    $form_data,
                    ['authData' => array_merge(
                        $form_data['authData'],
                        ['otp' => array_merge(
                            $form_data['authData']['otp'],
                            ['otpValue' => substr($form_data['authData']['otp']['otpValue'], 0, 50) . '...']
                        )]
                    )]
                )
            ]);

            $options = [
                'headers' => $this->getHeadersWithToken(),
                'body' => json_encode($form_data),
            ];

            $this->apiCall($this->_urls['abha-enroll-via-aadhaar-verification'], $options, 'POST');
            $api_response = $this->toGuzzleArray();

            Log::info('ABDM API VERIFY VIA AADHAAR SUCCESS', [
                'response_keys' => array_keys($api_response)
            ]);

            // Save data to patient
            $patient = Patient::find($patient_id);
            $additional_info = $patient->additional_info ?? (object)[];
            
            // Handle both object and array response
            $abhaProfile = is_array($api_response) 
                ? ($api_response['ABHAProfile'] ?? $api_response)
                : ($api_response->ABHAProfile ?? $api_response);
            
            $additional_info->abha_profile = $abhaProfile;
            $patient->additional_info = $additional_info;
            $patient->save();

            Log::info('ABHA profile saved for patient', [
                'patient_id' => $patient_id,
                'abha_number' => $abhaProfile['ABHANumber'] ?? 'unknown'
            ]);

            $response = [
                'message' => 'ABHA details saved successfully',
                'abha_profile' => $abhaProfile
            ];

        } catch (ValidationException $e) {
            throw $e; // Re-throw validation exceptions as-is
            
        } catch (\Exception $e) {
            $error = [
                'code' => $this->error->getCode(),
                'message' => $this->error->getMessage(),
            ];

            Log::error('ABDM API VERIFY VIA AADHAAR ERROR', $error);

            // Handle specific error codes
            if ($error['code'] == 401) {
                $api_response = $this->toGuzzleArray();
                $errorMessage = $api_response['error']['message'] ?? $error['message'];
                $errorCode = $api_response['error']['code'] ?? 'UNKNOWN';

                Log::error('ABDM API 401 ERROR DETAILS', [
                    'error_code' => $errorCode,
                    'error_message' => $errorMessage
                ]);

                // Map ABDM error codes to user-friendly messages
                $userMessage = match($errorCode) {
                    'ABDM-1017' => 'Transaction has expired or is invalid. Please request a new OTP.',
                    'ABDM-1018' => 'Invalid OTP. Please try again.',
                    'ABDM-1019' => 'OTP attempts exceeded. Please request a new OTP.',
                    default => $errorMessage
                };

                throw ValidationException::withMessages(['otp' => [$userMessage]]);
            }

            if ($error['code'] == 422) {
                $api_response = $this->toGuzzleArray();
                throw ValidationException::withMessages([
                    'otp' => [$api_response['error']['message'] ?? 'Validation failed']
                ]);
            }

            throw new BadRequestHttpException($e->getMessage(), $e);
        }

        return $response;
    }
}