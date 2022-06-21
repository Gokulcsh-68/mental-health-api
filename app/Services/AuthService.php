<?php

namespace App\Services;

use App\Entities\ActivityWellness;
use App\Entities\Doc;
use App\Entities\FamilyHistory;
use App\Entities\Form;
use App\Entities\Hospital;
use App\Entities\Master;
use App\Entities\Patient;
use App\Entities\PatientHealth;
use App\Entities\PatientHistory;
use App\Entities\PhysicalExamination;
use App\Entities\Provider;
use App\Entities\ReviewOfSystem;
use App\Entities\Role;
use App\Entities\Staff;
use App\Entities\Timezone;
use App\Entities\User;
use App\Entities\Vital;
use App\Enums\EmailTemplateEnum;
use App\Enums\EnumAnalyticsChart;
use App\Enums\InternalCodeEnum;
use App\Enums\UserTypeEnum;
use App\Jobs\CommunicationJob;
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
use App\Services\CureselectApis\PeripheralApiService;
use App\Services\CureselectApis\TeleConsultApiService;
use App\Services\EntityService;
use App\Services\UtilService;
use App\Traits\DicomUploadTrait;
use App\Transformers\MasterTransformer;
use App\Transformers\ProviderTransformer;
use App\Transformers\UserTransformer;
use App\Utils\AuthHelper;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mpdf\Mpdf;


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


    public function authGuestx(Request $request, User $user): JsonResponse
    {

            $roleId = Role::Where('code',$request->get('role'))->value('id');
            $userInfo = User::where('username', $request->get('username'))
                ->where('role_id', $roleId)
                ->first();
            $requestedData['username'] = $userInfo->username;
            $requestedData['id'] = $userInfo->id;
            $requestedData['role'] = Role::Where('id',$userInfo->role_id)->value('code');

            $user = $user->guestLoginAttempt($requestedData);

            if ($user) {
                $result['info'] = $user->getBasicInfo();
                $data['userId'] = $user->id;
                $Authorization  = $result['token'] =  $this->getAuthorization($data);

                $token_details = $this->decodeJwt($Authorization);

                if($token_details->exp)
                    $result['token_expiration_time'] = $token_details->exp;

                return $this->httpResponse->setHttpData($result)
                        ->setHttpHeader(['Authorization' => $Authorization])
                        ->jsonResponse();
            }


        return $this->httpResponse->setHttpCode(401)->jsonResponse();
    }

    public function consultTokenValidate(ConsultTokenValidateRequest $request, User $user): JsonResponse
    {


        $getpatient_id = self::getConsultInfo($request);

        $patient_id = $getpatient_id['patient_id'];
        $consult_id = $getpatient_id['consult_id'];

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


     public function getConsultInfo(Request $request){
        $this->_teleconsult_service = new TeleConsultApiService;

        $consultInfo = $this->_teleconsult_service->consultDetails($request);

         $consult_id = '-1';
         $patient_id = '-1';
        $provider_details = $consultInfo?$consultInfo['data']['info']:[];
        $consult_details = [];

        if(!empty($consultInfo)){
            if(isset($consultInfo['data']['consult'])){

                $consult_id = $consultInfo['data']['consult']['id'];
                $filters['consult_id'] = $consult_id;
                $consults = $this->_teleconsult_service->fetchById($filters,$consult_id);

                if(!empty($consults)){
                    if(isset($consults['data']['consults'])){
                        $consult_details = $consults['data']['consults'];
                    }
                }
            }

            if(isset($consultInfo['data']['participants'])){
                $consultPatient = $consultInfo['data']['participants'];

                foreach ($consultPatient as $key => $value) {
                    if(!$value['is_guest'] && !str_contains($value['ref_number'], 'guest')){
                        $patient_id = $value['ref_number'];
                    }
                }
            }
        }

        return ["patient_id"=>$patient_id, "consult_id"=> $consult_id, "provider_info" => $provider_details, "consultInfo" => $consult_details ];
    }

    public function consultSummary(Request $request): JsonResponse
    {
        $getpatient_id = self::getConsultInfo($request);

        $patient_id = $getpatient_id['patient_id'];
        $consult_id = $getpatient_id['consult_id'];

        $summary['1_profile'] = $request->user()->Where('id',$patient_id)->first(['first_name','last_name','dob','gender','blood_group']);

        $summary['3_health'] = PatientHealth::Where('consult_id',$consult_id)
                            ->orderBy('slug','asc')->get();

        $summary['6_stroke_scale'] = PatientHistory::Where('consult_id',$consult_id)
                            ->Where('slug','stroke-scale')
                            ->orderBy('slug','asc')->get();

        $summary['4_ros'] = ReviewOfSystem::Where('patient_id',$patient_id)
                            ->Where('consult_id',$consult_id)
                            ->orderBy('slug','asc')->get();

        $summary['5_pe'] = PhysicalExamination::Where('patient_id',$patient_id)
                            ->Where('consult_id',$consult_id)
                            ->orderBy('slug','asc')->get();

        $summary['8_doc'] = Doc::Where('consult_id',$consult_id)
                            ->orderBy('document_source','asc')->get();

        $summary['2_vital'] = Vital::Where('consult_id',$consult_id)
                            ->orderBy('slug','asc')->get();
        $summary['7_history'] = PatientHistory::Where('consult_id',$consult_id)
                            ->Where('slug','!=','stroke-scale')
                            ->orderBy('slug','asc')->get();

        $summary['doc_slug'] = Doc::Where('consult_id',$consult_id)
                            ->orderBy('document_source','asc')->groupBy('document_source')->pluck('document_source');


        $providers = Provider::Where('user_id',$getpatient_id['provider_info']['ref_number'])->first(["additional_info","license_no"]);

         $getpatient_id['provider_info']['license_no'] = $providers?$providers['license_no']:'';
         $getpatient_id['provider_info']['qualification'] = $providers?$providers['additional_info']?$providers['additional_info']->qualification:'':'';
        $summary['provider_details'] = $getpatient_id['provider_info'];
        $summary['0_consult_details'] = $getpatient_id['consultInfo'];

        return $this->httpResponse->setHttpData($summary)
                    ->jsonResponse();
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
            $requestedData['password'] = $request->get('currentpassword');
                $user = $request->user();
            if ($user->isValidUser($requestedData)) {

                $user->update(['password' => $request->get('password')]);
                // $user->captureEvent(UserEventTypeEnum::PasswordChange);

                return $this->httpResponse
                            ->setHttpMessage("Password Updated Successfully!...")
                            ->jsonResponse();
            }
            else{
                 return $this->httpResponse->setHttpCode(401)
                            ->setHttpMessage("Current Password not match!...")
                            ->jsonResponse();
            }
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

    public function providerinfo(Request $request): JsonResponse
    {
       $uid = $request->user()->id;
        if($request->get('user_id')){
            if($request->get('user_id') > 0){
                $uid = $request->get('user_id');
            }
        }
        $providerinfo = app(Provider::class)->where('user_id',$uid)->first()->toArray();

        return $this->httpResponse->setHttpData($providerinfo)->jsonResponse();
    }

    public function getMasterx(Request $request): JsonResponse
    {

        $resource = 'Master';
        $entity = new Master();
        $collection = callUserFuncArray([$entity, 'getModelList'], [])->get();

        $res_name = snake_case(camel_case(str_plural($resource)));

        $result[$res_name] = $collection->isNotEmpty() ? $this->collectionTransform($resource, $collection) : [];

        return $this->httpResponse->setHttpData($result)->jsonResponse();

    }


    public function getTimezonex(Request $request): JsonResponse
    {

        $resource = 'Timezone';
        $entity = new Timezone();
        $collection = callUserFuncArray([$entity, 'getModelList'], [])->get();

        $res_name = snake_case(camel_case(str_plural($resource)));

        $result[$res_name] = $collection->isNotEmpty() ? $this->collectionTransform($resource, $collection) : [];

        return $this->httpResponse->setHttpData($result)->jsonResponse();

    }

    public function saveHospitalsx(Request $request): JsonResponse
    {

        $requestClass = sprintf('\App\Requests\%sRequest', 'Hospital');
        class_exists($requestClass) ? app()->make($requestClass) : $request;


        $hospital_request = $request;
        $hospital_request->merge(['register' => true]);

        $hospital = Hospital::createModel($hospital_request)->toArray();
        $user_id = Staff::where('user_id',$hospital['id'])->value('user_id');

        $user = User::where('id', $user_id)
            ->first();
        if (!empty($user)) {
            $data['otp_type'] = "registered";
            $this->otpNotification($data, $user);

            return $this->httpResponse->setHttpMessage("Success, Check!... Your account will be activated within 24 hours,Further updates please contact support...")->jsonResponse();
        }

        return $this->httpResponse
                    ->setHttpMessage("Registered Successfully!... Your account will be activated within 24 hours,Further updates please contact support...")->jsonResponse();

    }

    public function savePatientsx(Request $request): JsonResponse
    {

        $requestClass = sprintf('\App\Requests\%sRequest', 'Patient');
        class_exists($requestClass) ? app()->make($requestClass) : $request;

        $patient_request = $request;
        $patient_request->merge(['register' => true]);

        $patient = Patient::createModel($patient_request)->toArray();

        $user = User::where('id', $patient['user_id'])
            ->first();
        if (!empty($user)) {
            $data['otp_type'] = "activation";
            $this->otpNotification($data, $user);

            return $this->httpResponse->setHttpMessage("Success, Check!... registered mail-id to activate your account")->jsonResponse();
        }

        return $this->httpResponse
                    ->setHttpMessage("Registered Successfully!... Activation Mail Failed,Please Contact Support...")->jsonResponse();

    }

    public function saveProvidersx(Request $request): JsonResponse
    {

        $requestClass = sprintf('\App\Requests\%sRequest', 'Provider');

        class_exists($requestClass) ? app()->make($requestClass) : $request;

        $provider_request = $request;
        $provider_request->merge(['register' => true]);


        $provider = Provider::createModel($provider_request)->toArray();

        $user = User::where('id', $provider['user_id'])
            ->first();
        if (!empty($user)) {
            $data['otp_type'] = "registered";
            $this->otpNotification($data, $user);

            return $this->httpResponse->setHttpMessage("Success, Check!... Your account will be activated within 24 hours,Further updates please contact support...")->jsonResponse();
        }

        return $this->httpResponse
                    ->setHttpMessage("Registered Successfully!... Your account will be activated within 24 hours,Further updates please contact support...")->jsonResponse();

    }

    public function activateAccountsx(Request $request)
    {


        if($request->get('token')){
        $token = base64_decode($request->get('token'));
            User::Where('id',$token)->update(['is_active'=>1]);
            // return redirect('activated');
            return $this->httpResponse
                    ->setHttpMessage("Account Activated Successfully!...")
                    ->jsonResponse();
        }


        return $this->httpResponse
                    ->setHttpMessage("Try Again")
                    ->jsonResponse();


    }


    public function uploadAvatar(Request $request): JsonResponse
    {

        try{

            $data = $request->all();

            $imageFile = $request->all();
            // return $this->httpResponse->setHttpData(['adsf' => $imageFile, 'a' => $request->file('file')])->jsonResponse();

            $imageName = 'profile_' . rand(9999,9999999).rand(100,1999).time().'.png';

            $user =  $request->user();

            $request['type'] = $user->role->code;
            $request['filetype'] = 'profile_image';
            $request['file_name'] = $imageName;

            // $request['type'] =  'patient';
            // $request['filetype'] =  'profile_image';



            /* $path =  config('api.fileSystem.' . $request->get('type'));
            $path = sprintf($path, $request->get('type'));
            $status = (new UtilService())->diskStorage($request->file('file'), $path, 'profile_');
            */


            $status = (new UtilService())->postSignedUrl($request);
            $res['file_path'] = $status['file_path'];
            $res['file_name'] = $status['file_name'];
            $res['file_tmp'] = $status['file_tmp'];

            $user->profile_image = $res['file_name'];
            $user->save();


            /*
            $response = ['status' => false];
            if ($status['success']) {
                $user->profile_image = $status['filename'];
                $user->save();

                $response['status'] = true;
                $response['profile_image'] = $user->profile_image;
                $response['message'] = 'Profile Image Updated';
            } else {
                $response['message'] = 'Something went wrong. Please try later';
            } */

            return $this->httpResponse->setHttpData($res)->jsonResponse();

        } catch (Exception $e) {
            exceptionLogger("Failed to upload document", $e);
            return false;
        }

    }

    public function uploadDocs(Request $request): JsonResponse
    {
        try {

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

            $imageName = 'Document'.rand(9999,9999999).rand(100,1999).time().'.'.$request->file('file')->getClientOriginalExtension();

            $image = $request->get('file');

            $request['type'] = $request->user()->role->code;
            $request['file_name'] = $imageName;

            $status = (new UtilService())->postSignedUrl($request);

            $res['s3_signed_url'] = $status['file_path'];
            $res['file_name'] = $status['file_name'];
            $res['file_path'] = $status['file_tmp'];
            $res['s3_upload'] = 1; // for s3 signed url upload in angular


            // $request['type'] = Role::Where('id',$user->role_id)->value('code');
            // $request['file_name'] = rand(9999,9999999).rand(100,1999).time().'.'.$request->file('file')->getClientOriginalExtension();


            // $other_response = new UtilService();
            // $status = $other_response->postSignedUrl($request);


            //  $res['file_path'] = $status['file_path'];
            //  $res['file_name'] = $status['file_name'];
            //  $res['file_tmp'] = $status['file_tmp'];


            /* $destinationPath = storage_path('/app/uploadDocs');
            $request->file('file')->move($destinationPath, $imageName);
            $res['file_path'] = $imageName;
            $res['file_name'] = $imageName; */
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
    
        $user = User::query();
        $user->where('email', $request->get('email'));
        $user->where('role_id', $roleId);
    

        if($request->get('username')){
            $user->where('username', $request->get('username'));
        }

        $user = $user->first();
        
        if (!empty($user)) {
            $data['otp_type'] = "forgotPassword";
            $this->otpNotification($data, $user);
            // $this->httpResponse->setHttpData(['reference_otp' => $this->generateOtp($user->secret)]);
            $this->httpResponse->setHttpMessage("Otp sent to your registered mobile and email based on your communication preference.");
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
        
            $user = User::query();
            $user->where('email', $request->get('email'));
            $user->where('role_id', $roleId);
        

            if($request->get('username')){
                $user->where('username', $request->get('username'));
            }

            $user = $user->first();
            
            if (!empty($user)) {
                $check_otp_token = $this->validateOtp($user->secret, $request->get('otp'));

                // dd($check_otp_token);

                if (!empty($check_otp_token)) {
                    if($check_otp_token === true){
                        $user->update(['password' => $request->get('password')]);
                        $this->httpResponse->setHttpMessage("Password changed Successfully.")
                        ->setHttpHeader(['Authorization' => $this->getAuthorization(['userId' => $user->id])]);
                    }else{
                        $this->httpResponse->setHttpMessage("OTP Expired.")->setHttpCode(400);
                    }
                } else {
                    $this->httpResponse->setHttpMessage("Invalid OTP.")->setHttpCode(400);
                }
            } else {
                $this->httpResponse->setHttpMessage("Email not found")->setHttpCode(404);
            }
            return $this->httpResponse->jsonResponse();
        }

        if ($request->get('action') == 'resetPassword') {
            
            $user = User::query();
            $user->where('email', $request->get('email'));
            $user->where('role_id', $roleId);
        

            if($request->get('username')){
                $user->where('username', $request->get('username'));
            }

            $user = $user->first();
            
            if (!empty($user)) {
                $check_otp_token = $this->validateOtp($user->secret, $request->get('otp'));

                if (!empty($check_otp_token)) {
                    if($check_otp_token === true){
                        $user->update(['password' => $request->get('password')]);
                        $this->httpResponse->setHttpMessage("Successfully password changed");
                    }else{
                        $this->httpResponse->setHttpMessage("OTP Expired.")->setHttpCode(400);
                    }
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
                $check_otp_token = $this->validateOtp($user->secret, $request->get('otp'));

                if (!empty($check_otp_token)) {
                    if($check_otp_token === true){
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
                    }else{
                        $this->httpResponse->setHttpMessage("OTP Expired.")->setHttpCode(400);
                        return $this->httpResponse->jsonResponse();
                    }
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


        $otp = $this->generateOtp($data['secret']);
        $uid = base64_encode($user->id);

        // Log::info($otp);

        $subject_prefix = 'A2Z Health';

        switch ($data['otp_type']) {
            case 'forgotPassword':
                $heading = 'Forget password OTP';
                $subject = $subject_prefix . ' - Forget Password';
                break;

            case '2faAuthentication':
                $heading = '2faAuthentication OTP sent';
                $subject = $subject_prefix . ' - 2FA OTP';
                break;

            case 'activation':
                $heading = 'User Account Activation';
                $subject = $subject_prefix . ' - Activation';
                break;

            case 'provider_activated':
                $heading = 'Your Account Activated';
                $subject = $subject_prefix . ' - Activated';
                break;

            case 'registered':
                $heading = 'Account Under Verification';
                $subject = $subject_prefix . ' - Verification';
                break;

            default:
                $heading = '';
                $subject = '';
        }

        $sms_template = config('api.communication_sms_template.' . $data['otp_type']);

        if(!$sms_template) {
            if($data['otp_type'] == 'activation'){
                $sms_template = view('sms.activation', [ 'uid' => $uid])->render();

            }else if($data['otp_type'] == 'provider_activated'){
                $sms_template = view('sms.provider_activated', [ 'uid' => $uid])->render();

            }else if($data['otp_type'] == 'registered'){
                $sms_template = view('sms.registered', [ 'uid' => $uid])->render();

            }else{
                $sms_template = view('sms.default_otp', ['otp' => $otp, 'type' => $data['otp_type']])->render();
            }
        } else {
            $sms_template = (string) Str::of($sms_template)
                ->replaceLast("{{otp}}", $otp)->replaceLast("{{uid}}", $uid);
        }

        $base_url = config('app.app_urls.' . $user->role->code);
        if($data['otp_type'] == 'activation'){
                $url = $base_url . '/auth/activate/' . $uid;
                $mail_template = (new MailMessage)->markdown('mail.activation', ['heading' => $heading, 'url' => $url])->render();

            }else if($data['otp_type'] == 'provider_activated'){
                $mail_template = (new MailMessage)->markdown('mail.provider_activated', ['heading' => $heading, 'url' => $base_url])->render();

            }else if($data['otp_type'] == 'registered'){
                $mail_template = (new MailMessage)->markdown('mail.registered', ['heading' => $heading])->render();

            }else{
                $mail_template = (new MailMessage)->markdown('mail.otp', ['heading' => $heading, 'otp' => $otp])->render();
            }


        $payload = [
            'email' => [
                'to' => [$user->email],
                'subject' => $subject,
                'message' => $mail_template,
            ],
            'sms' => [
                'mobile' => $user->mobile,
                'isd_code' => $user->isd_code,
                'message' => $sms_template,
            ]
        ];

        dispatch(new CommunicationJob($user, $payload));

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



    public function freezePhrEmr(Request $request){
        $this->validate($request, ['user_id' => 'required|exists:users,id']);

        $user_id = $request->input('user_id');
        dispatch(new \App\Jobs\FreezePatientHealthRecordJob($user_id));

        $this->httpResponse->setHttpMessage("Record Updated")->setHttpCode(200);
        return $this->httpResponse->jsonResponse();
    }





    public function historyPDFx(Request $request){

        $resource = 'PatientHistory';
        $entity = new PatientHistory;

        $getResourceName = snake_case(camel_case(str_plural($resource)));

        $history_info = [];

        $x_consult_id = callUserFuncArray([$entity, 'getModelList'], [])->groupBy('consult_id')->pluck('consult_id');


        foreach ($x_consult_id as $key => $value) {


            $filters['consult_id'] = $value;
            $request->request->add(['consult_id' => $value?$value:'-1']);
            $collection = callUserFuncArray([$entity, 'getModelList'], [])->get();

            $medicine_records = $collection->isNotEmpty() ? $this->collectionTransform($resource, $collection) : [];

            if($value != null){
                $this->_teleconsult_service = new TeleConsultApiService;
                $consult_info  = $this->_teleconsult_service->fetch($filters, 1, 1);
                    foreach ($consult_info['data']['consults'] as $k => $v) {
                        foreach ($v['participants'] as $k1 => $v1) {
                            if($v1['role'] == "publisher"){

                                $v1['participant_info']['consult_speciality'] = $v1['participant_info']['additional_info']['consult_speciality'];

                                unset($v1['participant_info']['additional_info']);

                                $providers = Provider::Where('user_id',$v1['ref_number'])->first(["additional_info","license_no"]);

                               $v1['participant_info']['license_no'] = $providers?$providers['license_no']:'';
                               $v1['participant_info']['qualification'] = $providers?$providers['additional_info']?$providers['additional_info']->qualification:'':'';

                                $history_info[$key]['providers'] = $v1['participant_info'];
                                $history_info[$key]['lists'] = $medicine_records;
                            }
                        }
                    }
            }
            else{

                $history_info[$key]['providers'] = null;
                $history_info[$key]['lists'] = $medicine_records;
            }

        }

        $patient_details = $request->get('user_id')? User::where('id',$request->get('user_id'))->get(): [];
        return ["history_info"=>$history_info,"patient_details"=>$patient_details];
    }



    public function healthPDFx(Request $request){

        $resource = 'PatientHealth';
        $entity = new PatientHealth;

        $getResourceName = snake_case(camel_case(str_plural($resource)));

        $medicine_info = [];

        $x_consult_id = callUserFuncArray([$entity, 'getModelList'], [])->groupBy('consult_id')->pluck('consult_id');


        foreach ($x_consult_id as $key => $value) {


            $filters['consult_id'] = $value;
            $request->request->add(['consult_id' => $value?$value:'-1']);
            $collection = callUserFuncArray([$entity, 'getModelList'], [])->get();

            $medicine_records = $collection->isNotEmpty() ? $this->collectionTransform($resource, $collection) : [];

            if($value != null){
                $this->_teleconsult_service = new TeleConsultApiService;
                $consult_info  = $this->_teleconsult_service->fetch($filters, 1, 1);
                    foreach ($consult_info['data']['consults'] as $k => $v) {
                        foreach ($v['participants'] as $k1 => $v1) {
                            if($v1['role'] == "publisher"){

                                $v1['participant_info']['consult_speciality'] = $v1['participant_info']['additional_info']['consult_speciality'];

                                unset($v1['participant_info']['additional_info']);

                                $providers = Provider::Where('user_id',$v1['ref_number'])->first(["additional_info","license_no"]);

                               $v1['participant_info']['license_no'] = $providers?$providers['license_no']:'';

            $v1['participant_info']['qualification'] = $providers?$providers['additional_info']?$providers['additional_info']->qualification:'':'';

                                $medicine_info[$key]['providers'] = $v1['participant_info'];
                                $medicine_info[$key]['lists'] = $medicine_records;
                            }
                        }
                    }
            }
            else{

                $medicine_info[$key]['providers'] = null;
                $medicine_info[$key]['lists'] = $medicine_records;
            }

        }

        $patient_details = $request->get('user_id')? User::where('id',$request->get('user_id'))->get(): [];
        return ["medicine_info"=>$medicine_info,"patient_details"=>$patient_details];
    }


    public function vitalsPDFx(Request $request){

        $resource = 'Vital';
        $entity = new Vital;

        $getResourceName = snake_case(camel_case(str_plural($resource)));

        $vitals_info = [];

        $x_consult_id = callUserFuncArray([$entity, 'getModelList'], [])->groupBy('consult_id')->pluck('consult_id');


        foreach ($x_consult_id as $key => $value) {


            $filters['consult_id'] = $value;
            $request->request->add(['consult_id' => $value?$value:'-1']);
            $collection = callUserFuncArray([$entity, 'getModelList'], [])->get();

            $medicine_records = $collection->isNotEmpty() ? $this->collectionTransform($resource, $collection) : [];

            if($value != null){
                $this->_teleconsult_service = new TeleConsultApiService;
                $consult_info  = $this->_teleconsult_service->fetch($filters, 1, 1);
                    foreach ($consult_info['data']['consults'] as $k => $v) {
                        foreach ($v['participants'] as $k1 => $v1) {
                            if($v1['role'] == "publisher"){

                                $v1['participant_info']['consult_speciality'] = $v1['participant_info']['additional_info']['consult_speciality'];

                                unset($v1['participant_info']['additional_info']);

                                $providers = Provider::Where('user_id',$v1['ref_number'])->first(["additional_info","license_no"]);

                               $v1['participant_info']['license_no'] = $providers?$providers['license_no']:'';

            $v1['participant_info']['qualification'] = $providers?$providers['additional_info']?$providers['additional_info']->qualification:'':'';

                                $vitals_info[$key]['providers'] = $v1['participant_info'];
                                $vitals_info[$key]['lists'] = $medicine_records;
                            }
                        }
                    }
            }
            else{

                $vitals_info[$key]['providers'] = null;
                $vitals_info[$key]['lists'] = $medicine_records;
            }

        }

        $patient_details = $request->get('user_id')? User::where('id',$request->get('user_id'))->get(): [];
        return ["vitals_info"=>$vitals_info,"patient_details"=>$patient_details];
    }


    public function activityWellnessPDFx(Request $request){

        $resource   = 'ActivityWellness';
        $entity     = new ActivityWellness;

        $getResourceName = snake_case(camel_case(str_plural($resource)));

        $data_info    = [];
        $collection   = callUserFuncArray([$entity, 'getModelList'], [])->get();

        $records = $collection->isNotEmpty() ? $this->collectionTransform($resource, $collection) : [];
        $data_info['lists']     = $records;

        $patient_details = $request->get('user_id')? User::where('id',$request->get('user_id'))->get(): [];
        return ["data_info" => $data_info, "patient_details"=>$patient_details];
    }

    public function masterPDFx(Request $request){

        $resource   = 'Master';
        $entity     = new Master;

        $getResourceName = snake_case(camel_case(str_plural($resource)));

        $data_info    = [];
        $collection   = callUserFuncArray([$entity, 'getModelList'], [])->get();

        $records = $collection->isNotEmpty() ? $this->collectionTransform($resource, $collection) : [];

        $data_info['lists']     = $records;

        $patient_details = $request->get('patient_id')? User::where('id',$request->get('patient_id'))->get(): [];

        $return = ["data_info" => $data_info, "patient_details"=>$patient_details];
        return $this->httpResponse->setHttpData($return)->jsonResponse();
    }

    public function immunisationPDF_globalx(Request $request){

        $pdf_content = self::masterPDFx($request);

        $template = view('pdf_m.immunisation_reports', ['content' => $pdf_content->getData()->data, 'request' => $request]);

        return self::pdf_m($request, $template);
    }

    public function familyHistoryPDF_globalx(Request $request){

        $pdf_content = self::masterPDFx($request);

        $template = view('pdf_m.family_history_reports', ['content' => $pdf_content->getData()->data, 'request' => $request]);

        return self::pdf_m($request, $template);
    }

    public function docsPDFx(Request $request){

        $resource = 'Doc';
        $entity = new Doc;

        $getResourceName = snake_case(camel_case(str_plural($resource)));

        $docs_info = [];

        $x_consult_id = callUserFuncArray([$entity, 'getModelList'], [])->groupBy('consult_id')->pluck('consult_id');

        foreach ($x_consult_id as $key => $value) {


            $filters['consult_id'] = $value;
            $request->request->add(['consult_id' => $value?$value:'-1']);
            $collection = callUserFuncArray([$entity, 'getModelList'], [])->get();

            $medicine_records = $collection->isNotEmpty() ? $this->collectionTransform($resource, $collection) : [];

            if($value != null){
                $this->_teleconsult_service = new TeleConsultApiService;
                $consult_info  = $this->_teleconsult_service->fetch($filters, 1, 1);
                    foreach ($consult_info['data']['consults'] as $k => $v) {
                        foreach ($v['participants'] as $k1 => $v1) {
                            if($v1['role'] == "publisher"){

                                $v1['participant_info']['consult_speciality'] = $v1['participant_info']['additional_info']['consult_speciality'];

                                unset($v1['participant_info']['additional_info']);

                                $providers = Provider::Where('user_id',$v1['ref_number'])->first(["additional_info","license_no"]);

                               $v1['participant_info']['license_no'] = $providers?$providers['license_no']:'';
                               $v1['participant_info']['qualification'] = $providers?$providers['additional_info']?$providers['additional_info']->qualification:'':'';

                                $docs_info[$key]['providers'] = $v1['participant_info'];
                                $docs_info[$key]['lists'] = $medicine_records;
                            }
                        }
                    }
            }
            else{

                $docs_info[$key]['providers'] = null;
                $docs_info[$key]['lists'] = $medicine_records;
            }

        }

        $patient_details = $request->get('user_id')? User::where('id',$request->get('user_id'))->get(): [];
        return ["docs_info"=>$docs_info,"patient_details"=>$patient_details];
    }

    public function ReviewOfSystemPDFx(Request $request){

        $resource = 'ReviewOfSystems';
        $entity = new ReviewOfSystem;

        $getResourceName = snake_case(camel_case(str_plural($resource)));

        $collection   = callUserFuncArray([$entity, 'getModelList'], [])->get();

        $records = $collection->isNotEmpty() ? $this->collectionTransform($resource, $collection) : [];

        $patient_details = $request->get('user_id')? User::where('id',$request->get('user_id'))->get(): [];

        return ["ros_info"=>$records,"patient_details"=>$patient_details];
    }

    public function PhysicalExaminationPDFx(Request $request){

        $resource = 'physicalExaminations';
        $entity = new PhysicalExamination;

        $getResourceName = snake_case(camel_case(str_plural($resource)));

        $collection   = callUserFuncArray([$entity, 'getModelList'], [])->get();

        $records = $collection->isNotEmpty() ? $this->collectionTransform($resource, $collection) : [];

        $patient_details = $request->get('user_id')? User::where('id',$request->get('user_id'))->get(): [];

        return ["pe_info"=>$records,"patient_details"=>$patient_details];
    }

    public function formPDFx(Request $request){

        $resource = 'Form';
        $entity = new Form;

        $getResourceName = snake_case(camel_case(str_plural($resource)));

        $collection   = callUserFuncArray([$entity, 'getModelList'], [])->get();

        $records = $collection->isNotEmpty() ? $this->collectionTransform($resource, $collection) : [];

        $patient_details = $request->get('user_id')? User::where('id',$request->get('user_id'))->get(): [];

        $return = ["form_info" => $records, "patient_details"=>$patient_details];
        return $this->httpResponse->setHttpData($return)->jsonResponse();
    }

    public function userDetails(Request $request){

        $patient_details = $request->get('user_id')? User::where('id',$request->get('user_id'))->get(): [];
        return $patient_details;
    }

    public function vitalsPDF_globalx(Request $request){

        $pdf_content = self::vitalsPDFx($request);

        $template = view('pdf_m.vitals_reports', ['content' => $pdf_content, 'request' => $request]);

        return self::pdf_m($request, $template);
    }

    public function healthPDF_globalx(Request $request){

        $pdf_content = self::healthPDFx($request);

        $template = view('pdf_m.health_reports', ['content' => $pdf_content, 'request' => $request]);

        return self::pdf_m($request, $template);
    }

    public function historyPDF_globalx(Request $request){

        $pdf_content = self::historyPDFx($request);
        // dd($pdf_content);
        $template = view('pdf_m.history_reports', ['content' => $pdf_content, 'request' => $request]);

        return self::pdf_m($request, $template);
    }

    public function docsPDF_globalx(Request $request){

        $pdf_content = self::docsPDFx($request);

        $template = view('pdf_m.docs_reports', ['content' => $pdf_content, 'request' => $request]);

        return self::pdf_m($request, $template);
    }

    public function ReviewOfSystem_globalx(Request $request){

        $pdf_content = self::ReviewOfSystemPDFx($request);

        $template = view('pdf_m.ros_reports', ['content' => $pdf_content, 'request' => $request]);

        return self::pdf_m($request, $template);
    }

    public function physicalExamination_globalx(Request $request){

        $pdf_content = self::PhysicalExaminationPDFx($request);

        $template = view('pdf_m.pe_reports', ['content' => $pdf_content, 'request' => $request]);

        return self::pdf_m($request, $template);
    }

    public function activityWellnessPDF_globalx(Request $request){

        $pdf_content = self::activityWellnessPDFx($request);
        // dd($pdf_content);
        $template = view('pdf_m.activity_wellness_reports', ['content' => $pdf_content, 'request' => $request]);

        return self::pdf_m($request, $template);
    }

    public function assessmentPDF_globalx(Request $request){

        $pdf_content = self::formPDFx($request);

        $template = view('pdf_m.assessment_reports', ['content' => $pdf_content->getData()->data, 'request' => $request]);

        return self::pdf_m($request, $template);
    }

    public function pdf_m(Request $request, $template) {

        $mpdf = new Mpdf(['tempDir' => storage_path('app/pdf')]);

        $title = $request->get('slug');

        if($title == null){
            if($request->get('act_catagory')){
                 $title = $request->get('act_catagory');
             }
        }


        if($request->get('title')){
             $title = $request->get('title');
         }

        $mpdf->WriteHTML($template);

        $mpdf->SetTitle(strtoupper($title));

        $a = $mpdf->Output('', 'S');

        $fileName = 'A2Z_'.strtoupper($title).'_REPORTS.pdf';

        $this->httpResponse
            ->setHttpData($a)
            ->setHttpHeader([
                'Content-Type' => 'application/pdf',
                'Content-disposition' => 'inline; filename=' .$fileName
            ]);

        return $this->httpResponse->streamResponse();

    }



    public function patientSummary(Request $request): JsonResponse
    {   

        if($request->get('patient_id')){
            $patient_id = $request->get('patient_id');
            $consult_id = null;
            $request['patient_id']= $request->get('patient_id');
            $request['user_id'] = $request->get('patient_id');
        }else{

            $getpatient_id = self::getConsultInfo($request);
            $patient_id = $getpatient_id['patient_id'];
            $consult_id = $getpatient_id['consult_id'];
            $request['patient_id']= $patient_id;
            $request['user_id'] = $patient_id;

        }
        
        $entityService = new EntityService;

        $request['limit'] = 3;

        if($request->get('from') && $request->get('to')){
            $request['limit'] = null;
        }

        if($request->get('pdf_for') == 'consult_report'){
            $request['consult_id']= $consult_id;
        }

        // $summary['1_profile'] = $request->user()->Where('id',$patient_id)->first(['first_name','last_name','dob','gender','blood_group']);

        $summary['a_profile'] = User::Where('id',$patient_id)->first();


        if($request->get('pdf_for') == 'consult_report'){
             $providers = Provider::Where('user_id',$getpatient_id['provider_info']['ref_number'])->first(["additional_info","license_no"]);

             $getpatient_id['provider_info']['license_no'] = $providers?$providers['license_no']:'';
             $getpatient_id['provider_info']['qualification'] = $providers?$providers['additional_info']?$providers['additional_info']->qualification:'':'';

              $getpatient_id['provider_info']['consult_speciality'] = $getpatient_id['provider_info']['additional_info']['consult_speciality'];


            $summary['c_provider_details'] = $getpatient_id['provider_info'];
            $summary['b_consult_details'] = $getpatient_id['consultInfo'];
        }

        // HEALTH

        unset($request['slug'],$request['resource'],$request['entity']);

        $request['resource']= 'PatientHealth';
        $request['entity']= new PatientHealth;

        $request['slug']= 'allergy';
        $allergy = $entityService->getLimitEntity($request);
        $health['a_allergy'] = $allergy->getData()->data;

        $request['slug']= 'medicine';
        $medicine = $entityService->getLimitEntity($request);
        $health['b_medicine'] = $medicine->getData()->data;

        $request['slug']= 'diet';
        $diet = $entityService->getLimitEntity($request);
        $health['c_diet'] = $diet->getData()->data;

        $request['slug']= 'hpi';
        $hpi = $entityService->getLimitEntity($request);
        $health['d_hpi'] = $hpi->getData()->data;

        $request['slug']= 'procedure';
        $procedure = $entityService->getLimitEntity($request);
        $health['e_procedure'] = $procedure->getData()->data;



        // unset($request['slug'],$request['resource'],$request['entity']);
        
        // $request['resource']= 'Master';
        // $request['entity']= new Master;
        // $request['slug']= 'vdx';
        // $vdx = $entityService->getLimitEntity($request);
        // $health['f_vdx'] = $vdx->getData()->data;

        // foreach ($health['f_vdx'] as $key => $value) {
        //     unset($request['slug']);
        //     $request['attr_slug']= $value->slug;
        //     $vdxType = $entityService->getEntity($request);
        //     $value->type = $vdxType->getData()->data;

        //     foreach ($value->type as $k => $v) {
                
        //         unset($request['slug']);
        //         $request['attr_slug']= $v->slug;
        //         $vdxsubType = $entityService->getEntity($request);
        //         $v->sub_type = $vdxsubType->getData()->data;
        //     }

        // }

        unset($request['slug'],$request['resource'],$request['entity']);
        
        $request['resource']= 'Master';
        $request['entity']= new Master;
        $request['slug']= 'symptoms_reason';
        $vdx = $entityService->getEntity($request);
        $health['f_symptoms_reason'] = $vdx->getData()->data;

        // HISTORIES

        unset($request['slug'],$request['resource'],$request['entity']);

        $request['resource']= 'PatientHistory';
        $request['entity']= new PatientHistory;

        $request['slug']= 'medical-history';
        $medical_history = $entityService->getLimitEntity($request);
        $history['a_medical_history'] = $medical_history->getData()->data;

        $request['slug']= 'social-history';
        $social_history = $entityService->getLimitEntity($request);
        $history['c_social_history'] = $social_history->getData()->data;

        $request['slug']= 'surgical-history';
        $surgical_history = $entityService->getLimitEntity($request);
        $history['b_surgical_history'] = $surgical_history->getData()->data;

        unset($request['slug'],$request['resource'],$request['entity']);

        $request['resource']= 'ReviewOfSystem';
        $request['entity']= new ReviewOfSystem;

        $ros_history = $entityService->getLimitEntity($request);
        $history['e_ros'] = $ros_history->getData()->data;

        unset($request['slug'],$request['resource'],$request['entity']);

        $request['resource']= 'PhysicalExamination';
        $request['entity']= new PhysicalExamination;

        $pe_history = $entityService->getLimitEntity($request);
        $history['f_pe'] = $pe_history->getData()->data;

        unset($request['slug'],$request['resource'],$request['entity']);

        $request['resource']= 'PatientHistory';
        $request['entity']= new PatientHistory;
        
        $request['slug']= 'stroke-scale';
        $stroke_scale = $entityService->getLimitEntity($request);
        $history['g_stroke_scale'] = $stroke_scale->getData()->data;


        unset($request['slug'],$request['resource'],$request['entity']);

        $request['resource']= 'Master';
        $request['entity']= new Master;

        $request['slug']= 'family_history_diseases';
        $family_history = $entityService->getEntity($request);
        $history['d_family_history'] = $family_history->getData()->data;

        // DOCUMENTS


        unset($request['slug'],$request['resource'],$request['entity']);

        $request['resource']= 'Doc';
        $request['entity']= new Doc;


        $request['slug']= 'lab';
        $lab = $entityService->getLimitEntity($request);
        $docs['a_lab'] = $lab->getData()->data;

        $request['slug']= 'imaging';
        $imaging = $entityService->getLimitEntity($request);
        $docs['b_imaging'] = $imaging->getData()->data;

        $request['slug']= 'icd';
        $icd = $entityService->getLimitEntity($request);
        $docs['c_icd'] = $icd->getData()->data;

        $request['slug']= 'chief-complaints';
        $chief_complaints = $entityService->getLimitEntity($request);
        $docs['d_chief_complaints'] = $chief_complaints->getData()->data;

        // $request['slug']= 'health-insurance';
        // $health_insurance = $entityService->getLimitEntity($request);
        // $docs['e_health_insurance'] = $health_insurance->getData()->data;


        unset($request['slug'],$request['resource'],$request['entity']);

        $request['resource']= 'Master';
        $request['entity']= new Master;

        $request['slug']= 'immunisation';
        $immunisation = $entityService->getEntity($request);
        $vaccine['a_immunisation'] = $immunisation->getData()->data;


        // VITALS

        unset($request['slug'],$request['resource'],$request['entity']);

        $request['resource']= 'Vital';
        $request['entity']= new Vital;

        $request['slug']= 'bmi';
        $bmi = $entityService->getLimitEntity($request);
        $vitals['a_bmi'] = $bmi->getData()->data;


        $request['slug']= 'temperature';
        $temperature = $entityService->getLimitEntity($request);
        $vitals['b_temperature'] = $temperature->getData()->data;


        $request['slug']= 'blood-sugar';
        $bs = $entityService->getLimitEntity($request);
        $vitals['c_bs'] = $bs->getData()->data;


        $request['slug']= 'spO2';
        $spo2 = $entityService->getLimitEntity($request);
        $vitals['d_spo2'] = $spo2->getData()->data;


        $request['slug']= 'urine';
        $urine = $entityService->getLimitEntity($request);
        $vitals['e_urine'] = $urine->getData()->data;


        $request['slug']= 'blood-pressure';
        $bp = $entityService->getLimitEntity($request);
        $vitals['f_bp'] = $bp->getData()->data;


        $request['slug']= 'heart-rate';
        $heart = $entityService->getLimitEntity($request);
        $vitals['g_heart'] = $heart->getData()->data;


        $request['slug']= 'lipid-profile';
        $lipid = $entityService->getLimitEntity($request);
        $vitals['h_lipid'] = $lipid->getData()->data;


        $request['slug']= 'respiration';
        $respiration = $entityService->getLimitEntity($request);
        $vitals['i_respiration'] = $respiration->getData()->data;

        
       
        $request['slug']= 'hct';
        $respiration = $entityService->getLimitEntity($request);
        $vitals['j_hct'] = $respiration->getData()->data;

        $request['slug']= 'hemoglobin';
        $respiration = $entityService->getLimitEntity($request);
        $vitals['k_hemoglobin'] = $respiration->getData()->data;

        $request['slug']= 'keytone';
        $respiration = $entityService->getLimitEntity($request);
        $vitals['l_keytone'] = $respiration->getData()->data;
        
        $request['slug']= 'uric_acid';
        $respiration = $entityService->getLimitEntity($request);
        $vitals['m_uric_acid'] = $respiration->getData()->data;


        return $this->httpResponse->setHttpData([
            'profile'=>$summary,
            'vitals'=>$vitals,
            'history'=>$history,
            'health'=>$health,
            'immunisation'=>$vaccine,
            'docs'=>$docs
        ])->jsonResponse();
    }



    public function patientSummaryPdf(Request $request){

        $pdf_content = self::patientSummary($request);

        // dd($pdf_content->getData()->data);

        $request->request->add(['title' => 'Patient summary report']);
        $template = view('pdf_m.patient_summary', ['content' => $pdf_content->getData()->data, 'request' => $request]);

        return self::pdf_m($request, $template);
    }

    public function consultSummaryPdf(Request $request){


        $request->request->add(['pdf_for' => 'consult_report']);

        $pdf_content = self::patientSummary($request);

        // dd($pdf_content->getData()->data);

        $request->request->add(['title' => 'Consult summary report']);
        $template = view('pdf_m.patient_summary', ['content' => $pdf_content->getData()->data, 'request' => $request]);

        return self::pdf_m($request, $template);
    }

    public function analytics(Request $request): JsonResponse{

        try{
            // $hospital_id = 1;
            $hospital_id = $request->user()->staff->hospital_id;

            $getPatientUserId = Patient::Where('hospital_id',$hospital_id)->pluck('user_id');

            $getProviderUserId = Provider::Where('hospital_id',$hospital_id)->pluck('id');


            $getVitals = Vital::whereIn('user_id',$getPatientUserId)->orderBy('details->date','desc');

            if($request->get('from') && $request->get('to')){

                $from = date('Y-m-d',strtotime($request->get('from')));
                $to = date('Y-m-d',strtotime($request->get('to')));
                $getVitals = $getVitals->whereBetween('details->date', [$from,$to]);

            }

            $getVitals = $getVitals->get()->groupBy('slug');
       
            $result = [];

            $result['age_counts'] =  EnumAnalyticsChart::AgeCounts($getPatientUserId);
            $result['provider_counts'] =  EnumAnalyticsChart::SpecialityCounts($getProviderUserId);

            foreach ($getVitals as $key => $value) {
                switch ($key) {
                    case 'bmi':
                        $result['bmi'] =  EnumAnalyticsChart::ChartCounts($value,$key);
                        break;

                    case 'temperature':
                        $result['temperature'] =  EnumAnalyticsChart::ChartCounts($value,$key);
                        break;

                    case 'spO2':
                        $result['spo'] =  EnumAnalyticsChart::ChartCounts($value,$key);
                        break;

                    case 'blood-pressure':
                        $result['bp'] =  EnumAnalyticsChart::ChartCounts($value,$key);
                        break;

                    case 'blood-sugar':
                        $result['bs'] =  EnumAnalyticsChart::ChartCounts($value,$key);
                        break;

                    case 'heart-rate':
                        $result['heart'] =  EnumAnalyticsChart::ChartCounts($value,$key);
                        break;

                    case 'urine':

                        $urine['urine_leukocytes'] =  EnumAnalyticsChart::ChartCounts($value,'urine_leukocytes');

                        $urine['urine_protein'] =  EnumAnalyticsChart::ChartCounts($value,'urine_protein');

                        $urine['urine_rbc'] =  EnumAnalyticsChart::ChartCounts($value,'urine_rbc');

                        $result['urine_lpr'] = array_merge(['leukocytes'=>$urine['urine_leukocytes']],['protein'=>$urine['urine_protein']],['rbc'=>$urine['urine_rbc']]);

                        $result['urine_sugar'] =  EnumAnalyticsChart::ChartCounts($value,'urine_sugar');

                        $result['urine'] =  EnumAnalyticsChart::ChartCounts($value,$key);
                        break;

                    case 'lipid-profile':
                        $lipid['lipid_ldl'] =  EnumAnalyticsChart::ChartCounts($value,'lipid_ldl');

                        $lipid['lipid_hdl'] =  EnumAnalyticsChart::ChartCounts($value,'lipid_hdl');

                        $lipid['lipid_vldl'] =  EnumAnalyticsChart::ChartCounts($value,'lipid_vldl');

                        $lipid['lipid_ldl'] =  EnumAnalyticsChart::ChartCounts($value,'lipid_ldl');

                        $lipid['lipid_hdl_ldl'] =  EnumAnalyticsChart::ChartCounts($value,'lipid_hdl_ldl');

                        $lipid['lipid_tri'] =  EnumAnalyticsChart::ChartCounts($value,'lipid_tri');

                        $lipid['lipid_total'] =  EnumAnalyticsChart::ChartCounts($value,'lipid_total');

                        $result['lipid'] = array_merge(['ldl'=>$lipid['lipid_ldl']],['hdl'=>$lipid['lipid_hdl']],['vldl'=>$lipid['lipid_vldl']],['hdl_ldl'=>$lipid['lipid_hdl_ldl']],['tri'=>$lipid['lipid_tri']],['total'=>$lipid['lipid_total']]);

                        break;

                    case 'respiration':
                        $result['respiration'] =  EnumAnalyticsChart::ChartCounts($value,$key);
                        break;

                    case 'keytone':
                        $result['keytone'] =  EnumAnalyticsChart::ChartCounts($value,$key);
                        break;

                    case 'hct':
                        $result['hct'] =  EnumAnalyticsChart::ChartCounts($value,$key);
                        break;

                    case 'hemoglobin':
                        $result['hemoglobin'] =  EnumAnalyticsChart::ChartCounts($value,$key);
                        break;

                    case 'uric_acid':
                        $result['uric_acid'] =  EnumAnalyticsChart::ChartCounts($value,$key);
                        break;
                    
                    default:
                        // code...
                        break;
                }
            }
           
           // dd($result);

            return $this->httpResponse->setHttpData($result)->jsonResponse();

        } catch (Exception $e) {
            exceptionLogger("Failed ", $e);
            return false;
        }
    }

    public function getPeriperalOtp(Request $request,$id){

        $peripheralApiService = new PeripheralApiService();

        $peripheral_credentials = $peripheralApiService->get($id);

        if(isset($peripheral_credentials['id'])) {

            $peripheral_user_data = [
                "generate_password" => "create",
            ];                    

            $peripheralApiService->patch($peripheral_credentials['id'], $peripheral_user_data);

        } else {

            $peripheral_user_data = [
                "ref_number" => $id
            ];

            $peripheralApiService->create($peripheral_user_data);

        }

        return ['result'=>json_decode($peripheralApiService->apiResponse)];
    }

}
