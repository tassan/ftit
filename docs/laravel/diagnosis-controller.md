## Laravel design for AI diagnosis endpoint (`/api/diagnostico-ia`)

This document specifies how to implement the existing `public/api/diagnostico-ia.php` behavior in Laravel while **preserving the exact JSON contract** expected by the frontend and by `tests/integration.php`.

---

## 1. Legacy behavior (reference)

The current PHP endpoint:

```1:41:c:\xampp\htdocs\ftit\public\api\diagnostico-ia.php
<?php
require_once __DIR__ . '/../config/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: ' . BASE_URL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['error' => 'Method not allowed']));
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    http_response_code(400);
    exit(json_encode(['error' => 'Invalid input']));
}

// Resolve segmento: se "outro", usa o textbox
$segmento = ($input['segmento'] ?? '') === 'outro'
    ? htmlspecialchars(trim($input['segmento_outro'] ?? 'Outro'), ENT_QUOTES, 'UTF-8')
    : htmlspecialchars(trim($input['segmento'] ?? ''), ENT_QUOTES, 'UTF-8');

$campos = ['nome', 'email', 'telefone', 'cidade', 'faturamento', 'funcionarios',
           'tem_site', 'google_meu_negocio', 'instagram', 'como_acham',
           'agendamento', 'followup', 'horas_admin', 'problema', 'objetivo'];

$dados = ['segmento' => $segmento];
foreach ($campos as $campo) {
    $dados[$campo] = htmlspecialchars(trim($input[$campo] ?? ''), ENT_QUOTES, 'UTF-8');
}

$prompt    = buildPrompt($dados);
$resultado = callOpenAI($prompt);

if ($resultado['success']) {
    dispatchWebhook($dados, $resultado['parecer']);
    echo json_encode(['success' => true, 'parecer' => $resultado['parecer']]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao gerar diagnóstico. Tente novamente.']);
}
```

The helper functions build the prompt, call OpenAI’s Chat Completions API, parse a JSON string in `choices[0].message.content`, and (optionally) dispatch a webhook.

Contract expected by the frontend and tests:

- On success: HTTP 200 with
  - `{"success": true, "parecer": { ...keys... }}`
- On failure: HTTP 500 with
  - `{"error": "Erro ao gerar diagnóstico. Tente novamente."}`

---

## 2. Laravel route

In `laravel-backend/routes/api.php`:

```php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DiagnosisController;

Route::post('/diagnostico-ia', [DiagnosisController::class, 'handle']);
```

This keeps the external URL `/api/diagnostico-ia` (handled by Laravel’s API routes) and maps it to the `handle` method on `DiagnosisController`.

---

## 3. Request validation (`DiagnosisRequest`)

Create a Form Request to validate and normalize the fields. Example: `app/Http/Requests/DiagnosisRequest.php`.

Responsibilities:

- Ensure required fields are present and reasonably formatted.
- Normalize `segmento` vs `segmento_outro`.
- Trim whitespace; Laravel’s `trim` middleware can handle most trimming automatically.

Example structure:

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiagnosisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email', 'max:255'],
            'telefone'        => ['required', 'string', 'max:50'],
            'cidade'          => ['nullable', 'string', 'max:255'],
            'segmento'        => ['required', 'string', 'max:255'],
            'segmento_outro'  => ['nullable', 'string', 'max:255'],
            'faturamento'     => ['nullable', 'string', 'max:255'],
            'funcionarios'    => ['nullable', 'string', 'max:255'],
            'tem_site'        => ['nullable', 'string', 'max:50'],
            'google_meu_negocio' => ['nullable', 'string', 'max:50'],
            'instagram'       => ['nullable', 'string', 'max:50'],
            'como_acham'      => ['nullable', 'string', 'max:255'],
            'agendamento'     => ['nullable', 'string', 'max:255'],
            'followup'        => ['nullable', 'string', 'max:255'],
            'horas_admin'     => ['nullable', 'string', 'max:255'],
            'problema'        => ['required', 'string'],
            'objetivo'        => ['required', 'string'],
        ];
    }

    public function validatedPayload(): array
    {
        $data = $this->validated();

        $segmento = $data['segmento'] ?? '';
        if ($segmento === 'outro') {
            $segmento = $data['segmento_outro'] ?? 'Outro';
        }

        return [
            'segmento'          => $segmento,
            'nome'              => $data['nome']            ?? '',
            'email'             => $data['email']           ?? '',
            'telefone'          => $data['telefone']        ?? '',
            'cidade'            => $data['cidade']          ?? '',
            'faturamento'       => $data['faturamento']     ?? '',
            'funcionarios'      => $data['funcionarios']    ?? '',
            'tem_site'          => $data['tem_site']        ?? '',
            'google_meu_negocio'=> $data['google_meu_negocio'] ?? '',
            'instagram'         => $data['instagram']       ?? '',
            'como_acham'        => $data['como_acham']      ?? '',
            'agendamento'       => $data['agendamento']     ?? '',
            'followup'          => $data['followup']        ?? '',
            'horas_admin'       => $data['horas_admin']     ?? '',
            'problema'          => $data['problema']        ?? '',
            'objetivo'          => $data['objetivo']        ?? '',
        ];
    }
}
```

Note: Laravel automatically protects against HTML injection via Blade escaping; we don’t need to manually call `htmlspecialchars` for JSON-only responses.

---

## 4. Service layer (`DiagnosisService`)

Create a service class that encapsulates prompt building, OpenAI calls, and webhook dispatch: `app/Services/DiagnosisService.php`.

Responsibilities:

- Build the prompt (parity with `buildPrompt()`).
- Call OpenAI Chat Completions using the configured model and key.
- Decode the JSON content into the `parecer` structure.
- Optionally dispatch the webhook.

Example outline:

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;
use Psr\Log\LoggerInterface;

class DiagnosisService
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

    public function generate(array $lead): array
    {
        $prompt = $this->buildPrompt($lead);

        $openaiKey   = config('services.openai.api_key');
        $openaiModel = config('services.openai.model', 'gpt-5.1');

        if (!$openaiKey) {
            $this->logger->error('[diagnostico-ia][DiagnosisService] OPENAI_API_KEY is not set');
            return ['success' => false];
        }

        $response = Http::withToken($openaiKey)
            ->timeout(30)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model'       => $openaiModel,
                'max_tokens'  => 1024,
                'temperature' => 0.2,
                'messages'    => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

        if (!$response->successful()) {
            $this->logger->error('[diagnostico-ia][DiagnosisService] Non-200 response from OpenAI', [
                'status' => $response->status(),
                'body'   => substr($response->body(), 0, 500),
            ]);
            return ['success' => false];
        }

        $content = Arr::get($response->json(), 'choices.0.message.content', '');
        if (!$content) {
            $this->logger->error('[diagnostico-ia][DiagnosisService] Missing choices[0].message.content');
            return ['success' => false];
        }

        $parecer = json_decode($content, true);
        if (!is_array($parecer)) {
            $this->logger->error('[diagnostico-ia][DiagnosisService] Failed to decode parecer JSON', [
                'content_preview' => substr($content, 0, 500),
            ]);
            return ['success' => false];
        }

        $this->dispatchWebhook($lead, $parecer);

        return ['success' => true, 'parecer' => $parecer];
    }

    private function buildPrompt(array $d): string
    {
        // Port the existing buildPrompt() template here, using the same Portuguese copy
        // and field placements to keep behavior consistent.
        return <<<EOT
Você é um consultor sênior da FTIT, especializado em transformação digital para pequenos negócios no Brasil.

Analise os dados deste lead e gere um diagnóstico digital personalizado. Seu objetivo é demonstrar expertise real e criar desejo genuíno pelo serviço — sem pressão, sem exagero.

DADOS DO LEAD:
- Nome/Empresa: {$d['nome']}
- Segmento: {$d['segmento']}
- Cidade: {$d['cidade']}
- Faturamento mensal estimado: {$d['faturamento']}
- Número de funcionários: {$d['funcionarios']}
- Tem site? {$d['tem_site']}
- Está no Google Meu Negócio? {$d['google_meu_negocio']}
- Tem Instagram ativo? {$d['instagram']}
- Como clientes encontram o negócio: {$d['como_acham']}
- Como agenda atendimentos: {$d['agendamento']}
- Faz acompanhamento pós-atendimento? {$d['followup']}
- Horas semanais em tarefas administrativas: {$d['horas_admin']}
- Maior problema digital hoje: {$d['problema']}
- Objetivo nos próximos 3 meses: {$d['objetivo']}

REGRAS:
1. Comece validando o contexto — reconheça o que o negócio tem ou faz bem
2. Identifique 2 a 3 gaps concretos com impacto direto em faturamento ou captação
3. Use dados reais do mercado brasileiro quando possível
4. Se o negócio agenda manualmente ou gasta muitas horas em tarefas repetitivas, destaque o potencial de automação
5. Aponte próximos passos em direção natural aos serviços da FTIT: site estratégico e/ou automação de processos
6. Finalize com CTA personalizado e urgente para agendar a call de 30 minutos com a FTIT
7. Tom: direto, especialista, humano — consultor experiente, não chatbot

Responda APENAS com JSON válido, sem markdown, sem texto antes ou depois:
{
  "titulo": "string — ex: Diagnóstico Digital — [Nome do negócio]",
  "situacao_atual": "string — 2 a 3 frases contextualizando o negócio",
  "gaps": [
    { "problema": "string", "impacto": "string — com número ou dado quando possível" }
  ],
  "potencial": "string — o que o negócio pode ganhar resolvendo esses gaps",
  "proximos_passos": "string — recomendação que aponta pro serviço FTIT adequado",
  "cta_texto": "string — frase personalizada para agendar a call",
  "urgencia": "alta|media|baixa"
}
EOT;
    }

    private function dispatchWebhook(array $lead, array $parecer): void
    {
        $url = config('services.make_webhook.url');
        if (!$url) {
            return;
        }

        $payload = [
            'lead'      => $lead,
            'parecer'   => $parecer,
            'timestamp' => now()->toIso8601String(),
            'source'    => config('services.make_webhook.source', 'diagnostico-ia'),
        ];

        Http::timeout(5)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post($url, $payload);
    }
}
```

This mirrors the responsibilities of `buildPrompt()`, `callOpenAI()`, and `dispatchWebhook()` while using Laravel’s HTTP client and config system.

---

## 5. Controller (`DiagnosisController`)

Create `app/Http/Controllers/DiagnosisController.php`.

Responsibilities:

- Accept the validated request.
- Call `DiagnosisService::generate()` with the normalized payload.
- Return JSON matching the legacy contract:
  - On success: `{ "success": true, "parecer": { ... } }`.
  - On failure: HTTP 500 with `{ "error": "Erro ao gerar diagnóstico. Tente novamente." }`.

Example:

```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiagnosisRequest;
use App\Services\DiagnosisService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DiagnosisController extends Controller
{
    public function __construct(
        private DiagnosisService $service
    ) {}

    public function handle(DiagnosisRequest $request): JsonResponse
    {
        $lead      = $request->validatedPayload();
        $resultado = $this->service->generate($lead);

        if (!($resultado['success'] ?? false)) {
            return response()->json(
                ['error' => 'Erro ao gerar diagnóstico. Tente novamente.'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return response()->json([
            'success' => true,
            'parecer' => $resultado['parecer'],
        ]);
    }
}
```

---

## 6. Contract preservation and tests

- The controller’s output is intentionally shaped to match what `tests/integration.php` expects:
  - `success` boolean flag.
  - `parecer` array with keys: `titulo`, `situacao_atual`, `gaps`, `potencial`, `proximos_passos`, `cta_texto`, `urgencia`.
- After routing `/api/diagnostico-ia` to Laravel, you can:
  - Update the test in `tests/integration.php` to hit `/api/diagnostico-ia` (without `.php`) while keeping the same assertions for the JSON structure.
  - Temporarily keep the legacy `public/api/diagnostico-ia.php` file for reference until you’re confident in the Laravel implementation.

