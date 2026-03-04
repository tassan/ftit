## Laravel design for submit endpoint (`/api/submit`)

This document specifies how to reimplement `public/api/submit.php` in Laravel, while preserving the JSON contract and webhook behavior.

---

## 1. Legacy behavior (reference)

Current endpoint:

```1:42:c:\xampp\htdocs\ftit\public\api\submit.php
<?php
$config = require __DIR__ . '/../config/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['ok' => false]);
    exit;
}

$payload = [
    'nome'     => $data['nome']     ?? '',
    'negocio'  => $data['negocio']  ?? '',
    'email'    => $data['email']    ?? '',
    'telefone' => $data['telefone'] ?? '',
    'segmento' => $data['segmento'] ?? '',
    'temSite'  => $data['temSite']  ?? '',
    'dor'      => $data['dor']      ?? '',
];

$ch = curl_init($config['webhook_url']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'x-make-apikey: ' . $config['webhook_key'],
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_exec($ch);
$ok = curl_getinfo($ch, CURLINFO_HTTP_CODE) < 400;
curl_close($ch);

echo json_encode(['ok' => $ok]);
```

Contract:

- On any POST with a valid JSON body, respond with JSON: `{ "ok": true|false }`.
- `ok` is `true` if the webhook returns HTTP status `< 400`, `false` otherwise.

---

## 2. Laravel route

In `laravel-backend/routes/api.php`:

```php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubmitController;

Route::post('/submit', [SubmitController::class, 'handle']);
```

This exposes `POST /api/submit` under Laravel’s API routes.

---

## 3. Request validation (`SubmitRequest`)

Create a Form Request at `app/Http/Requests/SubmitRequest.php` to validate the incoming JSON:

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome'     => ['required', 'string', 'max:255'],
            'negocio'  => ['nullable', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255'],
            'telefone' => ['required', 'string', 'max:50'],
            'segmento' => ['nullable', 'string', 'max:255'],
            'temSite'  => ['nullable', 'string', 'max:50'],
            'dor'      => ['required', 'string'],
        ];
    }

    public function payload(): array
    {
        $data = $this->validated();

        return [
            'nome'     => $data['nome']     ?? '',
            'negocio'  => $data['negocio']  ?? '',
            'email'    => $data['email']    ?? '',
            'telefone' => $data['telefone'] ?? '',
            'segmento' => $data['segmento'] ?? '',
            'temSite'  => $data['temSite']  ?? '',
            'dor'      => $data['dor']      ?? '',
        ];
    }
}
```

By using `payload()`, the controller can easily build the webhook body in the same shape as the legacy script.

---

## 4. Webhook client service

Create a small service to send the webhook request, using the same config keys as the legacy app (`MAKE_WEBHOOK_URL`, `WEBHOOK_API_KEY` via `config/services.php`).

Example: `app/Services/SubmitWebhookService.php`:

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SubmitWebhookService
{
    public function send(array $payload): bool
    {
        $url = config('services.make_webhook.url');
        $key = config('services.make_webhook.key');

        if (!$url || !$key) {
            // If not configured, mirror legacy behavior by returning false.
            return false;
        }

        $response = Http::withHeaders([
                'Content-Type'  => 'application/json',
                'x-make-apikey' => $key,
            ])
            ->asJson()
            ->post($url, $payload);

        return $response->status() < 400;
    }
}
```

This mirrors the `curl` call in `public/api/submit.php`, using Laravel’s HTTP client instead.

---

## 5. Controller (`SubmitController`)

Create `app/Http/Controllers/SubmitController.php`.

Responsibilities:

- Accept the validated request.
- Call `SubmitWebhookService::send()` with the normalized payload.
- Respond with `{ "ok": true|false }`, matching legacy behavior and `tests/integration.php`.

Example:

```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitRequest;
use App\Services\SubmitWebhookService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SubmitController extends Controller
{
    public function __construct(
        private SubmitWebhookService $webhook
    ) {}

    public function handle(SubmitRequest $request): JsonResponse
    {
        $payload = $request->payload();

        // In the legacy script, invalid JSON resulted in 400 with { ok: false }.
        // Here, Laravel's validation layer will already have ensured a valid JSON body.

        $ok = $this->webhook->send($payload);

        // Keep status 200 in both success and failure cases, just like legacy,
        // but you could optionally change this later if you adjust tests and frontend.
        return response()->json(
            ['ok' => $ok],
            Response::HTTP_OK
        );
    }
}
```

---

## 6. Contract preservation and tests

- The JSON structure remains **exactly** `{ "ok": bool }`, as expected by `tests/integration.php`.
- Once `/api/submit` is routed into Laravel (via the `/api/*` mod_rewrite + Laravel `Route::post('/submit', ...)`), you can:
  - Update `tests/integration.php` to hit `/api/submit` instead of `/api/submit.php`.
  - Keep the same assertions that check for:
    - 2xx HTTP status.
    - A JSON body with an `ok` key of type boolean.

The legacy `public/api/submit.php` file can remain temporarily as a reference during migration and be removed once the Laravel version has been verified in staging/production.

