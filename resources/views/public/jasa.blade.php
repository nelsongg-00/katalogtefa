@extends('layouts.public')

@section('title', 'Layanan Jasa — TEFA SMKN 4 Tanjungpinang')

@section('content')
<style>
    /* Scoped Styles for Layanan Jasa Page */
    .jasa-page-wrapper {
        background-color: #f8fafc;
        min-height: 80vh;
        padding-bottom: 90px;
    }

    /* Hero & Header Section */
    .jasa-header {
        text-align: center;
        padding: 55px 20px 30px;
        max-width: 850px;
        margin: 0 auto;
    }

    .jasa-badge-label {
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

    .jasa-title {
        font-size: 38px;
        font-weight: 900;
        color: #0a215e;
        letter-spacing: -0.5px;
        margin-bottom: 12px;
        text-transform: uppercase;
    }

    .jasa-subtitle {
        font-size: 16px;
        color: #64748b;
        line-height: 1.6;
        max-width: 640px;
        margin: 0 auto;
    }

    /* Filter & Search Bar Container (Centered - Sesuai Gambar 3) */
    .jasa-controls-container {
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

    /* Service Cards Grid */
    .jasa-grid {
        max-width: 1240px;
        margin: 0 auto;
        padding: 0 24px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 28px;
    }

    @media (max-width: 1024px) {
        .jasa-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
    }

    @media (max-width: 640px) {
        .jasa-grid {
            grid-template-columns: 1fr;
            gap: 18px;
        }
        .search-box-wrapper {
            max-width: 100%;
        }
    }

    /* Single Service Card */
    .jasa-card {
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

    .jasa-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 30px -10px rgba(15, 23, 42, 0.12), 0 0 0 1.5px rgba(37, 99, 235, 0.2);
    }

    /* Thumbnail Container */
    .jasa-thumb {
        width: 100%;
        height: 220px;
        position: relative;
        overflow: hidden;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .jasa-thumb-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.5s ease;
    }

    .jasa-card:hover .jasa-thumb-img {
        transform: scale(1.06);
    }

    .jasa-thumb-fallback {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 64px;
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

    .thumb-status-badge {
        position: absolute;
        top: 14px;
        right: 14px;
        font-size: 11px;
        font-weight: 800;
        padding: 4px 12px;
        border-radius: 999px;
        background: rgba(16, 185, 129, 0.9);
        color: #ffffff;
        backdrop-filter: blur(6px);
        z-index: 2;
    }

    /* Card Content */
    .jasa-content {
        padding: 24px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .jasa-card-title {
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.35;
        margin-bottom: 8px;
        transition: color 0.2s ease;
    }

    .jasa-card:hover .jasa-card-title {
        color: #2563eb;
    }

    .jasa-card-desc {
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

    .jasa-price-box {
        font-size: 20px;
        font-weight: 900;
        color: #2563eb;
        margin-bottom: 16px;
    }

    .jasa-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 14px;
        border-top: 1px solid #f1f5f9;
        font-size: 13px;
    }

    .btn-card-consult {
        width: 100%;
        background: #10b981;
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
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
    }

    .btn-card-consult:hover {
        background: #059669;
        transform: translateY(-1px);
    }

    /* Modal Backdrop & Container */
    .jasa-modal-backdrop {
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

    .jasa-modal-container {
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

    .modal-serv-title {
        font-size: 24px;
        font-weight: 900;
        color: #0a215e;
        line-height: 1.3;
        margin-bottom: 12px;
    }

    .modal-serv-price {
        font-size: 24px;
        font-weight: 900;
        color: #2563eb;
        margin-bottom: 18px;
    }

    .modal-serv-desc-box {
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
        color: #0f172a;
    }
</style>

<div class="jasa-page-wrapper">
    <!-- Header Section -->
    <div class="jasa-header">
        <span class="jasa-badge-label">
            ✨ SOLUSI KEJURUAN INDUSTRI
        </span>
        <h1 class="jasa-title">LAYANAN JASA KEJURUAN TEFA</h1>
        <p class="jasa-subtitle">
            Solusi profesional karya siswa dan unit Teaching Factory SMKN 4 Tanjungpinang berstandar industri.
        </p>
    </div>

    <!-- Filter & Search Controls (Centered - Sesuai Gambar 3) -->
    <div class="jasa-controls-container">
        <!-- Centered Search Bar (Sesuai Gambar 3) -->
        <div class="search-box-wrapper">
            <span class="search-icon-left">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </span>
            <input type="text" 
                   id="jasa-search-input" 
                   class="search-input-field" 
                   placeholder="Cari layanan jasa kejuruan..." 
                   oninput="filterJasa()" />
            <button type="button" class="search-btn-action" onclick="filterJasa()">
                Cari
            </button>
        </div>

        <!-- Filter Pills Row (Semua, RPL, TKJ, DKV, PSPT, ANIMASI, GIM) - Sesuai Gambar 3 -->
        <div class="filter-pills-row">
            <button type="button" class="filter-pill-btn active" data-filter="all" onclick="setJasaFilter('all')">
                <span>Semua</span>
            </button>
            <button type="button" class="filter-pill-btn" data-filter="RPL" onclick="setJasaFilter('RPL')">
                <span>RPL</span>
            </button>
            <button type="button" class="filter-pill-btn" data-filter="TKJ" onclick="setJasaFilter('TKJ')">
                <span>TKJ</span>
            </button>
            <button type="button" class="filter-pill-btn" data-filter="DKV" onclick="setJasaFilter('DKV')">
                <span>DKV</span>
            </button>
            <button type="button" class="filter-pill-btn" data-filter="PSPT" onclick="setJasaFilter('PSPT')">
                <span>PSPT</span>
            </button>
            <button type="button" class="filter-pill-btn" data-filter="ANIMASI" onclick="setJasaFilter('ANIMASI')">
                <span>ANIMASI</span>
            </button>
            <button type="button" class="filter-pill-btn" data-filter="GIM" onclick="setJasaFilter('GIM')">
                <span>GIM</span>
            </button>
        </div>
    </div>

    <!-- Service Cards Grid -->
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
                 data-jurusan="{{ $deptKode }}"
                 data-title="{{ strtolower($service->nama_layanan) }}"
                 data-desc="{{ strtolower($service->deskripsi ?? '') }}"
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
                            <span>💬</span>
                            <span>Lihat Detail & Konsultasi</span>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 70px 20px; background: #fff; border-radius: 20px; border: 1.5px dashed #cbd5e1;">
                <div style="font-size: 52px; margin-bottom: 14px;">🤝</div>
                <h3 style="font-size: 19px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">Belum Ada Layanan Jasa</h3>
                <p style="color: #64748b; font-size: 14.5px; max-width: 480px; margin: 0 auto; line-height: 1.6;">
                    Saat ini belum ada data layanan jasa yang aktif. Silakan kembali lagi nanti atau hubungi unit produksi sekolah.
                </p>
            </div>
        @endforelse
    </div>

    <!-- Empty Filter Result Alert -->
    <div id="no-jasa-match-alert" style="display: none; max-width: 600px; margin: 40px auto; text-align: center; background: #ffffff; padding: 40px 20px; border-radius: 18px; border: 1px solid #e2e8f0;">
        <div style="font-size: 42px; margin-bottom: 10px;">🔍</div>
        <h4 style="font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 6px;">Layanan Tidak Ditemukan</h4>
        <p style="color: #64748b; font-size: 14px; margin-bottom: 16px;">Tidak ada layanan jasa yang sesuai dengan kata kunci atau jurusan yang Anda pilih.</p>
        <button type="button" onclick="resetJasaFilters()" style="background: #2563eb; color: #ffffff; border: none; padding: 9px 20px; border-radius: 999px; font-size: 13.5px; font-weight: 700; cursor: pointer;">
            Reset Filter & Tampilkan Semua
        </button>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL POP-UP DETAIL LAYANAN JASA DINAMIS   -->
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
                <label style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">
                    Deskripsi Lengkap & Lingkup Layanan
                </label>
                <div id="modal-serv-desc" class="modal-serv-desc-box">
                    Deskripsi layanan jasa...
                </div>
            </div>

            <div class="modal-wa-notice">
                <span style="font-size: 20px;">💬</span>
                <span><strong>Konsultasi Langsung via WhatsApp:</strong> Tim instruktur dan admin jurusan TEFA akan mendiskusikan kebutuhan spesifikasi, estimasi waktu, serta penugasan siswa untuk proyek Anda.</span>
            </div>

            <!-- Footer & Tombol Aksi -->
            <div class="modal-actions-row">
                <button type="button" class="modal-btn-close-sec" onclick="closeServiceModal()">
                    Tutup
                </button>
                <a id="modal-serv-wa-btn" href="#" target="_blank" class="modal-btn-wa-order">
                    <span>💬</span>
                    <span>Konsultasi via WhatsApp</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    let activeJasaFilter = 'all';

    function setJasaFilter(dept) {
        activeJasaFilter = dept;

        document.querySelectorAll('.filter-pill-btn').forEach(btn => {
            if (btn.getAttribute('data-filter') === dept) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });

        filterJasa();
    }

    function resetJasaFilters() {
        const input = document.getElementById('jasa-search-input');
        if (input) input.value = '';
        setJasaFilter('all');
    }

    function filterJasa() {
        const query = (document.getElementById('jasa-search-input')?.value || '').toLowerCase().trim();
        const cards = document.querySelectorAll('.jasa-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const cardDept = card.getAttribute('data-jurusan') || '';
            const cardTitle = card.getAttribute('data-title') || '';
            const cardDesc = card.getAttribute('data-desc') || '';

            const matchesDept = (activeJasaFilter === 'all' || cardDept.toUpperCase() === activeJasaFilter.toUpperCase());
            const matchesQuery = (!query || cardTitle.includes(query) || cardDesc.includes(query));

            if (matchesDept && matchesQuery) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const alert = document.getElementById('no-jasa-match-alert');
        if (alert) {
            alert.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    }

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
