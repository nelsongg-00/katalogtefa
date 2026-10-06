@extends('layouts.public')

@section('title', $product->nama_produk . ' — Katalog Produk TEFA SMKN 4 Tanjungpinang')

@section('content')
<style>
    /* ==========================================================
       DETAIL PRODUK PAGE — restyle mengikuti desain halaman Katalog
       Produk (resources/views/public/produk.blade.php = source of
       truth). Token identik, di-scope ke .produk-detail-page agar
       tidak bocor ke halaman lain.
       ========================================================== */
    .produk-detail-page {
        --color-primary: #0a4aa6;
        --color-primary-dark: #00357f;
        --color-accent-blue: #1414c8;
        --color-text: #111111;
        --color-muted: #666666;
        --color-bg: #fafafa;
        --color-white: #ffffff;
        --color-red: #e53935;
        --color-placeholder: #cad2db;
        --radius-card: 15px;
        --radius-pill: 999px;

        background-color: var(--color-bg);
        color: var(--color-text);
        padding-bottom: 90px;
    }

    .produk-detail-page .visually-hidden {
        position: absolute;
        width: 1px;
        height: 1px;
        overflow: hidden;
        clip: rect(0 0 0 0);
        white-space: nowrap;
    }

    .produk-detail-page .detail-main {
        padding: 28px 16px 0;
    }

    /* ---------- BREADCRUMB ---------- */
    .produk-detail-page .breadcrumb {
        max-width: 966px;
        margin: 0 auto 28px;
        font-size: 13px;
        color: var(--color-muted);
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .produk-detail-page .breadcrumb a {
        color: var(--color-primary);
        font-weight: 700;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .produk-detail-page .breadcrumb a:hover {
        color: var(--color-primary-dark);
        text-decoration: underline;
    }

    .produk-detail-page .breadcrumb .breadcrumb-sep {
        color: var(--color-placeholder);
        user-select: none;
    }

    .produk-detail-page .breadcrumb .breadcrumb-current {
        font-weight: 700;
        color: var(--color-text);
    }

    /* ---------- DETAIL CARD ---------- */
    .produk-detail-page .detail-card {
        display: flex;
        flex-direction: column;
        max-width: 966px;
        margin: 0 auto;
        background: var(--color-white);
        border: 1px solid #e6eaef;
        border-radius: var(--radius-card);
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.05);
    }

    .produk-detail-page .detail-thumb {
        position: relative;
        width: 100%;
        aspect-ratio: 16 / 9;
        overflow: hidden;
        background: var(--color-placeholder);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .produk-detail-page .detail-thumb-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .produk-detail-page .detail-thumb-fallback {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 88px;
        color: var(--color-white);
        position: relative;
    }

    .produk-detail-page .thumb-pattern {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.15) 1px, transparent 1px);
        background-size: 16px 16px;
    }

    .produk-detail-page .thumb-dept-badge {
        position: absolute;
        top: 14px;
        left: 14px;
        font-size: 11px;
        font-weight: 800;
        padding: 5px 12px;
        border-radius: var(--radius-pill);
        letter-spacing: 0.5px;
        text-transform: uppercase;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        z-index: 2;
    }

    .produk-detail-page .thumb-stock-badge {
        position: absolute;
        top: 14px;
        right: 14px;
        font-size: 11px;
        font-weight: 800;
        padding: 5px 12px;
        border-radius: var(--radius-pill);
        backdrop-filter: blur(6px);
        z-index: 2;
    }

    .produk-detail-page .stock-available {
        background: rgba(16, 185, 129, 0.9);
        color: var(--color-white);
    }

    .produk-detail-page .stock-empty {
        background: rgba(220, 38, 38, 0.9);
        color: var(--color-white);
    }

    /* ---------- DETAIL BODY ---------- */
    .produk-detail-page .detail-body {
        padding: 28px 30px 30px;
        display: flex;
        flex-direction: column;
    }

    .produk-detail-page .detail-badges-row {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 12px;
    }

    .produk-detail-page .detail-badge {
        font-size: 12px;
        font-weight: 800;
        padding: 4px 12px;
        border-radius: var(--radius-pill);
    }

    .produk-detail-page .detail-badge-stok {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }

    .produk-detail-page .detail-badge-stok-habis {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    .produk-detail-page .detail-title {
        font-size: clamp(1.4rem, 3.5vw, 1.9rem);
        font-weight: 900;
        color: var(--color-primary);
        line-height: 1.3;
        margin-bottom: 10px;
    }

    .produk-detail-page .detail-price {
        font-size: clamp(1.4rem, 3.5vw, 1.9rem);
        font-weight: 900;
        color: var(--color-primary);
        margin-bottom: 22px;
    }

    .produk-detail-page .detail-desc-label {
        font-size: 12px;
        font-weight: 700;
        color: #666666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 6px;
    }

    .produk-detail-page .detail-desc-box {
        background: var(--color-bg);
        border-radius: 14px;
        padding: 16px 18px;
        border: 1px solid #e6eaef;
        font-size: 14px;
        color: #334155;
        line-height: 1.65;
        margin-bottom: 22px;
    }

    .produk-detail-page .detail-pickup-notice {
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

    .produk-detail-page .detail-guest-notice {
        text-align: center;
        font-size: 12px;
        color: #b91c1c;
        font-weight: 700;
        background: #fef2f2;
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #fecaca;
        margin-top: 12px;
    }

    /* ---------- CTA ROW ---------- */
    .produk-detail-page .detail-actions-row {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .produk-detail-page .btn-detail-checkout {
        flex: 1;
        min-width: 220px;
        background: var(--color-primary);
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
        box-shadow: 0 4px 14px rgba(10, 74, 166, 0.3);
        transition: all 0.2s ease;
    }

    .produk-detail-page .btn-detail-checkout:hover {
        background: var(--color-primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(10, 74, 166, 0.4);
    }

    .produk-detail-page .btn-detail-disabled {
        background: #94a3b8 !important;
        cursor: not-allowed !important;
        box-shadow: none !important;
        pointer-events: none;
    }

    .produk-detail-page .btn-detail-back {
        flex-shrink: 0;
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
        padding: 13px 22px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    .produk-detail-page .btn-detail-back:hover {
        background: #e2e8f0;
        color: #111111;
    }

    /* ---------- BREAKPOINT >= 640px (tablet) ---------- */
    @media (min-width: 640px) {
        .produk-detail-page .detail-main {
            padding: 40px 16px 0;
        }

        .produk-detail-page .detail-body {
            padding: 32px 36px 36px;
        }
    }
</style>

<div class="produk-detail-page">
    <main class="detail-main">
        <!-- ================= BREADCRUMB ================= -->
        <nav class="breadcrumb" aria-label="Navigasi breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <span class="breadcrumb-sep" aria-hidden="true">/</span>
            <a href="{{ route('produk') }}">Katalog Produk</a>
            <span class="breadcrumb-sep" aria-hidden="true">/</span>
            <span class="breadcrumb-current">{{ $product->nama_produk }}</span>
        </nav>

        <!-- ================= DETAIL CARD ================= -->
        <article class="detail-card" aria-labelledby="detail-title">
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

            <!-- Hero Thumbnail -->
            <div class="detail-thumb">
                @if($photoUrl)
                    <img src="{{ $photoUrl }}"
                         alt="{{ $product->nama_produk }}"
                         class="detail-thumb-img"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="detail-thumb-fallback" style="background: {{ $fallbackBg }}; display: none;">
                        <div class="thumb-pattern"></div>
                        <span>{{ $fallbackIcon }}</span>
                    </div>
                @else
                    <div class="detail-thumb-fallback" style="background: {{ $fallbackBg }};">
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

            <!-- Konten Detail Produk -->
            <div class="detail-body">
                <div class="detail-badges-row">
                    <span class="detail-badge" style="{{ $badgeStyle }}">
                        {{ $deptNama }} ({{ $deptKode }})
                    </span>
                    <span class="detail-badge {{ $product->stok > 0 ? 'detail-badge-stok' : 'detail-badge-stok-habis' }}">
                        {{ $product->stok > 0 ? 'Stok: ' . $product->stok . ' unit' : 'Stok Habis' }}
                    </span>
                </div>

                <h1 id="detail-title" class="detail-title">
                    {{ $product->nama_produk }}
                </h1>

                <div class="detail-price">
                    Rp {{ number_format($product->harga, 0, ',', '.') }}
                </div>

                <div>
                    <label class="detail-desc-label">Deskripsi Lengkap</label>
                    <div class="detail-desc-box">
                        {{ $product->deskripsi ?: 'Tidak ada keterangan detail untuk produk ini.' }}
                    </div>
                </div>

                <div class="detail-pickup-notice">
                    <span style="font-size: 20px;" aria-hidden="true">📍</span>
                    <span><strong>Pengambilan Langsung di Sekolah:</strong> Pesanan produk fisik diambil di Lab/Unit Teaching Factory SMKN 4 Tanjungpinang setelah pesanan diverifikasi.</span>
                </div>

                <!-- Tombol Aksi -->
                <div class="detail-actions-row">
                    @if($product->stok > 0)
                        <a href="{{ route('checkout.show', $product->id) }}" class="btn-detail-checkout">
                            <span aria-hidden="true">🛒</span>
                            <span>Pesan Sekarang (Checkout)</span>
                        </a>
                    @else
                        <span class="btn-detail-checkout btn-detail-disabled" aria-disabled="true">
                            <span aria-hidden="true">🛒</span>
                            <span>Stok Habis</span>
                        </span>
                    @endif
                    <a href="{{ route('produk') }}" class="btn-detail-back">
                        <span aria-hidden="true">←</span>
                        <span>Kembali ke Katalog</span>
                    </a>
                </div>

                @if(!auth()->check())
                    <div class="detail-guest-notice">
                        🔒 Anda akan diarahkan ke halaman login terlebih dahulu untuk menyelesaikan pemesanan
                    </div>
                @endif
            </div>
        </article>
    </main>
</div>
@endsection
