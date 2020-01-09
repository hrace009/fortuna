<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PagSeguro
    |--------------------------------------------------------------------------
    |
    | This array contains the needed credentials to use PagSeguro to receive
    | payments.
    |
    */
    'pagseguro' => [
        'env'               => env('PAGSEGURO_ENV', 'sandbox'),
        'email'             => env('PAGSEGURO_EMAIL', 'your_pagseguro_email'),
        'token_production'  => env('PAGSEGURO_PRODUCTION_TOKEN', ''),
        'token_sandbox'     => env('PAGSEGURO_SANDBOX_TOKEN', ''),
    ],

    'paypal'    => [
        'env'                   => env('PAYPAL_ENV', 'sandbox'),
        'client_id_live'        => env('PAYPAL_CLIENT_ID_LIVE', ''),
        'client_secret_live'    => env('PAYPAL_CLIENT_SECRET_LIVE', ''),
        'client_id_sandbox'     => env('PAYPAL_CLIENT_ID_SANDBOX', ''),
        'client_secret_sandbox'    => env('PAYPAL_CLIENT_SECRET_SANDBOX', ''),
    ],
];
