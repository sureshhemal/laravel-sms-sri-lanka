<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default SMS Provider
    |--------------------------------------------------------------------------
    |
    | This value determines the default SMS provider that will be used
    | when sending SMS notifications. You can change this to any
    | provider that implements the SmsServiceContract.
    |
    */

    'default' => env('SMS_PROVIDER', 'hutch'),

    /*
    |--------------------------------------------------------------------------
    | SMS Providers
    |--------------------------------------------------------------------------
    |
    | Here you may configure the SMS providers for your application.
    | Each provider should implement the SmsServiceContract interface.
    |
    */

    'providers' => [
        'hutch' => [
            'service' => \Sureshhemal\SmsSriLanka\Providers\Hutch\HutchSmsService::class,
            'authenticator' => \Sureshhemal\SmsSriLanka\Providers\Hutch\HutchSmsAuthenticator::class,
            'config' => [
                'base_url' => env('HUTCH_SMS_BASE_URL', 'https://bsms.hutch.lk/api'),
                'username' => env('HUTCH_SMS_USERNAME'),
                'password' => env('HUTCH_SMS_PASSWORD'),
                'default_mask' => env('HUTCH_SMS_DEFAULT_MASK'),
            ],
            'default_options' => [
                'delivery_report_request' => env('HUTCH_SMS_DELIVERY_REPORT_REQUEST', false),
                'campaign_name' => env('HUTCH_SMS_DEFAULT_CAMPAIGN_NAME', 'Laravel SMS'),
            ],
        ],
        // More Sri Lankan providers coming soon...
    ],
];
