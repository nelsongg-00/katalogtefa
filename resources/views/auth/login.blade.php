<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Masuk ke Akun - Katalog TEFA SMKN 4 Tanjungpinang</title>
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/400.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/500.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/600.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/700.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/800.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/900.css" rel="stylesheet">

  <style>
    /* ==========================================================
       1. DESIGN TOKENS
       All sizes = original mockup size x --scale.
       0.79 = a further 11% smaller.
       ========================================================== */
    :root {
      --scale: 0.79;

      /* Colors (from design) */
      --color-bg: #0048ab;
      --color-card: #ffffff;
      --color-heading: #000000;
      --color-text: #111111;
      --color-muted: #666666;
      --color-link: #5470ff;
      --color-button: #5470ff;
      --color-button-text: #ffffff;
      --color-input-bg: #f1f1f1;
      --color-input-border: #000000;
      --color-icon: #444444;
      --color-error: #d92d20;

      /* Typography */
      --font-body: "Open Sauce Sans", "Hanken Grotesk", "Helvetica Neue", Arial, sans-serif;
      --fs-title: calc(34px * var(--scale));
      --fs-subtitle: calc(17px * var(--scale));
      --fs-label: calc(18px * var(--scale));
      --fs-input: calc(17px * var(--scale));
      --fs-button: calc(20px * var(--scale));
      --fs-footer: calc(17px * var(--scale));

      /* Shape & spacing */
      --card-width: calc(526px * var(--scale));
      --radius-card: calc(48px * var(--scale));
      --radius-pill: 999px;
      --field-height: calc(44px * var(--scale));
      --field-gap: calc(25px * var(--scale));
      --card-pad-x: calc(53px * var(--scale));
    }

    /* ==========================================================
       2. RESET & BASE
       ========================================================== */
    *, *::before, *::after { box-sizing: border-box; }
    body {
      margin: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      background: var(--color-bg);
      font-family: var(--font-body);
      font-size: var(--fs-input);
      color: var(--color-text);
    }
    /* Area kartu mengisi sisa tinggi di bawah navbar; kartu tetap di tengah. */
    .login-shell {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px 16px;
    }
    h1, p { margin: 0; }
    a { color: var(--color-link); text-decoration: none; }
    button, input { font: inherit; }

    /* ==========================================================
       3. CARD
       ========================================================== */
    .login-card {
      width: 100%;
      max-width: var(--card-width);
      padding: calc(32px * var(--scale)) var(--card-pad-x) calc(20px * var(--scale));
      background: var(--color-card);
      border-radius: var(--radius-card);
    }
    .login-card__header { text-align: center; }
    .login-card__title {
      font-size: var(--fs-title);
      font-weight: 700;
      line-height: 1.2;
      color: var(--color-heading);
    }
    .login-card__subtitle {
      margin-top: calc(3px * var(--scale));
      font-size: var(--fs-subtitle);
      line-height: 1.35;
      color: var(--color-muted);
    }

    /* ==========================================================
       4. FORM FIELDS
       ========================================================== */
    .login-form { margin-top: calc(36px * var(--scale)); }
    .field { margin-bottom: var(--field-gap); }
    .field--last { margin-bottom: 0; }
    .field__label {
      display: block;
      margin: 0 0 calc(2px * var(--scale)) calc(10px * var(--scale));
      font-size: var(--fs-label);
      line-height: calc(22px * var(--scale));
      color: var(--color-text);
    }
    .field__control { position: relative; }
    .field__input {
      width: 100%;
      height: var(--field-height);
      padding: 0 calc(46px * var(--scale));
      border: 1px solid var(--color-input-border);
      border-radius: var(--radius-pill);
      background: var(--color-input-bg);
      font-size: var(--fs-input);
      color: var(--color-text);
    }
    .field__input:focus-visible { outline: 3px solid var(--color-button); outline-offset: 2px; }
    .field__icon {
      position: absolute;
      top: 50%;
      left: calc(16px * var(--scale));
      width: calc(24px * var(--scale));
      height: calc(20px * var(--scale));
      transform: translateY(-50%);
      pointer-events: none;
    }
    .field__toggle {
      position: absolute;
      top: 50%;
      right: calc(14px * var(--scale));
      transform: translateY(-50%);
      display: grid;
      place-items: center;
      padding: 2px;
      border: 0;
      background: transparent;
      cursor: pointer;
    }
    .field__toggle:focus-visible { outline: 3px solid var(--color-button); border-radius: 4px; }

    .login-form__forgot { display: block; margin-top: calc(8px * var(--scale)); text-align: right; font-size: var(--fs-footer); }

    /* Error & status (komponen Blade x-input-error / x-auth-session-status;
       class utility Tailwind di komponen tidak dimuat di halaman mandiri ini). */
    .login-form__error {
      margin: calc(6px * var(--scale)) 0 0 calc(10px * var(--scale));
      padding: 0;
      list-style: none;
      font-size: calc(15px * var(--scale));
      line-height: 1.35;
      color: var(--color-error);
    }
    .login-form__error li + li { margin-top: 2px; }
    .login-form__status {
      margin: 0 0 calc(18px * var(--scale));
      font-size: calc(15px * var(--scale));
      line-height: 1.4;
      color: #067647;
    }

    /* ==========================================================
       5. REMEMBER ME
       ========================================================== */
    .remember { display: flex; align-items: center; gap: calc(10px * var(--scale)); margin-top: calc(28px * var(--scale)); color: var(--color-muted); font-size: var(--fs-footer); cursor: pointer; }
    .remember__input {
      appearance: none;
      -webkit-appearance: none;
      flex: none;
      width: calc(22px * var(--scale));
      height: calc(22px * var(--scale));
      margin: 0;
      border: 2px solid #222222;
      border-radius: 50%;
      background: var(--color-card);
      cursor: pointer;
    }
    .remember__input:checked { background: radial-gradient(circle, #222222 0 45%, transparent 50%); }
    .remember__input:focus-visible { outline: 3px solid var(--color-button); outline-offset: 2px; }

    /* ==========================================================
       6. SUBMIT & FOOTER LINK
       ========================================================== */
    .login-form__submit {
      display: block;
      width: 100%;
      height: var(--field-height);
      margin-top: calc(35px * var(--scale));
      border: 0;
      border-radius: var(--radius-pill);
      background: var(--color-button);
      color: var(--color-button-text);
      font-size: var(--fs-button);
      font-weight: 700;
      cursor: pointer;
    }
    .login-form__submit:focus-visible { outline: 3px solid var(--color-bg); outline-offset: 3px; }

    .login-card__footer {
      margin-top: calc(24px * var(--scale));
      text-align: center;
      font-size: var(--fs-footer);
      color: var(--color-text);
    }
    .login-card__footer a { margin-left: 4px; }

    /* ==========================================================
       7. SMALL SCREENS
       ========================================================== */
    @media (max-width: 520px) {
      :root { --card-pad-x: 24px; --radius-card: 32px; }
      .login-card { padding-top: 28px; padding-bottom: 22px; }
    }
  </style>
</head>
<body>
  @include('partials.navbar')

  <div class="login-shell">
    <main class="login-card">
    <header class="login-card__header">
      <h1 class="login-card__title">Masuk ke Akun</h1>
      <p class="login-card__subtitle">Masukkan email dan kata sandi Anda untuk melanjutkan</p>
    </header>

    <x-auth-session-status class="login-form__status" :status="session('status')" />

    <form class="login-form" action="{{ route('login') }}" method="post">
      @csrf

      <div class="field">
        <label class="field__label" for="email">Email</label>
        <div class="field__control">
          <svg class="field__icon" viewBox="0 0 24 20" fill="none" stroke="#444444" stroke-width="1.400" stroke-linejoin="round" aria-hidden="true">
            <rect x="1" y="3" width="22" height="15" rx="2"/>
            <polyline points="1.500,4 12,12 22.500,4"/>
          </svg>
          <input class="field__input" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" autofocus required>
        </div>
        <x-input-error :messages="$errors->get('email')" class="login-form__error" />
      </div>

      <div class="field field--last">
        <label class="field__label" for="password">Password</label>
        <div class="field__control">
          <svg class="field__icon" viewBox="0 0 24 20" fill="none" aria-hidden="true">
            <path d="M8 8.500V6a4 4 0 0 1 8 0v2.500" stroke="#444444" stroke-width="2"/>
            <rect x="5" y="8.500" width="14" height="10.500" rx="2" fill="#444444"/>
            <circle cx="12" cy="13" r="1.400" fill="#ffffff"/>
            <rect x="11.300" y="13.700" width="1.400" height="2.600" fill="#ffffff"/>
          </svg>
          <input class="field__input" id="password" name="password" type="password" autocomplete="current-password" required>
          <button class="field__toggle" type="button" data-toggle-password aria-label="Tampilkan kata sandi" aria-pressed="false">
            <svg width="28" height="18" viewBox="0 0 28 18" fill="none" stroke="#555555" stroke-width="1.3" aria-hidden="true">
              <path d="M1 9c3.5-5.5 8-7.5 13-7.5S23.500 3.500 27 9c-3.500 5.500-8 7.500-13 7.500S4.500 14.500 1 9Z"/>
              <circle cx="14" cy="9" r="3.500"/>
            </svg>
          </button>
        </div>
        @if (Route::has('password.request'))
          <a class="login-form__forgot" href="{{ route('password.request') }}">Lupa kata sandi?</a>
        @endif
        <x-input-error :messages="$errors->get('password')" class="login-form__error" />
      </div>

      <label class="remember">
        <input class="remember__input" type="checkbox" name="remember">
        <span>Ingatkan saya di perangkat ini</span>
      </label>

      <button class="login-form__submit" type="submit">Masuk Sekarang</button>
    </form>

    @if (Route::has('register'))
      <p class="login-card__footer">Belum memiliki akun? <a href="{{ route('register') }}">Daftar Akun</a></p>
    @endif
    </main>
  </div>

  <script>
    (function () {
      var btn = document.querySelector('[data-toggle-password]');
      var input = document.getElementById('password');
      if (!btn || !input) return;
      btn.addEventListener('click', function () {
        var show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.setAttribute('aria-pressed', String(show));
        btn.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
      });
    })();
  </script>
</body>
</html>
