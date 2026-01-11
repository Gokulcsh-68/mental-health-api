<?php

namespace App\Utils;

use App\Enums\UserEventTypeEnum;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Cache;
use lfkeitel\phptotp\Totp;

trait AuthHelper
{
    private $otpExpiry = 300;

    /**
     * Generate JWT token for given data
     */
    protected function getAuthorization($data)
    {
        $key = config('app.jwt.key');
        $ttl = config('app.jwt.ttl');

        $tokenId = base64_encode(openssl_random_pseudo_bytes(32));
        $ip = request()->get('ip') ?: request()->ip();
        $expiration = Carbon::now()->addSeconds($ttl + 5)->timestamp;

        $token = [
            "jti"  => $tokenId,
            "iss"  => $ip,
            "exp"  => $expiration,
            "data" => $data,
        ];

        return aesEncrypt(
            JWT::encode($token, $key, 'HS256')
        );
    }

    /**
     * Refresh an existing JWT token
     */
    public function refreshToken($request, $decoded_token)
    {
        $key = app('config')->get('app.jwt.key');
        $ttl = app('config')->get('app.jwt.ttl');

        $decoded_token->exp = Carbon::now()->addSeconds($ttl + 5)->timestamp;

        return [
            'token' => aesEncrypt(
                JWT::encode((array) $decoded_token, $key, 'HS256')
            ),
            'token_expiration_time' => $decoded_token->exp
        ];
    }

    /**
     * Decode a JWT token
     */
    protected function decodeJwt($token, $keyConfig = 'key')
    {
        $key = app('config')->get("app.jwt.$keyConfig");

        return JWT::decode(
            aesDecrypt($token),
            new Key($key, 'HS256')
        );
    }

    /**
     * Generate OTP for a secret
     */
    protected function generateOtp($secret, $startTime = 0, $length = 6)
    {
        if ($startTime === 0) {
            $startTime = Carbon::now()->second;
        }

        $otp = (new Totp('sha1', $startTime, $this->otpExpiry))
            ->GenerateToken($secret, null, $length);

        Cache::put($secret, $startTime, $this->otpExpiry);

        return $otp;
    }


    /**
     * Validate OTP for a secret
     */
    protected function validateOtp($secret, $otp)
    {
        if (!Cache::has($secret)) {
            return 'expired';
        }

        $startTime = Cache::get($secret);

        return $this->generateOtp($secret, $startTime) === $otp;
    }
    /**
     * Handle successful login
     */
    protected function successLogin($user)
    {
        $user->captureEvent(UserEventTypeEnum::Login);
        $user->last_access = Carbon::now();
        $user->unsetEventDispatcher();
        $user->save();

        return [];
    }
}
