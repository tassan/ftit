## Laravel adoption strategy

- **Chosen approach**: Incremental, API-first adoption using a separate Laravel backend, keeping the existing PHP pages and URLs intact in the short term.
- **Laravel app location**: A new Laravel application will live in a subdirectory of this repo (e.g. `laravel-backend/`), without changing the current `public/` web root.
- **Scope for first phase**:
  - Move the AI diagnosis endpoint (`/api/diagnostico-ia`) into Laravel, mirroring the behavior and JSON contract of `public/api/diagnostico-ia.php`.
  - Move the webhook submit endpoint (`/api/submit`) into Laravel, mirroring the behavior and JSON contract of `public/api/submit.php`.
  - Keep all user-facing pages (`/`, `/diagnostico`, `/privacidade`, `/obrigado`) served by the existing PHP files for now.
- **Routing strategy**:
  - Apache will continue to route clean URLs to `public/*.php` as today.
  - New rules (when enabled) will forward `/api/*` requests to the Laravel front controller inside the Laravel subdirectory, so Laravel handles APIs while legacy PHP handles pages.
- **Config & environment**:
  - Both the legacy PHP code and the Laravel app will read configuration from environment variables, using the existing root `.env` keys (`OPENAI_API_KEY`, `ANTHROPIC_API_KEY`, `MAKE_WEBHOOK_URL`, `WHATSAPP_NUMBER`, `EMAIL_TO`, etc.).
  - Laravel configuration files will be wired to these env keys so that the same values drive both stacks.
- **Longer term**:
  - Gradually port the main landing page and other static pages into Blade views, preserving HTML/CSS/JS to keep the same visual identity and behavior.
  - Once stable, Apache’s document root can be switched to the Laravel `public/` directory, with routes reproducing current URLs (and 301 redirects only when explicitly desired).

