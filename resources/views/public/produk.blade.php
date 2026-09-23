@extends('layouts.public')

@section('title', 'Katalog Produk — TEFA SMKN 4 Tanjungpinang')

@section('content')
<style>
    /* Scoped Styles for Produk Page */
    .produk-page-wrapper {
        background-color: #f8fafc;
        min-height: 80vh;
        padding-bottom: 90px;
    }

    /* Hero & Header Section */
    .produk-header {
        text-align: center;
        padding: 55px 20px 30px;
        max-width: 850px;
        margin: 0 auto;
    }

    .produk-badge-label {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
        padding: 5px 16px;
        border-radius: 999px;
        font-size: 12.5px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 14px;
    }

    .produk-title {
        font-size: 38px;
        font-weight: 900;
        color: #0a215e;
        letter-spacing: -0.5px;
        margin-bottom: 12px;
        text-transform: uppercase;
    }

    .produk-subtitle {
        font-size: 16px;
        color: #64748b;
        line-height: 1.6;
        max-width: 640px;
        margin: 0 auto;
    }

    /* Filter & Search Bar Container (Centered - Sesuai Gambar 3) */
    .produk-controls-container {
        max-width: 1240px;
        margin: 0 auto 35px;
        padding: 0 24px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 16px;
    }

    /* Centered Search Bar (Matching Image 3) */
    .search-box-wrapper {
        display: flex;
        align-items: center;
        background: #ffffff;
        border: 1.5px solid #dbeafe;
        border-radius: 999px;
        padding: 5px 6px 5px 18px;
        box-shadow: 0 10px 30px -5px rgba(37, 99, 235, 0.12), 0 2px 6px rgba(0, 0, 0, 0.03);
        transition: all 0.25s ease;
        width: 100%;
        max-width: 600px;
    }

    .search-box-wrapper:focus-within {
        border-color: #2563eb;
        box-shadow: 0 12px 35px -5px rgba(37, 99, 235, 0.22), 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .search-icon-left {
        color: #0284c7;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-right: 8px;
    }

    .search-input-field {
        border: none;
        background: transparent;
        padding: 8px 6px;
        font-size: 14.5px;
        color: #0f172a;
        outline: none;
        width: 100%;
        font-weight: 500;
    }

    .search-input-field::placeholder {
        color: #94a3b8;
    }

    .search-btn-action {
        background: #2563eb;
        color: #ffffff;
        border: none;
        padding: 9px 24px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        flex-shrink: 0;
    }

    .search-btn-action:hover {
        background: #1d4ed8;
        transform: scale(1.02);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
    }

    /* Filter Pills (Semua, RPL, TKJ, DKV, PSPT, ANIMASI, GIM) - Sesuai Gambar 3 */
    .filter-pills-row {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        flex-wrap: wrap;
        width: 100%;
    }

    .filter-pill-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 20px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1.5px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        text-decoration: none;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        user-select: none;
    }

    .filter-pill-btn:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
        color: #0f172a;
        transform: translateY(-1px);
    }

    .filter-pill-btn.active {
        background: #0f172a;
        color: #ffffff;
        border-color: #0f172a;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.25);
    }

    /* Product Cards Grid */
    .produk-grid {
        max-width: 1240px;
        margin: 0 auto;
        padding: 0 24px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 28px;
    }

    @media (max-width: 1024px) {
        .produk-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
    }

    @media (max-width: 640px) {
        .produk-grid {
            grid-template-columns: 1fr;
            gap: 18px;
        }
        .search-box-wrapper {
            max-width: 100%;
        }
    }

    /* Single Product Card */
    .produk-card {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 4px 15px -2px rgba(15, 23, 42, 0.05);
        display: flex;
        flex-direction: column;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
    }

    .produk-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 30px -10px rgba(15, 23, 42, 0.12), 0 0 0 1.5px rgba(37, 99, 235, 0.2);
    }

    /* Thumbnail Container */
    .produk-thumb {
        width: 100%;
        height: 220px;
        position: relative;
        overflow: hidden;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .produk-thumb-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.5s ease;
    }

    .produk-card:hover .produk-thumb-img {
        transform: scale(1.06);
    }

    .produk-thumb-fallback {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 58px;
        color: #ffffff;
        position: relative;
    }

    .thumb-pattern {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.15) 1px, transparent 1px);
        background-size: 16px 16px;
    }

    .thumb-dept-badge {
        position: absolute;
        top: 14px;
        left: 14px;
        font-size: 11.5px;
        font-weight: 800;
        padding: 5px 12px;
        border-radius: 999px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        box-shadow: 0 2px 8px rgba(0,0,0,0.12);
        z-index: 2;
    }

    .thumb-stock-badge {
        position: absolute;
        top: 14px;
        right: 14px;
        font-size: 11px;
        font-weight: 800;
        padding: 4px 12px;
        border-radius: 999px;
        backdrop-filter: blur(6px);
        z-index: 2;
    }

    .stock-available {
        background: rgba(16, 185, 129, 0.9);
        color: #ffffff;
    }

    .stock-empty {
        background: rgba(220, 38, 38, 0.9);
        color: #ffffff;
    }

    /* Card Content */
    .produk-content {
        padding: 24px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .produk-card-title {
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.35;
        margin-bottom: 8px;
        transition: color 0.2s ease;
    }

    .produk-card:hover .produk-card-title {
        color: #2563eb;
    }

    .produk-card-desc {
        font-size: 13.5px;
        color: #64748b;
        line-height: 1.55;
        margin-bottom: 16px;
        flex-grow: 1;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .produk-price-box {
        font-size: 20px;
        font-weight: 900;
        color: #2563eb;
        margin-bottom: 16px;
    }

    .produk-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 14px;
        border-top: 1px solid #f1f5f9;
        font-size: 13px;
    }

    .btn-card-order {
        width: 100%;
        background: #2563eb;
        color: #ffffff;
        border: none;
        padding: 10px 16px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 13.5px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }

    .btn-card-order:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
    }

    /* Modal Backdrop & Container */
    .produk-modal-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background-color: rgba(15, 23, 42, 0.7);
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

    .produk-modal-container {
        background: #ffffff;
        border-radius: 24px;
        max-width: 680px;
        width: 100%;
        box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.35);
        border: 1px solid #e2e8f0;
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
        background: rgba(15, 23, 42, 0.6);
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
        background: #dc2626;
        transform: rotate(90deg);
    }

    .modal-hero-thumb {
        height: 230px;
        width: 100%;
        position: relative;
        background: #f1f5f9;
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

    .modal-body {
        padding: 28px 30px;
        color: #0f172a;
    }

    .modal-prod-title {
        font-size: 24px;
        font-weight: 900;
        color: #0a215e;
        line-height: 1.3;
        margin-bottom: 12px;
    }

    .modal-prod-price {
        font-size: 24px;
        font-weight: 900;
        color: #2563eb;
        margin-bottom: 18px;
    }

    .modal-prod-desc-box {
        background: #f8fafc;
        border-radius: 14px;
        padding: 16px 18px;
        border: 1px solid #e2e8f0;
        font-size: 14px;
        color: #334155;
        line-height: 1.65;
        margin-bottom: 22px;
        max-height: 180px;
        overflow-y: auto;
    }

    .modal-pickup-notice {
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

    .modal-btn-checkout {
        flex: 1;
        background: #2563eb;
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
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
        transition: all 0.2s ease;
    }

    .modal-btn-checkout:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(37, 99, 235, 0.4);
    }

    .modal-btn-disabled {
        background: #94a3b8 !important;
        cursor: not-allowed !important;
        box-shadow: none !important;
        pointer-events: none;
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
        color: #0f172a;
    }
</style>

<div class="produk-page-wrapper">
    <!-- Header Section -->
    <div class="produk-header">
        <span class="produk-badge-label">
            ✨ KARYA INOVASI & PRODUKSI TEFA
        </span>
        <h1 class="produk-title">KATALOG PRODUK UNGGULAN</h1>
        <p class="produk-subtitle">
            Temukan berbagai produk fisik karya inovasi siswa dan unit Teaching Factory SMKN 4 Tanjungpinang.
        </p>
    </div>

    <!-- Filter & Search Controls (Centered - Sesuai Gambar 3) -->
    <div class="produk-controls-container">
        <!-- Centered Search Bar (Sesuai Gambar 3) -->
        <div class="search-box-wrapper">
            <span class="search-icon-left">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </span>
            <input type="text" 
                   id="produk-search-input" 
                   class="search-input-field" 
                   placeholder="Cari produk fisik atau kerajinan..." 
                   oninput="filterProduk()" />
            <button type="button" class="search-btn-action" onclick="filterProduk()">
                Cari
            </button>
        </div>

        <!-- Filter Pills Row (Semua, RPL, TKJ, DKV, PSPT, ANIMASI, GIM) - Sesuai Gambar 3 -->
        <div class="filter-pills-row">
            <button type="button" class="filter-pill-btn active" data-filter="all" onclick="setProdukFilter('all')">
                <span>Semua</span>
            </button>
            <button type="button" class="filter-pill-btn" data-filter="RPL" onclick="setProdukFilter('RPL')">
                <span>RPL</span>
            </button>
            <button type="button" class="filter-pill-btn" data-filter="TKJ" onclick="setProdukFilter('TKJ')">
                <span>TKJ</span>
            </button>
            <button type="button" class="filter-pill-btn" data-filter="DKV" onclick="setProdukFilter('DKV')">
                <span>DKV</span>
            </button>
            <button type="button" class="filter-pill-btn" data-filter="PSPT" onclick="setProdukFilter('PSPT')">
                <span>PSPT</span>
            </button>
            <button type="button" class="filter-pill-btn" data-filter="ANIMASI" onclick="setProdukFilter('ANIMASI')">
                <span>ANIMASI</span>
            </button>
            <button type="button" class="filter-pill-btn" data-filter="GIM" onclick="setProdukFilter('GIM')">
                <span>GIM</span>
            </button>
        </div>
    </div>

    <!-- Product Cards Grid -->
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

                $productModalData = [
                    'id' => $product->id,
                    'nama' => $product->nama_produk,
                    'jurusan' => $deptNama,
                    'jurusan_code' => $deptKode,
                    'badge_style' => $badgeStyle,
                    'fallback_bg' => $fallbackBg,
                    'fallback_icon' => $fallbackIcon,
                    'harga' => 'Rp ' . number_format($product->harga, 0, ',', '.'),
                    'stok' => (int) $product->stok,
                    'deskripsi' => $product->deskripsi,
                    'foto' => $photoUrl,
                    'checkout_url' => route('checkout.show', $product->id),
                    'login_url' => route('login', ['redirect' => route('checkout.show', $product->id)]),
                ];
            @endphp

            <div class="produk-card" 
                 data-id="{{ $product->id }}"
                 data-jurusan="{{ $deptKode }}"
                 data-title="{{ strtolower($product->nama_produk) }}"
                 data-desc="{{ strtolower($product->deskripsi ?? '') }}"
                 onclick='openProductModal(@json($productModalData))'>
                
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
                        <button type="button" class="btn-card-order">
                            <span>🛒</span>
                            <span>Lihat Detail & Pesan</span>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 70px 20px; background: #fff; border-radius: 20px; border: 1.5px dashed #cbd5e1;">
                <div style="font-size: 52px; margin-bottom: 14px;">📦</div>
                <h3 style="font-size: 19px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">Belum Ada Produk Tersedia</h3>
                <p style="color: #64748b; font-size: 14.5px; max-width: 480px; margin: 0 auto; line-height: 1.6;">
                    Saat ini katalog produk fisik sedang dalam pembaruan inventaris. Silakan kembali lagi nanti atau hubungi unit produksi sekolah.
                </p>
            </div>
        @endforelse
    </div>

    <!-- Empty Filter Result Alert -->
    <div id="no-produk-match-alert" style="display: none; max-width: 600px; margin: 40px auto; text-align: center; background: #ffffff; padding: 40px 20px; border-radius: 18px; border: 1px solid #e2e8f0;">
        <div style="font-size: 42px; margin-bottom: 10px;">🔍</div>
        <h4 style="font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 6px;">Produk Tidak Ditemukan</h4>
        <p style="color: #64748b; font-size: 14px; margin-bottom: 16px;">Tidak ada produk yang sesuai dengan kata kunci atau jurusan yang Anda pilih.</p>
        <button type="button" onclick="resetProdukFilters()" style="background: #2563eb; color: #ffffff; border: none; padding: 9px 20px; border-radius: 999px; font-size: 13.5px; font-weight: 700; cursor: pointer;">
            Reset Filter & Tampilkan Semua
        </button>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL POP-UP DETAIL PRODUK FISIK DINAMIS  -->
<!-- ========================================== -->
<div id="product-detail-modal" class="produk-modal-backdrop" onclick="handleProductBackdropClick(event)">
    <div class="produk-modal-container">
        <!-- Close Button -->
        <button type="button" class="modal-close-btn" onclick="closeProductModal()" aria-label="Tutup Modal">
            &times;
        </button>

        <!-- Modal Hero Thumbnail -->
        <div class="modal-hero-thumb">
            <img id="modal-prod-img" src="" alt="Foto Produk" class="modal-hero-img" style="display: none;">
            <div id="modal-prod-fallback" class="produk-thumb-fallback" style="display: flex;">
                <div class="thumb-pattern"></div>
                <span id="modal-prod-icon" style="font-size: 72px;">📦</span>
            </div>
        </div>

        <!-- Konten Detail Produk -->
        <div class="modal-body">
            <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap; margin-bottom: 10px;">
                <span id="modal-prod-jurusan" style="font-size: 12px; font-weight: 700; padding: 4px 12px; border-radius: 999px;">
                    Jurusan
                </span>
                <span id="modal-prod-stok-badge" style="font-size: 12px; font-weight: 800; padding: 4px 12px; border-radius: 999px;">
                    Stok
                </span>
            </div>

            <h2 id="modal-prod-name" class="modal-prod-title">
                Nama Produk
            </h2>

            <div id="modal-prod-price" class="modal-prod-price">
                Rp 0
            </div>

            <div style="margin-bottom: 8px;">
                <label style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">
                    Deskripsi Lengkap
                </label>
                <div id="modal-prod-desc" class="modal-prod-desc-box">
                    Deskripsi produk...
                </div>
            </div>

            <div class="modal-pickup-notice">
                <span style="font-size: 20px;">📍</span>
                <span><strong>Pengambilan Langsung di Sekolah:</strong> Pesanan produk fisik diambil di Lab/Unit Teaching Factory SMKN 4 Tanjungpinang setelah pesanan diverifikasi.</span>
            </div>

            <!-- Footer & Tombol Aksi -->
            <div class="modal-actions-row">
                <button type="button" class="modal-btn-close-sec" onclick="closeProductModal()">
                    Tutup
                </button>
                <a id="modal-prod-action-btn" href="#" class="modal-btn-checkout">
                    <span>🛒</span>
                    <span id="modal-prod-btn-text">Pesan Sekarang (Checkout)</span>
                </a>
            </div>

            <div id="modal-prod-guest-notice" style="display: none; text-align: center; font-size: 12px; color: #b91c1c; font-weight: 700; background: #fef2f2; padding: 8px 12px; border-radius: 8px; border: 1px solid #fecaca; margin-top: 10px;">
                🔒 Anda akan diarahkan ke halaman login terlebih dahulu untuk menyelesaikan pemesanan
            </div>
        </div>
    </div>
</div>

<script>
    const isUserLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
    let activeProdukFilter = 'all';

    function setProdukFilter(dept) {
        activeProdukFilter = dept;

        document.querySelectorAll('.filter-pill-btn').forEach(btn => {
            if (btn.getAttribute('data-filter') === dept) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });

        filterProduk();
    }

    function resetProdukFilters() {
        const input = document.getElementById('produk-search-input');
        if (input) input.value = '';
        setProdukFilter('all');
    }

    function filterProduk() {
        const query = (document.getElementById('produk-search-input')?.value || '').toLowerCase().trim();
        const cards = document.querySelectorAll('.produk-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const cardDept = card.getAttribute('data-jurusan') || '';
            const cardTitle = card.getAttribute('data-title') || '';
            const cardDesc = card.getAttribute('data-desc') || '';

            const matchesDept = (activeProdukFilter === 'all' || cardDept.toUpperCase() === activeProdukFilter.toUpperCase());
            const matchesQuery = (!query || cardTitle.includes(query) || cardDesc.includes(query));

            if (matchesDept && matchesQuery) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const alert = document.getElementById('no-produk-match-alert');
        if (alert) {
            alert.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    }

    // Modal Interaction
    function openProductModal(product) {
        const modal = document.getElementById('product-detail-modal');
        if (!modal) return;

        const imgEl = document.getElementById('modal-prod-img');
        const fallbackEl = document.getElementById('modal-prod-fallback');
        const iconEl = document.getElementById('modal-prod-icon');

        if (product.foto) {
            imgEl.src = product.foto;
            imgEl.style.display = 'block';
            fallbackEl.style.display = 'none';
        } else {
            imgEl.style.display = 'none';
            fallbackEl.style.display = 'flex';
            fallbackEl.style.background = product.fallback_bg;
            iconEl.textContent = product.fallback_icon;
        }

        const deptEl = document.getElementById('modal-prod-jurusan');
        deptEl.textContent = product.jurusan + ' (' + product.jurusan_code + ')';
        deptEl.setAttribute('style', product.badge_style + '; font-size: 12px; font-weight: 700; padding: 4px 12px; border-radius: 999px;');

        document.getElementById('modal-prod-name').textContent = product.nama;
        document.getElementById('modal-prod-price').textContent = product.harga;
        document.getElementById('modal-prod-desc').textContent = product.deskripsi || 'Tidak ada keterangan detail untuk produk ini.';

        const stokBadge = document.getElementById('modal-prod-stok-badge');
        const actionBtn = document.getElementById('modal-prod-action-btn');
        const btnText = document.getElementById('modal-prod-btn-text');
        const guestNotice = document.getElementById('modal-prod-guest-notice');

        if (product.stok > 0) {
            stokBadge.textContent = 'Stok: ' + product.stok + ' unit';
            stokBadge.style.background = '#ecfdf5';
            stokBadge.style.color = '#059669';
            stokBadge.style.border = '1px solid #a7f3d0';
        } else {
            stokBadge.textContent = 'Stok Habis';
            stokBadge.style.background = '#fef2f2';
            stokBadge.style.color = '#dc2626';
            stokBadge.style.border = '1px solid #fecaca';
        }

        if (isUserLoggedIn) {
            guestNotice.style.display = 'none';
            if (product.stok > 0) {
                actionBtn.href = product.checkout_url;
                actionBtn.className = 'modal-btn-checkout';
                btnText.textContent = 'Pesan Sekarang (Checkout)';
            } else {
                actionBtn.href = 'javascript:void(0)';
                actionBtn.className = 'modal-btn-checkout modal-btn-disabled';
                btnText.textContent = 'Stok Habis';
            }
        } else {
            guestNotice.style.display = 'block';
            actionBtn.href = product.login_url;
            actionBtn.className = 'modal-btn-checkout';
            btnText.textContent = 'Pesan Sekarang (Login)';
        }

        modal.style.display = 'grid';
        document.body.style.overflow = 'hidden';
    }

    function closeProductModal() {
        const modal = document.getElementById('product-detail-modal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    function handleProductBackdropClick(e) {
        if (e.target.id === 'product-detail-modal') {
            closeProductModal();
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeProductModal();
        }
    });
</script>
@endsection
