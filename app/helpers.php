<?php

use Carbon\Carbon;

if (!function_exists('app_path')) {

    function app_path()
    {
        return base_path('app');
    }    
}


if (!function_exists('aesEncrypt')) {
    
    function aesEncrypt($plainText, $method = 'aes-256-cbc')
    {
        $password = config('app.key');
        $key = substr(hash('sha256', $password, true), 0, 32);
        $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

        return base64_encode(openssl_encrypt($plainText, $method, $key, OPENSSL_RAW_DATA, $iv));
    }
}

if (!function_exists('aesDecrypt')) {
    
    function aesDecrypt($encrypted, $method = 'aes-256-cbc')
    {
        $password = config('app.key');
        $key = substr(hash('sha256', $password, true), 0, 32);
        $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

        return openssl_decrypt(base64_decode($encrypted), $method, $key, OPENSSL_RAW_DATA, $iv);

    }
}

if (!function_exists('exceptionLogger')) {
    
    function exceptionLogger($name, $e)
    {
        $content = sprintf('%s File name : %s Line at %s', $e->getMessage(), $e->getFile(), $e->getLine());
        \Log::error($name . " Exception : " . $content);
    }
}

if(!function_exists('callUserFuncArray')){
    function callUserFuncArray($function, $param)
    {
        return call_user_func_array($function, $param);
    }
}

if (!function_exists('logInfo')) {
    function logInfo($message, $enableLog =  false)
    {
        if (env('APP_ENV') != 'production' || $enableLog) {
            \Log::info($message);
        }
    }
}

if (! function_exists('dd')) {
    function dd(...$args)
    {
        foreach ($args as $x) {
            Symfony\Component\VarDumper\VarDumper::dump($x);
        }
        die(1);
    }
}

if (!function_exists('apiUrl')) {
    function apiUrl($config, $action)
    {
        return config('api.' . $config . '.baseUrl') . config('api.' . $config . '.' . $action);
    }
}

if (!function_exists('internalCodeException')) {
    function internalCodeException(string $message, int $internalCode)
    {
        throw new Symfony\Component\HttpKernel\Exception\ConflictHttpException($message, null, $internalCode);
    }
}

if (!function_exists('dateTimezoneConversion')) {
    function dateTimezoneConversion($dateTime, $to = "UTC")
    {
        if (!$dateTime instanceof Carbon) {
            $dateTime = Carbon::createFromFormat('Y-m-d H:i:s', $dateTime, 'UTC');
        }
        
        return $dateTime->setTimezone($to);
    }
}


if (!function_exists('isJSON')) {
    function isJSON($string)
    {
        return is_string($string) && is_array(json_decode($string, true)) ? true : false;
    }
}
