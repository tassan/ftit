# FTIT — Universal AI Working Context

Use this document as the **source of truth** for any AI assistant working on this repository.

---

## 1) Copy/Paste Prompt for Any AI

You are a coding assistant working on the FTIT website repository.

Goals:
- Make precise, minimal, production-safe changes.
- Keep existing visual identity and behavior unless explicitly asked.
- Respect current architecture (PHP + vanilla JS + CSS, no frameworks).
- Preserve Portuguese-first UX and bilingual support where it already exists.

Rules:
- Do not expose or hardcode secrets.
- Do not commit `.env` values.
- Prefer targeted edits over refactors.
- Keep URLs and routing compatible with Apache `.htaccess` rules.
- When changing copy with i18n keys, update both `public/lang/pt.json` and `public/lang/en.json`.
- When changing forms/APIs, keep backend validation and JSON contracts consistent.
- Before finishing: run basic syntax/lint checks where possible and summarize changed files.

Context:
- Project: FTIT (digital transformation consultancy website)
- Stack: PHP 8.2 (Apache), HTML, CSS, vanilla JS, JSON i18n, cURL integrations
- Web root: `public/`
- Local env loaded from repo root `.env`

---

## 2) Project Snapshot

- Brand: **FTIT — f(t) it**
- Owner: Flávio Tassan
- Main site: one-page landing (`public/index.php`)
- Diagnosis flow: multi-step form with AI analysis (`public/diagnostico.php`)
- Privacy page: (`public/privacidade.php`)
- Thank-you page: (`public/obrigado.php`)

Primary business objective:
- Convert visitors into WhatsApp leads and diagnosis calls.

---

## 3) Runtime & Structure

### Runtime
- PHP + Apache in Docker (`Dockerfile`, `docker-compose.yml`)
- Apache serves only `public/`
- HTTPS enabled in container (self-signed for local dev)

### Key Paths
- Entrypoint redirect (dev helper): `index.php` (root) → `public/`
- Web app root: `public/`
- Config loader: `public/config/config.php`
- Shared `<head>` partial: `public/config/head.php`
- API endpoints: `public/api/`
- Frontend JS: `public/assets/js/`
- Frontend CSS: `public/assets/css/`
- i18n JSON: `public/lang/`

---

## 4) Environment Variables

Defined in root `.env` (never commit secrets):
- `OPENAI_API_KEY`
- `MAKE_WEBHOOK_URL`
- `WEBHOOK_URL`
- `WEBHOOK_API_KEY`
- `WHATSAPP_NUMBER`
- `EMAIL_TO`
- `APP_ENV`

How they are used:
- Loaded by `public/config/config.php`
- Exposed in config return array:
  - `whatsapp`
  - `email`
  - `webhook_url`
  - `webhook_key`

---

## 5) Routing & URL Behavior

File: `public/.htaccess`
- Clean URLs: `/privacidade` maps to `privacidade.php`
- Production redirects:
  - `www.ftit.com.br` → `ftit.com.br`
  - HTTP → HTTPS (only when `APP_ENV=production`)
- Static cache, gzip, and security headers configured.

When adding pages:
- Create `public/new-page.php`
- Link as `/new-page` (extensionless URL)
- Keep canonical/OG metadata via `public/config/head.php`

---

## 6) Frontend Architecture

## Main landing page
- File: `public/index.php`
- Styles: `public/assets/css/style.css`
- Script: `public/assets/js/script.js`
- Uses `data-i18n` attributes and language JSON files.

## Diagnosis page (AI flow)
- File: `public/diagnostico.php`
- Styles: `public/assets/css/diagnostico.css`
- Script: `public/assets/js/diagnostico.js`
- 6-step form + loading + result state
- Collects lead data and calls `/api/diagnostico-ia.php`

---

## 7) Backend/API Contracts

## `public/api/submit.php`
- Accepts JSON POST
- Forwards selected fields to webhook with `x-make-apikey`
- Returns `{ "ok": true|false }`

## `public/api/diagnostico-ia.php`
- Accepts JSON POST from diagnosis form
- Normalizes/sanitizes input (including `segmento`/`segmento_outro` logic)
- Builds prompt and calls the OpenAI Chat Completions API
- Expects strict JSON response from the model (parsed from the assistant message)
- Returns:
  - success: `{ "success": true, "parecer": { ... } }`
  - failure: `{ "error": "..." }` with HTTP 500
- Dispatches lead + AI result to Make webhook.

Important response schema (`parecer`):
- `titulo`
- `situacao_atual`
- `gaps[]` (`problema`, `impacto`)
- `potencial`
- `proximos_passos`
- `cta_texto`
- `urgencia` (`alta|media|baixa`)

---

## 8) i18n Rules

Language files:
- `public/lang/pt.json`
- `public/lang/en.json`

Current behavior:
- Landing page i18n is active via `public/assets/js/script.js`
- Stored language key: `ftit-lang` in `localStorage`
- Diagnosis page currently uses mostly hardcoded PT content + custom JS logic.

If an edit introduces new translatable UI on i18n-enabled screens:
1. Add key to both JSON files.
2. Add `data-i18n` attribute in HTML.
3. Ensure `script.js` applies translation correctly.

---

## 9) Design/UX Constraints

- Keep existing FTIT dark cyberpunk visual identity.
- Avoid introducing new UI frameworks.
- Preserve responsive behavior.
- Maintain conversion-first CTA hierarchy:
  1. Diagnosis
  2. WhatsApp
- Do not add unnecessary steps, popups, or complex interactions unless requested.

---

## 10) Safe Change Playbooks

### A) Update contact destination (email/WhatsApp)
- Prefer `.env` updates (`EMAIL_TO`, `WHATSAPP_NUMBER`)
- Confirm all server-rendered links in:
  - `public/index.php`
  - `public/diagnostico.php` (via `window.FTIT.whatsapp`)

### B) Edit diagnosis fields
- Update UI in `public/diagnostico.php`
- Update state/validation/send payload in `public/assets/js/diagnostico.js`
- Update backend parsing in `public/api/diagnostico-ia.php`
- Keep webhook payload compatible if needed

### C) Change AI output card
- Frontend render: `renderParecer()` in `public/assets/js/diagnostico.js`
- Backend schema contract: `public/api/diagnostico-ia.php`
- Keep both synchronized

### D) Add new section to home page
- Markup in `public/index.php`
- Style in `public/assets/css/style.css`
- i18n keys in both language JSON files
- Optional scroll animation class: `animate-on-scroll`

---

## 11) Validation Checklist (Before Delivery)

- PHP syntax check for edited PHP files.
- Confirm no secret values were added to tracked files.
- Verify key routes load:
  - `/`
  - `/diagnostico`
  - `/privacidade`
  - `/obrigado`
- For diagnosis changes, verify:
  - step navigation
  - required-field logic
  - submit flow
  - success/fallback WhatsApp link
- For i18n changes, verify PT/EN toggle on landing.

---

## 12) Known Caveats

- `BASE_URL` is currently fixed in config (`https://ftit.com.br`), so avoid environment-dependent assumptions unless explicitly changing this behavior.
- Diagnosis flow is more complex than old 4-step docs; trust current code over older specs.

---

## 13) Suggested Task Format for Any AI

Use this request format to get better results:

1. Objective (what should change)
2. Scope (which files can be changed)
3. Non-goals (what must not change)
4. Acceptance criteria (how success is verified)
5. Output format (summary only, patch, etc.)

Example:
- Objective: Add one new FAQ section to home page.
- Scope: `public/index.php`, `public/assets/css/style.css`, `public/lang/*.json`.
- Non-goals: No new JS libraries, no redesign.
- Acceptance: Section responsive, PT/EN working, no changes to diagnosis flow.

---

If this file becomes outdated, update it immediately after structural, routing, API, or i18n changes.