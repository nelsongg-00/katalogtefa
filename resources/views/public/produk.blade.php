@extends('layouts.public')

@section('title', 'Katalog Produk — TEFA SMKN 4 Tanjungpinang')

@section('content')
<style>
    /* ==========================================================
       PRODUK PAGE — restyle sesuai desain baru.
       Token di-scope ke .produk-page (bukan :root) supaya tidak
       bocor ke halaman lain. Semua selector di-prefix .produk-page
       agar menang spesifisitas atas aturan global di layouts/public.
       ========================================================== */
    .produk-page {
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
        --font-display: 'Open Sauce Sans', 'Anton', 'Impact', 'Arial Narrow', sans-serif;

        background-color: var(--color-bg);
        color: var(--color-text);
        padding-bottom: 90px;
    }

    .produk-page .visually-hidden {
        position: absolute;
        width: 1px;
        height: 1px;
        overflow: hidden;
        clip: rect(0 0 0 0);
        white-space: nowrap;
    }

    .produk-page .produk-main {
        padding: 32px 16px 0;
    }

    /* ---------- BANNER ---------- */
    .produk-page .banner {
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

    .produk-page .banner__content {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 24px 16px;
        text-align: center;
    }

    .produk-page .banner__title {
        font-family: var(--font-display);
        /* Dikecilkan ~20% dari clamp(2.25rem, 7vw, 4.25rem) */
        font-size: clamp(1.8rem, 5.6vw, 3.4rem);
        /* Anton (font display lama) berbobot berat pada 400 — Open Sauce Sans perlu
           bobot eksplisit agar judul banner tetap setebal sebelumnya. */
        font-weight: 800;
        line-height: 1.05;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .produk-page .banner__cta {
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

    .produk-page .banner__cta:hover {
        background-color: var(--color-white);
        color: var(--color-primary);
    }

    /* ---------- FILTER BAR + SEARCH ---------- */
    .produk-page .filter {
        display: flex;
        flex-direction: column;
        gap: 12px;
        max-width: 966px;
        margin: 35px auto 0;
    }

    .produk-page .filter-tabs {
        display: flex;
        gap: 28px;
        overflow-x: auto;
        padding-left: 4px;
        white-space: nowrap;
        font-size: 14px;
        font-weight: 700;
    }

    .produk-page .filter-pill-btn {
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

    .produk-page .filter-pill-btn:hover {
        color: var(--color-primary);
    }

    .produk-page .filter-pill-btn.active {
        color: var(--color-accent-blue);
    }

    .produk-page .search {
        position: relative;
        width: 100%;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .produk-page .search__icon {
        position: absolute;
        top: 50%;
        left: 10px;
        width: 14px;
        height: 14px;
        transform: translateY(-50%);
        pointer-events: none;
        z-index: 1;
    }

    .produk-page .search__input {
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

    .produk-page .search__input:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(10, 74, 166, 0.15);
    }

    .produk-page .search__input::placeholder {
        color: #777777;
        opacity: 1;
    }

    /* Tombol "Cari" — mengikuti pola halaman Layanan Jasa (public/jasa.blade.php) */
    .produk-page .search__btn {
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

    .produk-page .search__btn:hover {
        background: var(--color-primary-dark);
        transform: translateY(-1px);
    }

    /* ---------- PRODUCT GRID ---------- */
    .produk-page .products {
        max-width: 1005px;
        margin: 44px auto 0;
    }

    .produk-page .produk-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 32px 16px;
    }

    .produk-page .produk-card {
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

    .produk-page .produk-card:hover,
    .produk-page .produk-card:focus-visible {
        transform: translateY(-4px);
        box-shadow: 0 18px 30px -12px rgba(15, 23, 42, 0.18);
    }

    .produk-page .produk-thumb {
        position: relative;
        width: 100%;
        aspect-ratio: 7 / 8;
        overflow: hidden;
        background: var(--color-placeholder);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .produk-page .produk-thumb-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.45s ease;
    }

    .produk-page .produk-card:hover .produk-thumb-img {
        transform: scale(1.05);
    }

    .produk-page .produk-thumb-fallback {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        color: var(--color-white);
        position: relative;
    }

    .produk-page .thumb-pattern {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.15) 1px, transparent 1px);
        background-size: 16px 16px;
    }

    .produk-page .thumb-dept-badge {
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

    .produk-page .thumb-stock-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        font-size: 10px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: var(--radius-pill);
        backdrop-filter: blur(6px);
        z-index: 2;
    }

    .produk-page .stock-available {
        background: rgba(16, 185, 129, 0.9);
        color: var(--color-white);
    }

    .produk-page .stock-empty {
        background: rgba(220, 38, 38, 0.9);
        color: var(--color-white);
    }

    .produk-page .produk-content {
        padding: 14px 14px 16px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .produk-page .produk-card-title {
        font-size: 14.5px;
        font-weight: 700;
        color: var(--color-text);
        line-height: 1.4;
        margin-bottom: 6px;
        transition: color 0.2s ease;
    }

    .produk-page .produk-card:hover .produk-card-title {
        color: var(--color-primary);
    }

    .produk-page .produk-card-desc {
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

    .produk-page .produk-price-box {
        font-size: 16px;
        font-weight: 800;
        color: var(--color-primary);
        margin-bottom: 12px;
    }

    .produk-page .produk-card-footer {
        padding-top: 12px;
        border-top: 1px solid #f1f5f9;
    }

    .produk-page .btn-card-order {
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

    .produk-page .btn-card-order:hover {
        background: var(--color-primary-dark);
        transform: translateY(-1px);
    }

    /* ---------- EMPTY STATES ---------- */
    .produk-page .produk-empty {
        grid-column: 1 / -1;
        text-align: center;
        padding: 70px 20px;
        background: var(--color-white);
        border-radius: var(--radius-card);
        border: 1.5px dashed var(--color-placeholder);
    }

    .produk-page .produk-empty__icon {
        font-size: 52px;
        margin-bottom: 14px;
    }

    .produk-page .produk-empty__title {
        font-size: 19px;
        font-weight: 800;
        color: var(--color-text);
        margin-bottom: 8px;
    }

    .produk-page .produk-empty__text {
        color: var(--color-muted);
        font-size: 14.5px;
        max-width: 480px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .produk-page .produk-empty__reset {
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

    .produk-page .produk-empty__reset:hover {
        background: var(--color-primary-dark);
    }

    /* ---------- BREAKPOINT >= 640px (tablet) ---------- */
    @media (min-width: 640px) {
        .produk-page .banner {
            min-height: 260px;
        }
        .produk-page .filter {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }
        .produk-page .filter-tabs {
            overflow: visible;
            gap: 40px;
        }
        .produk-page .search {
            width: 314px;
            flex: none;
        }
        .produk-page .produk-grid {
            grid-template-columns: repeat(3, 1fr);
            column-gap: 24px;
            row-gap: 40px;
        }
    }

    /* ---------- BREAKPOINT >= 1024px (desktop) ---------- */
    @media (min-width: 1024px) {
        .produk-page .produk-main {
            padding-top: 56px;
        }
        .produk-page .banner {
            height: 310px;
        }
        .produk-page .filter-tabs {
            gap: 50px;
        }
        .produk-page .produk-grid {
            grid-template-columns: repeat(4, 1fr);
            column-gap: 28px;
            row-gap: 44px;
        }
    }
</style>

<div class="produk-page">
    <main class="produk-main">
        <!-- ================= BANNER ================= -->
        <section class="banner" aria-labelledby="banner-title">
            <div class="banner__content">
                <h1 class="banner__title" id="banner-title">Katalog Produk TEFA</h1>
                <a href="#produk-items-grid" class="banner__cta">Lihat Selengkapnya</a>
            </div>
        </section>

        <!-- ================= FILTER BAR + SEARCH ================= -->
        <div class="filter">
            @php
                $aktifJurusan = request('jurusan', 'all');
                $qSekarang = request('q');
                $tabProdukUrl = fn (string $tab) => route('produk', array_filter([
                    'jurusan' => $tab === 'all' ? null : $tab,
                    'q' => $qSekarang,
                ], fn ($value) => $value !== null && $value !== ''));
            @endphp
            <nav aria-label="Filter program keahlian">
                <div class="filter-tabs">
                    <a href="{{ $tabProdukUrl('all') }}" class="filter-pill-btn{{ $aktifJurusan === 'all' ? ' active' : '' }}">Semua</a>
                    <a href="{{ $tabProdukUrl('RPL') }}" class="filter-pill-btn{{ $aktifJurusan === 'RPL' ? ' active' : '' }}">RPL</a>
                    <a href="{{ $tabProdukUrl('DKV') }}" class="filter-pill-btn{{ $aktifJurusan === 'DKV' ? ' active' : '' }}">DKV</a>
                    <a href="{{ $tabProdukUrl('TKJ') }}" class="filter-pill-btn{{ $aktifJurusan === 'TKJ' ? ' active' : '' }}">TKJ</a>
                    <a href="{{ $tabProdukUrl('GIM') }}" class="filter-pill-btn{{ $aktifJurusan === 'GIM' ? ' active' : '' }}">GIM</a>
                    <a href="{{ $tabProdukUrl('PSPT') }}" class="filter-pill-btn{{ $aktifJurusan === 'PSPT' ? ' active' : '' }}">PSPT</a>
                    <a href="{{ $tabProdukUrl('ANIMASI') }}" class="filter-pill-btn{{ $aktifJurusan === 'ANIMASI' ? ' active' : '' }}">ANIMASI</a>
                </div>
            </nav>

            <form class="search" role="search" method="GET" action="{{ route('produk') }}" id="produk-search-form">
                @if(request()->filled('jurusan') && request('jurusan') !== 'all')
                    <input type="hidden" name="jurusan" value="{{ request('jurusan') }}">
                @endif
                <svg class="search__icon" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <circle cx="10.5" cy="10.5" r="7"/>
                    <line x1="16" y1="16" x2="21.5" y2="21.5"/>
                </svg>
                <label class="visually-hidden" for="produk-search-input">Cari di katalog produk</label>
                <input class="search__input"
                       id="produk-search-input"
                       name="q"
                       type="search"
                       value="{{ request('q') }}"
                       placeholder="Cari di katalog produk..."
                       oninput="debounceProdukSearch()" />
                <button type="submit" class="search__btn">Cari</button>
            </form>
        </div>

        <!-- ================= PRODUCT GRID ================= -->
        <section class="products" aria-label="Daftar produk">
            <div class="produk-grid" id="produk-items-grid">
                @forelse($products as $product)
                    @php
                        $rawKode = strtoupper($product->jurusan->kode ?? 'RPL');
                        $deptKode = match(true) {
                            str_contains($rawKode, 'ANI') => 'ANIMASI',
                            str_contains($rawKode, 'GIM') => 'GIM',
                            str_contains($rawKode, 'RPL') => 'RPL',
                            str_contains($rawKode, 'TKJ') => 'TKJ',
                            str_contains($rawKode, 'DKV') => 'DKV',
                            str_contains($rawKode, 'PSPT') || str_contains($rawKode, 'PSTV') => 'PSPT',
                            default => 'TEFA',
                        };
                        $deptNama = $product->jurusan->nama_jurusan ?? 'Unit Teaching Factory';

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
                            default => '📦',
                        };

                        $hasRealPhoto = !empty($product->foto) && !str_starts_with($product->foto, 'http') && file_exists(public_path('storage/' . $product->foto));
                        $photoUrl = $hasRealPhoto ? asset('storage/' . $product->foto) : null;

                    @endphp

                    <a href="{{ route('produk.show', $product) }}" class="produk-card" data-id="{{ $product->id }}">

                        <!-- Thumbnail -->
                        <div class="produk-thumb">
                            @if($photoUrl)
                                <img src="{{ $photoUrl }}"
                                     alt="{{ $product->nama_produk }}"
                                     class="produk-thumb-img"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="produk-thumb-fallback" style="background: {{ $fallbackBg }}; display: none;">
                                    <div class="thumb-pattern"></div>
                                    <span>{{ $fallbackIcon }}</span>
                                </div>
                            @else
                                <div class="produk-thumb-fallback" style="background: {{ $fallbackBg }};">
                                    <div class="thumb-pattern"></div>
                                    <span>{{ $fallbackIcon }}</span>
                                </div>
                            @endif

                            <!-- Department Badge -->
                            <span class="thumb-dept-badge" style="{{ $badgeStyle }}">
                                {{ $deptKode }}
                            </span>

                            <!-- Stock Badge -->
                            <span class="thumb-stock-badge {{ $product->stok > 0 ? 'stock-available' : 'stock-empty' }}">
                                {{ $product->stok > 0 ? 'Stok: ' . $product->stok : 'Habis' }}
                            </span>
                        </div>

                        <!-- Content -->
                        <div class="produk-content">
                            <h3 class="produk-card-title">
                                {{ $product->nama_produk }}
                            </h3>

                            <p class="produk-card-desc">
                                {{ $product->deskripsi ?: 'Produk hasil karya unit Teaching Factory kejuruan SMKN 4 Tanjungpinang.' }}
                            </p>

                            <div class="produk-price-box">
                                Rp {{ number_format($product->harga, 0, ',', '.') }}
                            </div>

                            <div class="produk-card-footer">
                                <span class="btn-card-order">
                                    <span aria-hidden="true">🛒</span>
                                    <span>Lihat Detail &amp; Pesan</span>
                                </span>
                            </div>
                        </div>
                    </a>
                @empty
                    @if(request()->filled('q') || (request()->filled('jurusan') && request('jurusan') !== 'all'))
                        <div class="produk-empty">
                            <div class="produk-empty__icon" aria-hidden="true">🔍</div>
                            <h3 class="produk-empty__title">Produk Tidak Ditemukan</h3>
                            <p class="produk-empty__text">
                                Tidak ada produk yang sesuai dengan kata kunci atau jurusan yang Anda pilih.
                            </p>
                            <a href="{{ route('produk') }}" class="produk-empty__reset">Reset Filter &amp; Tampilkan Semua</a>
                        </div>
                    @else
                        <div class="produk-empty">
                            <div class="produk-empty__icon" aria-hidden="true">📦</div>
                            <h3 class="produk-empty__title">Belum Ada Produk Tersedia</h3>
                            <p class="produk-empty__text">
                                Saat ini katalog produk fisik sedang dalam pembaruan inventaris. Silakan kembali lagi nanti atau hubungi unit produksi sekolah.
                            </p>
                        </div>
                    @endif
                @endforelse
            </div>

            {{-- Pagination 12/halaman; view global vendor/pagination/custom, hanya render bila punya halaman --}}
            {{ $products->links() }}
        </section>
    </main>
</div>

@endsection
