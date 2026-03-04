## Apache and Laravel routing design

This document describes how to let **Laravel handle only `/api/*`** requests while the existing PHP files in `public/` continue to serve all pages (`/`, `/diagnostico`, `/privacidade`, `/obrigado`, etc.).

Assumptions:

- The existing web root for the site is the `public/` directory in this repo.
- A future Laravel app will live in a sibling directory at the project root, for example: `laravel-backend/` with its own `public/` directory.
- Apache has `mod_rewrite` enabled (already true for the current setup).

---

## 1. Current `.htaccess` behavior (summary)

The current `public/.htaccess` file:

- Enables clean URLs by internally mapping `/foo` → `/foo.php` if `foo.php` exists.
- Adds production-only redirects (www → non-www and HTTPS).
- Configures caching, compression, and security headers.

This logic should **remain intact** for non-API routes.

---

## 2. Short-term routing goal

- **Pages**:
  - Continue to be served by the existing PHP files:
    - `/` → `public/index.php`
    - `/diagnostico` → `public/diagnostico.php`
    - `/privacidade` → `public/privacidade.php`
    - `/obrigado` → `public/obrigado.php`
- **APIs**:
  - Route requests starting with `/api/` into the Laravel app’s front controller, where:
    - `POST /api/submit` will be handled by a Laravel controller instead of `public/api/submit.php`.
    - `POST /api/diagnostico-ia` will be handled by a Laravel controller instead of `public/api/diagnostico-ia.php`.

---

## 3. Example directory layout

At the project root (`c:\xampp\htdocs\ftit`):

- `public/` → current web root (existing PHP pages and static assets)
- `laravel-backend/` → new Laravel application
  - `laravel-backend/public/index.php` → Laravel front controller

In production, Apache’s document root is still `public/`. The Laravel app is not the main web root yet; it is only used for `/api/*`.

---

## 4. Updated `.htaccess` rules (short term)

Below is an example of how `public/.htaccess` can be extended so that **API requests are proxied to Laravel**, while all other requests continue to behave as today.

Place the new API rules **before** the existing “clean URLs” rule, so that `/api/*` is captured first.

```apache
Options -Indexes
ServerSignature Off

RewriteEngine On

# ----------------------------------------------------------------------
# 1) Route /api/* to Laravel backend
# ----------------------------------------------------------------------

# If the request URI starts with /api/, send it to the Laravel backend front controller.
# Adjust the relative path (../laravel-backend/public/index.php) if your Laravel app lives elsewhere.
RewriteCond %{REQUEST_URI} ^/api/ [NC]
RewriteRule ^api/(.*)$ ../laravel-backend/public/index.php [QSA,L]

# ----------------------------------------------------------------------
# 2) Clean URLs for legacy PHP pages (existing behavior)
# ----------------------------------------------------------------------
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME}.php -f
RewriteRule ^([^\.]+)$ $1.php [NC,L]

# ----------------------------------------------------------------------
# 3) Existing production redirects and headers (unchanged)
# ----------------------------------------------------------------------

# Redirect www → non-www (produção)
RewriteCond %{ENV:APP_ENV} =production
RewriteCond %{HTTP_HOST} ^www\.ftit\.com\.br [NC]
RewriteRule ^ https://ftit.com.br%{REQUEST_URI} [R=301,L]

# Force HTTPS (produção apenas)
RewriteCond %{ENV:APP_ENV} =production
RewriteCond %{HTTP_HOST} ^ftit\.com\.br$ [NC]
RewriteCond %{HTTPS} off
RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [R=301,L]

# Cache, compression and security headers...
# (keep the existing <IfModule> blocks as they are)
```

With this configuration:

- `GET /` and other page URLs are handled exactly as before.
- `POST /api/submit` and `POST /api/diagnostico-ia` are now routed into Laravel.

> Note: While both `public/api/submit.php` and `public/api/diagnostico-ia.php` will still exist, they will **no longer be on the hot path** once `/api/*` is routed to Laravel. You can keep them temporarily for reference during migration.

---

## 5. Laravel route definitions

Inside the future Laravel app, use `routes/api.php` to define API routes that mirror the existing endpoints’ behavior:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubmitController;
use App\Http\Controllers\DiagnosisController;

Route::post('/submit', [SubmitController::class, 'handle']);
Route::post('/diagnostico-ia', [DiagnosisController::class, 'handle']);
```

Key points:

- The Laravel app will see URLs like `/api/submit` and `/api/diagnostico-ia` (the `/api` prefix is part of the route path).
- The controllers must:
  - Accept and validate the same JSON payloads as the legacy PHP scripts.
  - Return **identical JSON contracts**:
    - `submit`: `{ "ok": true|false }`
    - `diagnostico-ia`: `{ "success": true, "parecer": { ... } }` with the same keys under `parecer`.

---

## 6. Medium-term routing (when Laravel becomes the main app)

Later, once key pages are migrated to Blade:

- Apache’s document root can be switched to `laravel-backend/public/`.
- Routes for `/`, `/diagnostico`, `/privacidade`, `/obrigado` will live in Laravel (e.g. `routes/web.php`).
- The old `public/*.php` files can either:
  - be removed; or
  - kept temporarily with 301 redirects pointing to the new Laravel routes, if necessary.

That medium-term change is **optional for now**; the short-term design focuses only on `/api/*` and keeps current pages as-is.

