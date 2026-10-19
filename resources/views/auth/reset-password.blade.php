<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Atur Password - Katalog TEFA SMKN 4 Tanjungpinang</title>
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
      --color-error: #d92d20;
      --color-ok: #067647;
      --color-badge: #00a1ff;
      --color-badge-icon: #0b2f63;

      /* Typography */
      --font-body: "Open Sauce Sans", "Hanken Grotesk", "Helvetica Neue", Arial, sans-serif;
      --fs-title: calc(34px * var(--scale));
      --fs-subtitle: calc(17px * var(--scale));
      --fs-input: calc(17px * var(--scale));
      --fs-rule: calc(17px * var(--scale));
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
    .password-shell {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px 16px;
    }
    h1, p, ul { margin: 0; }
    button, input { font: inherit; }

    /* ==========================================================
       3. CARD
       ========================================================== */
    .password-card {
      position: relative;
      width: 100%;
      max-width: var(--card-width);
      padding: calc(32px * var(--scale)) var(--card-pad-x) calc(36px * var(--scale));
      background: var(--color-card);
      border-radius: var(--radius-card);
      animation: cardIn .7s ease-out both;
    }
    .password-card__header { text-align: center; }
    .password-card__title {
      font-size: var(--fs-title);
      font-weight: 700;
      line-height: 1.2;
      color: var(--color-heading);
    }
    .password-card__subtitle {
      margin-top: calc(3px * var(--scale));
      font-size: var(--fs-subtitle);
      line-height: 1.35;
      color: var(--color-muted);
    }

    /* ==========================================================
       3b. BADGE (gembok di tepi atas kartu)
       ========================================================== */
    .password-card__badge {
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
    .password-card__badge svg {
      width: calc(24px * var(--scale));
      height: calc(24px * var(--scale));
      fill: var(--color-badge-icon);
    }

    /* ==========================================================
       4. FORM FIELDS
       ========================================================== */
    .password-form { margin-top: calc(56px * var(--scale)); }
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

    /* Daftar syarat password */
    .password-rules {
      margin-top: calc(18px * var(--scale));
      padding: 0 calc(6px * var(--scale));
      list-style: none;
      font-size: var(--fs-rule);
      line-height: 1.6;
      color: var(--color-muted);
      transition: color .2s ease;
    }
    .password-rules li { transition: color .2s ease; }
    .password-rules li.ok { color: var(--color-ok); }

    /* Error messages (komponen Blade x-input-error) */
    .password-form__error {
      margin: calc(6px * var(--scale)) 0 0 calc(10px * var(--scale));
      padding: 0;
      list-style: none;
      font-size: calc(15px * var(--scale));
      line-height: 1.35;
      color: var(--color-error);
    }
    .password-form__error li + li { margin-top: 2px; }

    /* Status (komponen Blade x-auth-session-status) */
    .password-form__status {
      margin: calc(18px * var(--scale)) 0 0;
      font-size: calc(15px * var(--scale));
      line-height: 1.4;
      color: var(--color-ok);
      text-align: center;
    }

    /* ==========================================================
       5. SUBMIT
       ========================================================== */
    .password-form__submit {
      display: block;
      width: 100%;
      height: var(--field-height);
      margin-top: calc(32px * var(--scale));
      border: 0;
      border-radius: var(--radius-pill);
      background: var(--color-button);
      color: var(--color-button-text);
      font-size: var(--fs-button);
      font-weight: 700;
      cursor: pointer;
      transition: transform .2s ease, filter .2s ease;
    }
    .password-form__submit:hover { transform: translateY(-1px); filter: brightness(1.05); }
    .password-form__submit:focus-visible { outline: 3px solid var(--color-bg); outline-offset: 3px; }

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
      .password-card { padding-top: 28px; padding-bottom: 30px; }
    }

    /* Catatan: blok prefers-reduced-motion sengaja dihapus — animasi dipaksa selalu tampil. */
  </style>
</head>
<body>
  @include('partials.particle-network')

  @include('partials.navbar')

  <div class="password-shell">
    <main class="password-card">
      <span class="password-card__badge" aria-hidden="true">
        <svg viewBox="0 0 24 24">
          <path d="M7 10V7a5 5 0 0 1 10 0v3h1.5a1.5 1.5 0 0 1 1.5 1.5v8a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 19.5v-8A1.5 1.5 0 0 1 5.5 10H7Zm2 0h6V7a3 3 0 0 0-6 0v3Zm3 4a1.5 1.5 0 0 0-.75 2.8V19h1.5v-2.2A1.5 1.5 0 0 0 12 14Z"/>
        </svg>
      </span>

      <header class="password-card__header">
        <h1 class="password-card__title">Atur Password</h1>
        <p class="password-card__subtitle">Buat Password Baru</p>
      </header>

      <x-auth-session-status class="password-form__status" :status="session('status')" />

      <form class="password-form" id="passwordForm" action="{{ route('password.store') }}" method="post">
        @csrf
        {{-- Token ada di URL (jalur OTP maupun link email), bukan di session. --}}
        <input type="hidden" name="token" value="{{ request()->route('token') }}">

        @if (session('email'))
            {{-- Jalur OTP: email sudah pasti benar, sembunyikan sesuai desain. --}}
            <input type="hidden" name="email" value="{{ session('email') }}">
        @else
            {{-- Jalur link email (perangkat lain): email harus bisa diisi manual. --}}
            <input class="field__input" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" aria-label="Email" required>
            <x-input-error :messages="$errors->get('email')" class="password-form__error" />
        @endif

        <div class="field__control">
          <svg class="field__icon" viewBox="0 0 24 20" fill="none" aria-hidden="true">
            <path d="M8 8.500V6a4 4 0 0 1 8 0v2.500" stroke="#444444" stroke-width="2"/>
            <rect x="5" y="8.500" width="14" height="10.500" rx="2" fill="#444444"/>
            <circle cx="12" cy="13" r="1.400" fill="#ffffff"/>
            <rect x="11.300" y="13.700" width="1.400" height="2.600" fill="#ffffff"/>
          </svg>
          <input class="field__input" id="password" name="password" type="password" autocomplete="new-password" aria-label="Password baru" autofocus required>
          <button class="field__toggle" type="button" data-toggle-password aria-label="Tampilkan kata sandi" aria-pressed="false">
            <svg width="28" height="18" viewBox="0 0 28 18" fill="none" stroke="#555555" stroke-width="1.3" aria-hidden="true">
              <path d="M1 9c3.5-5.5 8-7.5 13-7.5S23.500 3.500 27 9c-3.500 5.500-8 7.500-13 7.500S4.500 14.500 1 9Z"/>
              <circle cx="14" cy="9" r="3.500"/>
            </svg>
          </button>
        </div>
        <x-input-error :messages="$errors->get('password')" class="password-form__error" />

        {{-- Dipakai aturan 'confirmed' di server; nilai disalin dari #password saat submit. --}}
        <input type="hidden" name="password_confirmation" id="passwordConfirmation" value="">

        <ul class="password-rules" id="passwordRules">
          <li data-rule="lower">Min. satu karakter huruf kecil</li>
          <li data-rule="upper">Min. satu karakter huruf besar</li>
          <li data-rule="length">8-16 karakter</li>
          <li data-rule="chars">Hanya huruf, angka, dan tanda baca umum yang dapat digunakan</li>
        </ul>

        <button class="password-form__submit" type="submit">Berikutnya</button>
      </form>
    </main>
  </div>

  <script>
    (function () {
      var form = document.getElementById('passwordForm');
      var input = document.getElementById('password');
      var btn = document.querySelector('[data-toggle-password]');
      var rules = document.querySelectorAll('#passwordRules li');

      // Tampilkan / sembunyikan password
      btn.addEventListener('click', function () {
        var show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.setAttribute('aria-pressed', String(show));
        btn.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
      });

      // Tandai syarat yang sudah terpenuhi
      var aturan = {
        lower: function (v) { return /[a-z]/.test(v); },
        upper: function (v) { return /[A-Z]/.test(v); },
        length: function (v) { return v.length >= 8 && v.length <= 16; },
        chars: function (v) { return v.length > 0 && /^[A-Za-z0-9!-\/:-@\[-`{-~]+$/.test(v); }
      };

      function periksa() {
        var v = input.value;
        rules.forEach(function (li) {
          var lulus = aturan[li.dataset.rule](v);
          li.classList.toggle('ok', lulus);
        });
        return Object.keys(aturan).every(function (k) { return aturan[k](v); });
      }

      input.addEventListener('input', periksa);

      // Cegah submit jika syarat belum terpenuhi
      form.addEventListener('submit', function (e) {
        if (!periksa()) {
          e.preventDefault();
          input.focus();
        }
      });
    })();
  </script>

  <script>
    // Salin password ke input konfirmasi tersembunyi (aturan 'confirmed' di server).
    document.getElementById('passwordForm').addEventListener('submit', function () {
      document.getElementById('passwordConfirmation').value = document.getElementById('password').value;
    });
  </script>
</body>
</html>
