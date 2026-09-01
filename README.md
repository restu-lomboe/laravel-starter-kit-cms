# CMS — Laravel Starter Kit

> CMS portal starter built on **Laravel 13 + Livewire 4 (SFC) + Fortify + Spatie Permission + Tailwind 4**. Auth is fully configurable (3 defaults + 2 optionals), RBAC-ready, and ships with 2FA, Passkeys, Magic Link, OTP, and Google SSO.

<p align="center"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="360" alt="Laravel Logo"></p>

## Tech Stack

| Layer     | Package / Version                                                                                                                                      |
| --------- | ------------------------------------------------------------------------------------------------------------------------------------------------------ |
| PHP       | `^8.3`                                                                                                                                                 |
| Framework | `laravel/framework ^13.17`                                                                                                                             |
| Frontend  | `livewire/livewire ^4.4` (SFC `pages::` + `wire:navigate`), `tailwindcss ^4.0` + `@tailwindcss/vite`, `vite ^8.0`, `sweetalert2 ^11`                   |
| Auth      | `laravel/fortify ^1.38` (login, 2FA TOTP, password confirm), `laravel/socialite ^5.30` (Google SSO), `laravel/passkeys ^0.4` (`@laravel/passkeys` npm) |
| RBAC      | `spatie/laravel-permission ^8.3`                                                                                                                       |
| Table     | `developerawam/livewire-datatable ^2.3` + `jantinnerezo/livewire-alert ^4.2`                                                                           |
| Tooling   | `laravel/pint ^1.27`, `laravel/boost ^2.5`, `phpunit ^12.5`                                                                                            |

## Features

### Authentication — `config/fortify.php:164` + `config/passkeys.php:1` + `config/services.php:31`

| Method               | Type            | Status                                                                                                                                           | Where                                                              |
| -------------------- | --------------- | ------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------ |
| **Email & Password** | Default (radio) | ✅ working                                                                                                                                       | `resources/views/pages/auth/⚡login.blade.php:11`                  |
| **Magic Link**       | Default (radio) | ✅ full — token `magic_login_tokens` 15m, `MagicLoginMail` markdown, `GET /auth/magic-link/verify` `routes/web.php:16`                           | `⚡login.blade.php:358`                                            |
| **OTP via Email**    | Default (radio) | ✅ full — 6-digit `email_otp_tokens` 10m, `EmailOtpMail` markdown, numeric-only `wire:model.live` + `regex:/^[0-9]{6}$/` `⚡login.blade.php:221` | `⚡login.blade.php:419`                                            |
| **Passkey**          | Optional toggle | ✅ WebAuthn via `laravel/passkeys` + `@laravel/passkeys` `resources/js/app.js:3`                                                                 | `⚡login.blade.php:204` + `pages/setting/⚡security.blade.php:515` |
| **Google SSO**       | Optional toggle | ✅ Socialite `app/Http/Controllers/Auth/GoogleAuthController.php:1` + `GET /auth/google/{redirect,callback}` `routes/web.php:16`                 | `⚡login.blade.php:204` + `⚡authentication-page.blade.php:272`    |

_Only one default can be active_ — stored as `authentication_settings.default_method` `enum('email','magic_link','otp')`. Optional toggles are independent. Preview on `pages/setting/⚡authentication-page.blade.php:345` reflects live `defaultMethod`/`passkeyEnabled`/`googleSsoEnabled`.

Additional auth:

- **Two-Factor (TOTP)** on `pages/setting/⚡security.blade.php:1` — `Enable/Confirm/Disable/Regenerate` via Fortify actions, QR `twoFactorQrCodeSvg()`, `TwoFactorAuthenticatable` `app/Models/User.php:22`. Login challenge `⚡two-factor-challenge.blade.php:1` + `GET /two-factor-challenge` `routes/web.php:6` (checks `session('login.id')`, `TwoFactorAuthenticationProvider::verify`, recovery codes).
- **Forgot / Reset Password** `⚡forgot-password.blade.php:5` (`Password::sendResetLink`) + `⚡change-password.blade.php:9` (`Password::reset`) with custom URL `AppServiceProvider.php:32` → `/?type=reset-password&token=&email=` and branded `emails/reset-password.blade.php` via `ResetPassword::toMailUsing` `AppServiceProvider.php:32`.
- **Passkey management** bypasses `password.confirm` via `BypassPasswordConfirmForPasskeys` `app/Http/Middleware/BypassPasswordConfirmForPasskeys.php:1` aliased in `bootstrap/app.php:17` + `AppServiceProvider.php:13` config override.

### Authorization

- `spatie/laravel-permission` with `roles` / `permissions` CRUD `pages::permission.*`, `pages::roles.*`, `pages::users.*` `routes/web.php:10`.
- Middleware `permission:{module}.{level}` `app/Http/Middleware/CheckPermission.php:1` (`Gate::before` Super Admin bypass `AppServiceProvider.php:26`), alias `permission` `bootstrap/app.php:17`.
- Seeder `RolesAndPermissionsSeeder.php` creates 15 permissions `module ∈ {permission,roles,users} × level ∈ {index,create,update,detail,delete}`.

### Settings

- `pages/setting/⚡profile.blade.php` — tabs `profile/security/authentication` via `<livewire:pages::setting.security />` etc.
- `⚡security.blade.php` — password update + 2FA + passkeys (list `passkeys()->latest()`, `deletePasskey()`, `window.registerPasskey()` via `window.Passkeys.register`).
- `⚡authentication-page.blade.php` — single-row `AuthenticationSetting::current()` `app/Models/AuthenticationSetting.php:22` (encrypted `google_client_secret`).

## Directory Structure

```
app/
  Http/Controllers/Auth/GoogleAuthController.php
  Http/Middleware/{CheckPermission,EnsureAuthenticated,BypassPasswordConfirmForPasskeys}.php
  Mail/{MagicLoginMail,EmailOtpMail}.php
  Models/{User,AuthenticationSetting,MagicLoginToken,EmailOtpToken}
  Providers/{AppServiceProvider,FortifyServiceProvider}.php
resources/
  views/
    pages/               # Livewire SFC — Route::livewire('/', 'pages::auth.login')
      auth/⚡login|two-factor-challenge|forgot-password|change-password.blade.php
      setting/⚡profile|security|authentication-page.blade.php
      users|roles|permission/⚡*.blade.php
    emails/{magic-login,email-otp,reset-password}.blade.php  # markdown
    layouts/app.blade.php
  js/app.js              # imports Passkeys + Swal
  css/app.css
database/migrations/
  2026_09_01_044204_create_authentication_settings_table.php
  2026_09_01_044247_create_magic_login_tokens_table.php
  2026_09_01_044307_create_email_otp_tokens_table.php
config/{fortify,passkeys,services}.php
routes/web.php
```

Livewire SFC convention: `pages::auth.login` → `resources/views/pages/auth/⚡login.blade.php`; full-page via `Route::livewire()` with `wire:navigate`.

## Quick Start

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed   # seeds roles/permissions + superadmin@example.com / password + authentication_settings
npm install
npm run build                # or npm run dev — note: WSL fails @rolldown/binding-linux-x64-gnu, run on Windows host
php artisan serve            # http://localhost:8000
```

Default login: `superadmin@example.com / password` (Super Admin, bypasses permission checks).

### Env

```env
APP_URL=http://localhost:8000
MAIL_MAILER=log              # see storage/logs/laravel.log for magic/OTP/reset links; use smtp for real delivery
# Google SSO — or set via Settings → Authentication UI (stored encrypted in DB, overrides .env at runtime via GoogleAuthController::configure)
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=         # leave empty to use route('auth.google.callback')
PASSKEYS_USER_HANDLE_SECRET="${APP_KEY}"
```

### Google SSO Setup

1. Google Cloud Console → Credentials → OAuth Client (Web) → Authorized redirect URI: `http://localhost:8000/auth/google/callback` (and production domain).
2. Copy Client ID / Secret into **Settings → Authentication → Google SSO** and Save, or set `.env` above.

### Mail

- Magic Link expiry 15m, OTP 10m, Reset 60m.
- Templates are markdown `emails/*.blade.php` with Anchor HR branding (amber `A`). Customize via `AppServiceProvider::boot` `ResetPassword::toMailUsing`.

## Configuration

- `config/fortify.php:170` — `twoFactorAuthentication(['confirm'=>true,'confirmPassword'=>true])`, `passkeys(['confirmPassword'=>true])`.
- `config/passkeys.php:93` — `management_middleware => []` (bypassed for Livewire panel; see `BypassPasswordConfirmForPasskeys`), `redirect => /admin/dashboard` (used by `AppServiceProvider` + `⚡login` `data-redirect`).
- `config/services.php:31` — `google` from `.env` (overridden by DB at runtime).

## Database

Run `php artisan migrate` — creates `authentication_settings` (single row `id=1`), `magic_login_tokens`, `email_otp_tokens`, `passkeys`, `users` (with `two_factor_*`), plus Spatie tables.

## Frontend

```bash
npm run dev   # Vite + Tailwind
npm run build # production
vendor/bin/pint --dirty   # format PHP (required before commit)
```

`@laravel/passkeys` is imported in `resources/js/app.js:3` as `window.Passkeys` for `Passkeys.register()` / `Passkeys.verify()`.

## Testing

```bash
php artisan test --compact                 # sqlite :memory: (phpunit.xml)
php artisan test --compact --filter=Example
```

Factories: `UserFactory`, `AuthenticationSettingFactory` (extend as needed). Livewire tests use `Livewire::test('pages::auth.login')` etc.

## Routes

| Method | URI                                     | Name                   | Middleware                    |
| ------ | --------------------------------------- | ---------------------- | ----------------------------- |
| `GET`  | `/`                                     | `login`                | `guest` (Livewire)            |
| `GET`  | `/two-factor-challenge`                 | `two-factor.login`     | `guest`                       |
| `GET`  | `/auth/magic-link/verify?token=&email=` | `auth.magic.verify`    | `guest`                       |
| `GET`  | `/auth/google/redirect`                 | `auth.google.redirect` | `guest`                       |
| `GET`  | `/auth/google/callback`                 | `auth.google.callback` | `guest`                       |
| `GET`  | `/admin/*`                              | `admin.*`              | `auth.login` + `permission:*` |

## Deployment

Laravel Cloud is recommended; otherwise standard `composer install --optimize-autoloader --no-dev && npm run build && php artisan migrate --force`.

---

Original Laravel docs: [laravel.com/docs](https://laravel.com/docs) • Boost: `composer require laravel/boost --dev && php artisan boost:install`
License MIT.
