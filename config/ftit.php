<?php

return [
    /*
    |--------------------------------------------------------------------------
    | FTIT Site Configuration
    |--------------------------------------------------------------------------
    |
    | Centralized configuration for the marketing site and diagnóstico flows.
    | Values come from environment variables, with sensible fallbacks to keep
    | compatibility with the legacy .env keys.
    |
    */

    'whatsapp' => env('FTIT_WHATSAPP', env('WHATSAPP_NUMBER', '5500000000000')),

    'email_to' => env('FTIT_EMAIL_TO', env('EMAIL_TO', 'contato@ftit.com.br')),

    'webhook_url' => env('FTIT_WEBHOOK_URL', env('MAKE_WEBHOOK_URL', env('WEBHOOK_URL'))),

    'webhook_key' => env('FTIT_WEBHOOK_KEY', env('WEBHOOK_API_KEY')),

    'base_url' => env('APP_URL', 'https://ftit.com.br'),
];

