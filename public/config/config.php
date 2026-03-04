<?php

declare(strict_types=1);

// Load .env from repository root (two levels up from public/config/)
$envPath = dirname(__DIR__, 2) . '/.env';

if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        $eqPos = strpos($line, '=');
        if ($eqPos === false) {
            continue;
        }
        $key   = trim(substr($line, 0, $eqPos));
        $value = trim(substr($line, $eqPos + 1));
        // Strip surrounding quotes
        if (
            strlen($value) >= 2 &&
            (($value[0] === '"' && $value[-1] === '"') ||
             ($value[0] === "'" && $value[-1] === "'"))
        ) {
            $value = substr($value, 1, -1);
        }
        if (getenv($key) === false) {
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
}

/**
 * Retrieve an environment variable with an optional default.
 *
 * @param  string  $key
 * @param  mixed   $default
 * @return mixed
 */
function env(string $key, mixed $default = null): mixed
{
    $value = getenv($key);
    if ($value !== false) {
        return $value;
    }
    return $_ENV[$key] ?? $default;
}

return [
    'whatsapp'    => env('FTIT_WHATSAPP',    env('WHATSAPP_NUMBER',   '5500000000000')),
    'email'       => env('FTIT_EMAIL_TO',    env('EMAIL_TO',          'contato@ftit.com.br')),
    'webhook_url' => env('FTIT_WEBHOOK_URL', env('MAKE_WEBHOOK_URL',  env('WEBHOOK_URL'))),
    'webhook_key' => env('FTIT_WEBHOOK_KEY', env('WEBHOOK_API_KEY')),
    'base_url'    => env('APP_URL',          'https://ftit.com.br'),
    'openai_key'  => env('OPENAI_API_KEY'),
    'openai_model'=> env('OPENAI_MODEL_DIAGNOSTICO', 'gpt-4.1-mini'),
    'app_env'     => env('APP_ENV',          'production'),
];
