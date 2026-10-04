<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Masuk ke Akun - Katalog TEFA SMKN 4 Tanjungpinang</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600&family=Inter:wght@700&display=swap" rel="stylesheet">

  <style>
    /* ==========================================================
       1. DESIGN TOKENS
       ========================================================== */
    :root {
      --color-bg: #0048ab;
      --color-card: #ffffff;
      --color-heading: #000000;
      --color-text: #111111;
      --color-muted: #666666;
      --color-link: #5468ff;
      --color-button: #00a3ff;
      --color-button-text: #ffffff;
      --color-input-bg: #f1f1f1;
      --color-input-border: #000000;
      --color-icon: #555555;

      --font-heading: "Inter", "Helvetica Neue", Arial, sans-serif;
      --font-body: "Hanken Grotesk", "Helvetica Neue", Arial, sans-serif;

      --radius-card: 48px;
      --radius-pill: 999px;
      --card-width: 418px;
      --field-height: 44px;
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
      font-size: 17px;
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
      padding: 40px 53px 38px;
      background: var(--color-card);
      border-radius: var(--radius-card);
    }
    .login-card__header { text-align: center; }
    .login-card__title {
      font-family: var(--font-heading);
      font-size: 34px;
      font-weight: 700;
      line-height: 1.2;
      color: var(--color-heading);
    }
    .login-card__subtitle {
      margin: 6px auto 0;
      max-width: 22em;
      font-size: 18px;
      line-height: 1.35;
      color: var(--color-muted);
    }

    /* ==========================================================
       4. FORM FIELDS
       ========================================================== */
    .login-form { margin-top: 32px; }
    .field { margin-bottom: 36px; }
    .field--last { margin-bottom: 0; }
    .field__label {
      display: block;
      margin: 0 0 4px 10px;
      font-size: 18px;
      color: var(--color-text);
    }
    .field__control { position: relative; }
    .field__input {
      width: 100%;
      height: var(--field-height);
      padding: 0 46px;
      border: 1px solid var(--color-input-border);
      border-radius: var(--radius-pill);
      background: var(--color-input-bg);
      font-size: 17px;
      color: var(--color-text);
    }
    .field__input:focus-visible { outline: 3px solid var(--color-button); outline-offset: 2px; }
    .field__icon {
      position: absolute;
      top: 50%;
      left: 16px;
      transform: translateY(-50%);
      display: block;
      pointer-events: none;
    }
    .field__toggle {
      position: absolute;
      top: 50%;
      right: 14px;
      transform: translateY(-50%);
      display: grid;
      place-items: center;
      padding: 2px;
      border: 0;
      background: transparent;
      cursor: pointer;
    }
    .field__toggle:focus-visible { outline: 3px solid var(--color-button); border-radius: 4px; }

    .login-form__forgot { display: block; margin-top: 8px; text-align: right; font-size: 17px; }

    /* Error & status (komponen Blade x-input-error / x-auth-session-status;
       class utility Tailwind di komponen tidak dimuat di halaman mandiri ini). */
    .login-form__error {
      margin: 6px 0 0 10px;
      padding: 0;
      list-style: none;
      font-size: 15px;
      line-height: 1.35;
      color: #d92d20;
    }
    .login-form__error li + li { margin-top: 2px; }
    .login-form__status {
      margin: 0 0 18px;
      font-size: 15px;
      line-height: 1.4;
      color: #067647;
    }

    /* ==========================================================
       5. REMEMBER ME
       ========================================================== */
    .remember { display: flex; align-items: center; gap: 10px; margin-top: 28px; color: var(--color-muted); cursor: pointer; }
    .remember__input {
      appearance: none;
      -webkit-appearance: none;
      flex: none;
      width: 22px;
      height: 22px;
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
      margin-top: 34px;
      border: 0;
      border-radius: var(--radius-pill);
      background: var(--color-button);
      color: var(--color-button-text);
      font-size: 20px;
      cursor: pointer;
    }
    .login-form__submit:focus-visible { outline: 3px solid var(--color-bg); outline-offset: 3px; }

    .login-card__footer { margin-top: 24px; text-align: center; color: var(--color-text); }
    .login-card__footer a { margin-left: 4px; }

    /* ==========================================================
       7. SMALL SCREENS
       ========================================================== */
    @media (max-width: 560px) {
      :root { --radius-card: 32px; }
      .login-card { padding: 32px 24px 30px; }
      .login-card__title { font-size: 28px; }
      .login-card__subtitle { font-size: 16px; }
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
          <svg class="field__icon" width="24" height="18" viewBox="0 0 24 18" fill="none" stroke="#444444" stroke-width="1.4" stroke-linejoin="round" aria-hidden="true">
            <rect x="1" y="1.5" width="22" height="15" rx="2"/>
            <polyline points="1.5,2.5 12,10.5 22.5,2.5"/>
          </svg>
          <input class="field__input" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" autofocus required>
        </div>
        <x-input-error :messages="$errors->get('email')" class="login-form__error" />
      </div>

      <div class="field field--last">
        <label class="field__label" for="password">Password</label>
        <div class="field__control">
          <svg class="field__icon" width="18" height="20" viewBox="0 0 18 20" fill="none" aria-hidden="true">
            <path d="M5 8V5.5a4 4 0 0 1 8 0V8" stroke="#555555" stroke-width="2"/>
            <rect x="1.5" y="8" width="15" height="11" rx="2" fill="#555555"/>
            <circle cx="9" cy="12.8" r="1.4" fill="#ffffff"/>
            <rect x="8.3" y="13.5" width="1.4" height="2.6" fill="#ffffff"/>
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
