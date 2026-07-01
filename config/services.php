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

    'ibge' => [
        'endpoint' => env('API_IBGE', 'https://servicodados.ibge.gov.br/api/v1/localidades'),
    ],

    'brasilapi' => [
        'cep_endpoint' => env('API_BRASILAPI_CEP', 'https://brasilapi.com.br/api/cep/v1'),
    ],

    'telegram_bot_api' => [
        'token' => env('TELEGRAM_BOT_TOKEN'),
        'chat_id' => env('TELEGRAM_CHAT_ID'),
    ],

    // Alertas/erros enviados ao Telegram (tópico do fórum via message_thread_id).
    'telegram' => [
        'token' => env('TELEGRAM_BOT_TOKEN'),
        'chat' => env('TELEGRAM_CHAT_ID'),
        'thread_alerts' => env('TELEGRAM_THREAD_ALERTS'),
    ],

    // Analytics/Ads — carregados somente após consentimento de cookies.
    'ga' => [
        'id' => env('GA_MEASUREMENT_ID'),
    ],

    'google_ads' => [
        'id' => env('GOOGLE_ADS_ID'),
    ],
];
