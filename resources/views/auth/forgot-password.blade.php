<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kesulitan Login? - Katalog TEFA SMKN 4 Tanjungpinang</title>
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
      --color-bg: #0b60cf;
      --color-card: #ffffff;
      --color-heading: #000000;
      --color-text: #111111;
      --color-muted: #666666;
      --color-button: #00a1ff;
      --color-button-text: #ffffff;
      --color-input-bg: #f1f1f1;
      --color-input-border: #000000;
      --color-icon: #444444;
      --color-badge: #00a1ff;
      --color-badge-icon: #0b2f63;

      /* Typography */
      --font-body: "Open Sauce Sans", "Hanken Grotesk", "Helvetica Neue", Arial, sans-serif;
      --fs-title: calc(34px * var(--scale));
      --fs-subtitle: calc(17px * var(--scale));
      --fs-input: calc(17px * var(--scale));
      --fs-button: calc(20px * var(--scale));

      /* Shape & spacing */
      --card-width: calc(526px * var(--scale));
      --radius-card: calc(48px * var(--scale));
      --radius-pill: 999px;
      --field-height: calc(44px * var(--scale));
      --card-pad-x: calc(53px * var(--scale));

      /* Badge */
      --badge-size: calc(56px * var(--scale));
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
    .reset-shell {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px 16px;
    }
    h1, p { margin: 0; }
    button, input { font: inherit; }

    /* ==========================================================
       3. CARD
       ========================================================== */
    .reset-card {
      position: relative;
      width: 100%;
      max-width: var(--card-width);
      padding: calc(32px * var(--scale)) var(--card-pad-x) calc(40px * var(--scale));
      background: var(--color-card);
      border-radius: var(--radius-card);
      animation: cardIn .7s ease-out both;
    }
    .reset-card__header { text-align: center; }
    .reset-card__title {
      font-size: var(--fs-title);
      font-weight: 700;
      line-height: 1.2;
      color: var(--color-heading);
    }
    .reset-card__subtitle {
      margin-top: calc(3px * var(--scale));
      font-size: var(--fs-subtitle);
      line-height: 1.35;
      color: var(--color-muted);
    }

    /* ==========================================================
       3b. BADGE (gembok di tepi atas kartu)
       Pusat badge tepat di tepi atas kartu putih:
       separuh di area biru, separuh menimpa kartu.
       ========================================================== */
    .reset-card__badge {
      position: absolute;
      top: 0;
      left: 50%;
      width: var(--badge-size);
      height: var(--badge-size);
      transform: translate(-50%, -50%);
      border-radius: 50%;
      background: var(--color-badge);
      display: grid;
      place-items: center;
      animation: badgeIn .5s cubic-bezier(.34, 1.56, .64, 1) .5s both;
    }
    .reset-card__badge svg {
      width: calc(24px * var(--scale));
      height: calc(24px * var(--scale));
      fill: var(--color-badge-icon);
    }

    /* ==========================================================
       4. FORM FIELDS
       ========================================================== */
    .reset-form { margin-top: calc(36px * var(--scale)); }
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

    /* Error messages (komponen Blade x-input-error) */
    .reset-form__error {
      margin: calc(6px * var(--scale)) 0 0 calc(10px * var(--scale));
      padding: 0;
      list-style: none;
      font-size: calc(15px * var(--scale));
      line-height: 1.35;
      color: #d92d20;
    }
    .reset-form__error li + li { margin-top: 2px; }

    /* Status (komponen Blade x-auth-session-status) */
    .reset-form__status {
      margin: calc(18px * var(--scale)) 0 0;
      font-size: calc(15px * var(--scale));
      line-height: 1.4;
      color: #067647;
      text-align: center;
    }

    /* ==========================================================
       5. SUBMIT
       ========================================================== */
    .reset-form__submit {
      display: block;
      width: 100%;
      height: var(--field-height);
      margin-top: calc(56px * var(--scale));
      border: 0;
      border-radius: var(--radius-pill);
      background: var(--color-button);
      color: var(--color-button-text);
      font-size: var(--fs-button);
      font-weight: 700;
      cursor: pointer;
      transition: transform .2s ease, filter .2s ease;
    }
    .reset-form__submit:hover { transform: translateY(-1px); filter: brightness(1.05); }
    .reset-form__submit:focus-visible { outline: 3px solid var(--color-bg); outline-offset: 3px; }

    /* ==========================================================
       6. ANIMATIONS
       ========================================================== */
    @keyframes cardIn {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes badgeIn {
      from { opacity: 0; transform: translate(-50%, -50%) scale(.8); }
      to   { opacity: 1; transform: translate(-50%, -50%) scale(1); }
    }

    /* ==========================================================
       7. SMALL SCREENS
       ========================================================== */
    @media (max-width: 520px) {
      :root { --card-pad-x: 24px; --radius-card: 32px; }
      .reset-card { padding-top: 28px; padding-bottom: 30px; }
    }

    /* Catatan: blok prefers-reduced-motion sengaja dihapus — animasi dipaksa selalu tampil. */
  </style>
</head>
<body>
  @include('partials.particle-network')

  @include('partials.navbar')

  <div class="reset-shell">
    <main class="reset-card">
      <span class="reset-card__badge" aria-hidden="true">
        <svg viewBox="0 0 24 24">
          <path d="M7 10V7a5 5 0 0 1 10 0v3h1.5a1.5 1.5 0 0 1 1.5 1.5v8a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 19.5v-8A1.5 1.5 0 0 1 5.5 10H7Zm2 0h6V7a3 3 0 0 0-6 0v3Zm3 4a1.5 1.5 0 0 0-.75 2.8V19h1.5v-2.2A1.5 1.5 0 0 0 12 14Z"/>
        </svg>
      </span>

      <header class="reset-card__header">
        <h1 class="reset-card__title">Kesulitan Login?</h1>
        <p class="reset-card__subtitle">Masukkan email yang telah terverifikasi untuk mereset password</p>
      </header>

      <x-auth-session-status class="reset-form__status" :status="session('status')" />

      <form class="reset-form" action="{{ route('password.email') }}" method="post">
        @csrf

        <div class="field__control">
          <svg class="field__icon" viewBox="0 0 24 20" fill="none" stroke="#444444" stroke-width="1.400" stroke-linejoin="round" aria-hidden="true">
            <rect x="1" y="3" width="22" height="15" rx="2"/>
            <polyline points="1.500,4 12,12 22.500,4"/>
          </svg>
          <input class="field__input" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" aria-label="Email" autofocus required>
        </div>
        <x-input-error :messages="$errors->get('email')" class="reset-form__error" />

        <button class="reset-form__submit" type="submit">Berikutnya</button>
      </form>
    </main>
  </div>
</body>
</html>
