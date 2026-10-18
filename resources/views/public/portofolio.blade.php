@extends('layouts.public')

@section('title', 'Portofolio — Katalog TEFA SMKN 4 Tanjungpinang')

@section('content')
<style>
    /* ==========================================================
       PORTOFOLIO PAGE — kartu disamakan dengan Produk/Jasa.
       Token di-scope ke .portofolio-page (bukan :root) supaya tidak
       bocor ke halaman lain. Semua selector di-prefix .portofolio-page
       agar menang spesifisitas atas aturan global di layouts/public.
       ========================================================== */
    .portofolio-page {
        --color-primary: #0a4aa6;
        --color-primary-dark: #00357f;
        --color-accent-blue: #1414c8;
        --color-text: #111111;
        --color-muted: #666666;
        --color-bg: #fafafa;
        --color-white: #ffffff;
        --color-placeholder: #cad2db;
        --radius-card: 15px;
        --radius-pill: 999px;
        --font-display: 'Open Sauce Sans', 'Anton', 'Impact', 'Arial Narrow', sans-serif;

        background-color: var(--color-bg);
        color: var(--color-text);
        padding-bottom: 90px;
    }

    /* ---------- HERO ---------- */
    .portofolio-page .pf-hero {
        position: relative;
        overflow: hidden;
        background: #0b60cf;
        color: var(--color-white);
        text-align: center;
        padding: 56px 24px 48px;
    }
    .portofolio-page .pf-hero__content {
        position: relative;
        z-index: 1;
    }
    .portofolio-page .pf-hero h1 {
        font-family: var(--font-display);
        font-size: 42px;
        font-weight: 800;
        letter-spacing: 1px;
        margin-bottom: 14px;
        text-transform: uppercase;
    }
    .portofolio-page .pf-hero p {
        max-width: 620px;
        margin: 0 auto;
        font-size: 15px;
        line-height: 1.55;
        opacity: .95;
    }

    /* ---------- PENCARIAN & FILTER ---------- */
    .portofolio-page .pf-filter {
        display: flex;
        flex-direction: column;
        gap: 12px;
        max-width: 966px;
        margin: 35px auto 0;
        padding: 0 16px;
    }
    .portofolio-page .pf-filter-tabs {
        display: flex;
        gap: 28px;
        overflow-x: auto;
        padding-left: 4px;
        white-space: nowrap;
        font-size: 14px;
        font-weight: 700;
    }
    .portofolio-page .pf-filter-tabs a {
        padding: 2px 0;
        font-size: 14px;
        font-weight: 700;
        color: var(--color-text);
        text-decoration: none;
        cursor: pointer;
        transition: color 0.2s ease;
    }
    .portofolio-page .pf-filter-tabs a:hover { color: var(--color-primary); }
    .portofolio-page .pf-filter-tabs a.active { color: var(--color-accent-blue); }

    .portofolio-page .pf-search {
        position: relative;
        width: 100%;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .portofolio-page .pf-search__icon {
        position: absolute;
        top: 50%;
        left: 10px;
        width: 14px;
        height: 14px;
        transform: translateY(-50%);
        pointer-events: none;
        z-index: 1;
    }
    .portofolio-page .pf-search__input {
        flex: 1;
        min-width: 0;
        height: 38px;
        padding: 0 10px 0 34px;
        border: 1px solid #000000;
        border-radius: 6px;
        background: var(--color-white);
        font-size: 13px;
        font-weight: 700;
        color: var(--color-text);
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .portofolio-page .pf-search__input:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(10, 74, 166, 0.15);
    }
    .portofolio-page .pf-search__input::placeholder { color: #777777; opacity: 1; }
    .portofolio-page .pf-search__btn {
        flex-shrink: 0;
        height: 38px;
        padding: 0 20px;
        background: var(--color-primary);
        color: var(--color-white);
        border: none;
        border-radius: var(--radius-pill);
        font: inherit;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: background-color 0.2s ease, transform 0.2s ease;
    }
    .portofolio-page .pf-search__btn:hover {
        background: var(--color-primary-dark);
        transform: translateY(-1px);
    }

    /* ---------- GRID KARTU ---------- */
    .portofolio-page .pf-area-kartu {
        max-width: 1005px;
        margin: 44px auto 0;
        padding: 0 16px;
    }
    .portofolio-page .pf-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 32px 16px;
    }
    .portofolio-page .pf-kartu {
        display: flex;
        flex-direction: column;
        background: var(--color-white);
        border: 1px solid #e6eaef;
        border-radius: var(--radius-card);
        overflow: hidden;
        cursor: pointer;
        text-align: left;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.05);
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .portofolio-page .pf-kartu:hover,
    .portofolio-page .pf-kartu:focus-visible {
        transform: translateY(-4px);
        box-shadow: 0 18px 30px -12px rgba(15, 23, 42, 0.18);
    }
    .portofolio-page .pf-thumb {
        position: relative;
        width: 100%;
        aspect-ratio: 7 / 8;
        overflow: hidden;
        background: var(--color-placeholder);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .portofolio-page .pf-thumb-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.45s ease;
    }
    .portofolio-page .pf-kartu:hover .pf-thumb-img { transform: scale(1.05); }
    .portofolio-page .pf-thumb-fallback {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        color: var(--color-white);
        position: relative;
    }
    .portofolio-page .pf-thumb-pattern {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.15) 1px, transparent 1px);
        background-size: 16px 16px;
    }
    .portofolio-page .pf-dept-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        font-size: 10.5px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: var(--radius-pill);
        letter-spacing: 0.5px;
        text-transform: uppercase;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        z-index: 2;
    }
    .portofolio-page .pf-isi {
        padding: 14px 14px 16px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .portofolio-page .pf-judul {
        font-size: 14.5px;
        font-weight: 700;
        color: var(--color-text);
        line-height: 1.4;
        margin-bottom: 6px;
        transition: color 0.2s ease;
    }
    .portofolio-page .pf-kartu:hover .pf-judul { color: var(--color-primary); }
    .portofolio-page .pf-deskripsi {
        font-size: 12.5px;
        color: var(--color-muted);
        line-height: 1.5;
        margin-bottom: 10px;
        flex-grow: 1;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .portofolio-page .pf-meta {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 11.5px;
        font-weight: 700;
        color: var(--color-muted);
        margin-bottom: 12px;
    }
    .portofolio-page .pf-meta svg { width: 13px; height: 13px; flex-shrink: 0; }
    .portofolio-page .pf-footer {
        padding-top: 12px;
        border-top: 1px solid #f1f5f9;
    }
    .portofolio-page .btn-card-detail {
        position: relative;
        overflow: hidden;
        width: 100%;
        background: var(--color-primary);
        color: var(--color-white);
        padding: 10px 14px;
        border-radius: var(--radius-pill);
        font-weight: 700;
        font-size: 12.5px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: background-color 0.2s ease, transform 0.2s ease;
    }
    .portofolio-page .btn-card-detail:hover {
        background: var(--color-primary-dark);
        transform: translateY(-1px);
    }

    /* ---------- EMPTY STATE ---------- */
    .portofolio-page .pf-kosong {
        grid-column: 1 / -1;
        text-align: center;
        padding: 70px 20px;
        background: var(--color-white);
        border-radius: var(--radius-card);
        border: 1.5px dashed var(--color-placeholder);
    }
    .portofolio-page .pf-kosong__icon { font-size: 52px; margin-bottom: 14px; }
    .portofolio-page .pf-kosong__title { font-size: 19px; font-weight: 800; margin-bottom: 8px; }
    .portofolio-page .pf-kosong__text {
        color: var(--color-muted);
        font-size: 14.5px;
        max-width: 480px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .portofolio-page .pf-paginasi { margin-top: 40px; }

    @media (max-width: 600px) {
        .portofolio-page .pf-hero h1 { font-size: 30px; }
    }

    /* ---------- BREAKPOINT >= 640px (tablet) ---------- */
    @media (min-width: 640px) {
        .portofolio-page .pf-filter {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }
        .portofolio-page .pf-filter-tabs { overflow: visible; gap: 40px; }
        .portofolio-page .pf-search { width: 314px; flex: none; }
        .portofolio-page .pf-grid {
            grid-template-columns: repeat(3, 1fr);
            column-gap: 24px;
            row-gap: 40px;
        }
    }

    /* ---------- BREAKPOINT >= 1024px (desktop) ---------- */
    @media (min-width: 1024px) {
        .portofolio-page .pf-filter-tabs { gap: 50px; }
        .portofolio-page .pf-grid {
            grid-template-columns: repeat(4, 1fr);
            column-gap: 28px;
            row-gap: 44px;
        }
    }

    /* ==========================================================
       ANIMASI (FORCE)
       Sengaja TIDAK memakai @media (prefers-reduced-motion) dan
       properti animation memakai !important agar tetap jalan
       walau perangkat mematikan efek gerak / ada aturan global.
       ========================================================== */
    @keyframes pf-rise {
        from { opacity: 0; transform: translateY(22px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes pf-fade {
        from { opacity: 0; }
        to   { opacity: 1; }
    }
    @keyframes pf-float {
        0%, 100% { transform: translateY(0); }
        50%      { transform: translateY(-8px); }
    }
    @keyframes pf-pop {
        0%   { opacity: 0; transform: scale(.6); }
        70%  { transform: scale(1.08); }
        100% { opacity: 1; transform: scale(1); }
    }
    @keyframes pf-line {
        from { transform: scaleX(0); }
        to   { transform: scaleX(1); }
    }

    /* Banner: konten muncul bertahap (animasi diambil dari Produk/Layanan Jasa) */
    .portofolio-page .pf-hero {
        animation: pf-rise .7s cubic-bezier(.16,1,.3,1) both !important;
    }
    .portofolio-page .pf-hero::before,
    .portofolio-page .pf-hero::after {
        content: "";
        position: absolute;
        border-radius: 50%;
        background: rgba(255,255,255,.10);
        pointer-events: none;
    }
    .portofolio-page .pf-hero::before { width: 220px; height: 220px; right: -60px; top: -90px;
        animation: pf-float 8s ease-in-out infinite !important; }
    .portofolio-page .pf-hero::after  { width: 130px; height: 130px; left: 8%; bottom: -70px;
        animation: pf-float 10s ease-in-out 1s infinite !important; }
    .portofolio-page .pf-hero h1 { animation: pf-rise .7s cubic-bezier(.16,1,.3,1) .2s both !important; }
    .portofolio-page .pf-hero p  { animation: pf-rise .7s cubic-bezier(.16,1,.3,1) .35s both !important; }

    /* Filter tab & pencarian */
    .portofolio-page .pf-filter-tabs { animation: pf-fade .6s ease .45s both !important; }
    .portofolio-page .pf-search      { animation: pf-fade .6s ease .55s both !important; }
    .portofolio-page .pf-filter-tabs a { position: relative; }
    .portofolio-page .pf-filter-tabs a::after {
        content: "";
        position: absolute;
        left: 0; right: 0; bottom: -3px;
        height: 2px;
        border-radius: 2px;
        background: currentColor;
        transform: scaleX(0);
        transform-origin: left;
        transition: transform .3s cubic-bezier(.16,1,.3,1);
    }
    .portofolio-page .pf-filter-tabs a:hover::after { transform: scaleX(1); }
    .portofolio-page .pf-filter-tabs a.active::after {
        transform: scaleX(1);
        animation: pf-line .45s cubic-bezier(.16,1,.3,1) .7s both !important;
    }
    .portofolio-page .pf-search__input { transition: border-color .25s ease, box-shadow .25s ease, transform .25s ease; }
    .portofolio-page .pf-search__input:focus { transform: translateY(-1px); }

    /* Kartu: muncul saat di-scroll (diatur JS lewat .pp-ready / .pp-in) */
    .portofolio-page.pp-ready .pf-kartu { opacity: 0; }
    .portofolio-page.pp-ready .pf-kartu.pp-in {
        animation: pf-rise .6s cubic-bezier(.16,1,.3,1) var(--pp-delay, 0ms) both !important;
    }
    .portofolio-page.pp-ready .pf-kartu.pp-in .pf-dept-badge {
        animation: pf-pop .5s cubic-bezier(.34,1.56,.64,1) calc(var(--pp-delay, 0ms) + 250ms) both !important;
    }

    /* Hover kartu: kilau halus pada tombol */
    .portofolio-page .btn-card-detail::after {
        content: "";
        position: absolute;
        top: 0; bottom: 0; left: -60%;
        width: 40%;
        background: linear-gradient(100deg, transparent, rgba(255,255,255,.35), transparent);
        transform: skewX(-20deg);
        transition: left .6s ease;
    }
    .portofolio-page .pf-kartu:hover .btn-card-detail::after { left: 130%; }

    /* Empty state */
    .portofolio-page .pf-kosong { animation: pf-rise .6s cubic-bezier(.16,1,.3,1) both !important; }
    .portofolio-page .pf-kosong__icon { display: inline-block;
        animation: pf-float 3.6s ease-in-out infinite !important; }
</style>

<div class="portofolio-page">
    {{-- HERO --}}
    <section class="pf-hero" aria-labelledby="pf-hero-title">
        <div class="pf-hero__content">
            <h1 id="pf-hero-title">PORTOFOLIO</h1>
            <p>Eksplorasi berbagai solusi digital, aplikasi web, dan produk inovatif yang telah kami kembangkan. Sebuah representasi komitmen kami dalam mengubah ide menjadi karya nyata.</p>
        </div>
    </section>

    {{-- PENCARIAN & FILTER --}}
    <div class="pf-filter">
        @php
            $filterUrl = fn (string $kode) => route('portofolio', array_filter([
                'jurusan' => $kode === 'all' ? null : $kode,
                'q' => request('q'),
            ], fn ($value) => $value !== null && $value !== ''));
        @endphp

        <nav aria-label="Filter program keahlian">
            <div class="pf-filter-tabs">
                <a href="{{ $filterUrl('all') }}" class="{{ $aktifJurusan === 'all' ? 'active' : '' }}">Semua</a>
                @foreach($filters as $kode => $label)
                    <a href="{{ $filterUrl($kode) }}" class="{{ $aktifJurusan === $kode ? 'active' : '' }}">{{ $label }}</a>
                @endforeach
            </div>
        </nav>

        <form class="pf-search" role="search" method="GET" action="{{ route('portofolio') }}">
            @if($aktifJurusan !== 'all')
                <input type="hidden" name="jurusan" value="{{ $aktifJurusan }}">
            @endif
            <svg class="pf-search__icon" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                <circle cx="10.5" cy="10.5" r="7"/>
                <line x1="16" y1="16" x2="21.5" y2="21.5"/>
            </svg>
            <label class="visually-hidden" for="pf-search-input" style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0);white-space:nowrap;">Cari portofolio</label>
            <input class="pf-search__input"
                   id="pf-search-input"
                   name="q"
                   type="search"
                   value="{{ request('q') }}"
                   placeholder="Cari portofolio..." />
            <button type="submit" class="pf-search__btn">Cari</button>
        </form>
    </div>

    {{-- GRID KARTU --}}
    <main class="pf-area-kartu">
        <div class="pf-grid">
            @forelse($portfolios as $portfolio)
                @php
                    $rawKode = strtoupper($portfolio->jurusan->kode ?? 'TEFA');
                    $deptKode = match(true) {
                        str_contains($rawKode, 'ANI') => 'ANIMASI',
                        str_contains($rawKode, 'GIM') => 'GIM',
                        str_contains($rawKode, 'RPL') => 'RPL',
                        str_contains($rawKode, 'TKJ') => 'TKJ',
                        str_contains($rawKode, 'DKV') => 'DKV',
                        str_contains($rawKode, 'PSPT') || str_contains($rawKode, 'PSTV') => 'PSPT',
                        default => 'TEFA',
                    };

                    $badgeStyle = match($deptKode) {
                        'RPL' => 'background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe;',
                        'DKV' => 'background: #faf5ff; color: #9333ea; border: 1px solid #e9d5ff;',
                        'TKJ' => 'background: #ecfeff; color: #0891b2; border: 1px solid #a5f3fc;',
                        'ANIMASI' => 'background: #fff7ed; color: #ea580c; border: 1px solid #fed7aa;',
                        'PSPT' => 'background: #fff1f2; color: #e11d48; border: 1px solid #fecdd3;',
                        'GIM' => 'background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0;',
                        default => 'background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe;',
                    };

                    $fallbackBg = match($deptKode) {
                        'RPL' => 'linear-gradient(135deg, #1e3a8a, #0284c7)',
                        'DKV' => 'linear-gradient(135deg, #6b21a8, #7c3aed)',
                        'TKJ' => 'linear-gradient(135deg, #0e7490, #0891b2)',
                        'ANIMASI' => 'linear-gradient(135deg, #c2410c, #b45309)',
                        'PSPT' => 'linear-gradient(135deg, #be123c, #e11d48)',
                        'GIM' => 'linear-gradient(135deg, #15803d, #16a34a)',
                        default => 'linear-gradient(135deg, #0a215e, #1e3a8a)',
                    };

                    $fallbackIcon = match($deptKode) {
                        'RPL' => '💻',
                        'DKV' => '🎨',
                        'TKJ' => '🔧',
                        'ANIMASI' => '🎬',
                        'PSPT' => '🎥',
                        'GIM' => '🎮',
                        default => '🏆',
                    };

                    $cover = $portfolio->images->first();
                @endphp

                <a href="{{ route('portofolio.show', $portfolio) }}" class="pf-kartu">
                    {{-- Thumbnail --}}
                    <div class="pf-thumb">
                        @if($cover)
                            <img src="{{ asset('storage/'.$cover->image_path) }}"
                                 alt="{{ $portfolio->title }}"
                                 class="pf-thumb-img"
                                 loading="lazy"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="pf-thumb-fallback" style="background: {{ $fallbackBg }}; display: none;">
                                <div class="pf-thumb-pattern"></div>
                                <span>{{ $fallbackIcon }}</span>
                            </div>
                        @else
                            <div class="pf-thumb-fallback" style="background: {{ $fallbackBg }};">
                                <div class="pf-thumb-pattern"></div>
                                <span>{{ $fallbackIcon }}</span>
                            </div>
                        @endif

                        <span class="pf-dept-badge" style="{{ $badgeStyle }}">{{ $deptKode }}</span>
                    </div>

                    {{-- Content --}}
                    <div class="pf-isi">
                        <h3 class="pf-judul">{{ $portfolio->title }}</h3>
                        <p class="pf-deskripsi">
                            {{ $portfolio->description ?: 'Karya portofolio siswa unit Teaching Factory SMKN 4 Tanjungpinang.' }}
                        </p>

                        <div class="pf-meta">
                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="9" cy="8" r="3.5"/><circle cx="17" cy="9" r="2.5"/><path d="M2 20c0-3.5 3-6 7-6s7 2.5 7 6z"/><path d="M15 14.5c3.5 0 7 2 7 5v.5h-5"/></svg>
                            <span>{{ $portfolio->team_name ?: 'Tim Siswa SMKN 4' }}</span>
                        </div>

                        <div class="pf-footer">
                            <span class="btn-card-detail">
                                <span aria-hidden="true">🔎</span>
                                <span>Lihat Detail</span>
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="pf-kosong">
                    <div class="pf-kosong__icon" aria-hidden="true">🔍</div>
                    <h3 class="pf-kosong__title">Portofolio Tidak Ditemukan</h3>
                    <p class="pf-kosong__text">
                        Tidak ada portofolio yang sesuai dengan kata kunci atau jurusan yang Anda pilih.
                    </p>
                </div>
            @endforelse
        </div>

        @if($portfolios->hasPages())
            <div class="pf-paginasi">
                {{ $portfolios->links() }}
            </div>
        @endif
    </main>
</div>

<script>
    /* ---------- ANIMASI KARTU PORTOFOLIO (reveal saat scroll) ---------- */
    (function () {
        var page  = document.querySelector('.portofolio-page');
        var cards = document.querySelectorAll('.portofolio-page .pf-kartu');
        if (!page || !cards.length) return;

        // .pp-ready baru dipasang oleh JS: kalau JS gagal, kartu tetap terlihat normal.
        page.classList.add('pp-ready');

        function show(card, i) {
            card.style.setProperty('--pp-delay', ((i % 4) * 80) + 'ms'); // stagger per baris
            card.classList.add('pp-in');
        }

        if (!('IntersectionObserver' in window)) {
            cards.forEach(show);
            return;
        }

        var seen = 0;
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (!e.isIntersecting) return;
                show(e.target, seen++);
                io.unobserve(e.target);
            });
            seen = 0; // reset stagger tiap gelombang kartu yang terlihat
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

        cards.forEach(function (c) { io.observe(c); });

        // Pengaman: tampilkan semua kartu bila observer tidak memicu dalam 2 detik
        setTimeout(function () {
            cards.forEach(function (c) { if (!c.classList.contains('pp-in')) show(c, 0); });
        }, 2000);
    })();
</script>
@endsection
