## Current entrypoints → planned Laravel routes

### Public pages (served by legacy PHP for now)

- `/`  
  - **Today**: `public/index.php` (via `index.php` redirect + `.htaccess` clean URLs)  
  - **Future Laravel**: `GET /` handled by a controller method (e.g. `PageController@home`) rendering `resources/views/landing.blade.php`.

- `/diagnostico`  
  - **Today**: `public/diagnostico.php` (via `.htaccess` clean URLs)  
  - **Future Laravel**: `GET /diagnostico` → `PageController@diagnostico` rendering `resources/views/diagnostico.blade.php`.

- `/privacidade`  
  - **Today**: `public/privacidade.php`  
  - **Future Laravel**: `GET /privacidade` → `PageController@privacidade` rendering `resources/views/privacidade.blade.php`.

- `/obrigado`  
  - **Today**: `public/obrigado.php`  
  - **Future Laravel**: `GET /obrigado` → `PageController@obrigado` rendering `resources/views/obrigado.blade.php`.

### API endpoints

- `/api/submit.php`  
  - **Today**: `public/api/submit.php`  
  - **Behavior**:
    - Accepts `POST` with JSON body containing: `nome`, `negocio`, `email`, `telefone`, `segmento`, `temSite`, `dor`.
    - Forwards these fields as JSON to a Make webhook (`$config['webhook_url']`) with header `x-make-apikey: $config['webhook_key']`.
    - Returns JSON `{ "ok": bool }`, where `ok` is `true` if HTTP status from webhook is `< 400`.
  - **Future Laravel**:
    - Route: `POST /api/submit` defined in `routes/api.php`.
    - Controller: `App\Http\Controllers\SubmitController@handle`.
    - Request validation: Form Request (e.g. `App\Http\Requests\SubmitRequest`) ensuring required fields and basic formats.
    - Service: Webhook client encapsulating the cURL call to the Make webhook, using env/config for URL and API key.
    - Response: same JSON contract `{ "ok": bool }`.

- `/api/diagnostico-ia.php`  
  - **Today**: `public/api/diagnostico-ia.php`  
  - **Behavior**:
    - Accepts `POST` with JSON body including diagnostic fields (nome, email, telefone, cidade, segmento, segmento_outro, faturamento, funcionarios, tem_site, google_meu_negocio, instagram, como_acham, agendamento, followup, horas_admin, problema, objetivo).
    - Normalizes and sanitizes data (HTML-escaping and trimming).
    - Builds a detailed Portuguese prompt and calls OpenAI Chat Completions API using `OPENAI_API_KEY` and `OPENAI_MODEL` (default `gpt-5.1`).
    - Expects the model to return a JSON string in `choices[0].message.content`, which is decoded into a `parecer` array with keys:
      - `titulo`, `situacao_atual`, `gaps` (array of `{ problema, impacto }`), `potencial`, `proximos_passos`, `cta_texto`, `urgencia`.
    - If successful:
      - Optionally dispatches a webhook with `{ lead, parecer, timestamp, source: "diagnostico-ia" }` to `MAKE_WEBHOOK_URL`.
      - Returns `{ "success": true, "parecer": <parecer JSON> }`.
    - On error: returns `HTTP 500` with `{ "error": "Erro ao gerar diagnóstico. Tente novamente." }`.
  - **Future Laravel**:
    - Route: `POST /api/diagnostico-ia` in `routes/api.php`.
    - Controller: `App\Http\Controllers\DiagnosisController@handle`.
    - Request validation: Form Request (e.g. `App\Http\Requests\DiagnosisRequest`) enforcing required fields and basic constraints.
    - Service: `App\Services\DiagnosisService` encapsulating:
      - Prompt building (parity with `buildPrompt()`).
      - OpenAI API call (parity with `callOpenAI()`), using Laravel’s HTTP client and `config('services.openai')`.
      - Webhook dispatch (parity with `dispatchWebhook()`), using `config('services.make_webhook')`.
    - Response: same JSON contract as today so `tests/integration.php` keeps passing.

### Tests expecting these contracts

- `tests/integration.php`
  - Verifies that:
    - `/` responds with `200` and contains brand copy.
    - `/diagnostico`, `/privacidade`, `/obrigado` respond with `200`.
    - `/api/submit.php` responds with `2xx` and JSON containing boolean `ok`.
    - `/api/diagnostico-ia.php` (when `OPENAI_API_KEY` is set) responds with `200` and JSON `{ success: true, parecer: { ...required keys... } }`.
  - After routing `/api/*` into Laravel, these tests will be updated to hit:
    - `/api/submit` instead of `/api/submit.php`.
    - `/api/diagnostico-ia` instead of `/api/diagnostico-ia.php`.
  - The JSON structures and HTTP status codes must remain identical so existing frontend logic and test expectations do not break.

