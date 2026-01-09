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

        // JWT v6+: specify algorithm explicitly
        return aesEncrypt(JWT::encode($token, $key, 'HS256'));
    }

    /**
     * Refresh an existing JWT token
     */
    public function refreshToken($request, $decoded_token)
    {
        $key = config('app.jwt.key');
        $ttl = config('app.jwt.ttl');

        $expiration = Carbon::now()->addSeconds($ttl + 5)->timestamp;
        $decoded_token->exp = $expiration;

        // Convert object to array to avoid JWT v6 issues
        $tokenArray = json_decode(json_encode($decoded_token), true);

        return [
            'token' => aesEncrypt(JWT::encode($tokenArray, $key, 'HS256')),
            'token_expiration_time' => $decoded_token->exp
        ];
    }

    /**
     * Decode a JWT token
     */
    protected function decodeJwt($token, $keyConfig = 'key')
    {
        $key = config("app.jwt.$keyConfig");

        // ✅ Use Key object for JWT v6+
        return JWT::decode(aesDecrypt($token), new Key($key, 'HS256'));
    }

    /**
     * Generate OTP for a secret
     */
    protected function generateOtp($secret, $startTime = null, $length = 6)
    {
        if ($startTime === null) {
            $startTime = time(); // Use full UNIX timestamp
        }

        $otp = (new Totp('sha1', $startTime, $this->otpExpiry))
            ->GenerateToken($secret, null, $length);

        // Store the start time in cache for future validation
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

        // Compare OTP using the same start time
        return $this->generateOtp($secret, $startTime) === $otp;
    }

    /**
     * Handle successful login
     */
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
