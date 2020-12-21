<?php

namespace App\Services;

use App\Entities\User;
use App\Requests\GeneralLoginRequest;
use App\Utils\AuthHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Enums\InternalCodeEnum;
use App\Enums\EmailTemplateEnum;
use App\Requests\VerifyOtpRequest;
use App\Jobs\SendEmailJob;
// use App\Requests\ChangeEmailRequest;
// use App\Requests\VerifyEmailRequest;
// use App\Requests\SetPasswordRequest;
// use App\Requests\PatientCheckRequest;
use Illuminate\Http\JsonResponse;

// use App\Requests\PatientLoginRequest;
// use App\Requests\ChangePasswordRequest;
// use App\Requests\ChangeUserPasswordRequest;
// use App\Requests\ForgotPasswordEmailRequest;

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
                $job = (new SendEmailJob(['otp' => $this->generateOtp($user->secret), 'email' => $user->email, 'name' => $user->getFullName(), 'template' => EmailTemplateEnum::Otp]))->onQueue('sendEmail');
                    dispatch($job);

                return $this->httpResponse->setHttpCode(409)
                    ->setHttpData(['2fa' => 'active'])
                    ->setHttpData(['reference_otp' => $this->generateOtp($user->secret)])
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

    // public function changePassword(ChangePasswordRequest $request): JsonResponse
    // {
    //     $user = $request->user();
    //     $user->update(['password' => $request->get('password')]);
    //     $user->captureEvent(UserEventTypeEnum::PasswordChange);

    //     return $this->httpResponse->jsonResponse();
    // }

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

    // public function forgotPasswordEmail(ForgotPasswordEmailRequest $request, User $user): JsonResponse
    // {
    //     $user = $user->where('email', $request->get('email'))
    //         ->first();
    //     if ($user) {
    //         $user->email_verify_token = base64_encode(openssl_random_pseudo_bytes(32));
    //         $customAttributes = (array) $user->custom_attributes;
    //         $customAttributes['email_verify_send_on'] = Carbon::now()->toDateTimeString();
    //         $user->custom_attributes = $customAttributes;
    //         $user->save();
    //         $emailVerifyToken = aesEncrypt($user->email_verify_token . ":" . $user->email . ":" . EmailTemplateEnum::ForgotPassword);

    //         $job = (new SendEmailJob(['token' => $emailVerifyToken, 'email' => $user->email, 'name' => $user->getFullName(), 'template' => EmailTemplateEnum::ForgotPassword]))->onQueue('sendEmail');
    //         dispatch($job);

    //         $this->httpResponse->setHttpMessage("Password reset link sent to email.");
    //     } else {
    //         $this->httpResponse->setHttpMessage("Email not found")->setHttpCode(404);
    //     }

    //     return $this->httpResponse->jsonResponse();
    // }

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
        $user = $user->generalLoginAttempt($requestedData);

        if ($user) {

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
                    ->setHttpHeader(['Authorization' => $Authorization])
                    ->jsonResponse();

            } else {
                $this->httpResponse->setHttpMessage("Invalid OTP.")->setHttpCode(400);
                return $this->httpResponse->jsonResponse();
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

    // /**
    //  * Resend Otp.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  \App\Entities\User  $user
    //  * @return json
    //  */

    public function resendOtp(Request $request, User $user): JsonResponse
    {
        $message = trans('auth.failed');
        $requestedData = $request->json()->all();
        $user = $user->generalLoginAttempt($requestedData);

        if ($user) {
            $job = (new SendEmailJob(['otp' => $this->generateOtp($user->secret), 'email' => $user->email, 'name' => $user->getFullName(), 'template' => EmailTemplateEnum::Otp]))->onQueue('sendEmail');
            dispatch($job);

            return $this->httpResponse->setHttpCode(409)
                ->setHttpData(['2fa' => 'active'])
                ->setHttpData(['reference_otp' => $this->generateOtp($user->secret)])
                ->setHttpMessage("Resend OTP sent.")
                ->jsonResponse();

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
