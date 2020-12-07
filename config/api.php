<?php

return [
    "timezone" => env('APP_TIMEZONE', 'UTC'),
    "zoom" => [
        "jwtKey" => env('ZOOM_JWT_KEY', 'EcwY1UX2QoyUOA5fW1tdlg'),
        "jwtSecret" => env('ZOOM_JWT_SECRET', 'N1Qd31cDed4eDSZfpyMy3odUJcmahRvB8HQJ'),
        "jwtTtl" => env('ZOOM_JWT_TTL', 6000),
        "baseUrl" => env('ZOOM_BASE_URL', 'https://api.zoom.us/'),
        "meetingCreate" => 'v2/users/me/meetings',
    ],
    "jitsi" => [
        "jwtKey" => env('JITSI_JWT_KEY', 'bd31fef6629110e'),
        "jwtTtl" => env('JITSI_JWT_TTL', 31535000),
        "client" => "a2zhealth",
        "domain" => env('JITSI_DOMAIN', 'wrtc-server.a2zhealth.in'),
        "generalConfig" => [
            "enableWelcomePage" => false,
            "disableAudioLevels" => true, // performance tuning
            "disableH264" => true, // performance tuning
            "enableClosePage" => false,
            "openBridgeChannel" => "websocket",
            // "startWithAudioMuted" => true, // For testing
            "disableDeepLinking" => true,
            "disableThirdPartyRequests" => true, // recording performance tuning
            "p2p" => [
                "enabled" => true,
                "preferH264" => true,
                "useStunTurn" => true, // Using Turn for p2p connections
                // "stunServers" => [
                //     [
                //         "urls" => "stun:meet-jit-si-turnrelay.jitsi.net:443"
                //     ]
                // ],
            ],
            "useStunTurn" => true, // Using Turn Server with JVB
            "enableUserRolesBasedOnToken" => true,
            "doNotStoreRoom" => true,
            "e2eping" => [
                "pingInterval" => -1,
            ],
        ],
        "generalInterfaceConfig" => [
            "APP_NAME" => "A2zhealth",
            "NATIVE_APP_NAME" => "A2zhealth",
            "PROVIDER_NAME" => "A2zhealth",
            "TOOLBAR_BUTTONS" => [
                'microphone', 'camera', 'desktop', 'fullscreen', 'fodeviceselection', 'hangup', 'chat', 'settings', 'raisehand', 'videoquality', 'tileview', 'e2ee',
            ],
            "SETTINGS_SECTIONS" => [
                'devices',
            ],
            "DISPLAY_WELCOME_PAGE_CONTENT" => false,
            "GENERATE_ROOMNAMES_ON_WELCOME_PAGE" => false,
            "DISABLE_VIDEO_BACKGROUND" => true, // performance tuning
            "SHOW_PROMOTIONAL_CLOSE_PAGE" => false,
            "DEFAULT_REMOTE_DISPLAY_NAME" => "",
            "DEFAULT_LOCAL_DISPLAY_NAME" => "me",
            "SHOW_JITSI_WATERMARK" => false,
            "SHOW_WATERMARK_FOR_GUESTS" => false,
            "SHOW_BRAND_WATERMARK" => true,
            "BRAND_WATERMARK_LINK" => 'https://www.a2zhealth.com/',
            "MOBILE_APP_PROMO" => false,
            "RECENT_LIST_ENABLED" => false,
        ],
        "providerInterfaceConfig" => [
            "TOOLBAR_BUTTONS" => [
                /*'info', 'recording', */'mute-everyone', 'invite',
            ],
        ],
        "patientConfig" => [
            "remoteVideoMenu" => [
                "disableKick" => true,
            ],
            "disableInviteFunctions" => true,
            "disableRemoteMute" => true,
        ],
    ],
    "app" => [
        "jwtKey" => env('JWT_SECRET', 'LjnFPiUKwPmvwIl1b4tZ2I48DzPFpTx1'),
        "jwtTtl" => env('JWT_TTL', 864000000),
    ],
    'fileSystem' => [
        'provider' => env('S3_BASE_PATH') . 'folio-patient/%s/',
        'patient' => env('S3_BASE_PATH') . 'folio-patient/%s/',
    ],
    'slack' => [
        'url' => env('SLACK_WEBHOOK', 'https://hooks.slack.com/services/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'),
        'channels' => env('SLACK_CHANNELS', 'api-logs'),
    ],
    'communication' => [
        'sms' => [
            'invite' => [
                'message' => 'Hi You are invited to join in TeleConsult on {$consultDatetime}. Please Click the link to start tele-consulting. {$consultJoinUrl} Do not share with others. Thanks A2Z TeleHealth',
                'subject' => '',
            ],
            'patientJoinNotification' => [
                'message' => 'Hi {$providerName}, Consult started by patient {$patientName}. Consult time at {$consultDatetime} Thanks  A2Z TeleHealth',
                'subject' => '',
            ],
        ],
        'email' => [
            'invite' => [
                'message' => 'Hi {$email}, <br> <br>You are invited to join in TeleConsult on {$consultDatetime} <br><br><b>Patient Details</b><br><b>Name: </b>{$name}<br><b>Registered ID: </b> {$patientId} <br><b>Mobile Number: </b> {$mobile}<br><b>Email: </b> {$email}.<br> <br>  Please Click the below link to start tele-consulting. <br><br>   <a style="color: white; background-color:#c2173b; padding: 10px;" href="{$consultJoinUrl}">Click me to start @ {$startTime}</a> <br><br> Do not share with others. <br><br>   <a  href="{$folioBaseUrl}">Login/SignUp</a> <br><br><br>  Thanks <br> A2Z TeleHealth',
                'subject' => 'A2Z Folio TeleConsult Invitation on {$consultDatetime}',
            ],
            'patientJoinNotification' => [
                'message' => 'Hi {$providerName}, <br> <br>Consult started by patient {$patientName}. Consult time at {$consultDatetime} Thanks <br> A2Z TeleHealth',
                'subject' => 'A2Z Folio TeleConsult Registered at {$consultDatetime}',
            ],
        ],
    ],
    'folio' => [
        'baseUrl' => env('FOLIO_HOST', 'https://folio.a2zhealth.in/'),
        'joinGuestConsult' => 'consult/guest?token={$uniqueId}',
    ],
    'elasticMail' => [
        'url' => 'https://api.elasticemail.com/mailer/send',
        'apiKey' => 'fe17fcb6-396e-4aa8-94d6-6a4f7bcb0194',
        'username' => 'cureselecthealth@gmail.com',
        'from' => 'noreply@a2z.health',
        'fromName' => 'A2Z Health',
    ],
    'twilio' => [
        'accountSid' => 'AC15053121175a01f1b262fdfe173607c9',
        'authToken' => 'f1311e8c03bf688533c7e879f32ee5f7',
        'fromNumber' => '+13252084845',
    ],
];
