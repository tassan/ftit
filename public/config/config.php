<?php
$envFile = dirname(__DIR__, 2) . '/.env';

if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if (!$line || str_starts_with($line, '#') || !str_contains($line, '=')) continue;
        [$key, $value] = explode('=', $line, 2);
        putenv(trim($key) . '=' . trim($value));
        $_ENV[trim($key)] = trim($value);
    }
}

define('ANTHROPIC_API_KEY', getenv('ANTHROPIC_API_KEY') ?: '');
define('OPENAI_API_KEY',    getenv('OPENAI_API_KEY') ?: '');
define('MAKE_WEBHOOK_URL',  getenv('MAKE_WEBHOOK_URL') ?: getenv('WEBHOOK_URL') ?: '');
define('WHATSAPP_NUMBER',   getenv('WHATSAPP_NUMBER') ?: '5500000000000');
define('APP_ENV',           getenv('APP_ENV') ?: 'production');
define('BASE_URL',          'https://ftit.com.br');

return [
    'whatsapp'    => WHATSAPP_NUMBER,
    'email'       => getenv('EMAIL_TO') ?: 'contato@ftit.com.br',
    'webhook_url' => MAKE_WEBHOOK_URL,
    'webhook_key' => getenv('WEBHOOK_API_KEY') ?: '',
];
