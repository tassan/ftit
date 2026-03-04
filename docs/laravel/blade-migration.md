## Blade migration plan for main pages

Goal: gradually move the main pages (`/`, `/diagnostico`, `/privacidade`, `/obrigado`) from plain PHP in `public/` to **Blade views** in a Laravel app, while preserving:

- Existing HTML structure and dark cyberpunk visual identity.
- Current URLs and user flows.
- Existing i18n behavior (JSON language files + `data-i18n` attributes + JS).

This is a **step-by-step outline**, not an immediate refactor.

---

## 1. Preparation

1. Ensure the Laravel app is set up (e.g. in `laravel-backend/`) and already handling `/api/*`.
2. Share `.env` configuration as described in `docs/laravel/config-bridge.md`.
3. Keep Apache’s document root pointed at the existing `public/` directory for now.

At this stage, only API traffic should be routed into Laravel.

---

## 2. Extract shared layout and head into Blade partials

1. **Create a base layout** in Laravel:
   - `resources/views/layouts/app.blade.php`
   - Include:
     - The `<html>`, `<head>`, and `<body>` skeleton.
     - Common navigation, footer, and shared scripts (mirroring `public/index.php` + `public/config/head.php`).
2. **Move `head.php` equivalents** into Blade:
   - Split `public/config/head.php` into Blade partials such as:
     - `resources/views/partials/head.blade.php`
     - `resources/views/partials/meta.blade.php`
   - Use `@include('partials.head')` inside the layout.
3. Keep the same CSS and JS includes:
   - Preserve `<link rel="stylesheet" href="/assets/css/style.css">`.
   - Preserve `<script src="/assets/js/script.js"></script>`.
4. Introduce `@yield('content')` in the layout, so each page can inject its main content.

During this step, the legacy PHP files remain the entrypoints; you are just recreating the structure in Blade templates.

---

## 3. Port the landing page (`/`) to Blade

1. Create `resources/views/landing.blade.php`:
   - Copy the main content from `public/index.php` (sections, CTAs, i18n attributes).
   - Replace only what’s necessary with Blade expressions, for example:
     - `<?php echo $whatsapp_url; ?>` → `{{ $whatsappUrl }}`.
     - `<?php echo $email_to; ?>` → `{{ $emailTo }}`.
2. In a Laravel controller (e.g. `App\Http\Controllers\PageController`), add a `home()` method:

   ```php
   public function home()
   {
       return view('landing', [
           'whatsappUrl' => $this->config->whatsappUrl(),
           'emailTo'     => $this->config->emailTo(),
       ]);
   }
   ```

   Where `$this->config` is a small service that wraps `config('services.contact.*')`.

3. Define a Laravel route in `routes/web.php`:

   ```php
   use App\Http\Controllers\PageController;

   Route::get('/', [PageController::class, 'home']);
   ```

4. Ensure the Blade view keeps:
   - All existing `data-i18n` attributes.
   - Section IDs (e.g. `#splash`, `#hero`, `#services`, etc.).
   - The same class names and structure used by CSS/JS.

At this point, Laravel can render the landing page, but Apache is still sending traffic to `public/index.php`. You can verify the Blade version via a temporary alternate route (e.g. `/__landing-preview`) before flipping the main `/` route in Apache.

---

## 4. Port `/diagnostico`, `/privacidade`, `/obrigado`

For each page:

1. Create Blade views:
   - `resources/views/diagnostico.blade.php`
   - `resources/views/privacidade.blade.php`
   - `resources/views/obrigado.blade.php`
2. Copy content from:
   - `public/diagnostico.php`
   - `public/privacidade.php`
   - `public/obrigado.php`
3. Replace only minimal PHP snippets with Blade syntax:
   - Echo variables via `{{ ... }}`.
   - Use `@include` for shared pieces (header, footer, etc.).
4. Add controller actions to `PageController`:

   ```php
   public function diagnostico() { return view('diagnostico'); }
   public function privacidade() { return view('privacidade'); }
   public function obrigado()    { return view('obrigado'); }
   ```

5. Add routes in `routes/web.php`:

   ```php
   Route::get('/diagnostico', [PageController::class, 'diagnostico']);
   Route::get('/privacidade', [PageController::class, 'privacidade']);
   Route::get('/obrigado',    [PageController::class, 'obrigado']);
   ```

Again, you can temporarily expose these under test URLs (e.g. `/__diagnostico-preview`) until you are ready to switch Apache to Laravel as the main app.

---

## 5. Switch Apache document root to Laravel (medium term)

Once Blade versions of all key pages are stable and tested:

1. Update Apache’s VirtualHost configuration so the document root becomes:
   - `c:\xampp\htdocs\ftit\laravel-backend\public`
2. Remove or disable the old `public/.htaccess` rules; Laravel’s own `public/.htaccess` will handle URL rewriting.
3. Ensure Laravel’s `routes/web.php` defines:
   - `GET /` → `PageController@home`
   - `GET /diagnostico` → `PageController@diagnostico`
   - `GET /privacidade` → `PageController@privacidade`
   - `GET /obrigado` → `PageController@obrigado`
4. Confirm that `tests/integration.php` (or its future Laravel-aware equivalent) still passes:
   - Page routes return HTTP 200 and expected content.
   - `/api/submit` and `/api/diagnostico-ia` still satisfy their JSON contracts.

You may optionally keep the old `public/*.php` files in the repo for a while, but they will no longer be on the request path once Apache points to the Laravel `public/`.

---

## 6. i18n and assets considerations

- **i18n**:
  - Keep using the existing JSON files (`public/lang/pt.json`, `public/lang/en.json`) and JS logic (`public/assets/js/script.js`).
  - Blade templates should preserve `data-i18n` attributes and not try to render translated strings on the server.
- **Assets**:
  - Keep the same `/assets/...` paths in Blade so current CSS/JS files continue to work.
  - In Laravel’s `public/` directory, you can symlink or copy the existing `assets/` directory from the old `public/` root to avoid duplication.

---

## 7. Rollout strategy

1. **Preview**: Expose Blade versions under non-public URLs first (e.g. `/__landing-preview`).
2. **Staging**: Point a staging vhost to `laravel-backend/public` and run full integration tests.
3. **Production switch**:
   - Move the main vhost’s document root to `laravel-backend/public`.
   - Keep a backup of the old Apache config in case rollback is needed.
4. **Cleanup**:
   - Once stable, archive or remove the legacy `public/*.php` pages.
   - Keep the CSS/JS and `public/lang/*.json` as shared assets, now served by Laravel’s `public/`.

