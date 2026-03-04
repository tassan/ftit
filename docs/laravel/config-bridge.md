## Config and .env bridge between legacy PHP and Laravel

Goal: use **one set of environment variables** for both the existing PHP code and the future Laravel app, so behavior stays consistent and secrets are not duplicated.

---

## 1. Current configuration behavior (legacy PHP)

The legacy app loads environment variables in `public/config/config.php`, roughly as follows:

```1:27:c:\xampp\htdocs\ftit\public\config\config.php
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
```

Key points:

- **Single `.env` at the project root**: `c:\xampp\htdocs\ftit\.env`.
- Legacy PHP code uses:
  - `OPENAI_API_KEY`, `OPENAI_MODEL`
  - `ANTHROPIC_API_KEY`
  - `MAKE_WEBHOOK_URL` / `WEBHOOK_URL`
  - `WHATSAPP_NUMBER`
  - `EMAIL_TO`
  - `APP_ENV`

This `.env` file will remain the **source of truth**.

---

## 2. Laravel `.env` strategy

When creating the Laravel app (e.g. in `laravel-backend/`):

- Point Laravel’s `.env` to the **same values** as the root `.env`. You can do this in two ways:

1. **Single shared .env file (simplest in dev)**  
   - Use the existing root `.env` and configure Laravel’s bootstrap to read from that path; or
   - Symlink/copy the root `.env` into `laravel-backend/.env` during deployment.

2. **Separate .env files with synchronized keys (common in prod)**  
   - Keep `c:\xampp\htdocs\ftit\.env` for legacy PHP.
   - Keep `c:\xampp\htdocs\ftit\laravel-backend\.env` for Laravel.
   - Ensure both define the same keys (`OPENAI_API_KEY`, `MAKE_WEBHOOK_URL`, etc.) with identical values via your deployment process or secrets manager.

For most setups, option (2) is more realistic in production, but during local development you can start with (1).

---

## 3. Laravel config wiring

In the Laravel app, wire the environment variables into `config/services.php` so application code can use `config('services.*')` consistently:

```php
<?php

return [
    'openai' => [
        'api_key' => env('OPENAI_API_KEY', ''),
        'model'   => env('OPENAI_MODEL', 'gpt-5.1'),
    ],

    'anthropic' => [
        'api_key' => env('ANTHROPIC_API_KEY', ''),
    ],

    'make_webhook' => [
        'url'  => env('MAKE_WEBHOOK_URL', env('WEBHOOK_URL', '')),
        'key'  => env('WEBHOOK_API_KEY', ''),
        'source' => 'diagnostico-ia',
    ],

    'contact' => [
        'whatsapp_number' => env('WHATSAPP_NUMBER', '5500000000000'),
        'email_to'        => env('EMAIL_TO', 'contato@ftit.com.br'),
    ],
];
```

Then, Laravel services and controllers can use:

- `config('services.openai.api_key')`
- `config('services.openai.model')`
- `config('services.make_webhook.url')`
- `config('services.make_webhook.key')`
- `config('services.contact.whatsapp_number')`
- `config('services.contact.email_to')`

This mirrors what `public/config/config.php` already provides to legacy PHP code.

---

## 4. Bridging behavior between legacy PHP and Laravel

Because both stacks read **the same environment keys**, most of the “bridge” is just **shared naming conventions**:

- Keep using the existing variable names:
  - `OPENAI_API_KEY`, `OPENAI_MODEL`
  - `MAKE_WEBHOOK_URL` / `WEBHOOK_URL`, `WEBHOOK_API_KEY`
  - `WHATSAPP_NUMBER`, `EMAIL_TO`, `APP_ENV`
- Ensure deployment sets these variables consistently in the environment used by:
  - Apache + legacy PHP (`public/config/config.php`).
  - PHP-FPM/CLI used by Laravel.

If you want an explicit PHP bridge for non-Laravel scripts inside the Laravel app, you can also expose a tiny helper file inside `laravel-backend/` such as:

```php
<?php
// laravel-backend/bootstrap/legacy-config.php

// This file is optional. It allows legacy-style scripts inside the Laravel app
// (if you ever need them) to pull from the same config array structure.

return [
    'openai_api_key' => env('OPENAI_API_KEY', ''),
    'openai_model'   => env('OPENAI_MODEL', 'gpt-5.1'),
    'anthropic_api_key' => env('ANTHROPIC_API_KEY', ''),
    'make_webhook_url'  => env('MAKE_WEBHOOK_URL', env('WEBHOOK_URL', '')),
    'make_webhook_key'  => env('WEBHOOK_API_KEY', ''),
    'whatsapp'          => env('WHATSAPP_NUMBER', '5500000000000'),
    'email'             => env('EMAIL_TO', 'contato@ftit.com.br'),
];
```

However, this bridge is **not required** for the current migration plan; it’s just a convenience pattern if you later introduce more procedural scripts near Laravel.

---

## 5. Summary

- **Single source of truth**: the `.env` keys already used by `public/config/config.php`.
- **Laravel** will be configured to use the **same key names**, via `config/services.php`.
- **No additional state** needs to be stored in Laravel-specific config files beyond these env lookups.
- This keeps behavior consistent while you gradually move logic from legacy PHP endpoints into Laravel controllers and services.

