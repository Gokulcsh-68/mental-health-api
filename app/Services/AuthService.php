<?php

namespace App\Services;

use App\Entities\User;
use App\Entities\Role;
use App\Requests\ChangePasswordRequest;
use App\Requests\GeneralLoginRequest;
use App\Requests\TwofaRequest;
use App\Requests\VerifyOtpRequest;
use App\Requests\ResendOtpRequest;
use App\Transformers\UserTransformer;
use App\Utils\AuthHelper;
use Carbon\Carbon;
use App\Enums\InternalCodeEnum;
use App\Enums\EmailTemplateEnum;
use App\Jobs\SendEmailJob;
use App\Notifications\InvoicePaid;
use App\Notifications\OtpNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
// use App\Requests\ChangeEmailRequest;
// use App\Requests\VerifyEmailRequest;
// use App\Requests\SetPasswordRequest;
// use App\Requests\PatientCheckRequest;
// use App\Requests\ChangeUserPasswordRequest;
use App\Requests\ForgotPasswordEmailRequest;

class AuthService extends BaseService
{
    use AuthHelper;

    /**
     * General login.
     *
     * @param  \App\Requests\GeneralLoginRequest  $request
     * @param  \App\Entities\User  $user
     * @return json
     */

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

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = $request->user();
        $user->update(['password' => $request->get('password')]);
        // $user->captureEvent(UserEventTypeEnum::PasswordChange);

        return $this->httpResponse
                    ->setHttpMessage("Password Updated Successfully!...")
                    ->jsonResponse();
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
        if ($request->get('action') == 'forgotPassword') {
            $user = User::where('email', $request->get('email'))
                ->where('role_id', $roleId)
                ->first();

            if (!empty($user)) {
                if ($this->validateOtp($user->secret, $request->get('otp'))) {
                    $this->httpResponse->setHttpMessage("OTP Verified Successfully.")
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

                    return $this->httpResponse->setHttpData($result)
                        ->setHttpData(['status' => 'OTP_VERIFIED'])
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
        $data['message'] = '';
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
        }
        $data += [
            'otp' => $this->generateOtp($data['secret']),
            'email' => $data['email'],
            'name' => $user->getFullName(),
            'template' => EmailTemplateEnum::Otp
        ];
        $user->notify(new OtpNotification($data));
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
