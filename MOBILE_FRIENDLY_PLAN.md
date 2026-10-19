# Katalog TEFA — Mobile-Friendly Pass (Client Pages)

Goal: every customer/visitor page usable on a phone in portrait (360×800 → 430×932) with no horizontal scrolling, no tiny text, no overlapping elements, no hard-to-tap controls — **without changing the design, business logic, routes, validation, prices, stock, or checkout math**.

Admin, worker, and super admin pages are out of scope.

Status: **plan approved** (all 7 questions answered). Implementation follows the step order in §5.

---

## 0. Key facts from investigation

1. **`app/Http/Middleware/SecurityHeaders.php` does not exist.** No `Content-Security-Policy` string anywhere in the repo. The site already ships inline `<script>` blocks and `onclick=` handlers. Working rule regardless: **no new CDNs, no new external origins, no new dependencies, no `unsafe-eval`, no new inline-script pattern.**
2. **The navbar is the single biggest failure** and it is on every public page including login/register:
   `resources/views/partials/navbar.blade.php:14-124` — one horizontal flex row, no hamburger, no breakpoint.
   `resources/views/layouts/public.blade.php:43` sets `overflow-x: hidden`, so on a 360px phone the right side (Masuk/Daftar, avatar, cart) is **clipped and unreachable**, not scrollable.
3. **Styling system:** Tailwind v3 via PostCSS (`tailwind.config.js` scans `resources/views/**/*.blade.php`) + per-page scoped inline `<style>` blocks. Global CSS = `resources/css/app.css` (only the three `@tailwind` directives). JS = `resources/js/app.js` (Alpine from npm via Vite) plus vanilla scripts inline in each view. **No external JS libraries anywhere.** Only external resource = Fontsource CSS from `cdn.jsdelivr.net`.
4. **All viewport meta tags are correct** (`width=device-width, initial-scale=1`) in `layouts/public`, `layouts/guest`, `layouts/app`, `auth/login`, `auth/register`.
5. **There are no data tables on any client page** → no "table → card" conversion needed.
6. `resources/views/client/checkout.blade.php` is an **orphan** (no controller renders it) — untouched.
7. `/dashboard` redirects by role (`DashboardController::index` → `client.orders` for pelanggan); `dashboard.blade.php` + `layouts/app` are effectively unused for customers.

---

## 1. Page inventory (Phase 1 results)

Status: **broken** = unusable on a phone · **needs work** = usable but violates the criteria · **OK** = already passes.

| # | Route | View file | Status | Main issues (file:line) |
|---|-------|-----------|--------|--------------------------|
| 1 | all public + login/register | `partials/navbar.blade.php` | **broken** | No mobile menu; flex row overflows ≤960px and is clipped (14-124); cart button 38px (130-132); avatar button ~40px (144-155) |
| 2 | `/my-orders/{order}` | `client/orders/show.blade.php` | **broken** | Inline `grid-template-columns: 1fr 1fr` never stacks (139); 4-col stepper inside ~340px (69) → labels wrap/overlap |
| 3 | `/login`, `/register` | `auth/login.blade.php`, `auth/register.blade.php` | needs work | `--scale:0.79` → inputs **13.4px** (43) → iOS auto-zoom; field/submit height **34.8px** (51, 187-200, 218-230); eye toggle ~19px (162-173); "remember" checkbox 17px (204) |
| 4 | `/checkout/{product}` | `public/checkout.blade.php` | needs work | Input font 14px (91); qty input 15px (71); stepper buttons 38px (69); grid already stacks ≤860px ✔ (114-117) |
| 5 | `/lacak`, `/lacak/{order_code}` | `public/tracking.blade.php` | needs work | Inline 4-col stepper (527) cramped at 360px; "Salin" button ~24px (308, 508); LACAK button 40px (149-161); meta grid is `auto-fit` ✔ (333) |
| 6 | `/profile` | `profile/partials/card.blade.php` | needs work | Inputs 35px tall / 14px font (126-135, 234-243) → iOS zoom + small target; `.btn` 34px (211); grid already stacks ✔ (110, 250-256) |
| 7 | `/produk` | `public/produk.blade.php` | needs work | Search input 13px/38px (165-178); "Cari" 38px (191-204); card CTA ~38px/12.5px (363-378); grid 2-col base ✔ (217-221); tabs scroll horizontally ✔ (114-122) |
| 8 | `/layanan-jasa` | `public/jasa.blade.php` | needs work | Same pattern as #7 (166-209, 220) |
| 9 | `/` | `home.blade.php` | needs work | Hero CTAs 34px (313-328); sections already responsive via Tailwind `sm:`/`lg:` ✔ (504, 555); carousel arrows 36px + dots 10px (`partials/home/slider.blade.php:53,120,130-135`); carousel pause is `mouseenter`/`mouseleave` only (47-48) |
| 10 | guest auth: forgot / reset / confirm / verify | `layouts/guest.blade.php` + Breeze components | needs work | Layout stacks ✔ (125-131, 274-287); inputs `text-sm` = 14px, buttons `py-2` ≈ 36px (`components/text-input.blade.php:2`, `components/primary-button.blade.php:2`) |
| 11 | all public pages | `layouts/public.blade.php` | needs work | Pagination buttons 34px (196-245 and `vendor/pagination/custom.blade.php`); toast `max-width:420px` at `right:24px` (281); global grids 3/4-col with **no** media queries (106, 111, 118, 128) — currently unused by client pages but a trap |
| 12 | `/profil-sekolah` | `public/profil.blade.php` | mostly OK | Breakpoints 992/760/640 ✔ (303-324); buttons 38px (163); `onerror` fallback to `images.unsplash.com` (388) — pre-existing external origin |
| 13 | `/produk/{product}`, `/layanan-jasa/{service}` | `public/produk/show.blade.php`, `public/jasa/show.blade.php` | mostly OK | CTAs ~46px ✔; single-column card ✔; only the shared navbar blocks them |
| 14 | `/portofolio`, `/portofolio/{portfolio}` | `public/portofolio.blade.php`, `public/portofolio/show.blade.php` | mostly OK | Breakpoints 600/640/1024 (302-331) and 800/520 (246-256) ✔; search/tabs share the small-input pattern of #7 |
| 15 | `/jurusan/{slug}` | `public/jurusan/show.blade.php` | OK | Mobile-first; 640/1024 rules (119, 130) |
| 16 | `/my-orders` | `client/orders.blade.php` | OK | Mobile-first; 640px adds the desktop layout (327-354) |
| 17 | shared footer | `partials/footer.blade.php` | needs work | 1/2/4-col breakpoints ✔ (122-137); social icons 30px tap target (92); body text 12px (29) |
| 18 | `/layanan-jasa/{service}/pesan` | — | n/a | `HomeController::orderService` is a redirect, no view |

### Cart & checkout flow (checked specifically)

Single product → qty stepper → fixed "Ambil di Tempat" + COD blocks (display only, no radios) → WhatsApp number + note → order summary (sticky, goes static ≤860px) → submit. No cart page, no quantity-edit page, no payment form. Nothing to redesign; only sizing/font fixes (row #4).

### Interactive components (touch audit)

| Component | Location | Touch status |
|---|---|---|
| Avatar popover | `partials/navbar.blade.php:406-442` | ✔ tap toggle, tap-outside, Escape already work |
| Home carousel | `partials/home/slider.blade.php` (Alpine `homeSlider()` in `home.blade.php:640-777`) | ✖ no swipe, pause only on hover; arrows work on tap |
| Qty steppers (checkout, client checkout) | `public/checkout.blade.php:187-189` | ✔ `onclick` works with touch; sizes too small |
| Password eye toggle | `auth/login.blade.php:334-346`, `auth/register.blade.php:317-341` | ✔ works; hit area ~19px |
| Scroll reveal / parallax / tilt | `home.blade.php:779-973`, `public/produk.blade.php:694-721` | ✔ `IntersectionObserver`; tilt correctly gated behind `(hover:hover)` (784, 863) |
| Particle background (auth pages) | `partials/particle-network.blade.php` | ✔ has `touchstart/touchmove/touchend` handlers; decorative only |
| Modals / accordions / tabs (client) | — | none exist (tabs are plain links) |

---

## 2. Approved decisions (Q1–Q7)

| # | Question | Decision |
|---|---|---|
| Q1 | Mobile nav | **Hamburger panel** ≤960px, tap-outside + Escape to close, `aria-expanded`/`aria-controls` |
| Q2 | JavaScript budget | **Hamburger JS (~25 lines) + carousel touch swipe/pause (~15 lines)** — nothing else, no libraries |
| Q3 | Breakpoints | **Navbar collapses ≤960px; all other pages keep the existing 640 / 1024 breakpoints** |
| Q4 | 4-step trackers on phones | **Stack vertically ≤640px**; desktop keeps the horizontal 4-column stepper |
| Q5 | CSP | File does not exist / unclear → **add nothing external anyway** (no new origin, CDN, dependency, `unsafe-eval`, or new inline-script pattern) |
| Q6 | Input font size | **16px only ≤640px** — iOS zoom fixed, desktop pixel-identical |
| Q7 | Guest auth pages | **In scope** — fixed via CSS override in `layouts/guest.blade.php`, not by editing shared Breeze components |

---

## 3. Approach & breakpoint strategy

1. **Mobile-first.** Base styles target phones; `@media (min-width: 640px)` = tablet; `(min-width: 1024px)` = desktop — the convention already used by produk, jasa, tracking, my-orders, profile, footer. No new convention.
2. **Navbar-only collapse at 960px** (the same number `layouts/guest.blade.php:125` already uses). ≥960px markup and CSS stay byte-identical → desktop unchanged.
3. **Touch targets ≥44×44px**, added inside `max-width` media queries (or low-specificity `:where()` rules) so desktop sizes never move.
4. **Form inputs ≥16px on ≤640px only** → kills iOS focus-zoom without touching desktop.
5. **Stack the inline 2-col/4-col grids at ≤640px** (`client/orders/show:139`, both steppers) → `1fr`, vertical step list with full labels.
6. **No tables** → no card conversion (recorded so we don't invent work).
7. **CSS stays inside the Blade file it belongs to**, scoped with the page prefix (`.produk-page`, `.status-page`, `.my-orders-page`, `.profile-page`…). No new CSS/JS files. No `npm run build` required as long as no new Tailwind utility classes are introduced — that is a rule for implementation: **prefer plain CSS over new Tailwind classes**.

---

## 4. Changes by file

### 4.1 Shared first (affects every client page)

| Step | File | Change |
|---|---|---|
| S1 | `resources/views/partials/navbar.blade.php` | Hamburger `<button>` (44×44, `aria-expanded`, `aria-controls`) visible only ≤960px; stacked menu panel containing the 5 links + Masuk/Daftar (or avatar popover entry); toggle/outside-tap/Escape close extending the existing JS at 406-442; shrink brand text ≤640px |
| S2 | `resources/views/layouts/public.blade.php` | ≤640px: pagination buttons → 44px (196-245), toast `max-width: calc(100vw - 32px)` (281), `.page-header h1` scale-down (151), safety `img { max-width: 100% }` |
| S3 | `resources/views/partials/footer.blade.php` | 44px hit areas on the 2 social icons + phone/mail links ≤640px (92, 211, 215); 12px → 13px text ≤640px (29) |
| S4 | `resources/views/partials/home/slider.blade.php` | Arrows → 44×44 and dots → 44px tap area (visual size unchanged, hit area grown); add touch swipe + `touchstart` pause via Alpine (`@touchstart`/`@touchend` on the wrapper, ~15 lines) |

### 4.2 P1 — checkout & tracking (first priority)

| Step | File | Change |
|---|---|---|
| P1.1 | `resources/views/public/checkout.blade.php` | Inputs → 16px ≤640px (91), stepper buttons/input → 44px (69, 71); keep the existing 860/520 rules (114-123) |
| P1.2 | `resources/views/public/tracking.blade.php` | Stepper stacks ≤640px (527); LACAK button + search input → 44px (149-161); "Salin" buttons → 44px (308, 508) |
| P1.3 | `resources/views/client/orders/show.blade.php` | `1fr 1fr` → `1fr` ≤640px (139); stepper stacks (69); WhatsApp button → 44px (221) |

### 4.3 P2 — catalogue & detail

| Step | File | Change |
|---|---|---|
| P2.1 | `resources/views/public/produk.blade.php` | Search input/button → 16px/44px (165-209); card CTA → 44px (363-378) |
| P2.2 | `resources/views/public/jasa.blade.php` | Same as P2.1 (166-209, card CTA) |
| P2.3 | `resources/views/home.blade.php` | Hero CTAs → 44px inside the existing ≤600px block (407-425) and `min-height:34px` (318) |
| P2.4 | `resources/views/public/portofolio.blade.php` | Same search/tab/button treatment (75-135) |
| P2.5 | `resources/views/public/produk/show.blade.php`, `public/jasa/show.blade.php`, `public/portofolio/show.blade.php`, `public/profil.blade.php` | Verify at 360px; touch only if a check fails (currently look OK; `profil` buttons 38px at 163 is the likely candidate) |

### 4.4 P3 — profile & auth

| Step | File | Change |
|---|---|---|
| P3.1 | `resources/views/profile/partials/card.blade.php` | Inputs → 16px/44px ≤640px (126-135, 234-243); `.btn` → 44px (211); `.info__action` links → 44px hit area (139-147) |
| P3.2 | `resources/views/auth/login.blade.php` | In the existing ≤520px block (256-259): `--fs-input` → 16px, `--field-height`/`--fs-button` → 44px; eye toggle + checkbox hit areas → 44px (162-173, 204) |
| P3.3 | `resources/views/auth/register.blade.php` | Same as P3.2 (221-224, toggle 159-171) |
| P3.4 | `resources/views/layouts/guest.blade.php` | CSS override scoped to `.guest-form-card` raising Breeze inputs to 16px and buttons to 44px ≤640px — **no edits to shared `components/text-input` / `components/primary-button`** (would risk admin pages) |

### 4.5 Files NOT touched

- No controllers, routes, models, migrations, seeders, config, `composer.json`, `package.json`, `tailwind.config.js`, `vite.config.js`.
- `resources/views/admin/*`, `worker/*`, `superadmin/*`, `layouts/admin|worker|superadmin` — excluded (they do not include `partials/navbar` or `partials/footer`; grep-verified).
- `resources/views/client/checkout.blade.php` — orphan, leave as is.
- **New files: none.**

---

## 5. Implementation order (report files changed after each step)

1. S1 navbar → biggest single win, verify home + login first.
2. S2 layout, S3 footer, S4 slider.
3. P1.1 → P1.2 → P1.3 (checkout, tracking, my-orders detail).
4. P2.1 → P2.4 (catalogue pages), then P2.5 verification pass.
5. P3.1 → P3.4 (profile, login, register, guest pages).
6. Phase 4 verification (§7).

Rules for every step: mobile-first CSS, existing breakpoints only, inputs ≥16px on phones, targets ≥44px, semantic HTML + labels kept, `aria-expanded`/`aria-controls` on the menu toggle, visible focus states, keyboard support intact, no logic/price/stock/validation/route changes.

---

## 6. Impact, desktop safety, risks

**Desktop:** every new rule lives inside a `max-width` media query or the ≤960px navbar block (hamburger `display:none` ≥960px) → desktop renders identically.

**Risks:**

1. **CSS specificity** — page styles are prefixed and beat the layout's `*` reset; additions stay inside the same prefixes. Any rule added in `layouts/public` must use a specific selector, never a bare element/class that admin pages could inherit (admin uses different layouts anyway).
2. **Shared Breeze components** (`x-text-input`, `x-primary-button`) — currently used only by guest auth views, but overridden via `layouts/guest` instead of editing them, to guarantee zero admin impact.
3. **Tailwind rebuild** — if any new utility class sneaks into a Blade file, run `npm run build`; otherwise the built assets are untouched. Prefer plain CSS.
4. **Carousel swipe** must not break the existing Alpine clone/loop logic (`homeSlider()`), the dot `aria-current`, or desktop arrows.
5. **Pre-existing issues not mine to fix:** `images.unsplash.com` fallback (`public/profil.blade.php:388`), Fontsource CDN links, orphan `client/checkout.blade.php`, English copy on guest auth pages, missing `SecurityHeaders.php`.

---

## 7. Verification (Phase 4)

1. `vendor/bin/pint --dirty --format agent` — only if PHP files changed (expected: none).
2. `php artisan test` — report **total / passed / failed**; on any failure, explain the cause before touching unrelated code. Suites covering these pages: `PhysicalProductCheckoutTest`, `ServiceAndTrackingTest`, `PublicCatalogPaginationTest`, `CatalogModalAndAuthTest`, `ProfileTest`, `SmartRedirectAuthTest`, `Auth/*`.
3. `composer check` — **not defined** in `composer.json` (only `composer test`); report N/A unless a substitute is requested.
4. CSP-equivalent check — grep proof that no `<link>`, `<script src>`, or new origin string was added to any view; `SecurityHeaders.php` still absent/untouched.
5. **Manual device checks (Chrome/Edge, F12 device toolbar, iPhone 12 Pro + Pixel 7 portrait, then landscape):** home → catalogue → product/service detail → checkout → tracking → my orders → order detail → profile → login → register → guest auth pages. Per page confirm: no horizontal scrolling, text readable without zooming, every menu/button opens and closes by tap, forms fill and submit, modals/toasts close, ≥44px targets, 16px inputs (no iOS-style zoom), no console/CSP/JS errors, desktop view unchanged. Complete one test checkout with a test account and test product; never use real customer data.
6. **Final summary** to the user: changed files grouped (shared vs page-specific), what changed per page, test results, any page still needing work with reasons, and the outstanding manual checks (ideally a real phone).

---

## 8. Open questions

None — Q1–Q7 all answered (§2). Implementation may begin.
