@extends('layouts.public')

@section('title', 'Katalog TEFA SMKN 4 Tanjungpinang')

@section('content')
    {{-- CSS halaman ini di-scope (Tailwind v3 tanpa CSS layer — spesifisitas yang menentukan). --}}
    <style>
        /* ================= SCROLL PROGRESS BAR ================= */
        .scroll-progress {
            position: fixed;
            inset: 0 0 auto 0;
            height: 3px;
            z-index: 100;
            background: linear-gradient(90deg, #f2b630, #ffd873, #0b60cf);
            transform-origin: 0 50%;
            transform: scaleX(0);
            pointer-events: none;
        }

        /* ================= REVEAL VARIAN =================
           Section (data-scroll-reveal) hanya jadi pemicu; anaknya (data-reveal) yang beranimasi.
           Delay per elemen lewat style="--d:120ms". Hanya aktif jika JS berjalan (gerbang .js). */
        .js [data-reveal] {
            opacity: 0;
            transform: translateY(44px);
            transition:
                opacity 0.8s ease,
                transform 0.95s cubic-bezier(0.16, 1, 0.3, 1),
                filter 0.8s ease,
                clip-path 1.1s cubic-bezier(0.77, 0, 0.175, 1);
            transition-delay: var(--d, 0ms);
        }

        .js [data-reveal="left"]  { transform: translateX(-70px); }
        .js [data-reveal="right"] { transform: translateX(70px); }
        .js [data-reveal="zoom"]  { transform: scale(0.88); }
        .js [data-reveal="blur"]  { transform: translateY(24px) scale(0.98); filter: blur(12px); }

        /* Gambar: tirai terbuka dari atas ke bawah. */
        .js [data-reveal="mask"] {
            opacity: 1;
            transform: none;
            clip-path: inset(0 0 100% 0);
        }

        .js .is-visible [data-reveal] {
            opacity: 1;
            transform: none;
            filter: none;
        }

        .js .is-visible [data-reveal="mask"] {
            clip-path: inset(0 0 0 0);
        }

        /* Gambar di dalam wrapper bergerak pelan (parallax) — diberi sedikit zoom agar tidak ada tepi kosong. */
        [data-parallax-img] {
            transform: translate3d(0, 0, 0) scale(1.18);
            will-change: transform;
        }

        /* Judul dipecah per kata (script): kata naik dari balik topeng + blur hilang. */
        .js .split-word {
            display: inline-block;
            overflow: hidden;
            vertical-align: top;
            padding-bottom: 0.12em;
            margin-bottom: -0.12em;
        }

        .js .split-word > span {
            display: inline-block;
            opacity: 0;
            transform: translateY(110%) rotate(5deg);
            filter: blur(6px);
            transform-origin: 0 100%;
            transition:
                transform 0.9s cubic-bezier(0.16, 1, 0.3, 1),
                opacity 0.7s ease,
                filter 0.7s ease;
            transition-delay: calc(var(--w, 0) * 70ms + var(--d, 0ms));
        }

        .js .is-visible .split-word > span {
            opacity: 1;
            transform: none;
            filter: none;
        }

        /* Kata "Mengapa" / aksen biru: kilau bergerak setelah muncul. */
        .shine-text {
            background: linear-gradient(100deg, currentColor 30%, #7fb2ff 50%, currentColor 70%);
            background-size: 220% 100%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shine 4.5s ease-in-out infinite;
        }

        @keyframes shine {
            0%   { background-position: 120% 0; }
            60%, 100% { background-position: -120% 0; }
        }

        /* Stagger kartu: muncul dari bawah dengan skala + blur, lalu "settle" agar bisa di-tilt. */
        .js [data-stagger-item] {
            opacity: 0;
            transform: translateY(48px) scale(0.86);
            filter: blur(8px);
            transition:
                opacity 0.7s ease,
                transform 0.9s cubic-bezier(0.16, 1, 0.3, 1),
                filter 0.7s ease;
            transition-delay: calc(var(--stagger-index, 0) * 110ms);
        }

        .js [data-stagger-item].is-visible {
            opacity: 1;
            transform: none;
            filter: none;
        }

        .js [data-stagger-item].is-settled {
            transition: transform 0.25s ease-out, box-shadow 0.3s ease;
            transition-delay: 0s;
            transform: perspective(800px) rotateX(var(--rx, 0deg)) rotateY(var(--ry, 0deg)) translateZ(0);
        }

        .js [data-stagger-item].is-settled:hover {
            box-shadow: 0 18px 40px -14px rgba(11, 96, 207, 0.35);
        }

        /* Kartu nilai (ilustrasi SVG inline). */
        .value-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 16px 22px;
            border-radius: 18px;
            background: #ffffff;
            text-align: center;
            box-shadow: 0 6px 20px -10px rgba(11, 96, 207, 0.25);
        }

        .value-card svg {
            width: 96px;
            height: 96px;
            margin-bottom: 12px;
        }

        .value-card h3 {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 800;
        }

        .value-card .value-tag {
            margin: 2px 0 8px;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .value-card p {
            margin: 0;
            font-size: 0.85rem;
            line-height: 1.45;
            color: #5b6470;
        }

        /* ================= HERO =================
           Token hero di-scope ke .tefa-hero (bukan :root) supaya tidak bocor ke halaman lain.
           Gradient 5 stop sama persis dengan desain: #6c9ddd -> #0b60cf. */
        .tefa-hero {
            --color-hero-top: #6c9ddd;
            --color-hero-bottom: #0b60cf;
            --hero-wave-height: 58%;

            position: relative;
            min-height: 600px;
            overflow: hidden;
            isolation: isolate;
            background: linear-gradient(
                to bottom,
                var(--color-hero-top) 0%,
                #5c91da 18%,
                #3c79d5 42%,
                #1c6bd2 68%,
                var(--color-hero-bottom) 100%
            );
        }

        /* Bola cahaya melayang di belakang konten. */
        .tefa-hero__orb {
            position: absolute;
            z-index: 0;
            border-radius: 50%;
            filter: blur(40px);
            pointer-events: none;
            will-change: transform;
        }

        .tefa-hero__orb > i {
            display: block;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            animation: float 9s ease-in-out infinite alternate;
        }

        .tefa-hero__orb--1 { width: 280px; height: 280px; top: 6%;  right: 8%;  background: radial-gradient(circle, rgba(255, 255, 255, 0.55), transparent 70%); }
        .tefa-hero__orb--2 { width: 220px; height: 220px; top: 38%; right: 30%; background: radial-gradient(circle, rgba(242, 182, 48, 0.45), transparent 70%); }
        .tefa-hero__orb--3 { width: 340px; height: 340px; top: -8%; left: -6%;  background: radial-gradient(circle, rgba(140, 190, 255, 0.55), transparent 70%); }
        .tefa-hero__orb--2 > i { animation-duration: 11s; animation-delay: -3s; }
        .tefa-hero__orb--3 > i { animation-duration: 13s; animation-delay: -6s; }

        @keyframes float {
            from { transform: translate3d(-18px, 14px, 0) scale(0.95); }
            to   { transform: translate3d(22px, -20px, 0) scale(1.08); }
        }

        /* Siluet gelombang di dasar hero; mengisi ruang ke warna surface (#fafafa). */
        .tefa-hero__wave {
            position: absolute;
            inset: auto 0 0 0;
            width: 100%;
            height: var(--hero-wave-height);
            z-index: 1;
            pointer-events: none;
            will-change: transform;
        }

        .tefa-hero__wave svg {
            display: block;
            width: 100%;
            height: 100%;
        }

        /* Gelombang belakang: lebih tinggi, tembus pandang, melayang ke kiri-kanan. */
        .tefa-hero__wave--back {
            height: calc(var(--hero-wave-height) + 10%);
        }

        .tefa-hero__wave--back svg {
            width: 112%;
            margin-left: -6%;
            animation: drift 10s ease-in-out infinite alternate;
        }

        @keyframes drift {
            from { transform: translateX(-4%); }
            to   { transform: translateX(4%); }
        }

        .tefa-hero__content {
            position: relative;
            z-index: 2;
            width: auto;
            max-width: 815px;
            margin: 0 0 0 8%;
            padding-top: 66px;
            will-change: transform, opacity;
        }

        .tefa-hero h1 {
            margin: 0;
            width: 430px;
            max-width: 100%;
            color: #ffffff;
            font-family: 'Open Sauce One', 'Plus Jakarta Sans', sans-serif;
            font-size: 40px;
            line-height: 0.96;
            font-weight: 700;
            letter-spacing: -1.05px;
            text-transform: uppercase;
        }

        /* Judul hero: tiap baris naik dari balik topeng saat halaman dimuat. */
        .tefa-hero__line {
            display: block;
            overflow: hidden;
            padding-bottom: 0.1em;
            margin-bottom: -0.1em;
        }

        .js .tefa-hero__line > span {
            display: block;
            transform: translateY(115%);
            animation: lineUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            animation-delay: calc(var(--i, 0) * 130ms + 200ms);
        }

        @keyframes lineUp {
            to { transform: none; }
        }

        .js .tefa-hero__actions {
            opacity: 0;
            transform: translateY(18px);
            animation: fadeUp 0.9s cubic-bezier(0.16, 1, 0.3, 1) 0.85s forwards;
        }

        @keyframes fadeUp {
            to { opacity: 1; transform: none; }
        }

        .tefa-hero__actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 25px;
        }

        .tefa-hero__btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 34px;
            padding: 0 16px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
            line-height: 1;
            text-decoration: none;
            white-space: nowrap;
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease, color 0.25s ease;
        }

        .tefa-hero__btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 22px -8px rgba(0, 0, 0, 0.45);
        }

        /* Kilatan cahaya melintas saat hover. */
        .tefa-hero__btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: -80%;
            width: 50%;
            height: 100%;
            background: linear-gradient(100deg, transparent, rgba(255, 255, 255, 0.65), transparent);
            transform: skewX(-20deg);
            transition: left 0.6s ease;
        }

        .tefa-hero__btn:hover::after {
            left: 130%;
        }

        .tefa-hero__btn--primary {
            background: #ffffff;
            color: #111111;
        }

        .tefa-hero__btn--outline {
            color: #ffffff;
            border: 1px solid #ffffff;
            background: transparent;
        }

        .tefa-hero__btn--outline:hover {
            background: #ffffff;
            color: #0b60cf;
        }

        /* Petunjuk scroll (mouse kecil) di dasar hero. */
        .tefa-hero__scroll {
            position: absolute;
            z-index: 2;
            left: 50%;
            bottom: 22px;
            width: 22px;
            height: 36px;
            margin-left: -11px;
            border: 2px solid rgba(11, 96, 207, 0.55);
            border-radius: 14px;
            transition: opacity 0.3s ease;
        }

        .tefa-hero__scroll::before {
            content: '';
            position: absolute;
            top: 6px;
            left: 50%;
            width: 3px;
            height: 7px;
            margin-left: -1.5px;
            border-radius: 2px;
            background: #0b60cf;
            animation: wheel 1.6s ease-in-out infinite;
        }

        @keyframes wheel {
            0%   { opacity: 0; transform: translateY(0); }
            30%  { opacity: 1; }
            100% { opacity: 0; transform: translateY(12px); }
        }

        @media (min-width: 601px) and (max-width: 1023px) {
            .tefa-hero h1 {
                font-size: 34px;
            }
        }

        @media (max-width: 600px) {
            .tefa-hero {
                min-height: 100vh;
                --hero-wave-height: 50%;
            }

            .tefa-hero__content {
                width: calc(100% - 44px);
                margin: 0 auto;
                padding-top: 68px;
            }

            .tefa-hero h1 {
                font-size: clamp(26px, 7vw, 30px);
            }

            .js [data-reveal="left"]  { transform: translateX(-36px); }
            .js [data-reveal="right"] { transform: translateX(36px); }
        }

        /* Fokus keyboard mengikuti aksen kuning dari desain. */
        main a:focus-visible,
        main button:focus-visible {
            outline: 3px solid #f2b630;
            outline-offset: 2px;
        }

        /* Catatan: blok prefers-reduced-motion sengaja dihapus — animasi dipaksa selalu tampil. */
    </style>

    <div class="scroll-progress" id="scrollProgress" aria-hidden="true"></div>

    <main class="bg-surface font-body">
        {{-- ================= HERO ================= --}}
        <section class="tefa-hero" aria-label="Hero" id="hero">
            <div class="tefa-hero__orb tefa-hero__orb--3" data-parallax-y="0.18" aria-hidden="true"><i></i></div>
            <div class="tefa-hero__orb tefa-hero__orb--1" data-parallax-y="0.32" aria-hidden="true"><i></i></div>
            <div class="tefa-hero__orb tefa-hero__orb--2" data-parallax-y="0.12" aria-hidden="true"><i></i></div>

            <div class="tefa-hero__content" id="heroContent">
                <h1 aria-label="Selamat datang di Katalog TEFA SMKN 4 Tanjungpinang">
                    <span class="tefa-hero__line" style="--i:0" aria-hidden="true"><span>SELAMAT DATANG DI</span></span>
                    <span class="tefa-hero__line" style="--i:1" aria-hidden="true"><span>KATALOG TEFA SMKN 4</span></span>
                    <span class="tefa-hero__line" style="--i:2" aria-hidden="true"><span>TANJUNGPINANG</span></span>
                </h1>

                <div class="tefa-hero__actions">
                    <a href="{{ route('produk') }}" class="tefa-hero__btn tefa-hero__btn--primary">Jelajahi Produk</a>
                    <a href="{{ route('jasa') }}" class="tefa-hero__btn tefa-hero__btn--outline">Lihat Layanan Jasa</a>
                </div>
            </div>

            {{-- Gelombang belakang (tembus pandang) --}}
            <div class="tefa-hero__wave tefa-hero__wave--back" data-parallax-y="-0.06" aria-hidden="true">
                <svg viewBox="0 0 815 145" preserveAspectRatio="none">
                    <path
                        fill="rgba(250,250,250,0.35)"
                        d="
                            M 0 60
                            C 60 30, 120 40, 190 62
                            C 260 84, 330 70, 400 46
                            C 470 22, 540 24, 610 50
                            C 680 76, 750 62, 815 34
                            L 815 145
                            L 0 145
                            Z
                        "
                    />
                </svg>
            </div>

            {{-- Gelombang depan (warna surface) --}}
            <div class="tefa-hero__wave" data-parallax-y="-0.02" aria-hidden="true">
                <svg viewBox="0 0 815 145" preserveAspectRatio="none">
                    <path
                        fill="#fafafa"
                        d="
                            M 0 75
                            C 28 89, 58 93, 91 92
                            C 143 90, 193 75, 235 54
                            C 285 29, 336 19, 376 19
                            C 416 19, 472 28, 516 51
                            C 579 84, 652 82, 712 72
                            C 754 65, 786 54, 815 30
                            L 815 145
                            L 0 145
                            Z
                        "
                    />
                </svg>
            </div>

            <div class="tefa-hero__scroll" id="heroScroll" aria-hidden="true"></div>
        </section>

        {{-- ================= ABOUT ================= --}}
        <section class="py-20" data-scroll-reveal>
            <div class="mx-auto grid w-full max-w-shell items-center gap-8 px-4 sm:px-8 lg:grid-cols-2 lg:gap-20">
                <div>
                    <h2 class="text-[1.35rem] font-extrabold" data-reveal="left" style="--d:0ms">Kreatif Inovatif Bersama</h2>
                    <p class="mb-3 text-[1.2rem] font-extrabold text-brand-blue" data-reveal="left" style="--d:120ms">TEFA SMKN 4 TANJUNGPINANG</p>
                    <p class="text-[1.05rem] text-ink-muted lg:max-w-[26rem]" data-reveal="left" style="--d:240ms">
                        Teaching Factory (TEFA) SMKN 4 Tanjungpinang adalah ekosistem pembelajaran berbasis produksi
                        nyata dengan standar industri. Kami menghadirkan produk unggulan dan layanan profesional yang
                        siap menjawab kebutuhan masyarakat serta dunia usaha.
                    </p>
                </div>
                <div class="aspect-[7/5] w-full overflow-hidden rounded-card bg-[#ddd]" data-reveal="mask" style="--d:150ms">
                    <img class="h-full w-full object-cover" data-parallax-img="0.08"
                         src="{{ asset('asset/img/foto-sekolahmu.jpg') }}"
                         alt="Siswa SMKN 4 Tanjungpinang berkumpul di halaman sekolah">
                </div>
            </div>
        </section>

        {{-- ================= WHY TEFA ================= --}}
        <section class="py-20 text-center" data-scroll-reveal>
            <div class="mx-auto w-full max-w-shell px-4 sm:px-8">
                <h2 class="mb-4 text-[clamp(1.5rem,3vw,2rem)] font-bold" data-split>
                    <span class="text-brand-blue">Mengapa</span> TEFA SMKN 4 Tanjungpinang?
                </h2>
                <p class="mx-auto max-w-[44rem] font-medium" data-reveal="blur" style="--d:450ms">
                    Hadir untuk mendukung kebutuhan masyarakat dan dunia usaha dengan produk bernilai jual tinggi serta
                    layanan jasa profesional yang inovatif, andal, dan berstandar industri modern.
                </p>
            </div>
        </section>

        {{-- ================= PROGRAM KEAHLIAN (SLIDER) ================= --}}
        <section class="py-20" data-scroll-reveal aria-labelledby="programs-title">
            <div class="mx-auto w-full max-w-shell px-4 sm:px-8">
                <h2 id="programs-title" class="mb-8 text-center text-[clamp(1.5rem,3vw,2rem)] font-bold" data-split>
                    Program Keahlian TEFA SMKN 4 Tanjungpinang
                </h2>

                <div data-reveal="zoom" style="--d:250ms">
                    @include('partials.home.slider')
                </div>
            </div>
        </section>

        {{-- ================= SIAP MENJADI MITRA ================= --}}
        <section class="py-20" data-scroll-reveal>
            <div class="mx-auto w-full max-w-shell px-4 sm:px-8">
                <h2 class="mb-8 text-center text-[clamp(1.5rem,3vw,2rem)] font-extrabold" data-split>
                    TEFA SMKN 4 TANJUNGPINANG <span class="block text-brand-blue">SIAP MENJADI MITRA YANG</span>
                </h2>

                <ul class="mx-auto grid max-w-[920px] gap-6 sm:grid-cols-2 lg:grid-cols-4" data-stagger-group data-tilt>
                    {{-- Kreatif --}}
                    <li class="value-card">
                        <svg viewBox="0 0 240 240" role="img" aria-label="Kreatif">
                            <defs>
                                <linearGradient id="vk-g" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#7fb2ff"/><stop offset="1" stop-color="#0b60cf"/></linearGradient>
                                <radialGradient id="vk-glow"><stop offset="0" stop-color="#ffe9a8"/><stop offset="1" stop-color="#f2b630"/></radialGradient>
                            </defs>
                            <circle cx="120" cy="120" r="112" fill="#e8f1ff"/>
                            <circle cx="120" cy="104" r="52" fill="url(#vk-glow)"/>
                            <path d="M98 150h44v14a10 10 0 0 1-10 10h-24a10 10 0 0 1-10-10z" fill="url(#vk-g)"/>
                            <rect x="104" y="178" width="32" height="10" rx="5" fill="#0b60cf"/>
                            <path d="M104 92l16 22 16-22M120 114v36" stroke="#fff" stroke-width="6" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                            <g stroke="#f2b630" stroke-width="7" stroke-linecap="round"><path d="M120 28v14M52 56l10 10M188 56l-10 10M34 112h14M192 112h14"/></g>
                            <circle cx="64" cy="170" r="7" fill="#0b60cf"/><circle cx="182" cy="168" r="5" fill="#f2b630"/><circle cx="176" cy="192" r="3.5" fill="#7fb2ff"/>
                        </svg>
                        <h3>Kreatif</h3>
                        <span class="value-tag text-brand-blue">Ide dari siswa</span>
                        <p>Karya dibuat berdasarkan kreativitas dan inovasi siswa sendiri.</p>
                    </li>

                    {{-- Kompeten --}}
                    <li class="value-card">
                        <svg viewBox="0 0 240 240" role="img" aria-label="Kompeten">
                            <defs>
                                <linearGradient id="vo-o" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#ffc46b"/><stop offset="1" stop-color="#ef7d00"/></linearGradient>
                            </defs>
                            <circle cx="120" cy="120" r="112" fill="#fff1de"/>
                            <circle cx="120" cy="108" r="54" fill="url(#vo-o)"/>
                            <circle cx="120" cy="108" r="38" fill="none" stroke="#fff" stroke-width="5" stroke-dasharray="3 9" stroke-linecap="round"/>
                            <path d="M98 108l16 16 30-32" stroke="#fff" stroke-width="10" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M92 150l-14 52 42-18 42 18-14-52" fill="#0b60cf"/>
                            <path d="M120 184l42 18-8-30" fill="#0a4fa8"/>
                            <circle cx="52" cy="60" r="6" fill="#ef7d00"/><circle cx="192" cy="54" r="8" fill="#ffc46b"/><circle cx="196" cy="162" r="5" fill="#0b60cf"/>
                        </svg>
                        <h3>Kompeten</h3>
                        <span class="value-tag text-brand-orange">Proses terarah</span>
                        <p>Dikerjakan melalui proses pembelajaran kejuruan yang terstruktur.</p>
                    </li>

                    {{-- Kolaboratif --}}
                    <li class="value-card">
                        <svg viewBox="0 0 240 240" role="img" aria-label="Kolaboratif">
                            <defs>
                                <linearGradient id="vl-gr" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#7fe0a4"/><stop offset="1" stop-color="#14a44d"/></linearGradient>
                            </defs>
                            <circle cx="120" cy="120" r="112" fill="#e4f8ec"/>
                            <g stroke="#14a44d" stroke-width="5" stroke-linecap="round" opacity=".6"><path d="M120 80L70 150M120 80l50 70M70 150h100"/></g>
                            <g fill="url(#vl-gr)"><circle cx="120" cy="64" r="22"/><circle cx="64" cy="156" r="22"/><circle cx="176" cy="156" r="22"/></g>
                            <g fill="#fff"><circle cx="120" cy="58" r="8"/><circle cx="64" cy="150" r="8"/><circle cx="176" cy="150" r="8"/>
                            <path d="M105 80a15 11 0 0 1 30 0z"/><path d="M49 172a15 11 0 0 1 30 0z"/><path d="M161 172a15 11 0 0 1 30 0z"/></g>
                            <circle cx="120" cy="132" r="14" fill="#f2b630"/><circle cx="120" cy="132" r="6" fill="#fff"/>
                            <circle cx="44" cy="68" r="6" fill="#14a44d"/><circle cx="200" cy="74" r="5" fill="#7fe0a4"/>
                        </svg>
                        <h3>Kolaboratif</h3>
                        <span class="value-tag text-brand-green">Lintas bidang</span>
                        <p>Melibatkan berbagai bidang keahlian dalam satu proses produksi.</p>
                    </li>

                    {{-- Siap Industri --}}
                    <li class="value-card">
                        <svg viewBox="0 0 240 240" role="img" aria-label="Siap Industri">
                            <defs>
                                <linearGradient id="vp-p" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#c9a0ff"/><stop offset="1" stop-color="#7a35d6"/></linearGradient>
                            </defs>
                            <circle cx="120" cy="120" r="112" fill="#f1e8ff"/>
                            <path d="M40 190V120l36-20v20l36-20v20l36-20v-40h32v120z" fill="url(#vp-p)"/>
                            <g fill="#fff" opacity=".9"><rect x="64" y="146" width="18" height="18" rx="3"/><rect x="96" y="146" width="18" height="18" rx="3"/><rect x="128" y="146" width="18" height="18" rx="3"/></g>
                            <rect x="164" y="60" width="14" height="30" rx="3" fill="#5a22a8"/>
                            <path d="M60 60a14 14 0 0 1 28 0" fill="none" stroke="#f2b630" stroke-width="6" stroke-linecap="round"/>
                            <g transform="translate(178 128)"><circle r="26" fill="#f2b630"/><circle r="10" fill="#f1e8ff"/>
                            <g stroke="#f2b630" stroke-width="10" stroke-linecap="round"><path d="M0-34v-6M0 34v6M-34 0h-6M34 0h6M24-24l4-4M-24 24l-4 4M24 24l4 4M-24-24l-4-4"/></g></g>
                            <rect x="30" y="190" width="180" height="8" rx="4" fill="#5a22a8"/>
                        </svg>
                        <h3>Siap Industri</h3>
                        <span class="value-tag text-brand-purple">Standar kerja</span>
                        <p>Mengenalkan siswa pada proses kerja yang mendekati dunia industri.</p>
                    </li>
                </ul>
            </div>
        </section>
    </main>

    <script>
        /* ===== Slider Program Keahlian (komponen Alpine) — animasi dipaksa aktif ===== */
        function homeSlider() {
            var reduceMotion = false; // dipaksa: autoplay & transisi selalu jalan
            var DURATION = 750;
            var EASE = 'cubic-bezier(0.65, 0, 0.35, 1)'; // easeInOut halus
            var INTERVAL = 4000;
            var CLONES = 3;

            return {
                index: 0,
                total: 6,
                moving: false,
                timer: null,

                init: function () {
                    this.addClones();

                    /* Transisi dikontrol dari JS agar mulus (menimpa durasi/easing dari CSS partial). */
                    var track = this.$refs.track;
                    track.style.willChange = 'transform';
                    track.style.backfaceVisibility = 'hidden';
                    track.style.transition = 'transform ' + DURATION + 'ms ' + EASE;

                    if (!reduceMotion) {
                        this.start();
                    }
                },

                addClones: function () {
                    var track = this.$refs.track;
                    var head = Array.prototype.slice.call(track.children).slice(0, CLONES);

                    head.forEach(function (slide) {
                        var clone = slide.cloneNode(true);
                        clone.setAttribute('aria-hidden', 'true');

                        var link = clone.querySelector('a');

                        if (link) {
                            link.setAttribute('tabindex', '-1');
                        }

                        track.appendChild(clone);
                    });
                },

                render: function (i, animate) {
                    this.index = i;
                    var track = this.$refs.track;
                    track.classList.toggle('is-instant', !animate);
                    track.style.transition = animate ? 'transform ' + DURATION + 'ms ' + EASE : 'none';
                    track.style.setProperty('--index', i);

                    if (!animate) {
                        void track.offsetWidth; // paksa reflow supaya lompatan instan tidak ikut beranimasi
                    }
                },

                start: function () {
                    if (!this.timer) {
                        var self = this;
                        this.timer = setInterval(function () { self.next(); }, INTERVAL);
                    }
                },

                stop: function () {
                    clearInterval(this.timer);
                    this.timer = null;
                },

                restart: function () {
                    this.stop();
                    if (!reduceMotion) {
                        this.start();
                    }
                },

                pause: function () {
                    this.stop();
                },

                resume: function () {
                    if (!reduceMotion) {
                        this.start();
                    }
                },

                next: function () {
                    if (this.moving) {
                        return;
                    }

                    this.moving = true;
                    this.render(this.index + 1, true);

                    var self = this;
                    setTimeout(function () {
                        if (self.index >= self.total) {
                            self.render(0, false);
                        }
                        self.moving = false;
                    }, reduceMotion ? 0 : DURATION + 20);
                },

                prev: function () {
                    if (this.moving) {
                        return;
                    }

                    this.moving = true;

                    if (this.index === 0) {
                        this.render(this.total, false);
                        void this.$refs.track.offsetWidth;
                    }

                    this.render(this.index - 1, true);

                    var self = this;
                    setTimeout(function () {
                        self.moving = false;
                    }, reduceMotion ? 0 : DURATION + 20);
                },

                goTo: function (i) {
                    if (this.moving) {
                        return;
                    }

                    this.moving = true;
                    this.render(i, true);

                    var self = this;
                    setTimeout(function () {
                        self.moving = false;
                    }, reduceMotion ? 0 : DURATION + 20);
                }
            };
        }

        /* ===== Animasi scroll: reveal, split kata, stagger, parallax, progress bar, tilt ===== */
        (function () {
            document.documentElement.classList.add('js');

            var reduceMotion = false; // dipaksa: semua animasi selalu tampil, abaikan pengaturan perangkat
            var canHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

            /* ---- Pecah judul [data-split] jadi kata-kata (aksen/span anak tetap utuh) ---- */
            function splitWords(root) {
                var label = root.textContent.replace(/\s+/g, ' ').trim();
                var counter = 0;

                function walk(node) {
                    Array.prototype.slice.call(node.childNodes).forEach(function (child) {
                        if (child.nodeType === 3) {
                            var frag = document.createDocumentFragment();

                            child.textContent.split(/(\s+)/).forEach(function (part) {
                                if (!part) { return; }

                                if (/^\s+$/.test(part)) {
                                    frag.appendChild(document.createTextNode(' '));
                                    return;
                                }

                                var outer = document.createElement('span');
                                var inner = document.createElement('span');
                                outer.className = 'split-word';
                                outer.style.setProperty('--w', counter++);
                                inner.textContent = part;
                                outer.appendChild(inner);
                                frag.appendChild(outer);
                            });

                            node.replaceChild(frag, child);
                        } else if (child.nodeType === 1) {
                            walk(child);
                        }
                    });
                }

                walk(root);
                root.setAttribute('aria-label', label);
                Array.prototype.forEach.call(root.children, function (c) { c.setAttribute('aria-hidden', 'true'); });
            }

            /* ---- Stagger anak berulang (grid nilai, kartu slider); klon slider dilewati ---- */
            function markStagger(section) {
                var groups = section.querySelectorAll('[data-stagger-group]');

                Array.prototype.forEach.call(groups, function (group) {
                    Array.prototype.forEach.call(group.children, function (child, i) {
                        if (child.getAttribute('aria-hidden') === 'true') {
                            return;
                        }

                        child.setAttribute('data-stagger-item', '');
                        child.style.setProperty('--stagger-index', Math.min(i, 5));
                    });
                });
            }

            /* ---- Tilt 3D mengikuti kursor (setelah kartu selesai muncul) ---- */
            function enableTilt(item) {
                item.addEventListener('pointermove', function (e) {
                    var r = item.getBoundingClientRect();
                    var x = (e.clientX - r.left) / r.width - 0.5;
                    var y = (e.clientY - r.top) / r.height - 0.5;
                    item.style.setProperty('--ry', (x * 14).toFixed(2) + 'deg');
                    item.style.setProperty('--rx', (-y * 14).toFixed(2) + 'deg');
                });

                item.addEventListener('pointerleave', function () {
                    item.style.setProperty('--rx', '0deg');
                    item.style.setProperty('--ry', '0deg');
                });
            }

            function reveal(section) {
                section.classList.add('is-visible');

                Array.prototype.forEach.call(section.querySelectorAll('[data-stagger-item]'), function (item) {
                    item.classList.add('is-visible');

                    if (canHover && !reduceMotion && item.parentNode.hasAttribute('data-tilt')) {
                        var idx = parseInt(item.style.getPropertyValue('--stagger-index'), 10) || 0;

                        setTimeout(function () {
                            item.classList.add('is-settled');
                            enableTilt(item);
                        }, 900 + idx * 110);
                    }
                });
            }

            /* ---- Parallax + progress bar (satu rAF per frame) ---- */
            function setupScrollEffects() {
                var bar = document.getElementById('scrollProgress');
                var hero = document.getElementById('hero');
                var heroContent = document.getElementById('heroContent');
                var heroScroll = document.getElementById('heroScroll');
                var layers = document.querySelectorAll('[data-parallax-y]');
                var imgs = document.querySelectorAll('[data-parallax-img]');
                var ticking = false;

                function update() {
                    ticking = false;

                    var y = window.pageYOffset;
                    var vh = window.innerHeight;
                    var max = document.documentElement.scrollHeight - vh;

                    if (bar) {
                        bar.style.transform = 'scaleX(' + (max > 0 ? Math.min(y / max, 1) : 0) + ')';
                    }

                    /* Hero: hanya dihitung selama masih terlihat. */
                    if (hero && y < hero.offsetHeight + 100) {
                        Array.prototype.forEach.call(layers, function (el) {
                            el.style.transform = 'translate3d(0,' + (y * parseFloat(el.getAttribute('data-parallax-y'))).toFixed(1) + 'px,0)';
                        });

                        if (heroContent) {
                            var fade = Math.max(0, 1 - y / (hero.offsetHeight * 0.55));
                            heroContent.style.transform = 'translate3d(0,' + (y * 0.28).toFixed(1) + 'px,0)';
                            heroContent.style.opacity = fade;
                        }

                        if (heroScroll) {
                            heroScroll.style.opacity = Math.max(0, 1 - y / 120);
                        }
                    }

                    /* Gambar: bergeser relatif terhadap tengah layar. */
                    Array.prototype.forEach.call(imgs, function (img) {
                        var wrap = img.parentNode.getBoundingClientRect();

                        if (wrap.bottom < 0 || wrap.top > vh) { return; }

                        var offset = (wrap.top + wrap.height / 2 - vh / 2) * parseFloat(img.getAttribute('data-parallax-img'));
                        img.style.transform = 'translate3d(0,' + offset.toFixed(1) + 'px,0) scale(1.18)';
                    });
                }

                function onScroll() {
                    if (!ticking) {
                        ticking = true;
                        window.requestAnimationFrame(update);
                    }
                }

                window.addEventListener('scroll', onScroll, { passive: true });
                window.addEventListener('resize', onScroll);
                update();
            }

            function init() {
                var sections = document.querySelectorAll('[data-scroll-reveal]');

                Array.prototype.forEach.call(document.querySelectorAll('[data-split]'), splitWords);

                Array.prototype.forEach.call(sections, markStagger);

                /* Kilau pada aksen biru judul "Mengapa". */
                var accent = document.querySelector('[data-split] .text-brand-blue');
                if (accent) {
                    setTimeout(function () { accent.classList.add('shine-text'); }, 1600);
                }

                setupScrollEffects();

                if (!('IntersectionObserver' in window)) {
                    Array.prototype.forEach.call(sections, reveal);
                    return;
                }

                var io = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            reveal(entry.target);
                            io.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.2, rootMargin: '0px 0px -8% 0px' });

                Array.prototype.forEach.call(sections, function (section) { io.observe(section); });
            }

            /* Ditunda sampai DOMContentLoaded supaya Alpine selesai menyalin kartu slider lebih dulu. */
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
        })();
    </script>
@endsection