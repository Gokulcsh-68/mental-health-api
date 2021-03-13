<?php

namespace App\Services;

use App\Entities\Doc;
use App\Entities\PatientHealth;
use App\Entities\PatientHistory;
use App\Entities\PhysicalExamination;
use App\Entities\ReviewOfSystem;
use App\Entities\Role;
use App\Entities\User;
use App\Entities\Vital;
use App\Enums\EmailTemplateEnum;
use App\Enums\InternalCodeEnum;
use App\Enums\UserTypeEnum;
use App\Jobs\SendEmailJob;
use App\Notifications\InvoicePaid;
use App\Notifications\OtpNotification;
use App\Requests\ChangePasswordRequest;
use App\Requests\CommunicationRequest;
use App\Requests\ConsultTokenValidateRequest;
use App\Requests\ForgotPasswordEmailRequest;
use App\Requests\GeneralLoginRequest;
use App\Requests\ResendOtpRequest;
use App\Requests\TwofaRequest;
use App\Requests\VerifyOtpRequest;
use App\Services\CureselectApis\TeleConsultApiService;
use App\Services\UtilService;
use App\Traits\DicomUploadTrait;
use App\Transformers\UserTransformer;
use App\Utils\AuthHelper;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class AuthService extends BaseService
{
    use AuthHelper;
    use DicomUploadTrait;

    /**
     * General login.
     *
     * @param  \App\Requests\GeneralLoginRequest  $request
     * @param  \App\Entities\User  $user
     * @return json
     */
    protected $_teleconsult_service;

    public function consultTokenValidate(ConsultTokenValidateRequest $request, User $user): JsonResponse
    {

        
            $patient_id = self::getConsultInfo($request);

            if($patient_id > 0){
        
                $userInfo = User::where('id', $patient_id)
                    ->first();
                $requestedData['username'] = $userInfo->username;
                $requestedData['id'] = $userInfo->id;
                $requestedData['role'] = Role::Where('id',$userInfo->role_id)->value('code');
               
                $user = $user->consultLoginAttempt($requestedData);
                   
                if ($user) {
                    $result['userInfo'] = $user->getBasicInfo();
                    $data['userId'] = $user->id;
                    $Authorization  = $result['token'] =  $this->getAuthorization($data);

                    return $this->httpResponse->setHttpData($result)
                            ->setHttpHeader(['Authorization' => $Authorization])
                            ->jsonResponse();
                }
                
            }
      

        return $this->httpResponse->setHttpCode(401)->jsonResponse();
    }

    public function generalLogin(GeneralLoginRequest $request, User $user): JsonResponse
    {
        $message = trans('auth.failed');
        $requestedData = $request->json()->all();
        $user = $user->generalLoginAttempt($requestedData);
        
        if ($user) {
            if (!empty($user->is_2fa)) {
                $data['otp_type'] = "2faAuthentication";
                $this->otpNotification($data, $user);
                return $this->httpResponse->setHttpCode(200)
                    ->setHttpData(['is_2fa' => $user->is_2fa, 'status' => 'OTP_SENT'])
                    // ->setHttpData(['reference_otp' => $this->generateOtp($user->secret)])
                    ->setHttpMessage("Otp sent to your registered mobile and email.")
                    ->jsonResponse();
            }

            if ($user->isValidUser($requestedData)) {
                $result = [];
                $result['info'] = $user->getBasicInfo();
                $data['userId'] = $user->id;
                $Authorization  = $result['token'] =  $this->getAuthorization($data);

                $token_details = $this->decodeJwt($Authorization);
                if($token_details->exp)
                    $result['token_expiration_time'] = $token_details->exp;
                $result['status'] = 'verified_user';

                return $this->httpResponse->setHttpData($result)
                    ->setHttpHeader(['Authorization' => $Authorization])
                    ->jsonResponse();
            } else if (!$user->active) {
                $message = trans('auth.in_active');
            }
        }

        return $this->httpResponse->setHttpMessage($message)->setHttpCode(401)->jsonResponse();
    }

    // /**
    //  * User Set password.
    //  *
    //  * @param  \App\Requests\SetPasswordRequest  $request
    //  * @return json
    //  */

    // public function setPassword(SetPasswordRequest $request): JsonResponse
    // {
    //     $user = $request->user();
    //     if (!$user->isSetupOver()) {
    //         $user->update(['password' => $request->get('password')]);
    //         $user->captureEvent(UserEventTypeEnum::PasswordSet);
    //         $this->httpResponse->setHttpHeader(['Authorization' => $this->getAuthorization(['userId' => $user->id])]);
    //     } else {
    //         $this->httpResponse->setHttpCode(400);
    //     }

    //     return $this->httpResponse->jsonResponse();
    // }

    // /**
    //  * Logged in User Change password.
    //  *
    //  * @param  \App\Requests\ChangePasswordRequest  $request
    //  * @return json
    //  */

    public function getConsultInfo(Request $request){
        $this->_teleconsult_service = new TeleConsultApiService;

        $consultInfo = $this->_teleconsult_service->consultDetails($request);

        
        $patient_id = '-1';

        if(!empty($consultInfo)){
            if(isset($consultInfo['data']['participants'])){
                $consultPatient = $consultInfo['data']['participants'];
                
                foreach ($consultPatient as $key => $value) {
                    if(!$value['is_guest'] && !str_contains($value['ref_number'], 'guest')){
                        $patient_id = $value['ref_number'];
                    }
                }
            }
        }

        return $patient_id;
    }

    public function consultSummary(Request $request): JsonResponse
    {


        $patient_id = self::getConsultInfo($request);


        $summary['1_profile'] = $request->user()->Where('id',$patient_id)->first(['first_name','last_name','dob','gender','blood_group']);

        $summary['3_health'] = PatientHealth::Where('consult_id',$request->get('token'))
                            ->orderBy('slug','asc')->get();

        $summary['6_stroke_scale'] = PatientHistory::Where('consult_id',$request->get('token'))
                            ->Where('slug','stroke-scale')
                            ->orderBy('slug','asc')->get();

        $summary['4_ros'] = ReviewOfSystem::Where('consult_id',$request->get('token'))
                            ->orderBy('slug','asc')->get();

        $summary['5_pe'] = PhysicalExamination::Where('consult_id',$request->get('token'))
                            ->orderBy('slug','asc')->get();

        $summary['8_doc'] = Doc::Where('consult_id',$request->get('token'))
                            ->orderBy('document_source','asc')->get();

        $summary['2_vital'] = Vital::Where('consult_id',$request->get('token'))
                            ->orderBy('slug','asc')->get();
        $summary['7_history'] = PatientHistory::Where('consult_id',$request->get('token'))
                            ->Where('slug','!=','stroke-scale')
                            ->orderBy('slug','asc')->get();



        return $this->httpResponse->setHttpData($summary)
                    ->jsonResponse();
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = $request->user();
        $user->update(['password' => $request->get('password')]);
        // $user->captureEvent(UserEventTypeEnum::PasswordChange);

        return $this->httpResponse
                    ->setHttpMessage("Password Updated Successfully!...")
                    ->jsonResponse();
    }

    public function communication(CommunicationRequest $request): JsonResponse
    {
        $user = $request->user();
        $val = $request->get('communication_channel');
        $user->update(['communication_channel' => $val]);

        return $this->httpResponse->jsonResponse();
    }


    public function twofa(TwofaRequest $request): JsonResponse
    {
        $user = $request->user();
        $user->update(['is_2fa' => $request->get('is_2fa')]);

        return $this->httpResponse->jsonResponse();
    }

    public function info(Request $request): JsonResponse
    {
        $user = (new UserTransformer($request->user())) ;

        return $this->httpResponse->setHttpData($user)->jsonResponse();
    }


    public function uploadAvatar(Request $request): JsonResponse
    {

        try{

            $data = $request->all();

            $ext =  explode('/', mime_content_type($request->get('file')))[1];
                   
            $imageName = 'Avatar'.rand(9999,9999999).rand(100,1999).time().'.'.$ext;
            
            $image = $request->get('file'); 
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            Storage::put('uploadDocs/'.$imageName,  base64_decode($image));


             // $pro['file_path'] = $imageName;
             // $pro['file_name'] = $imageName;
             // $add['title'] = 'profile-photo';

             // $res['properties'] = $pro;
             // $res['addition_info'] = $add;
             // $res['user_id'] = $request->user()->id;
             // $res['created_by'] = $request->user()->id;
             // $res['document_source'] = 'profile-photo';
        
             // Doc::Create($res);

             $user['profile_image'] = $imageName;
             User::Where('id',$request->user()->id)->update($user);

            return $this->httpResponse->setHttpData($user)->jsonResponse();

        } catch (Exception $e) {
            exceptionLogger("Failed to upload document", $e);
            return false;
        }

    }

    public function uploadDocs(Request $request): JsonResponse
    {

        try{

        $data = $request->all();

         $res['file_path'] = "";
         $res['file_name'] = "";


        $ext = strtolower($request->file('file')->getClientOriginalExtension());
        

            $user = (new UserTransformer($request->user()));
            $request['id'] =  $user->id;
            $request['filetype'] =  'item_image';
        if($ext == 'dcm'){
            
                $dicom_response = $this->initiateDicomUpload($request->file('file'), $ext, $request['id']);
              
                 $res['file_path'] = $dicom_response['file_path'];
                 $res['file_name'] = $dicom_response['file_name'];
        }
        else{     

            // $request['type'] = Role::Where('id',$user->role_id)->value('code');
            // $request['file_name'] = rand(9999,9999999).rand(100,1999).time().'.'.$request->file('file')->getClientOriginalExtension();


            // $other_response = new UtilService();
            // $status = $other_response->postSignedUrl($request);


            //  $res['file_path'] = $status['file_path'];
            //  $res['file_name'] = $status['file_name'];  
            //  $res['file_tmp'] = $status['file_tmp'];  
           
                $imageName = 'Document'.rand(9999,9999999).rand(100,1999).time().'.'.$request->file('file')->getClientOriginalExtension();
            
            $destinationPath = storage_path('/app/uploadDocs');
            $request->file('file')->move($destinationPath, $imageName);
             $res['file_path'] = $imageName;
             $res['file_name'] = $imageName;
        }


        return $this->httpResponse->setHttpData($res)->jsonResponse();

        } catch (Exception $e) {
            exceptionLogger("Failed to upload document", $e);
            return false;
        }

    }

    // /**
    //  * Change User password.
    //  *
    //  * @param  \App\Requests\ChangeUserPasswordRequest  $request
    //  * @param  \App\Entities\User  $user
    //  * @return json
    //  */

    // public function changeUserPassword($id, ChangeUserPasswordRequest $request, User $user): JsonResponse
    // {
    //     $user = $user->where('id', $id)
    //         ->firstOrFail();
    //     $user->update(['password' => $request->get('password')]);
    //     $user->captureEvent(UserEventTypeEnum::PasswordChange, ['changed_by' => $request->attributes->get('user')->id]);

    //     return $this->httpResponse->jsonResponse();
    // }

    // /**
    //  * Change Email.
    //  *
    //  * @param  \App\Requests\ChangeEmailRequest  $request
    //  * @param  \App\Entities\User  $user
    //  * @return json
    //  */

    // public function changeEmail(ChangeEmailRequest $request, User $user): JsonResponse
    // {
    //     $user = $request->attributes->get('user');
    //     $user->email_verify_token = base64_encode(openssl_random_pseudo_bytes(32));
    //     $customAttributes = $user->custom_attributes;
    //     $customAttributes['email_verify_send_on'] = Carbon::now()->toDateTimeString();
    //     $customAttributes['change_email'] = $request->get('email');
    //     $user->custom_attributes = $customAttributes;
    //     $user->save();
    //     $tokenPayload['userId'] = $user->id;
    //     $tokenPayload['emailVerifyToken'] = aesEncrypt($user->email_verify_token . ":" . $user->email);

    //     return $this->httpResponse->setHttpHeader(['Authorization' => $this->getAuthorization($tokenPayload)])
    //         ->jsonResponse();
    // }

    //  /**
    //  * Forgot Password Email.
    //  *
    //  * @param  \App\Requests\ForgotPasswordEmailRequest  $request
    //  * @param  \App\Entities\User  $user
    //  * @return json
    //  */

    public function forgotPasswordEmail(ForgotPasswordEmailRequest $request, User $user): JsonResponse
    {
        $roleId = Role::where("code", $request->get('role'))->pluck('id')->first();
        $user = $user->where('email', $request->get('email'))
            ->where('role_id', $roleId)
            ->first();
        if (!empty($user)) {
            $data['otp_type'] = "forgotPassword";
            $this->otpNotification($data, $user);
            // $this->httpResponse->setHttpData(['reference_otp' => $this->generateOtp($user->secret)]);
            $this->httpResponse->setHttpMessage("Otp sent to your registered mobile and email.");
        } else {
            $this->httpResponse->setHttpMessage("Email not found")->setHttpCode(404);
        }

        return $this->httpResponse->jsonResponse();
    }

    /**
     * Verify Email OTP.
     *
     * @param  \App\Requests\VerifyOtpRequest  $request
     * @return json
     */

    public function verifyOtp(VerifyOtpRequest $request, User $user): JsonResponse
    {
        $message = trans('auth.failed');
        $requestedData = $request->json()->all();
        $roleId = Role::where("code", $request->get('role'))->pluck('id')->first();

        // Forgot Password Change
        if ($request->get('action') == 'forgotPassword') {
            $user = User::where('email', $request->get('email'))
                ->where('role_id', $roleId)
                ->first();

            if (!empty($user)) {
                if ($this->validateOtp($user->secret, $request->get('otp'))) {
                    $user->update(['password' => $request->get('password')]);
                    $this->httpResponse->setHttpMessage("Password changed Successfully.")
                        ->setHttpHeader(['Authorization' => $this->getAuthorization(['userId' => $user->id])]);
                } else {
                    $this->httpResponse->setHttpMessage("Invalid OTP.")->setHttpCode(400);
                }
            } else {
                $this->httpResponse->setHttpMessage("Email not found")->setHttpCode(404);
            }
            return $this->httpResponse->jsonResponse();
        }

        if ($request->get('action') == 'resetPassword') {
            $user = User::where('email', $request->get('email'))
                ->where('role_id', $roleId)
                ->first();
            if (!empty($user)) {
                if ($this->validateOtp($user->secret, $request->get('otp'))) {
                    $user->update(['password' => $request->get('password')]);
                    $this->httpResponse->setHttpMessage("Successfully password changed");
                } else {
                    $this->httpResponse->setHttpMessage("Invalid OTP.")->setHttpCode(400);
                }
            } else {
                $this->httpResponse->setHttpMessage("Email not found")->setHttpCode(404);
            }
            return $this->httpResponse->jsonResponse();
        }

        if ($request->get('action') == '2faAuthentication') {
            $user = $user->generalLoginAttempt($requestedData);
            if (!empty($user)) {
                if ($this->validateOtp($user->secret, $request->get('otp'))) {
                    $this->httpResponse->setHttpMessage("OTP Verified Successfully.");
                    $result = [];
                    $result['info'] = $user->getBasicInfo();
                    $data['userId'] = $user->id;
                    $Authorization  = $result['token'] =  $this->getAuthorization($data);

                    $token_details = $this->decodeJwt($Authorization);
                    if($token_details->exp)
                        $result['token_expiration_time'] = $token_details->exp;
                    $result['status'] = 'OTP_VERIFIED';

                    return $this->httpResponse->setHttpData($result)
                        // ->setHttpData(['status' => 'OTP_VERIFIED'])
                        ->setHttpHeader(['Authorization' => $Authorization])
                        ->jsonResponse();

                } else {
                    $this->httpResponse->setHttpMessage("Invalid OTP.")->setHttpCode(400);
                    return $this->httpResponse->jsonResponse();
                }
            }

        }

        return $this->httpResponse->setHttpMessage($message)->setHttpCode(401)->jsonResponse();
    }

    // /**
    //  * Verify Email.
    //  *
    //  * @param  \App\Requests\VerifyEmailRequest  $request
    //  * @return json
    //  */

    // public function verifyEmail(VerifyEmailRequest $request, User $user)
    // {
    //     list($token, $email, $action) = explode(':', aesDecrypt($request->get('token')));
    //     $user = $user->where('email_verify_token', $token)
    //         ->where(function ($query) use($email) {
    //             $query->where('custom_attributes->change_email', $email)
    //                 ->orWhere('email', $email);
    //         })
    //         ->first();

    //     if ($user) {
    //         $this->httpResponse->setHttpMessage("Email Verified Successfully.");
    //         $user->email_verified_on = Carbon::now();
    //         $user->email_verified = 1;
    //         if (isset($user->custom_attributes['change_email'])) {
    //             $user->email = $user->custom_attributes['change_email'];
    //             $emailUser = User::where('email', $user->custom_attributes['change_email'])
    //                 ->where('id', '!=', $user->id)
    //                 ->first();
    //             if ($emailUser) {
    //                 $emailUser->email = $emailUser->email . '__' . $emailUser->id;
    //                 $emailUser->save();
    //             }
    //             $customAttributes = $user->custom_attributes;
    //             unset($customAttributes['change_email']);
    //             $user->custom_attributes = $customAttributes;
    //         }
    //         $user->save();

    //         $this->httpResponse->setHttpData(['info' => $user->getBasicInfo()]);
    //         $tokenPayload['userId'] = $user->id;

    //         $this->httpResponse->setHttpHeader(['Authorization' => $this->getAuthorization($tokenPayload)]);
    //     } else {
    //         $this->httpResponse->setHttpMessage("Invalid Token.")->setHttpCode(400);
    //     }

    //     return $this->httpResponse->jsonResponse();
    // }

    public function otpNotification($data, $user) {
        $data += $user->toArray();

        // $subject = 'A2Z ' . ucwords($user->role->name) . ' Telehealth';

        switch ($data['otp_type']) {
            case 'resendOtp':
                $data['message'] = "Otp resent";
                break;

            case 'forgotPassword':
                $data['message'] = "Forget password otp";
                break;

            case '2faAuthentication':
                $data['message'] = "2faAuthentication Otp sent";
                break;

            default:
                $data['message'] = '';
        }
        $data += [
            'otp' => $this->generateOtp($data['secret']),
            'email' => $data['email'],
            'name' => $user->getFullName(),
            'template' => EmailTemplateEnum::Otp
        ];

        dispatch(new SendEmailJob($data));

        // SendEmailJob::dispatch($data);

        // $user->notify(new OtpNotification($data));
        return true;
    }

    /**
     * Resend Otp.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entities\User  $user
     * @return json
     */

    public function resendOtp(ResendOtpRequest $request, User $user): JsonResponse
    {
        $message = trans('auth.failed');
        $requestedData = $request->json()->all();
        $roleId = Role::where("code", $request->get('role'))->pluck('id')->first();

        if ($request->get('action') == 'forgotPassword') {
            $user = User::where('email', $request->get('email'))
                ->where('role_id', $roleId)
                ->first();

            if (!empty($user)) {
                $data['otp_type'] = "resendOtp";
                $this->otpNotification($data, $user);
                return $this->httpResponse->setHttpCode(200)
                    ->setHttpData(['status' => 'OTP_SENT'])
                    // ->setHttpData(['reference_otp' => $this->generateOtp($user->secret)])
                    ->setHttpMessage("Resend OTP sent.")
                    ->jsonResponse();
            } else {
                $this->httpResponse->setHttpMessage("Email not found")->setHttpCode(404);
            }
            return $this->httpResponse->jsonResponse();

        } elseif ($request->get('action') == '2faAuthentication') {

            $user = $user->generalLoginAttempt($requestedData);

            if ($user) {
                $data['otp_type'] = "resendOtp";
                $this->otpNotification($data, $user);
                return $this->httpResponse->setHttpCode(200)
                    ->setHttpData(['2fa' => 'active', 'status' => 'OTP_SENT'])
                    // ->setHttpData(['reference_otp' => $this->generateOtp($user->secret)])
                    ->setHttpMessage("Resend OTP sent.")
                    ->jsonResponse();

            }
        }
        return $this->httpResponse->setHttpMessage($message)->setHttpCode(401)->jsonResponse();
    }

    // /**
    //  * Forgot Password Email Otp.
    //  *
    //  * @param  \App\Requests\ForgotPasswordEmailRequest  $request
    //  * @param  \App\Entities\User  $user
    //  * @return json
    //  */

    // public function forgotPasswordEmailOtp(ForgotPasswordEmailRequest $request, User $user): JsonResponse
    // {
    //     $user = $user->where('email', $request->get('email'))
    //         ->first();
    //     if ($user) {
    //         $job = (new SendEmailJob(['otp' => $this->generateOtp($user->secret), 'email' => $user->email, 'name' => $user->getFullName(), 'template' => EmailTemplateEnum::Otp]))->onQueue('sendEmail');
    //         dispatch($job);

    //         $this->httpResponse->setHttpMessage("Otp sent to email.")
    //             ->setHttpHeader(['Authorization' => $this->getAuthorization(['userId' => $user->id])]);
    //     } else {
    //         $this->httpResponse->setHttpMessage("Email not found")->setHttpCode(404);
    //     }

    //     return $this->httpResponse->jsonResponse();
    // }
}
