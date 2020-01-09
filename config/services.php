<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Models\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'paypal' => [
        'env' => env('PAYPAL_ENV'),
        'client_id_live' => env('PAYPAL_CLIENT_ID_LIVE'),
        'client_secret_live' => env('PAYPAL_CLIENT_SECRET_LIVE'),
        'client_id_sandbox' => env('PAYPAL_CLIENT_ID_SANDBOX'),
        'client_secret_sandbox' => env('PAYPAL_CLIENT_SECRET_SANDBOX'),
    ],

    'paghiper' => [
        'api_key' => env('PAGHIPER_API_KEY'),
        'token' => env('PAGHIPER_API_TOKEN'),
    ],

    'mercado_pago' => [
        'client_id' => env('MP_CLIENT_ID'),
        'client_secret' => env('MP_CLIENT_SECRET'),
    ],


    'social_auth' => false,

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    ],

    'google' => [
        'client_id' =>  env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'recaptcha' => [
            'site_key' => env('RECAPTCHA_SITE_KEY'),
            'secret_key' => env('RECAPTCHA_SECRET_KEY'),
        ],
    ],

    'nexmo' => [
        'key' => '',
        'secret' => '',
        'sms_from' => '',
    ],

    'pw_api' => [
        'endpoint' => env('PWPAPI_ENDPOINT'),
        'token' => env('PWAPI_TOKEN'),
    ],
];
