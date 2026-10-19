# Re-Audit Report — Katalog TEFA (SMKN 4 Tanjungpinang)

**Type:** read-only verification audit (no code, config, migration, seeder or dependency was modified)
**Baseline compared against:** `SECURITY_AUDIT_REPORT.md` (17 Oct 2026) — 4 risk categories, SEC-001…SEC-024, authorization score 1/5
**Code state audited:** working tree at commit `0233823` (`refactor(order): retire the Pesanan mirror`) + local `.env`
**Environment:** Laravel 13.30.1, PHP 8.5.10, MySQL `tefa_katalog` (read-only) / `tefa_katalog_testing` (tests)
**Date:** 18 Oct 2026

---

## 1. Summary

| Status | Count | IDs |
|---|---|---|
| RESOLVED | 15 | SEC-001, 002, 003, 004, 005, 006, 007, 008, 009, 012, 013, 015, 016, 017, 018 |
| PARTIALLY RESOLVED | 4 | SEC-010, 011, 021, 023 |
| NOT RESOLVED | 3 | SEC-019, 020, 024 |
| CANNOT VERIFY | 2 | SEC-014, 022 (production environment values) |
| **Total** | **24** | |

**Overall risk judgment: MEDIUM.**

Every P0 item (SEC-001, SEC-002, SEC-004, SEC-009) and every P1 authorization item (SEC-003, SEC-006, SEC-011, SEC-012, SEC-005, SEC-007, SEC-018) is closed or closed with a documented gap, and object-level authorization went from "missing in four controllers" to "department/owner-scoped in every routed controller" with negative tests covering each. Dependency risk is cleared (`composer audit` reports zero advisories) and the response headers, CORS, session-cookie and checkout-transaction work is in place and tested. What keeps this from LOW: three original findings are untouched (password-reset email enumeration, privileged `$fillable` fields, over-wide public API/placeholder contact numbers — all Low), SEC-010/SEC-011 have real remaining gaps (no token revocation on password change; `worker_id` still unvalidated in the manual-order form), and this audit found one **HIGH availability** defect created by the interaction of the `verified` middleware with the seeders (all 27 accounts in the dev database are unverified and therefore locked out of every authenticated area) plus a **MEDIUM** CSP/style-src mismatch that blocks the CDN font stylesheets the layouts load.

---

## 2. Finding-by-finding

| ID | Severity | Status | Evidence | Notes |
|---|---|---|---|---|
| SEC-001 | High | RESOLVED | `app/Models/Order.php:38-45`, `routes/web.php:33-34`, `app/Http/Controllers/TrackingController.php:46-48`, `database/migrations/2026_10_17_000000_regenerate_guessable_order_codes.php:23-28` | `generateCode()` now does `strtoupper(Str::random(12))` (62^12 ≈ 3.2e21) inside a `do/while` uniqueness check; both tracking routes carry `throttle:10,1`; misses are logged. Migration rewrites every old `TEFA[-X]-NNNN` code in place. Test: `TrackingSecurityTest`. |
| SEC-002 | High | RESOLVED | `app/Http/Controllers/WorkerController.php:70,103`, `config/filesystems.php:51-59`, `app/Models/ProjectLog.php:55`, `app/Models/Project.php:86` | `mimes:` + `extensions:` whitelists (jpg/jpeg/png/webp/pdf, and zip/mp4 for submissions), `max:5120` / `max:10240`, `hashName()` naming, stored on the private `uploads` disk (`storage/app/uploads`, outside the web root) and served only by 30-minute signed URLs. Residual exposure listed as N-3. Test: `UploadValidationTest`. |
| SEC-003 | High | RESOLVED | `app/Http/Controllers/WorkerController.php:52-54,66,100`, git `0233823` | Every worker action re-queries `Project::where('worker_id', auth()->id())->findOrFail($id)`; the old `/worker/update-progress/{penugasan}` route, `WorkerController::updateProgress` and the legacy `Penugasan` write are gone (absent from `route:list`). Test: `AuthorizationOwnershipTest::test_worker_cannot_open_another_workers_project`. |
| SEC-004 | High | RESOLVED | `database/seeders/SuperAdminSeeder.php:21-28,288-297`, `database/seeders/UserSeeder.php:14-28`, `database/seeders/WorkerTaskSeeder.php:19-31` | No literal password remains in any seeder. Passwords come from `SEED_ADMIN_PASSWORD` / `SEED_USER_PASSWORD`; when unset a random `Str::password(16)` is generated and printed once. Every seeder returns early under `app()->environment('production')`, and users are created with `firstOrCreate`, so re-seeding never resets an existing password. |
| SEC-005 | Medium | RESOLVED | `php artisan route:list` (no `layanan-jasa/{service}/pesan`), `tests/Feature/CatalogModalAndAuthTest.php:124` | The state-changing GET route and its `HomeController::orderService` handler were removed; service ordering now goes through the throttled `POST /lacak/search` / checkout flows (CSRF-protected web group). |
| SEC-006 | Medium | RESOLVED | `app/Http/Controllers/Admin/ProjectController.php:77,103,113-120` | `update`/`destroy` call `authorizeProject()`, which aborts 403 unless `projects.jurusan_id` matches the admin's department. Tests: `AuthorizationOwnershipTest::test_admin_cannot_update/delete_another_departments_project`. Listing still fails open when the admin has no department — see N-6. |
| SEC-007 | Medium | RESOLVED | `app/Http/Requests/Auth/LoginRequest.php:47-53`, `app/Http/Middleware/EnsureUserIsActive.php:23-38`, `routes/api.php:8,14`, `app/Http/Controllers/Api/AuthController.php:31-37`, `app/Http/Controllers/HomeController.php:82` | Web login logs the session out and rejects; `EnsureUserIsActive` is appended to the whole `web` group and logs out + invalidates the session on the next request; API login returns 403; `auth:sanctum` routes carry the `active` alias; `showService` 404s on inactive services. Tests: `InactiveAccountTest` (6 cases). |
| SEC-008 | Medium | RESOLVED (audit premise was incorrect) | `vendor/laravel/framework/src/Illuminate/Foundation/Auth/User.php:19`, `.../Auth/Middleware/EnsureEmailIsVerified.php:33-38`, `bootstrap/app.php:21-24` | `Illuminate\Foundation\Auth\User` already applies `MustVerifyEmail`, so the `verified` middleware was **not** a no-op for this project — the baseline diagnosis was wrong, and no code change was needed. Note the real consequence of that: **N-1** (all seeded accounts are unverified and locked out). |
| SEC-009 | Medium | RESOLVED | `routes/api.php:14`, `app/Providers/AppServiceProvider.php:31-32` | Named limiter `api-login` = 5 attempts/minute keyed on `email|ip`; test `ApiLoginThrottleTest::test_api_login_is_throttled_after_five_failed_attempts`. |
| SEC-010 | Medium | PARTIALLY RESOLVED | `config/sanctum.php:53`, `routes/api.php:15`, `app/Http/Controllers/Api/AuthController.php:58-66`, `app/Http/Controllers/SuperAdmin/UserController.php:140-142` | Expiry is now finite (`SANCTUM_TOKEN_EXPIRATION`, default 10080 min = 7 days), `POST /api/logout` deletes the current token, and deactivating a user deletes all their tokens. **Gap:** changing the password does not revoke other sessions/tokens (`app/Http/Controllers/Auth/PasswordController.php:23-25` calls `update()` only) and there is still no token/device list UI. |
| SEC-011 | Medium | PARTIALLY RESOLVED | `app/Http/Controllers/AdminJurusanController.php:147,162,225-235`, `:165-167`, `app/Http/Controllers/Admin/OrderController.php:112,173,289-304` | `validateOrder` / `assignTask` now run `authorizePesanan()` (department-scoped `Order` query, fails closed for admins with no department) and `worker_id` is restricted to `role=worker` inside the admin's department. **Gaps:** (a) the manual-order form still accepts `worker_id => 'nullable|exists:users,id'` for both `store` and `update`, so a cross-department worker — or any user id — can be attached through `admin.orders.store/update`; (b) `Admin\OrderController::authorizeOrder()` only checks when the admin *has* a department, so it fails open otherwise (N-6). |
| SEC-012 | Medium | RESOLVED | `app/Http/Controllers/DashboardController.php:62-66` | `abort_unless($order->user_id !== null && (int) $order->user_id === (int) auth()->id(), 403)`. Tests: `test_pelanggan_cannot_view_an_order_owned_by_nobody`, `test_pelanggan_cannot_view_another_users_order`. |
| SEC-013 | Medium | RESOLVED | `app/Http/Controllers/CheckoutController.php:57-68`, `:215-228`, `app/Http/Controllers/Admin/OrderController.php:215-228` | Checkout is wrapped in `DB::transaction()` with `Product::whereKey(...)->lockForUpdate()`, stock is validated inside the lock before `decrement`, and all five writes (stock, order, log, notification, order items) share the transaction. Cancellation restores stock under the same lock, so double-cancel cannot double-refund. Test: `CheckoutTransactionTest` (6 cases). |
| SEC-014 | Low | CANNOT VERIFY | `config/session.php:174,187,204`, `.env` (no `SESSION_SECURE_COOKIE` entry) | Code is now env-driven as recommended, but the repository cannot show the production value: locally it must be `false` (plain HTTP) and `.env` omits it. Needs a check of the deployed environment (Laravel Cloud config). Tests only prove the wiring: `SessionCookieTest`. |
| SEC-015 | Low | RESOLVED | `config/cors.php:16-33` | `paths: api/*, sanctum/csrf-cookie`; origins come from `CORS_ALLOWED_ORIGINS` (empty allowlist by default, no `*`), `allowed_origins_patterns` empty, `supports_credentials => false`. Test: `CorsConfigTest` (4 cases). |
| SEC-016 | Low | RESOLVED | `composer audit` output; `composer.lock` | `composer audit --no-interaction` → **"No security vulnerability advisories found."** All three baseline advisories are gone: `symfony/http-foundation` is now v8.1.9 (was < 8.1.7, CVE-2026-108082) and the two `league/commonmark` advisories (GHSA-97jj-33gv-5xf9, GHSA-3q6r-r5mr-hxv8) are no longer flagged for any installed version. |
| SEC-017 | Low | RESOLVED | `app/Http/Middleware/SecurityHeaders.php:20-32,43-45`, `bootstrap/app.php:19` | `X-Content-Type-Options: nosniff`, `X-Frame-Options: DENY`, `Referrer-Policy`, `Permissions-Policy` on every response (middleware appended globally, so `/api/*` and `/up` included); HSTS opt-in via `HSTS_MAX_AGE`; CSP with `object-src 'none'`, `frame-ancestors 'none'`, `base-uri 'self'`, `form-action 'self'`. Test: `SecurityHeadersTest`. CSP defect: N-2. |
| SEC-018 | Low | RESOLVED | `app/Http/Controllers/Admin/MessageController.php:44-50` | `markAsRead` compares `pesanMasuk->jurusan_id` with `allowedJurusanIds()` and 403s otherwise (null-department broadcast messages stay readable by all, as designed). Test: `test_admin_cannot_mark_another_departments_message_as_read`. |
| SEC-019 | Low | **NOT RESOLVED** | `app/Http/Controllers/Auth/PasswordResetLinkController.php:40-43` | Still `back()->with('status', __($status))` on success vs `withErrors(['email' => __($status)])` on failure, i.e. a distinguishable "user not found" response. No change since the baseline; no test added. |
| SEC-020 | Low | **NOT RESOLVED** | `app/Models/User.php:19-28` (`role`:23, `jurusan_id`:24, `is_active`:25) | `role`, `jurusan_id` and `is_active` remain mass-assignable. All current callers were re-checked and are safe (`RegisteredUserController.php:43-49` hardcodes `pelanggan`, `SuperAdmin\UserController.php:87-94` forces `admin_jurusan` and validates the rest, `ProfileUpdateRequest.php:19-31` allows only name/email/phone/photo), so this is latent, not exploitable today. |
| SEC-021 | Info | PARTIALLY RESOLVED | `app/Http/Controllers/CheckoutController.php:108-116`, `app/Http/Controllers/AdminJurusanController.php:66-78`, `app/Http/Controllers/SuperAdmin/DepartmentController.php:21,140`, `app/Models/User.php:77-88`, `app/Http/Controllers/Api/ProdukController.php:13` | The dual-write is gone: checkout writes `orders` + `order_items` only (the mirror block is deleted and a test asserts "no legacy mirror"), `assignTask` writes `orders.worker_id` instead of `Penugasan`, and the demo seeder writes `orders`/`order_items`. The legacy models, tables and relations still exist and are still **read** (legacy `Produk` counts on the admin/super-admin dashboards, `jurusans.produks` relation, `User::pesanans()/penugasans()`, public `/api/produk`), so the structural finding stays open in reduced form. |
| SEC-022 | Info | CANNOT VERIFY | `.env.example:2-4`, `.env` (`APP_ENV=local`, `APP_DEBUG=true`, `MAIL_MAILER=log`), `route:list` shows `POST _boost/browser-logs` | Debug values are correct for local development, and the Boost browser-log route only exists while `app.debug` is true. Whether production runs `APP_DEBUG=false` / real mail transport cannot be read from the repository. (The `MAIL_MAILER=log` value is the reason N-1 is unrecoverable by users.) |
| SEC-023 | Info | PARTIALLY RESOLVED | `composer.json:56-71`, `phpstan.neon`, `phpstan-baseline.neon`, `larastan/larastan 3.13` + `phpstan/phpstan 2.3` in `composer.json:20,28` | `composer check` now chains `composer audit` → `pint --test` → `phpstan analyse` (level 5, one baselined finding for SEC-008) → `php artisan test`. **Gap:** nothing runs it automatically — no `.github/` workflow (confirmed absent) and no git hook, so the gates are still manual. |
| SEC-024 | Info | **NOT RESOLVED** | `app/Http/Controllers/Api/ProdukController.php:13`, `app/Http/Controllers/CheckoutController.php:77` | `GET /api/produk` still returns `Produk::all()` unpaginated with every column (`nomor_wa` included, no `is_active` filter, no API Resource). The hardcoded placeholder phone `'081234567890'` is still the fallback in checkout (`'customer_phone' => $request->customer_phone ?? auth()->user()->phone ?? '081234567890'`). |

---

## 3. Targeted checks (Step 3)

### 3.1 Ownership per controller action

No Policy or Gate classes exist anywhere (`app/Policies` does not exist; `Gate::` and `$this->authorize(` have zero matches; the base `App\Http\Controllers\Controller` is empty, so `$this->authorize()` is not even available). Authorization is entirely bespoke per-controller helpers plus the `role:` middleware.

| Action | Record | Ownership check | Where |
|---|---|---|---|
| `Admin\OrderController::index/create` | Order / Service / User | Department-scoped query (`whereIn department_id / service / product.jurusan_id`) | `Admin/OrderController.php:34-40,82-91` — fails open if admin has no department (N-6) |
| `Admin\OrderController::store` | creates Order + Project | Service department checked (`abort 403`) | `:118-121` — `worker_id` unvalidated (SEC-011 gap) |
| `Admin\OrderController::update` | Order (+ Project) | `authorizeOrder()` → `resolveDepartmentId()` in allowed ids | `:169,289-305` |
| `Admin\OrderController::updatePhysicalStatus` | Order + Product stock | `authorizeOrder()` | `:204` |
| `Admin\OrderController::destroy` | Order + Project + stock | `authorizeOrder()` | `:262` |
| `Admin\ProductController::edit/update/destroy` | Product | `authorizeProduct()` (kode/slug/department id) | `:87,97,131,145-183` |
| `Admin\ServiceController::edit/update/destroy` | Service | `authorizeService()` | `:95,105,140,155-193` |
| `Admin\ProjectController::update/destroy` | Project | `authorizeProject()` | `:77,103,113-120` |
| `Admin\ProjectController::index` | Project | Department filter only, **fails open** when no department | `:24-26` (N-6) |
| `Admin\WorkerController::index` | Worker users | Filter only, **fails open** when no department | `:23-25` (N-6) |
| `Admin\WorkerController::destroy` | User | Role + department equality | `:65` |
| `Admin\MessageController::markAsRead` | PesanMasuk | `allowedJurusanIds()` comparison | `:46-50` |
| `AdminJurusanController::validateOrder/assignTask` | Order | `authorizePesanan()` (fails closed) | `:147,162,225-235` |
| `AdminJurusanController::index` | Order/Project/Service/PesanMasuk | Department-scoped queries | `:32,44,105-117` |
| `AdminJurusanController::storeWorker/…/markMessageAsRead` | User/Project/PesanMasuk | **unrouted dead code**, project methods have no check | `:240-340` (N-5) |
| `WorkerController::showProject/storeLog/submitProject` | Project (+ProjectLog) | `where('worker_id', auth()->id())->findOrFail()` | `:52-54,66,100` |
| `DashboardController::clientOrderDetail` | Order | Explicit `user_id` equality + non-null | `:62-66` |
| `SuperAdmin\ReportController::correct` | Order | Role middleware only (super admin may correct globally, by design) | `:38-51` |
| `SuperAdmin\UserController::update/destroy` | User | Role middleware + self-protection | `:119-122,153-156` |
| `SuperAdmin\DepartmentController::*` | Jurusan | Role middleware only (global scope, by design) | `:63-156` |
| `Pesanan` (legacy) | — | **No routed action reads or writes `Pesanan` any more** | `route:list` + git `0233823` |

### 3.2 GET routes that change data
None. All 40+ `Route::get` entries are read-only renders; every mutation is `POST`/`PATCH`/`PUT`/`DELETE` inside the CSRF-protected `web` group. The two historic offenders are gone: `GET /layanan-jasa/{service}/pesan` (removed) and tracking search (now `POST /lacak/search`, throttled). The remaining GET writes are framework-signed: `GET /verify-email/{id}/{hash}` (signed link, marks the email verified) and `GET storage/{path}` / `GET secure-files/{path}` (signed file serving).

### 3.3 `is_active`
* Web login: **checked** — `LoginRequest.php:47-53` (`Auth::logout()` + validation error).
* Web session middleware: **checked** — `EnsureUserIsActive.php:23`, appended to the whole `web` group in `bootstrap/app.php:26-28` (JSON 403 for `api/*`, logout + session invalidation + token regeneration for the UI).
* API login: **checked** — `Api/AuthController.php:31-37`.
* API token routes: **checked** — `routes/api.php:8,14` use the `active` alias on `GET /api/user` and `POST /api/logout` (validated by `InactiveAccountTest::test_existing_api_token_stops_working_after_deactivation`).
* Service `is_active`: **checked** on the public detail page (`HomeController.php:82`) and in both listings; inactive services are also excluded from the PDF catalogue.

### 3.4 Tracking code
* Generation: `Order::generateCode($prefix)` → `$prefix.strtoupper(Str::random(12))` (`Order.php:38-45`); used by checkout (`TEFA-FISIK-`), manual admin orders (`TEFA-`) and the demo seeder (`TEFA-DEMO-`).
* Length: 12 random characters (A–Z, a–z, 0–9; ~71 bits) on top of the prefix.
* Uniqueness: `do { … } while (static::where('order_code', $code)->exists())`.
* Throttle: `throttle:10,1` on both `GET /lacak/{order_code}` and `POST /lacak/search` (`routes/web.php:33-34`).
* Legacy codes: rewrite migration `2026_10_17_000000_regenerate_guessable_order_codes.php` (irreversible `down()`), so pre-existing 4-digit codes no longer work.
* Failed lookups: `Log::warning('Tracking lookup failed', …)` with code + IP (`TrackingController.php:46-48`), and valid/invalid codes render the same view shape.

### 3.5 Uploads
| Upload | Validation | Size | Naming | Disk |
|---|---|---|---|---|
| Worker progress attachment (`WorkerController.php:70,77`) | `file|mimes:jpg,jpeg,png,webp,pdf|extensions:…` | 5 MB | `hashName()` | private `uploads` (`storage/app/uploads`, `serve => true`, signed URL) |
| Final submission (`WorkerController.php:103,107`) | `file|mimes:jpg,jpeg,png,pdf,zip,mp4|extensions:…` | 10 MB | `hashName()` | private `uploads` |
| Product photo (`Admin/ProductController.php:62,104`) | `image|mimes:jpeg,png,jpg,webp` | 2 MB | `hashName()` | public `storage/app/public` (public catalogue image — intended) |
| Service photo (`Admin/ServiceController.php:60,111`) | `image|mimes:jpeg,png,jpg,webp` | 2 MB | `hashName()` | public disk |
| Profile photo (`ProfileUpdateRequest.php:30`, `ProfileController.php:50`) | `image|mimes:jpeg,jpg,png` | 1 MB | `hashName()` | public disk |

No upload path accepts an arbitrary extension any more. `HandlesUploads::storeUploadedFile()` (all five callers) writes with `putFileAs(..., $file->hashName())`.

### 3.6 Seeders and hardcoded passwords
No literal password remains (`regex Hash::make('<literal>')` / `'password' => '<literal>'` → zero matches). All four user-creating seeders read `SEED_ADMIN_PASSWORD` / `SEED_USER_PASSWORD`, fall back to a random 16-char password printed once, and abort in production.

### 3.7 API login and tokens
* Throttle: `throttle:api-login` → 5/minute per `lower(email)|ip` (`routes/api.php:14`, `AppServiceProvider.php:31-32`).
* Expiration: `config/sanctum.php:53` → `(int) env('SANCTUM_TOKEN_EXPIRATION', 10080)` (7 days), `.env.example:49` documents it.
* Revocation: `POST /api/logout` (`AuthController.php:58-66`); all tokens deleted when a user is deactivated (`SuperAdmin/UserController.php:140-142`). Not revoked on password change (SEC-010 gap).

### 3.8 Checkout
`DB::transaction()` at `CheckoutController.php:57`; `Product::whereKey($product->getKey())->lockForUpdate()->firstOrFail()` at `:59`; stock compared before `decrement` at `:61-68` and a `ValidationException` aborts the whole transaction; `order_items` is written inside the same transaction (`:110-116`). Cancellation restores stock under a lock in `Admin/OrderController.php:215-228`.

### 3.9 Cookies
`config/session.php`: `secure` → `env('SESSION_SECURE_COOKIE', false)` (:174), `http_only` → `env('SESSION_HTTP_ONLY', true)` (:187), `same_site` → `env('SESSION_SAME_SITE', 'lax')` (:204), `domain`/`partitioned` env-driven, cookie encryption off (Laravel default) and `serialization => 'json'`. All three are environment-driven; only the production values are unverifiable.

### 3.10 CORS
`config/cors.php`: origins = trimmed `CORS_ALLOWED_ORIGINS` list (empty by default), `allowed_origins_patterns` empty, `supports_credentials => false`, methods `GET/POST/OPTIONS`, paths `api/*` + `sanctum/csrf-cookie`. **No wildcard origin, and no wildcard-plus-credentials combination.**

### 3.11 Security headers and CSP
Set in `app/Http/Middleware/SecurityHeaders.php:20-32` (global append in `bootstrap/app.php:19`): `X-Content-Type-Options`, `X-Frame-Options: DENY`, `Referrer-Policy`, `Permissions-Policy`, opt-in HSTS, and CSP (`:54-65`):
`default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; font-src 'self' https://cdn.jsdelivr.net data:; img-src 'self' data: blob: https://images.unsplash.com; connect-src 'self'[+vite in local]; form-action 'self'; base-uri 'self'; object-src 'none'; frame-ancestors 'none'`.

Assets the CSP must allow, found in views:
* 32 inline `<script>` blocks + inline event handlers → the `'unsafe-inline' 'unsafe-eval'` tokens (Alpine's `new Function`) — already covered and documented as a `shortcut:` in the middleware.
* No external `<script src=…>` at all.
* **10+ external stylesheets** from `https://cdn.jsdelivr.net/fontsource/...` (`layouts/public.blade.php:11-16,21-23`, `layouts/admin.blade.php:8-13`, `layouts/guest.blade.php`, `layouts/worker.blade.php`, `layouts/superadmin.blade.php`, `partials/footer.blade.php`, `auth/login.blade.php`, `auth/register.blade.php`, `client/orders.blade.php`, `profile/partials/card.blade.php`, `public/tracking.blade.php`, `superadmin/katalog/katalog.blade.php`, `superadmin/reports/print.blade.php`) — **`style-src` does not list `cdn.jsdelivr.net`, so these are blocked.** See N-2.
* `https://images.unsplash.com` images → allowed by `img-src`.

### 3.12 Models
`Order` **and** `Pesanan` both still exist (`app/Models/Order.php`, `Pesanan.php`, plus `DetailPesanan`, `Penugasan`, `Progres`, `Pembayaran`, `Produk`). **No code writes to the legacy family any more**: grep for `Pesanan::create/update/delete/firstOrCreate/insert`, `DetailPesanan::`, `Penugasan::`, `Progres::`, `Pembayaran::`, `Produk::create/updateOrCreate/firstOrCreate` returns matches only inside the models' own relations. What still *reads* legacy: `AdminJurusanController.php:66-78` (legacy `Produk` counts), `SuperAdmin/DepartmentController.php:21,140` (`jurusan->produks`, `withCount('produks')`), `User.php:77-88` (`pesanans()`, `penugasans()`), `Api/ProdukController.php:13`.

---

## 4. Dependencies (Step 4)

`composer audit --no-interaction` → **No security vulnerability advisories found.**

| Baseline advisory | Package | Baseline version | Installed now | Status |
|---|---|---|---|---|
| GHSA-97jj-33gv-5xf9 (DisallowedRawHtml bypass) | `league/commonmark` | 2.10.1 | not flagged (no advisory match) | Fixed |
| GHSA-3q6r-r5mr-hxv8 (quadratic-time DoS, GFM tables) | `league/commonmark` | 2.10.1 | not flagged (no advisory match) | Fixed |
| CVE-2026-108082 (ReDoS via `Cache-Control`) | `symfony/http-foundation` | < 8.1.7 | **v8.1.9** | Fixed |

Other installed versions of interest: `laravel/framework` v13.30.1, `laravel/sanctum` v4.3.3, `barryvdh/laravel-dompdf` v3.1.2 (`dompdf/dompdf` v3.1.6), `guzzlehttp/guzzle` 8.2.0, `nesbot/carbon` 3.13.2. New advisories: **none**.

---

## 5. Tests (Step 5)

`php artisan test --compact` against MySQL `tefa_katalog_testing`:

```
{"tool":"phpunit","result":"passed","tests":164,"passed":164,"assertions":671,"duration_ms":17943}
```

**164 tests, 164 passed, 0 failed, 0 skipped, 671 assertions, ~18 s.** No test failure to diagnose.

Coverage added since the baseline (this is the strongest part of the remediation): `TrackingSecurityTest`, `UploadValidationTest`, `AuthorizationOwnershipTest` (11 negative/positive cases), `InactiveAccountTest` (6), `ApiLoginThrottleTest` (4), `CheckoutTransactionTest` (6), `SecurityHeadersTest` (3), `SessionCookieTest` (3), `CorsConfigTest` (4), `PhysicalProductCheckoutTest` (8, incl. cross-department rejection), `SuperAdminModularTest`, `AdminModularAndProductCrudTest`. Untested gaps that map to open findings: password-reset response uniformity (SEC-019), token revocation on password change (SEC-010), `worker_id` scoping in the manual-order form (SEC-011), the seeded-account/unverified-email combination (N-1), and CSP vs. external stylesheet origins (N-2).

---

## 6. New issues found (not in the baseline)

**N-1 — HIGH (availability): every seeded account is unverified and locked out of all authenticated areas.** — **RESOLVED after this audit** (see §9).
`Illuminate\Foundation\Auth\User` applies `MustVerifyEmail` (`vendor/laravel/framework/src/Illuminate/Foundation/Auth/User.php:19`), so the `verified` middleware on `routes/web.php:36` really does redirect unverified users to `verification.notice`. No seeder ever sets `email_verified_at` (`database/seeders/SuperAdminSeeder.php:103-181`, `UserSeeder.php:31-60`, `WorkerTaskSeeder.php:32-38`), the column is nullable with no default (`database/migrations/0001_01_01_000000_create_users_table.php:19`), and a read-only count of the local database returns **27 unverified of 27 users**. Because `MAIL_MAILER=log` (`.env`), those accounts cannot self-verify: every one of them is redirected away from `/dashboard`, `/superadmin/*`, `/admin/*`, `/worker/*`, `/my-orders` and `/checkout`. New registrations hit the same wall. The test suite does not catch it because `UserFactory` sets `email_verified_at`.
*Smallest fix (not applied):* set `'email_verified_at' => now()` in the seeders, or drop `verified` from the group if email verification is not part of the workflow.

**N-2 — MEDIUM (the header fix breaks the UI it protects): CSP `style-src` omits the CDN that every layout uses.**
`SecurityHeaders.php:57` sets `style-src 'self' 'unsafe-inline'`, but the layouts load 6–9 Fontsource stylesheets from `https://cdn.jsdelivr.net` (e.g. `layouts/public.blade.php:11-16`, `layouts/admin.blade.php:8-13`). External stylesheets are governed by `style-src`, not `font-src` (which does list the CDN at `:45`), so the browser blocks them and every page falls back to the fallback font stack. `SecurityHeadersTest::test_content_security_policy_allows_the_assets_the_views_actually_use` asserts the `font-src` entry, which is why it passes. *Fix:* add `https://cdn.jsdelivr.net` to `style-src`, or self-host the CSS through Vite.

**N-3 — MEDIUM: worker progress attachments are handed to anonymous visitors on the public tracking page.**
`resources/views/public/tracking.blade.php:427-433` renders `$log->lampiran_url`, and `ProjectLog::getLampiranUrlAttribute()` (`app/Models/ProjectLog.php:55`) mints a 30-minute signed URL. Anyone who has the order code — or arrives via a leaked/shared link, browser history or proxy log — gets the internal work notes and the raw attachment download without authenticating. The tracking code is now unguessable (SEC-001 closed), which is what keeps this at MEDIUM rather than HIGH. Consider rendering progress notes without the attachment link on the public page.

**N-4 — LOW: four registered routes point at controller methods that do not exist (500 on hit).**
`admin.orders.show`, `admin.orders.edit`, `admin.products.show`, `admin.services.show` come from `Route::resource(...)` (`routes/web.php:76,79,83`), but `Admin\OrderController` has no `show()`/`edit()`, and `Admin\ProductController`/`Admin\ServiceController` have no `show()`. No view links to them (grep: zero hits), so they are currently unreachable, but any direct hit returns a 500 (`APP_DEBUG=true` would then render the stack trace).

**N-5 — LOW: dead AdminJurusanController methods with missing checks.**
`app/Http/Controllers/AdminJurusanController.php:240-340` still contains `storeWorker`, `deleteWorker`, `storeProject`, `updateProject`, `deleteProject` and `markMessageAsRead`; none is routed (the routed equivalents live in `Admin\*`). `updateProject`/`deleteProject` (`:303-330`) perform **no** ownership check, so re-wiring them would silently reopen SEC-006/SEC-012-class holes. Delete or align them with the `Admin\ProjectController` guards.

**N-6 — LOW: inconsistent fail-open department scoping.**
`Admin\OrderController::authorizeOrder()` (`:289-305`), `Admin\ProjectController::index` (`:24-26`) and `Admin\WorkerController::index` (`:23-25`) skip filtering when the admin has no `jurusan_id`, while `AdminJurusanController` fails closed (`:208-214`) and `Admin\ProjectController::authorizeProject` denies. A `admin_jurusan` row with `jurusan_id = NULL` (only creatable by direct DB edit today, since `SuperAdmin\UserController.php:110-114` requires it) would see and edit every department's orders and projects. Pick one behaviour — fail closed — for all four.

**N-7 — LOW: debug-gated Boost log endpoint present in the running app.**
`route:list` shows `POST _boost/browser-logs` (unauthenticated, CSRF-exempt writer) because `APP_DEBUG=true` locally. Not a new defect (baseline SEC-022), kept here so the production checklist does not miss it.

---

## 7. Cannot be verified statically

| Item | What is needed |
|---|---|
| Production cookie flag (SEC-014) | The deployed `SESSION_SECURE_COOKIE=true` (and `SESSION_SAME_SITE`) value — check the Laravel Cloud environment, not the repo. |
| Production debug/mail config (SEC-022, N-1) | `APP_DEBUG=false` and a real `MAIL_MAILER` in production; without the latter, email verification (N-1) is impossible for staff. |
| HTTPS/HSTS in production | Actual scheme served; `HSTS_MAX_AGE` value in the deployed environment (`config/app.php:68`). |
| PHP execution in `public/storage` / `public/uploads` | `public/.htaccess` exists, but whether the web server refuses to execute `.php`/`.phtml` inside `public/storage` (symlinked `storage/app/public`) requires a live probe. The private `uploads` disk removes the equivalent risk for worker files. |
| `storage/{path}` and `secure-files/{path}` signature enforcement | Both are framework `serve` routes for `local`/`uploads`; confirm on the server that an unsigned request 403s and that `serve` is not enabled unexpectedly in production. |
| Real `.env` secrets/keys | Not printed by this audit (read-only, no secret values inspected). |
| Runtime CSP behaviour | The `style-src` verdict in N-2 is static; a browser console check confirms whether fonts load. |
| Legacy data volume/consistency | Row counts and mirror parity in `pesanans`/`detail_pesanans` vs `orders` (no writes were made). |

---

## 8. Recommended next actions (priority order, none implemented)

1. **N-1 (HIGH)** — unblock seeded/staff accounts: set `email_verified_at` in the seeders, or remove `verified` from the authenticated route group (`routes/web.php:36`); add a test that a seeded staff account can open its dashboard.
2. **SEC-011 remainder (MEDIUM)** — constrain `worker_id` in `Admin\OrderController::store/update` to `role=worker` + own department (`Rule::exists`, same as `Admin\ProjectController.php:53-55`); make `authorizeOrder()` fail closed when `jurusan_id` is null (also N-6).
3. **SEC-010 remainder (MEDIUM)** — revoke other tokens/sessions on password change (`PasswordController::update`), and add a token list/revoke action.
4. **N-2 (MEDIUM)** — add `https://cdn.jsdelivr.net` to CSP `style-src` (or self-host fonts) and extend `SecurityHeadersTest` to assert every external origin the layouts load.
5. **N-3 (MEDIUM)** — stop rendering worker attachments on the public tracking page (notes only), or require the client to be logged in for file links.
6. **SEC-019 (LOW)** — return one generic response from `PasswordResetLinkController::store`; add a test that the "unknown email" and "link sent" responses are indistinguishable.
7. **SEC-020 (LOW)** — drop `role`/`jurusan_id`/`is_active` from `User::$fillable` and set them explicitly in the controllers that already validate them.
8. **SEC-024 (LOW)** — paginate + narrow `/api/produk` (API Resource, drop `nomor_wa`), and move the placeholder phone/WhatsApp fallbacks into config.
9. **N-4 / N-5 / N-7 (LOW)** — delete the four dead resource methods/routes, delete the six unrouted `AdminJurusanController` methods, and add the production debug/mail checks to the deployment checklist.
10. **SEC-023 remainder (LOW)** — run `composer check` from CI (`.github/workflows`) so the existing gates are automatic.
11. **SEC-021 (structural, Info)** — retire the remaining legacy read paths (`Produk` counts, `jurusans.produks`, `/api/produk`) once the dashboards read `Product`/`Order` only.

---

*End of report. Read-only audit: no application file, config, migration, seeder or dependency was changed; the only database access was a read-only row count against the local dev database, and the test suite ran against `tefa_katalog_testing`.*

---

## 9. Follow-up fix applied after the audit (N-1 only)

Applied with the user's approval; every other finding is still open.

| Change | File |
|---|---|
| `verified` removed from the authenticated route group (email verification stays implemented but is no longer a gate) | `routes/web.php:36-39` |
| Demo accounts marked verified via an idempotent `ensureVerified()` helper that also heals pre-existing rows | `database/seeders/SuperAdminSeeder.php` (`ensureVerified`, used for all 18 accounts), `database/seeders/UserSeeder.php`, `database/seeders/WorkerTaskSeeder.php` |
| Staff-created accounts marked verified at creation (mail is log-only, so the creating admin is the verification authority) | `app/Http/Controllers/SuperAdmin/UserController.php:87-99`, `app/Http/Controllers/Admin/WorkerController.php:47-59` |
| Regression test: unverified client reaches `/my-orders`, all three staff roles reach their dashboards, a Super-Admin-created admin is verified and usable | `tests/Feature/AccountAccessTest.php` (3 tests) |

Verification: `php artisan test` → **167 tests, 167 passed, 679 assertions** (was 164); `vendor/bin/pint --dirty --format agent` → passed. `php artisan db:seed --class=SuperAdminSeeder` healed the 18 demo rows in the local dev database (the 9 pre-existing hand-made/test accounts stay unverified but are no longer blocked).

Note: `email_verified_at` is not in `User::$fillable`, so it is written with `forceFill()` rather than by widening the fillable list (which would work against SEC-020).

