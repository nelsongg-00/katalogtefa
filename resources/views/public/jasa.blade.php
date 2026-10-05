@extends('layouts.public')

@section('title', 'Layanan Jasa — TEFA SMKN 4 Tanjungpinang')

@section('content')
<style>
    /* ==========================================================
       LAYANAN JASA PAGE — restyle mengikuti desain halaman Produk
       (resources/views/public/produk.blade.php = source of truth).
       Token di-scope ke .jasa-page (bukan :root) supaya tidak
       bocor ke halaman lain. Semua selector di-prefix .jasa-page
       agar menang spesifisitas atas aturan global di layouts/public.
       ========================================================== */
    .jasa-page {
        --color-primary: #0a4aa6;
        --color-primary-dark: #00357f;
        --color-accent-blue: #1414c8;
        --color-text: #111111;
        --color-muted: #666666;
        --color-bg: #fafafa;
        --color-white: #ffffff;
        --color-yellow: #f2b630;
        --color-red: #e53935;
        --color-placeholder: #cad2db;
        --color-banner-from: #0a84e0;
        --color-banner-to: #45affa;
        --radius-banner: 40px;
        --radius-card: 15px;
        --radius-pill: 999px;
        --font-display: 'Anton', 'Impact', 'Arial Narrow', sans-serif;

        background-color: var(--color-bg);
        color: var(--color-text);
        padding-bottom: 90px;
    }

    .jasa-page .visually-hidden {
        position: absolute;
        width: 1px;
        height: 1px;
        overflow: hidden;
        clip: rect(0 0 0 0);
        white-space: nowrap;
    }

    .jasa-page .jasa-main {
        padding: 32px 16px 0;
    }

    /* ---------- BANNER ---------- */
    .jasa-page .banner {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        max-width: 966px;
        min-height: 220px;
        margin: 0 auto;
        overflow: hidden;
        border-radius: var(--radius-banner);
        background: linear-gradient(120deg, var(--color-banner-from) 0%, #1b94ee 50%, var(--color-banner-to) 100%);
        color: var(--color-white);
    }

    .jasa-page .banner__content {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 24px 16px;
        text-align: center;
    }

    .jasa-page .banner__title {
        font-family: var(--font-display);
        font-size: clamp(2.25rem, 7vw, 4.25rem);
        font-weight: 400;
        line-height: 1.05;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .jasa-page .banner__cta {
        display: inline-flex;
        align-items: center;
        height: 39px;
        margin-top: 24px;
        padding: 0 20px;
        border: 1px solid var(--color-white);
        border-radius: var(--radius-pill);
        font-size: 13px;
        font-weight: 500;
        color: var(--color-white);
        text-decoration: none;
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .jasa-page .banner__cta:hover {
        background-color: var(--color-white);
        color: var(--color-primary);
    }

    /* ---------- FILTER BAR + SEARCH ---------- */
    .jasa-page .filter {
        display: flex;
        flex-direction: column;
        gap: 12px;
        max-width: 966px;
        margin: 35px auto 0;
    }

    .jasa-page .filter-tabs {
        display: flex;
        gap: 28px;
        overflow-x: auto;
        padding-left: 4px;
        white-space: nowrap;
        font-size: 14px;
        font-weight: 700;
    }

    .jasa-page .filter-pill-btn {
        background: none;
        border: none;
        padding: 2px 0;
        font: inherit;
        font-size: 14px;
        font-weight: 700;
        color: var(--color-text);
        text-decoration: none;
        cursor: pointer;
        transition: color 0.2s ease;
        user-select: none;
    }

    .jasa-page .filter-pill-btn:hover {
        color: var(--color-primary);
    }

    .jasa-page .filter-pill-btn.active {
        color: var(--color-accent-blue);
    }

    .jasa-page .search {
        position: relative;
        width: 100%;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .jasa-page .search__icon {
        position: absolute;
        top: 50%;
        left: 10px;
        width: 14px;
        height: 14px;
        transform: translateY(-50%);
        pointer-events: none;
        z-index: 1;
    }

    .jasa-page .search__input {
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

    .jasa-page .search__input:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(10, 74, 166, 0.15);
    }

    .jasa-page .search__input::placeholder {
        color: #777777;
        opacity: 1;
    }

    /* Tombol "Cari" — dipertahankan dari desain lama, memakai token desain Produk */
    .jasa-page .search__btn {
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

    .jasa-page .search__btn:hover {
        background: var(--color-primary-dark);
        transform: translateY(-1px);
    }

    /* ---------- SERVICE GRID ---------- */
    .jasa-page .jasa-services {
        max-width: 1005px;
        margin: 44px auto 0;
    }

    .jasa-page .jasa-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 32px 16px;
    }

    .jasa-page .jasa-card {
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

    .jasa-page .jasa-card:hover,
    .jasa-page .jasa-card:focus-visible {
        transform: translateY(-4px);
        box-shadow: 0 18px 30px -12px rgba(15, 23, 42, 0.18);
    }

    .jasa-page .jasa-thumb {
        position: relative;
        width: 100%;
        aspect-ratio: 7 / 8;
        overflow: hidden;
        background: var(--color-placeholder);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .jasa-page .jasa-thumb-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.45s ease;
    }

    .jasa-page .jasa-card:hover .jasa-thumb-img {
        transform: scale(1.05);
    }

    .jasa-page .jasa-thumb-fallback {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        color: var(--color-white);
        position: relative;
    }

    .jasa-page .thumb-pattern,
    .modal-hero-thumb .thumb-pattern {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.15) 1px, transparent 1px);
        background-size: 16px 16px;
    }

    .jasa-page .thumb-dept-badge {
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

    .jasa-page .thumb-status-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        font-size: 10px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: var(--radius-pill);
        background: rgba(16, 185, 129, 0.9);
        color: var(--color-white);
        backdrop-filter: blur(6px);
        z-index: 2;
    }

    .jasa-page .jasa-content {
        padding: 14px 14px 16px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .jasa-page .jasa-card-title {
        font-size: 14.5px;
        font-weight: 700;
        color: var(--color-text);
        line-height: 1.4;
        margin-bottom: 6px;
        transition: color 0.2s ease;
    }

    .jasa-page .jasa-card:hover .jasa-card-title {
        color: var(--color-primary);
    }

    .jasa-page .jasa-card-desc {
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

    .jasa-page .jasa-price-box {
        font-size: 16px;
        font-weight: 800;
        color: var(--color-primary);
        margin-bottom: 12px;
    }

    .jasa-page .jasa-card-footer {
        padding-top: 12px;
        border-top: 1px solid #f1f5f9;
    }

    .jasa-page .btn-card-consult {
        width: 100%;
        background: var(--color-primary);
        color: var(--color-white);
        border: none;
        padding: 10px 14px;
        border-radius: var(--radius-pill);
        font-weight: 700;
        font-size: 12.5px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: background-color 0.2s ease, transform 0.2s ease;
    }

    .jasa-page .btn-card-consult:hover {
        background: var(--color-primary-dark);
        transform: translateY(-1px);
    }

    /* ---------- EMPTY STATES ---------- */
    .jasa-page .jasa-empty {
        grid-column: 1 / -1;
        text-align: center;
        padding: 70px 20px;
        background: var(--color-white);
        border-radius: var(--radius-card);
        border: 1.5px dashed var(--color-placeholder);
    }

    .jasa-page .jasa-empty__icon {
        font-size: 52px;
        margin-bottom: 14px;
    }

    .jasa-page .jasa-empty__title {
        font-size: 19px;
        font-weight: 800;
        color: var(--color-text);
        margin-bottom: 8px;
    }

    .jasa-page .jasa-empty__text {
        color: var(--color-muted);
        font-size: 14.5px;
        max-width: 480px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .jasa-page .jasa-empty__reset {
        display: inline-block;
        margin-top: 16px;
        background: var(--color-primary);
        color: var(--color-white);
        padding: 9px 20px;
        border-radius: var(--radius-pill);
        font-size: 13.5px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .jasa-page .jasa-empty__reset:hover {
        background: var(--color-primary-dark);
    }

    /* ---------- MODAL DETAIL LAYANAN (visual saja) ---------- */
    .jasa-modal-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background-color: rgba(17, 17, 17, 0.7);
        backdrop-filter: blur(6px);
        z-index: 99999;
        overflow-y: auto;
        padding: 24px 16px;
        place-items: center;
        animation: modalFadeIn 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes modalFadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .jasa-modal-container {
        background: var(--color-white, #ffffff);
        border-radius: 20px;
        max-width: 680px;
        width: 100%;
        box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.35);
        border: 1px solid #e6eaef;
        overflow: hidden;
        position: relative;
        animation: modalSlideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        margin: auto;
    }

    @keyframes modalSlideUp {
        from { transform: scale(0.96) translateY(20px); opacity: 0; }
        to { transform: scale(1) translateY(0); opacity: 1; }
    }

    .modal-close-btn {
        position: absolute;
        top: 16px;
        right: 16px;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: rgba(17, 17, 17, 0.6);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 20px;
        line-height: 1;
        transition: all 0.2s ease;
        backdrop-filter: blur(4px);
        z-index: 10;
    }

    .modal-close-btn:hover {
        background: var(--color-red, #e53935);
        transform: rotate(90deg);
    }

    .modal-hero-thumb {
        height: 230px;
        width: 100%;
        position: relative;
        background: var(--color-placeholder, #cad2db);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .modal-hero-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .modal-hero-thumb .jasa-thumb-fallback {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 72px;
        color: #ffffff;
        position: relative;
    }

    .modal-body {
        padding: 28px 30px;
        color: var(--color-text, #111111);
    }

    .modal-serv-title {
        font-size: 24px;
        font-weight: 900;
        color: var(--color-primary, #0a4aa6);
        line-height: 1.3;
        margin-bottom: 12px;
    }

    .modal-serv-price {
        font-size: 24px;
        font-weight: 900;
        color: var(--color-primary, #0a4aa6);
        margin-bottom: 18px;
    }

    .modal-serv-desc-box {
        background: var(--color-bg, #fafafa);
        border-radius: 14px;
        padding: 16px 18px;
        border: 1px solid #e6eaef;
        font-size: 14px;
        color: #334155;
        line-height: 1.65;
        margin-bottom: 22px;
        max-height: 180px;
        overflow-y: auto;
    }

    .modal-wa-notice {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 13px;
        color: #166534;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 22px;
    }

    .modal-actions-row {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .modal-btn-wa-order {
        flex: 1;
        background: #10b981;
        color: #ffffff;
        text-decoration: none;
        padding: 13px 20px;
        border-radius: 12px;
        font-weight: 800;
        font-size: 14.5px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);
        transition: all 0.2s ease;
    }

    .modal-btn-wa-order:hover {
        background: #059669;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(16, 185, 129, 0.4);
    }

    .modal-btn-close-sec {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
        padding: 13px 22px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .modal-btn-close-sec:hover {
        background: #e2e8f0;
        color: #111111;
    }

    /* ---------- BREAKPOINT >= 640px (tablet) ---------- */
    @media (min-width: 640px) {
        .jasa-page .banner {
            min-height: 260px;
        }
        .jasa-page .filter {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }
        .jasa-page .filter-tabs {
            overflow: visible;
            gap: 40px;
        }
        .jasa-page .search {
            width: 314px;
            flex: none;
        }
        .jasa-page .jasa-grid {
            grid-template-columns: repeat(3, 1fr);
            column-gap: 24px;
            row-gap: 40px;
        }
    }

    /* ---------- BREAKPOINT >= 1024px (desktop) ---------- */
    @media (min-width: 1024px) {
        .jasa-page .jasa-main {
            padding-top: 56px;
        }
        .jasa-page .banner {
            height: 310px;
        }
        .jasa-page .filter-tabs {
            gap: 50px;
        }
        .jasa-page .jasa-grid {
            grid-template-columns: repeat(4, 1fr);
            column-gap: 28px;
            row-gap: 44px;
        }
    }
</style>

<div class="jasa-page">
    <main class="jasa-main">
        <!-- ================= BANNER ================= -->
        <section class="banner" aria-labelledby="banner-title">
            <div class="banner__content">
                <h1 class="banner__title" id="banner-title">Layanan Jasa Kejuruan TeFa</h1>
                <a href="#jasa-items-grid" class="banner__cta">Lihat Selengkapnya</a>
            </div>
        </section>

        <!-- ================= FILTER BAR + SEARCH ================= -->
        <div class="filter">
            @php
                $aktifJurusan = request('jurusan', 'all');
                $qSekarang = request('q');
                $tabJasaUrl = fn (string $tab) => route('jasa', array_filter([
                    'jurusan' => $tab === 'all' ? null : $tab,
                    'q' => $qSekarang,
                ], fn ($value) => $value !== null && $value !== ''));
            @endphp
            <nav aria-label="Filter program keahlian">
                <div class="filter-tabs">
                    <a href="{{ $tabJasaUrl('all') }}" class="filter-pill-btn{{ $aktifJurusan === 'all' ? ' active' : '' }}">Semua</a>
                    <a href="{{ $tabJasaUrl('RPL') }}" class="filter-pill-btn{{ $aktifJurusan === 'RPL' ? ' active' : '' }}">RPL</a>
                    <a href="{{ $tabJasaUrl('DKV') }}" class="filter-pill-btn{{ $aktifJurusan === 'DKV' ? ' active' : '' }}">DKV</a>
                    <a href="{{ $tabJasaUrl('TKJ') }}" class="filter-pill-btn{{ $aktifJurusan === 'TKJ' ? ' active' : '' }}">TKJ</a>
                    <a href="{{ $tabJasaUrl('GIM') }}" class="filter-pill-btn{{ $aktifJurusan === 'GIM' ? ' active' : '' }}">GIM</a>
                    <a href="{{ $tabJasaUrl('PSPT') }}" class="filter-pill-btn{{ $aktifJurusan === 'PSPT' ? ' active' : '' }}">PSPT</a>
                    <a href="{{ $tabJasaUrl('ANIMASI') }}" class="filter-pill-btn{{ $aktifJurusan === 'ANIMASI' ? ' active' : '' }}">ANIMASI</a>
                </div>
            </nav>

            <form class="search" role="search" method="GET" action="{{ route('jasa') }}" id="jasa-search-form">
                @if(request()->filled('jurusan') && request('jurusan') !== 'all')
                    <input type="hidden" name="jurusan" value="{{ request('jurusan') }}">
                @endif
                <svg class="search__icon" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <circle cx="10.5" cy="10.5" r="7"/>
                    <line x1="16" y1="16" x2="21.5" y2="21.5"/>
                </svg>
                <label class="visually-hidden" for="jasa-search-input">Cari di katalog layanan jasa</label>
                <input class="search__input"
                       id="jasa-search-input"
                       name="q"
                       type="search"
                       value="{{ request('q') }}"
                       placeholder="Cari di katalog layanan jasa..."
                       oninput="debounceJasaSearch()" />
                <button type="submit" class="search__btn">Cari</button>
            </form>
        </div>

        <!-- ================= SERVICE GRID ================= -->
        <section class="jasa-services" aria-label="Daftar layanan jasa">
            <div class="jasa-grid" id="jasa-items-grid">
                @forelse($services as $service)
                    @php
                        $rawKode = strtoupper($service->department->kode ?? 'RPL');
                        $deptKode = match(true) {
                            str_contains($rawKode, 'ANI') => 'ANIMASI',
                            str_contains($rawKode, 'GIM') => 'GIM',
                            str_contains($rawKode, 'RPL') => 'RPL',
                            str_contains($rawKode, 'TKJ') => 'TKJ',
                            str_contains($rawKode, 'DKV') => 'DKV',
                            str_contains($rawKode, 'PSPT') || str_contains($rawKode, 'PSTV') => 'PSPT',
                            default => 'TEFA',
                        };
                        $deptNama = $service->department->nama_jurusan ?? 'Unit Teaching Factory';

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
                            default => '🤝',
                        };

                        $hasRealPhoto = !empty($service->foto) && !str_starts_with($service->foto, 'http') && file_exists(public_path('storage/' . $service->foto));
                        $photoUrl = $hasRealPhoto ? asset('storage/' . $service->foto) : null;

                        $waMessage = urlencode("Halo Admin Teaching Factory SMKN 4 Tanjungpinang, saya tertarik untuk berkonsultasi mengenai layanan jasa: {$service->nama_layanan} ({$deptNama}). Mohon info prosedur dan jadwalnya.");
                        $waUrl = "https://wa.me/628781948317?text={$waMessage}";

                        $serviceModalData = [
                            'id' => $service->id,
                            'nama' => $service->nama_layanan,
                            'jurusan' => $deptNama,
                            'jurusan_code' => $deptKode,
                            'badge_style' => $badgeStyle,
                            'fallback_bg' => $fallbackBg,
                            'fallback_icon' => $fallbackIcon,
                            'harga' => 'Mulai Rp ' . number_format($service->estimasi_harga, 0, ',', '.'),
                            'deskripsi' => $service->deskripsi,
                            'foto' => $photoUrl,
                            'wa_url' => $waUrl,
                        ];
                    @endphp

                    <div class="jasa-card"
                         data-id="{{ $service->id }}"
                         onclick='openServiceModal(@json($serviceModalData))'>

                        <!-- Thumbnail -->
                        <div class="jasa-thumb">
                            @if($photoUrl)
                                <img src="{{ $photoUrl }}"
                                     alt="{{ $service->nama_layanan }}"
                                     class="jasa-thumb-img"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="jasa-thumb-fallback" style="background: {{ $fallbackBg }}; display: none;">
                                    <div class="thumb-pattern"></div>
                                    <span>{{ $fallbackIcon }}</span>
                                </div>
                            @else
                                <div class="jasa-thumb-fallback" style="background: {{ $fallbackBg }};">
                                    <div class="thumb-pattern"></div>
                                    <span>{{ $fallbackIcon }}</span>
                                </div>
                            @endif

                            <!-- Department Badge -->
                            <span class="thumb-dept-badge" style="{{ $badgeStyle }}">
                                {{ $deptKode }}
                            </span>

                            <!-- Status Badge -->
                            <span class="thumb-status-badge">
                                Tersedia
                            </span>
                        </div>

                        <!-- Content -->
                        <div class="jasa-content">
                            <h3 class="jasa-card-title">
                                {{ $service->nama_layanan }}
                            </h3>

                            <p class="jasa-card-desc">
                                {{ $service->deskripsi ?: 'Solusi layanan jasa kejuruan terpercaya hasil bimbingan guru dan instruktur TEFA SMKN 4 Tanjungpinang.' }}
                            </p>

                            <div class="jasa-price-box">
                                Mulai Rp {{ number_format($service->estimasi_harga, 0, ',', '.') }}
                            </div>

                            <div class="jasa-card-footer">
                                <button type="button" class="btn-card-consult">
                                    <span aria-hidden="true">💬</span>
                                    <span>Lihat Detail &amp; Konsultasi</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    @if(request()->filled('q') || (request()->filled('jurusan') && request('jurusan') !== 'all'))
                        <div class="jasa-empty">
                            <div class="jasa-empty__icon" aria-hidden="true">🔍</div>
                            <h3 class="jasa-empty__title">Layanan Tidak Ditemukan</h3>
                            <p class="jasa-empty__text">
                                Tidak ada layanan jasa yang sesuai dengan kata kunci atau jurusan yang Anda pilih.
                            </p>
                            <a href="{{ route('jasa') }}" class="jasa-empty__reset">Reset Filter &amp; Tampilkan Semua</a>
                        </div>
                    @else
                        <div class="jasa-empty">
                            <div class="jasa-empty__icon" aria-hidden="true">🤝</div>
                            <h3 class="jasa-empty__title">Belum Ada Layanan Jasa</h3>
                            <p class="jasa-empty__text">
                                Saat ini belum ada data layanan jasa yang aktif. Silakan kembali lagi nanti atau hubungi unit produksi sekolah.
                            </p>
                        </div>
                    @endif
                @endforelse
            </div>

            {{-- Pagination 12/halaman; view global vendor/pagination/custom, hanya render bila punya halaman --}}
            {{ $services->links() }}
        </section>
    </main>
</div>

<!-- ========================================== -->
<!-- MODAL POP-UP DETAIL LAYANAN JASA DINAMIS   -->
<!-- (struktur & logika dipertahankan, visual saja) -->
<!-- ========================================== -->
<div id="service-detail-modal" class="jasa-modal-backdrop" onclick="handleServiceBackdropClick(event)">
    <div class="jasa-modal-container">
        <!-- Close Button -->
        <button type="button" class="modal-close-btn" onclick="closeServiceModal()" aria-label="Tutup Modal">
            &times;
        </button>

        <!-- Modal Hero Thumbnail -->
        <div class="modal-hero-thumb">
            <img id="modal-serv-img" src="" alt="Foto Layanan" class="modal-hero-img" style="display: none;">
            <div id="modal-serv-fallback" class="jasa-thumb-fallback" style="display: flex;">
                <div class="thumb-pattern"></div>
                <span id="modal-serv-icon" style="font-size: 72px;">🤝</span>
            </div>
        </div>

        <!-- Konten Detail Jasa -->
        <div class="modal-body">
            <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap; margin-bottom: 10px;">
                <span id="modal-serv-jurusan" style="font-size: 12px; font-weight: 700; padding: 4px 12px; border-radius: 999px;">
                    Jurusan
                </span>
                <span style="font-size: 12px; font-weight: 800; color: #059669; background: #ecfdf5; border: 1px solid #a7f3d0; padding: 4px 12px; border-radius: 999px;">
                    Dikerjakan Siswa & Instruktur TEFA
                </span>
            </div>

            <h2 id="modal-serv-name" class="modal-serv-title">
                Nama Layanan
            </h2>

            <div id="modal-serv-price" class="modal-serv-price">
                Mulai Rp 0
            </div>

            <div style="margin-bottom: 8px;">
                <label style="font-size: 12px; font-weight: 700; color: #666666; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">
                    Deskripsi Lengkap & Lingkup Layanan
                </label>
                <div id="modal-serv-desc" class="modal-serv-desc-box">
                    Deskripsi layanan jasa...
                </div>
            </div>

            <div class="modal-wa-notice">
                <span style="font-size: 20px;" aria-hidden="true">💬</span>
                <span><strong>Konsultasi Langsung via WhatsApp:</strong> Tim instruktur dan admin jurusan TEFA akan mendiskusikan kebutuhan spesifikasi, estimasi waktu, serta penugasan siswa untuk proyek Anda.</span>
            </div>

            <!-- Footer & Tombol Aksi -->
            <div class="modal-actions-row">
                <button type="button" class="modal-btn-close-sec" onclick="closeServiceModal()">
                    Tutup
                </button>
                <a id="modal-serv-wa-btn" href="#" target="_blank" class="modal-btn-wa-order">
                    <span aria-hidden="true">💬</span>
                    <span>Konsultasi via WhatsApp</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Filter jurusan & pencarian kini lewat query string (?jurusan=&q=) supaya
    // selamat saat berpindah halaman paginator. Pencarian submit otomatis 400ms
    // setelah mengetik agar terasa seperti filter langsung seperti sebelumnya.
    let jasaSearchTimer = null;

    function debounceJasaSearch() {
        clearTimeout(jasaSearchTimer);
        jasaSearchTimer = setTimeout(() => {
            const form = document.getElementById('jasa-search-form');
            if (form) form.submit();
        }, 400);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('jasa-search-input');
        if (input && input.value) {
            input.focus();
            input.setSelectionRange(input.value.length, input.value.length);
        }
    });

    // Modal Interaction
    function openServiceModal(service) {
        const modal = document.getElementById('service-detail-modal');
        if (!modal) return;

        const imgEl = document.getElementById('modal-serv-img');
        const fallbackEl = document.getElementById('modal-serv-fallback');
        const iconEl = document.getElementById('modal-serv-icon');

        if (service.foto) {
            imgEl.src = service.foto;
            imgEl.style.display = 'block';
            fallbackEl.style.display = 'none';
        } else {
            imgEl.style.display = 'none';
            fallbackEl.style.display = 'flex';
            fallbackEl.style.background = service.fallback_bg;
            iconEl.textContent = service.fallback_icon;
        }

        const deptEl = document.getElementById('modal-serv-jurusan');
        deptEl.textContent = service.jurusan + ' (' + service.jurusan_code + ')';
        deptEl.setAttribute('style', service.badge_style + '; font-size: 12px; font-weight: 700; padding: 4px 12px; border-radius: 999px;');

        document.getElementById('modal-serv-name').textContent = service.nama;
        document.getElementById('modal-serv-price').textContent = service.harga;
        document.getElementById('modal-serv-desc').textContent = service.deskripsi || 'Tidak ada keterangan detail untuk layanan ini.';

        const waBtn = document.getElementById('modal-serv-wa-btn');
        if (waBtn) {
            waBtn.href = service.wa_url;
        }

        modal.style.display = 'grid';
        document.body.style.overflow = 'hidden';
    }

    function closeServiceModal() {
        const modal = document.getElementById('service-detail-modal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    function handleServiceBackdropClick(e) {
        if (e.target.id === 'service-detail-modal') {
            closeServiceModal();
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeServiceModal();
        }
    });
</script>
@endsection
