<?php

return [
    "timezone" => env('APP_TIMEZONE', 'UTC'),
    "app" => [
        "jwtKey" => env('JWT_SECRET', 'LjnFPiUKwPmvwIl1b4tZ2I48DzPFpTx1'),
        "jwtTtl" => env('JWT_TTL', 864000000),
    ],
    'fileSystem' => [
        'admin' => env('S3_BASE_PATH') . 'school-folio/%s/',
        'school' => env('S3_BASE_PATH') . 'school-folio/%s/',
        'staff' => env('S3_BASE_PATH') . 'school-folio/%s/',
        'provider' => env('S3_BASE_PATH') . 'school-folio/%s/',
        'student' => env('S3_BASE_PATH') . 'school-folio/%s/',
    ],
    'communication_sms_template' => [
        'forgotPassword' => 'Your ' . config('app.name') . ' forget OTP is {{otp}}',
        '2faAuthentication' => '{{otp}} is your ' . config('app.name') . ' verification code',
    ],
    'slack' => [
        'url' => env('SLACK_WEBHOOK', 'https://hooks.slack.com/services/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'),
        'channels' => env('SLACK_CHANNELS', 'api-logs'),
    ],
];
