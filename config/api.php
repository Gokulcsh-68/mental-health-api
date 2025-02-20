<?php

return [
    "timezone" => env('APP_TIMEZONE', 'UTC'),
    "health_record_freeze_hours" => env('PATIENT_HEALTH_RECORD_FREEZE_HOURS', '24'),
    "app" => [
        "jwtKey" => env('JWT_SECRET', 'LjnFPiUKwPmvwIl1b4tZ2I48DzPFpTx1'),
        "jwtTtl" => env('JWT_TTL', 864000000),
    ],
    'fileSystem' => [
        'admin' => env('S3_BASE_PATH') . '%s/',
        'hospitalgroup' => env('S3_BASE_PATH') . '%s/',
        'hospital' => env('S3_BASE_PATH') . '%s/',
        'provider' => env('S3_BASE_PATH') . '%s/',
        'folio' => env('S3_BASE_PATH') . '%s/',
        'peripheral' => env('S3_BASE_PATH') . 'peripheral/',
        'remidio' => env('S3_BASE_PATH') . 'remidio/',
        'rijuven' => env('S3_BASE_PATH') . 'rijuven/',
    ],
    'communication_sms_template' => [
        'forgotPassword' => 'Your ' . config('app.name') . ' forget OTP is {{otp}}',
        '2faAuthentication' => '{{otp}} is your ' . config('app.name') . ' verification code',
    ],
    'slack' => [
        'url' => env('SLACK_WEBHOOK', 'https://hooks.slack.com/services/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'),
        'channels' => env('SLACK_CHANNELS', 'api-logs'),
    ],
    's3_images' => [
        'public_url' => env('S3_PUBLIC_BASE_PATH', 'https://a2ztelehealth.s3.amazonaws.com/')
    ],
    'teleconsult' => [
        'default_service_provider' => env('DEFAULT_SERVICE_PROVIDER', 'tokbox'),
        'api_return_url' => env('TELECONSULT_API_RETURN_URL'),
        'api_return_url_version' => env('TELECONSULT_API_RETURN_URL_VERSION', 'v1'),
        'is_payment_enabled' => env('TELECONSULT_ENABLE_PAYMENT_MODULE', true),
        'statuses' => [
            'consult_approval_pending' => 'pending',
        ]
    ]

];
