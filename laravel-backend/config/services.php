<?php

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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | FTIT / AI & Webhook Services
    |--------------------------------------------------------------------------
    |
    | These entries mirror the existing public/config/config.php behavior so
    | that both the legacy PHP code and Laravel share the same environment
    | variables and external integrations.
    |
    */

    'openai' => [
        'api_key' => env('OPENAI_API_KEY', ''),
        'model'   => env('OPENAI_MODEL', 'gpt-5.1'),
    ],

    'anthropic' => [
        'api_key' => env('ANTHROPIC_API_KEY', ''),
    ],

    'make_webhook' => [
        'url'    => env('MAKE_WEBHOOK_URL', env('WEBHOOK_URL', '')),
        'key'    => env('WEBHOOK_API_KEY', ''),
        'source' => 'diagnostico-ia',
    ],

    'contact' => [
        'whatsapp_number' => env('WHATSAPP_NUMBER', '5500000000000'),
        'email_to'        => env('EMAIL_TO', 'contato@ftit.com.br'),
    ],

];
