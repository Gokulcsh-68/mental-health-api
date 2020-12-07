<?php

namespace App\Utils;

use App\Enums\UserEventTypeEnum;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cache;
use lfkeitel\phptotp\Totp;

trait AuthHelper
{

    private $otpExpiry = 300;

    protected function getAuthorization($data)
    {
        $key = config('app.jwt.key');
        $ttl = config('app.jwt.ttl');
        $tokenId = base64_encode(openssl_random_pseudo_bytes(32));
        $ip = app('request')->get('ip') ? app('request')->get('ip') : app('request')->ip();
        $expiration = Carbon::now()->addSeconds($ttl + 5)->timestamp;
        $token = [
            "jti" => $tokenId,
            "iss" => $ip,
            "exp" => $expiration,
            "data" => $data,
        ];

        return aesEncrypt(JWT::encode($token, $key));
    }

    protected function decodeJwt($token, $keyConfig = 'key')
    {
        $key = config("app.jwt.$keyConfig");
        return JWT::decode(aesDecrypt($token), $key, ['HS256']);
    }

    protected function generateOtp($secret, $startTime = 0, $length = 6)
    {
        if ($startTime == 0) {
            $startTime = Carbon::now()->second;
        }

        $otp = (new Totp('sha1', $startTime, $this->otpExpiry))->GenerateToken($secret, null, $length);
        Cache::put($secret, $startTime, $this->otpExpiry);

        return $otp;
    }

    protected function validateOtp($secret, $otp): bool
    {
        $startTime = 0;
        if (Cache::has($secret)) {
            $startTime = Cache::get($secret);
        }

        return $this->generateOtp($secret, $startTime) === $otp;
    }

    protected function successLogin($user)
    {
        $result = [];
        $user->captureEvent(UserEventTypeEnum::Login);
        $user->last_access = Carbon::now();
        $user->unsetEventDispatcher();
        $user->save();

        return $result;
    }
}
