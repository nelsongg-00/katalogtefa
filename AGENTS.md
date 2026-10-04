# AGENTS.md

Instructions for AI agents in `katalogtefa`: a single-app Laravel 13 repo (PHP 8.5, Breeze auth, Sanctum) — an Indonesian-language school catalog + order/production system ("TEFA"). All models, columns, and UI copy are Indonesian.

Everything outside the `<laravel-boost-guidelines>` block is hand-maintained. `php artisan boost:update` rewrites only the inside of that block (`vendor/laravel/boost/src/Install/GuidelineWriter.php`) — never put repo notes in there.

## Agent Skills (OpenCode)

This project uses skills installed under `.opencode/skills/`. Before acting on any request, check if a skill applies and invoke it with the `skill` tool.

### Core Rules

- If a task matches a skill, invoke it with the `skill` tool before acting.
- Skills are located in `.opencode/skills/<skill-name>/SKILL.md`.
- Follow the skill workflow strictly; do not partially apply it.
- Never skip required steps such as spec, plan, or test when a skill demands them.

### Intent → Skill Mapping

Map the user's intent to the matching skill automatically:

- Feature / new functionality → `spec-driven-development`, then `incremental-implementation` + `test-driven-development`
- Planning / breakdown → `planning-and-task-breakdown`
- Bug / failure / unexpected behavior → `debugging-and-error-recovery`
- Code review → `code-review-and-quality`
- Refactoring / simplification → `code-simplification`
- API or interface design → `api-and-interface-design`
- UI work → `frontend-ui-engineering`
- Deploy / release → `shipping-and-launch`

### Execution Model

For every request:

1. Determine if any skill applies (even a small chance).
2. Load the skill with `skill({ name: "<skill-name>" })`.
3. Follow the skill workflow exactly.
4. Only proceed to implementation once required steps are complete.

## Standing constraints

- No new top-level folders, no dependency changes, no documentation files — unless the user asks.

## Commands

- **Fresh setup:** `composer setup` — install, create `.env`, `key:generate`, `migrate --force`, `npm install --ignore-scripts`, `npm run build`. It does **not** seed.
- **Dev:** `composer run dev` → `php artisan dev` (runs `artisan serve` + `queue:listen` + `npm run dev` concurrently; list with `php artisan dev:list`). Logs: `php artisan pail`.
- **Frontend:** `npm run build` / `npm run dev` (Vite inputs: `resources/css/app.css`, `resources/js/app.js`).
- **Tests:** `composer test` (= `config:clear` + `php artisan test`)
  - one file: `php artisan test tests/Feature/ProfileTest.php`
  - one test: `php artisan test --filter=testName`
- **After any PHP edit:** `vendor/bin/pint --dirty --format agent` (no `pint.json` → default `laravel` preset; fix-all form in the Boost block below).
- Run pint + tests yourself before finishing — no CI (`.github` confirmed absent) or pre-commit hook does it for you.

## Architecture

- Routing: all UI lives in `routes/web.php`; `routes/api.php` is tiny (`GET /api/produk` is public, `POST /api/login`, `GET /api/user` behind `auth:sanctum`).
- Authorization: plain string on `users.role` — `super_admin`, `admin_jurusan`, `worker`, `pelanggan` — enforced by the `role` middleware alias (`app/Http/Middleware/CheckRole.php`); unauthorized users get redirected to `/`. Areas: `/superadmin/*`, `/admin/*`, `/worker/*`, `/my-orders` (pelanggan). Users in role areas normally need `jurusan_id` (department).
- **Two parallel data families — the main trap:**
  - New English models/tables: `Product`/`products`, `Order`/`orders`, `Service`/`services`, `OrderLog`.
  - Legacy Indonesian: `Produk`/`produks`, `Pesanan`/`pesanans`, `DetailPesanan`, `Penugasan`, `Progres`, `Pembayaran`.
  - They are synced **by hand**: checkout (`CheckoutController`, `HomeController::orderService`) writes the new `Order`, then mirrors into `Pesanan` + `Produk` + `DetailPesanan` (step commented "Sinkronisasi ke tabel Pesanan") because Super Admin reports and the Admin Jurusan dashboard read the legacy tables. When adding order/product logic, write both families — or first confirm which family the consuming view/query already uses. When in doubt about which family a view or query reads, grep the view name in `resources/views/` before writing code — do not guess.
- File uploads: use `App\Traits\HandlesUploads::storeUploadedFile()` — it dodges a Windows/PHP 8.5 `UploadedFile` realpath bug; writes to the `public` disk.
- Controllers are grouped `Admin\` (jurusan-admin modules), `SuperAdmin\`, and root (home/worker/client/checkout), but naming is inconsistent (`AdminJurusanController`, `SuperadminController`) — match the file you're editing.

## Database & tests

- Local dev DB is **MySQL `tefa_katalog`** (Laragon, root, empty password) per `.env`. `.env.example` says `sqlite` — stale; trust `.env`.
- Tests are hard-pinned by `phpunit.xml` to **MySQL `tefa_katalog_testing`**. It must already exist *and be migrated* before the first run (some feature tests use no reset trait and query tables in `setUp()`). Migrate into it once from PowerShell:
  ```powershell
  $env:DB_DATABASE='tefa_katalog_testing'; php artisan migrate --force; Remove-Item Env:DB_DATABASE
  ```
  Never repoint tests at `tefa_katalog` — several tests delete/write rows.
- Reset strategy is mixed per test file: `RefreshDatabase` (wipes schema), `DatabaseTransactions`, or no trait (rows persist across runs). Tests are written idempotently (`firstOrCreate`, explicit deletes in `setUp`), so don't assume a clean or pre-seeded DB. After schema changes run the whole suite, not one file.
- Local data: `php artisan db:seed` (`DatabaseSeeder` calls `SuperAdminSeeder` first, then Product/Project/PesanMasuk/WorkerTask/Service/TefaCatalog).
- The root file `tefa_katalog` is a 0-byte stray artifact — ignore it.

## Frontend

- **Tailwind v3 via PostCSS** (`postcss.config.js` + `tailwind.config.js`, `@tailwind` directives in `resources/css/app.css`). `package.json` also lists `@tailwindcss/vite` v4, but it is *not* registered in `vite.config.js` — don't switch pipelines or write v4 syntax.
- `tailwind.config.js` content globs scan only `resources/views/**/*.blade.php` (+ pagination views): class names assembled in JS/Alpine strings won't be generated.
- `ViteException: Unable to locate file in Vite manifest` → run `npm run build` (or `composer run dev`).

## Other repo facts

- `CLAUDE.md` is Laravel Boost's bootstrap stub telling agents to install Boost — it's already installed (`boost.json`, `laravel/boost` in require-dev); skip that. Boost MCP (`php artisan boost:mcp`, config in `.agents/mcp_config.json`) isn't available to OpenCode sessions — don't wait on Boost tools.
- `.agents/skills/` holds 5 Boost skills (`laravel-best-practices`, `testing-best-practices`, `tailwindcss-development`, `infer-conventions`, `deploying-to-cloud`); load the matching one when working in that domain.

<laravel-boost-guidelines>
=== laravel boost guidelines (generated by `php artisan boost:update` — edit outside this block) ===

- Follow existing sibling-file conventions; check for existing components/classes to reuse before writing new ones.
- Prefer tests over tinker or throwaway verification scripts when tests cover the behavior.
- Create files with `php artisan make:*` (models → also create factories/seeders; tests → `php artisan make:test --phpunit Name`, no suite prefix in `{name}`). Pass `--no-interaction` to Artisan commands.
- Run the narrowest test set that covers the change: `php artisan test --compact <path>` or `--filter=testName`; rerun a test after each change to it. `vendor/bin/phpunit` accepts the same arguments. In tests, build models with factories (check factory states first), not manual rows.
- Tinker: `php artisan tinker --execute 'Code();'` — single quotes only (shell expansion), double quotes for PHP strings inside.
- Prefer named routes + `route()` for links. For APIs, use Eloquent API Resources unless existing API routes don't.
- After modifying PHP files: `vendor/bin/pint --dirty --format agent` before finishing (fix-all: `vendor/bin/pint --format agent`; don't run `pint --test`).
- Deployment target is Laravel Cloud — activate the `deploying-to-cloud` skill when deploying or touching Cloud config.
</laravel-boost-guidelines>
