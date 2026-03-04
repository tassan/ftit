# GitHub Copilot Instructions — FTIT

You are a coding assistant working on the **FTIT** website repository.

## Mindset

- Make **precise, minimal, production-safe** changes.
- Prefer targeted edits over large refactors.
- Keep existing visual identity, layout, and behavior unless explicitly asked.

## Stack & Architecture

- **Stack:** PHP 8.2 (Apache), HTML, CSS, vanilla JS, JSON i18n, cURL integrations.
- No new frontend or backend frameworks unless explicitly approved.
- **Web root:** `public/`; the root `index.php` is just a redirect helper.
- Respect existing PHP file structure and partials (`public/config/head.php`, `public/config/config.php`).

## Routing & URLs

- Apache `.htaccess` in `public/` defines clean URLs (e.g. `/privacidade` → `privacidade.php`).
- Keep URLs and routing compatible with these rules when adding or changing pages.
- New pages: add as `public/new-page.php` and expose as `/new-page` (extensionless URL).

## Environment & Secrets

- Environment variables live in root `.env` and are loaded via `public/config/config.php`.
- **Never commit secrets, `.env` contents, or hardcoded API keys.**
- Prefer existing config keys (`whatsapp`, `email`, `webhook_url`, `webhook_key`) before adding new env variables.

## Key Files

| Area | Files |
|------|-------|
| Landing page | `public/index.php`, `public/assets/css/style.css`, `public/assets/js/script.js` |
| Diagnosis flow | `public/diagnostico.php`, `public/assets/css/diagnostico.css`, `public/assets/js/diagnostico.js`, `public/api/diagnostico-ia.php` |
| Other pages | `public/privacidade.php`, `public/obrigado.php` |
| Config | `public/config/config.php`, `public/config/head.php` |
| i18n | `public/lang/pt.json`, `public/lang/en.json` |

## API Contracts

- **`public/api/submit.php`** — JSON POST → forwards selected fields to webhook with `x-make-apikey` → returns `{ "ok": true|false }`.
- **`public/api/diagnostico-ia.php`** — JSON POST → normalizes/sanitizes input → calls OpenAI Chat Completions API → returns `{ "success": true, "parecer": { ... } }` or `{ "error": "..." }` (HTTP 500).
- Preserve existing response and payload contracts unless breaking changes are explicitly agreed upon.

`parecer` schema: `titulo`, `situacao_atual`, `gaps[]` (`problema`, `impacto`), `potencial`, `proximos_passos`, `cta_texto`, `urgencia` (`alta|media|baixa`).

## i18n Rules

- Language files: `public/lang/pt.json` and `public/lang/en.json`.
- Landing page uses `data-i18n` attributes; language stored as `ftit-lang` in `localStorage`.
- When adding/changing translatable UI on i18n-enabled screens:
  1. Add or update keys in **both** `pt.json` and `en.json`.
  2. Use `data-i18n` attributes in HTML.

## Design & UX Constraints

- Preserve FTIT **dark cyberpunk** visual identity and responsive behavior.
- Maintain conversion-first CTA hierarchy: **(1) Diagnosis → (2) WhatsApp**.
- Avoid extra steps, popups, or complex interactions unless explicitly requested.

## Safe-Change Patterns

- **Diagnosis fields:** keep frontend form, JS state/validation, and backend parsing in sync.
- **AI output card:** keep `renderParecer()` in `diagnostico.js` and `parecer` schema in `diagnostico-ia.php` aligned.
- **Contact destinations:** prefer `.env` changes (`EMAIL_TO`, `WHATSAPP_NUMBER`); ensure all links/flows use config values.
- **New home section:** add markup in `public/index.php`, styles in `style.css`, i18n keys in both JSON files.

## Validation Checklist Before Delivery

- [ ] PHP syntax check on every edited `.php` file.
- [ ] No secret values added to tracked files.
- [ ] Key routes still load: `/`, `/diagnostico`, `/privacidade`, `/obrigado`.
- [ ] For diagnosis changes: step navigation, required-field logic, submit flow, fallback WhatsApp link.
- [ ] For i18n changes: PT/EN toggle works on landing page.
