<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'viacep' => [
        'endpoint' => env('API_VIACEP', 'https://viacep.com.br/ws'),
    ],

    'brasilapi' => [
        'cep_endpoint' => env('API_BRASILAPI_CEP', 'https://brasilapi.com.br/api/cep/v1'),
    ],

    'telegram_bot_api' => [
        'token' => env('TELEGRAM_BOT_TOKEN'),
        'chat_id' => env('TELEGRAM_CHAT_ID'),
    ],

    // Analytics/Ads — carregados somente após consentimento de cookies.
    'ga' => [
        'id' => env('GA_MEASUREMENT_ID', 'G-4VSY21XL8V'),
    ],

    'google_ads' => [
        'id' => env('GOOGLE_ADS_ID'),
    ],
];
