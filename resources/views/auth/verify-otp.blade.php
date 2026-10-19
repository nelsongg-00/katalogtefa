<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Masukkan Kode OTP - Katalog TEFA SMKN 4 Tanjungpinang</title>
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
      --color-resend: #8c9cf5;
      --color-otp-line: #d4d4d4;
      --color-error: #d92d20;
      --color-badge: #00a1ff;
      --color-badge-icon: #0b2f63;

      /* Typography */
      --font-body: "Open Sauce Sans", "Hanken Grotesk", "Helvetica Neue", Arial, sans-serif;
      --fs-title: calc(34px * var(--scale));
      --fs-subtitle: calc(17px * var(--scale));
      --fs-input: calc(17px * var(--scale));
      --fs-button: calc(20px * var(--scale));
      --fs-otp: calc(30px * var(--scale));

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
    .otp-shell {
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
    .otp-card {
      position: relative;
      width: 100%;
      max-width: var(--card-width);
      padding: calc(32px * var(--scale)) var(--card-pad-x) calc(40px * var(--scale));
      background: var(--color-card);
      border-radius: var(--radius-card);
      animation: cardIn .7s ease-out both;
    }
    .otp-card__header { text-align: center; }
    .otp-card__title {
      font-size: var(--fs-title);
      font-weight: 700;
      line-height: 1.2;
      color: var(--color-heading);
    }
    .otp-card__subtitle {
      margin-top: calc(3px * var(--scale));
      font-size: var(--fs-subtitle);
      line-height: 1.35;
      color: var(--color-muted);
    }
    .otp-card__email {
      margin-top: calc(4px * var(--scale));
      font-size: var(--fs-subtitle);
      font-weight: 700;
      color: #444444;
      word-break: break-all;
    }

    /* ==========================================================
       3b. BADGE (gembok di tepi atas kartu)
       ========================================================== */
    .otp-card__badge {
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
    .otp-card__badge svg {
      width: calc(24px * var(--scale));
      height: calc(24px * var(--scale));
      fill: var(--color-badge-icon);
    }

    /* ==========================================================
       4. OTP INPUTS
       ========================================================== */
    .otp-form { margin-top: calc(36px * var(--scale)); }
    .otp-group {
      display: flex;
      justify-content: space-between;
      gap: calc(10px * var(--scale));
      margin: calc(40px * var(--scale)) calc(10px * var(--scale)) 0;
    }
    .otp-input {
      width: calc(46px * var(--scale));
      height: calc(56px * var(--scale));
      border: 0;
      border-bottom: 2px solid var(--color-otp-line);
      border-radius: 0;
      background: transparent;
      text-align: center;
      font-size: var(--fs-otp);
      font-weight: 700;
      color: var(--color-text);
      outline: none;
      transition: border-color .2s ease;
    }
    .otp-input:focus { border-bottom-color: var(--color-button); }
    .otp-input.filled { border-bottom-color: var(--color-button); }

    .otp-resend-row {
      margin-top: calc(56px * var(--scale));
      text-align: center;
      font-size: var(--fs-subtitle);
      color: var(--color-muted);
    }
    .otp-resend {
      background: none;
      border: 0;
      padding: 0 0 0 4px;
      font-size: var(--fs-subtitle);
      color: var(--color-resend);
      cursor: pointer;
    }
    .otp-resend:hover { text-decoration: underline; }
    .otp-resend:focus-visible { outline: 3px solid var(--color-button); border-radius: 4px; }

    /* Error messages (komponen Blade x-input-error) */
    .otp-form__error {
      margin: calc(10px * var(--scale)) 0 0;
      padding: 0;
      list-style: none;
      font-size: calc(15px * var(--scale));
      line-height: 1.35;
      color: var(--color-error);
      text-align: center;
    }
    .otp-form__error li + li { margin-top: 2px; }

    /* Status (komponen Blade x-auth-session-status) */
    .otp-form__status {
      margin: calc(14px * var(--scale)) 0 0;
      font-size: calc(15px * var(--scale));
      line-height: 1.4;
      color: #067647;
      text-align: center;
    }

    /* ==========================================================
       5. SUBMIT
       ========================================================== */
    .otp-form__submit {
      display: block;
      width: 100%;
      height: var(--field-height);
      margin-top: calc(28px * var(--scale));
      border: 0;
      border-radius: var(--radius-pill);
      background: var(--color-button);
      color: var(--color-button-text);
      font-size: var(--fs-button);
      font-weight: 700;
      cursor: pointer;
      transition: transform .2s ease, filter .2s ease;
    }
    .otp-form__submit:hover { transform: translateY(-1px); filter: brightness(1.05); }
    .otp-form__submit:focus-visible { outline: 3px solid var(--color-bg); outline-offset: 3px; }

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
      :root { --card-pad-x: 24px; --radius-card: 32px; --fs-otp: 22px; }
      .otp-card { padding-top: 28px; padding-bottom: 30px; }
      .otp-input { width: calc(38px * var(--scale)); }
    }

    /* Catatan: blok prefers-reduced-motion sengaja dihapus — animasi dipaksa selalu tampil. */
  </style>
</head>
<body>
  @include('partials.particle-network')

  @include('partials.navbar')

  <div class="otp-shell">
    <main class="otp-card">
      <span class="otp-card__badge" aria-hidden="true">
        <svg viewBox="0 0 24 24">
          <path d="M7 10V7a5 5 0 0 1 10 0v3h1.5a1.5 1.5 0 0 1 1.5 1.5v8a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 19.5v-8A1.5 1.5 0 0 1 5.5 10H7Zm2 0h6V7a3 3 0 0 0-6 0v3Zm3 4a1.5 1.5 0 0 0-.75 2.8V19h1.5v-2.2A1.5 1.5 0 0 0 12 14Z"/>
        </svg>
      </span>

      <header class="otp-card__header">
        <h1 class="otp-card__title">Masukkan Kode OTP</h1>
        <p class="otp-card__subtitle">Kode OTP telah dikirim via e-mail ke</p>
        <p class="otp-card__email">{{ session('email', 'contoh@gmail.com') }}</p>
      </header>

      <x-auth-session-status class="otp-form__status" :status="session('status')" />

      <form class="otp-form" id="otpForm" action="{{ route('password.verify.otp') }}" method="post">
        @csrf
        <input type="hidden" name="email" value="{{ session('email') }}">
        <input type="hidden" name="otp" id="otpValue">

        <div class="otp-group" id="otpGroup">
          <input class="otp-input" type="text" inputmode="numeric" maxlength="1" autocomplete="one-time-code" aria-label="Digit 1" autofocus>
          <input class="otp-input" type="text" inputmode="numeric" maxlength="1" aria-label="Digit 2">
          <input class="otp-input" type="text" inputmode="numeric" maxlength="1" aria-label="Digit 3">
          <input class="otp-input" type="text" inputmode="numeric" maxlength="1" aria-label="Digit 4">
          <input class="otp-input" type="text" inputmode="numeric" maxlength="1" aria-label="Digit 5">
          <input class="otp-input" type="text" inputmode="numeric" maxlength="1" aria-label="Digit 6">
        </div>

        <x-input-error :messages="$errors->get('otp')" class="otp-form__error" />

        <p class="otp-resend-row">
          Tidak menerima Kode OTP?
          <button class="otp-resend" type="submit" formaction="{{ route('password.resend.otp') }}" formnovalidate>Kirim Ulang</button>
        </p>

        <button class="otp-form__submit" type="submit">Berikutnya</button>
      </form>
    </main>
  </div>

  <script>
    (function () {
      var form = document.getElementById('otpForm');
      var group = document.getElementById('otpGroup');
      var hidden = document.getElementById('otpValue');
      var boxes = Array.prototype.slice.call(group.querySelectorAll('.otp-input'));

      function updateValue() {
        hidden.value = boxes.map(function (b) { return b.value; }).join('');
        boxes.forEach(function (b) { b.classList.toggle('filled', b.value !== ''); });
      }

      boxes.forEach(function (box, i) {
        box.addEventListener('input', function () {
          box.value = box.value.replace(/\D/g, '').slice(0, 1);
          if (box.value && i < boxes.length - 1) boxes[i + 1].focus();
          updateValue();
        });

        box.addEventListener('keydown', function (e) {
          if (e.key === 'Backspace' && !box.value && i > 0) {
            boxes[i - 1].focus();
            boxes[i - 1].value = '';
            updateValue();
            e.preventDefault();
          }
          if (e.key === 'ArrowLeft' && i > 0) boxes[i - 1].focus();
          if (e.key === 'ArrowRight' && i < boxes.length - 1) boxes[i + 1].focus();
        });

        box.addEventListener('focus', function () { box.select(); });
      });

      // Tempel kode 6 digit sekaligus
      group.addEventListener('paste', function (e) {
        var teks = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, boxes.length);
        if (!teks) return;
        e.preventDefault();
        teks.split('').forEach(function (ch, i) { boxes[i].value = ch; });
        boxes[Math.min(teks.length, boxes.length - 1)].focus();
        updateValue();
      });

      // Pastikan kode lengkap sebelum submit (tombol Kirim Ulang tidak terpengaruh)
      form.addEventListener('submit', function (e) {
        if (e.submitter && e.submitter.classList.contains('otp-resend')) return;
        updateValue();
        if (hidden.value.length !== boxes.length) {
          e.preventDefault();
          boxes.find(function (b) { return !b.value; }).focus();
        }
      });
    })();
  </script>
</body>
</html>
