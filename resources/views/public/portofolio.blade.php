@extends('layouts.public')

@section('title', 'Portofolio TEFA — SMKN 4 Tanjungpinang')

@section('content')
<style>
    /* Scoped Styles for Portofolio Page */
    .portfolio-page-wrapper {
        background-color: #f8fafc;
        min-height: 80vh;
        padding-bottom: 90px;
    }

    /* Hero & Header Section */
    .portfolio-header {
        text-align: center;
        padding: 55px 20px 30px;
        max-width: 850px;
        margin: 0 auto;
    }

    .portfolio-badge-label {
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

    .portfolio-title {
        font-size: 38px;
        font-weight: 900;
        color: #0a215e;
        letter-spacing: -0.5px;
        margin-bottom: 12px;
        text-transform: uppercase;
    }

    .portfolio-subtitle {
        font-size: 16px;
        color: #64748b;
        line-height: 1.6;
        max-width: 620px;
        margin: 0 auto;
    }

    /* Filter & Search Bar Container (Centered - Sesuai Gambar 3) */
    .portfolio-controls-container {
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

    .filter-pill-btn .pill-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    /* Portfolio Cards Grid */
    .portfolio-grid {
        max-width: 1240px;
        margin: 0 auto;
        padding: 0 24px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 28px;
    }

    @media (max-width: 1024px) {
        .portfolio-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
    }

    @media (max-width: 640px) {
        .portfolio-grid {
            grid-template-columns: 1fr;
            gap: 18px;
        }
        .search-box-wrapper {
            min-width: 100%;
        }
    }

    /* Single Portfolio Card */
    .portfolio-card {
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

    .portfolio-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 30px -10px rgba(15, 23, 42, 0.12), 0 0 0 1.5px rgba(37, 99, 235, 0.2);
    }

    /* Card Thumbnail Container */
    .portfolio-thumb {
        width: 100%;
        height: 220px;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .portfolio-thumb-bg {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 68px;
        position: relative;
        transition: transform 0.5s ease;
    }

    .portfolio-card:hover .portfolio-thumb-bg {
        transform: scale(1.06);
    }

    .thumb-overlay-pattern {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.15) 1px, transparent 1px);
        background-size: 16px 16px;
        opacity: 0.7;
    }

    .thumb-badge {
        position: absolute;
        top: 14px;
        left: 14px;
        font-size: 11.5px;
        font-weight: 800;
        padding: 5px 12px;
        border-radius: 999px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        z-index: 2;
    }

    .thumb-year {
        position: absolute;
        top: 14px;
        right: 14px;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 999px;
        background: rgba(15, 23, 42, 0.65);
        color: #ffffff;
        backdrop-filter: blur(4px);
        z-index: 2;
    }

    /* Card Content */
    .portfolio-content {
        padding: 24px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .portfolio-card-title {
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.35;
        margin-bottom: 8px;
        transition: color 0.2s ease;
    }

    .portfolio-card:hover .portfolio-card-title {
        color: #2563eb;
    }

    .portfolio-card-desc {
        font-size: 13.5px;
        color: #64748b;
        line-height: 1.55;
        margin-bottom: 20px;
        flex-grow: 1;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .portfolio-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 14px;
        border-top: 1px solid #f1f5f9;
        font-size: 12.5px;
    }

    .portfolio-team-info {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #475569;
        font-weight: 600;
    }

    .portfolio-click-hint {
        color: #2563eb;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: transform 0.2s ease;
    }

    .portfolio-card:hover .portfolio-click-hint {
        transform: translateX(3px);
    }

    /* Empty State */
    .portfolio-empty {
        grid-column: 1 / -1;
        background: #ffffff;
        border-radius: 20px;
        border: 1.5px dashed #cbd5e1;
        padding: 70px 20px;
        text-align: center;
    }

    .portfolio-empty-icon {
        font-size: 52px;
        margin-bottom: 12px;
    }

    .portfolio-empty-title {
        font-size: 19px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .portfolio-empty-desc {
        color: #64748b;
        font-size: 14px;
        max-width: 440px;
        margin: 0 auto;
    }

    /* ==========================================================
       MODAL POPUP STYLING (Penjelasan Portofolio)
       ========================================================== */
    .portfolio-modal-backdrop {
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

    .portfolio-modal-container {
        background: #ffffff;
        border-radius: 24px;
        max-width: 760px;
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

    /* Modal Hero Visual */
    .modal-hero-banner {
        height: 200px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        overflow: hidden;
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

    .modal-badge-dept {
        position: absolute;
        bottom: 16px;
        left: 24px;
        font-size: 12px;
        font-weight: 800;
        padding: 5px 14px;
        border-radius: 999px;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }

    /* Modal Body Content */
    .modal-body {
        padding: 30px 32px 34px;
        color: #0f172a;
    }

    .modal-project-title {
        font-size: 26px;
        font-weight: 900;
        line-height: 1.25;
        color: #0a215e;
        margin-bottom: 16px;
    }

    .modal-meta-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 14px 18px;
        margin-bottom: 24px;
    }

    @media (max-width: 600px) {
        .modal-meta-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }
    }

    .modal-meta-item {
        display: flex;
        flex-direction: column;
    }

    .modal-meta-label {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }

    .modal-meta-val {
        font-size: 13.5px;
        font-weight: 700;
        color: #1e293b;
    }

    .modal-section-heading {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .modal-desc-text {
        font-size: 14.5px;
        color: #334155;
        line-height: 1.7;
        margin-bottom: 24px;
    }

    .modal-features-list {
        list-style: none;
        padding: 0;
        margin: 0 0 24px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .modal-feature-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 14px;
        color: #334155;
        line-height: 1.5;
    }

    .modal-feature-icon {
        color: #10b981;
        font-weight: bold;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .modal-tech-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 28px;
    }

    .modal-tech-pill {
        background: #eff6ff;
        color: #1e40af;
        border: 1px solid #bfdbfe;
        font-size: 12.5px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 999px;
    }

    /* Modal Action Buttons */
    .modal-actions-row {
        display: flex;
        align-items: center;
        gap: 14px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
    }

    .modal-btn-wa {
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

    .modal-btn-wa:hover {
        background: #059669;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(16, 185, 129, 0.4);
    }

    .modal-btn-close-secondary {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
        padding: 13px 24px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .modal-btn-close-secondary:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
</style>

<div class="portfolio-page-wrapper">
    <!-- Header Section -->
    <div class="portfolio-header">
        <span class="portfolio-badge-label">
            ✨ KARYA UNGGULAN SISWA
        </span>
        <h1 class="portfolio-title">PORTOFOLIO TEFA</h1>
        <p class="portfolio-subtitle">
            Karya dan proyek unggulan karya siswa-siswi SMKN 4 Tanjungpinang dari berbagai program keahlian.
        </p>
    </div>

    <!-- Filter & Search Controls (Centered - Sesuai Gambar 3) -->
    <div class="portfolio-controls-container">
        <!-- Centered Search Bar (Sesuai Gambar 3) -->
        <div class="search-box-wrapper">
            <span class="search-icon-left">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </span>
            <input type="text" 
                   id="portfolio-search-input" 
                   class="search-input-field" 
                   placeholder="Cari karya portofolio..." 
                   oninput="filterPortfolios()" />
            <button type="button" class="search-btn-action" onclick="filterPortfolios()">
                Cari
            </button>
        </div>

        <!-- Filter Pills Row (Semua, RPL, TKJ, DKV, PSPT, ANIMASI, GIM) - Sesuai Gambar 3 -->
        <div class="filter-pills-row">
            <button type="button" class="filter-pill-btn active" data-filter="all" onclick="setFilterCategory('all')">
                <span>Semua</span>
            </button>
            <button type="button" class="filter-pill-btn" data-filter="RPL" onclick="setFilterCategory('RPL')">
                <span>RPL</span>
            </button>
            <button type="button" class="filter-pill-btn" data-filter="TKJ" onclick="setFilterCategory('TKJ')">
                <span>TKJ</span>
            </button>
            <button type="button" class="filter-pill-btn" data-filter="DKV" onclick="setFilterCategory('DKV')">
                <span>DKV</span>
            </button>
            <button type="button" class="filter-pill-btn" data-filter="PSPT" onclick="setFilterCategory('PSPT')">
                <span>PSPT</span>
            </button>
            <button type="button" class="filter-pill-btn" data-filter="Animasi" onclick="setFilterCategory('Animasi')">
                <span>ANIMASI</span>
            </button>
            <button type="button" class="filter-pill-btn" data-filter="Gim" onclick="setFilterCategory('Gim')">
                <span>GIM</span>
            </button>
        </div>
    </div>

    <!-- Portfolio Cards Grid -->
    <div class="portfolio-grid" id="portfolio-items-grid">
        @forelse($portfolios as $item)
            <div class="portfolio-card" 
                 data-id="{{ $item['id'] }}"
                 data-jurusan="{{ $item['jurusan_code'] }}"
                 data-title="{{ strtolower($item['title']) }}"
                 data-desc="{{ strtolower($item['short_desc']) }}"
                 onclick="openPortfolioModal({{ json_encode($item) }})">
                
                <!-- Thumbnail -->
                <div class="portfolio-thumb">
                    <div class="portfolio-thumb-bg" style="background: {{ $item['image_bg'] }};">
                        <div class="thumb-overlay-pattern"></div>
                        <span>{{ $item['icon'] }}</span>
                    </div>

                    <!-- Department Badge -->
                    <span class="thumb-badge" style="background: {{ $item['badge_bg'] }}; color: {{ $item['badge_color'] }}; border: 1px solid {{ $item['badge_border'] }};">
                        {{ $item['jurusan_code'] }}
                    </span>

                    <!-- Academic Year Badge -->
                    <span class="thumb-year">
                        {{ $item['year'] }}
                    </span>
                </div>

                <!-- Card Content -->
                <div class="portfolio-content">
                    <h3 class="portfolio-card-title">
                        {{ $item['title'] }}
                    </h3>

                    <p class="portfolio-card-desc">
                        {{ $item['short_desc'] }}
                    </p>

                    <div class="portfolio-card-footer">
                        <div class="portfolio-team-info">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span>{{ $item['team'] }}</span>
                        </div>

                        <div class="portfolio-click-hint">
                            <span>Detail</span>
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="portfolio-empty">
                <div class="portfolio-empty-icon">📁</div>
                <div class="portfolio-empty-title">Belum Ada Portofolio</div>
                <p class="portfolio-empty-desc">Data karya siswa sedang dalam proses kurasi. Silakan kembali lagi nanti.</p>
            </div>
        @endforelse
    </div>

    <!-- Empty Filter Result Alert -->
    <div id="no-filter-match-alert" style="display: none; max-width: 600px; margin: 40px auto; text-align: center; background: #ffffff; padding: 40px 20px; border-radius: 18px; border: 1px solid #e2e8f0;">
        <div style="font-size: 42px; margin-bottom: 10px;">🔍</div>
        <h4 style="font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 6px;">Karya Tidak Ditemukan</h4>
        <p style="color: #64748b; font-size: 14px; margin-bottom: 16px;">Tidak ada karya yang sesuai dengan pencarian atau jurusan yang Anda pilih.</p>
        <button type="button" onclick="resetAllFilters()" style="background: #2563eb; color: #ffffff; border: none; padding: 9px 20px; border-radius: 999px; font-size: 13.5px; font-weight: 700; cursor: pointer;">
            Reset Filter & Cari Semua
        </button>
    </div>
</div>

<!-- ==============================================================
     MODAL POPUP EXPLANATION DETAIL (INTERAKTIF POP UP PENJELASAN)
     ============================================================== -->
<div id="portfolio-modal" class="portfolio-modal-backdrop" onclick="handleBackdropClick(event)">
    <div class="portfolio-modal-container">
        <!-- Close Button -->
        <button type="button" class="modal-close-btn" onclick="closePortfolioModal()" aria-label="Tutup Popup">
            &times;
        </button>

        <!-- Modal Top Visual Banner -->
        <div id="modal-banner" class="modal-hero-banner">
            <div class="thumb-overlay-pattern"></div>
            <div id="modal-icon" style="font-size: 76px; filter: drop-shadow(0 4px 10px rgba(0,0,0,0.3));"></div>
            <span id="modal-dept-badge" class="modal-badge-dept"></span>
        </div>

        <!-- Modal Body Content -->
        <div class="modal-body">
            <h2 id="modal-title" class="modal-project-title"></h2>

            <!-- Quick Metadata -->
            <div class="modal-meta-grid">
                <div class="modal-meta-item">
                    <span class="modal-meta-label">👥 Tim Pengembang</span>
                    <span id="modal-team" class="modal-meta-val"></span>
                </div>
                <div class="modal-meta-item">
                    <span class="modal-meta-label">👨‍🏫 Pembimbing / Instruktur</span>
                    <span id="modal-mentor" class="modal-meta-val"></span>
                </div>
                <div class="modal-meta-item">
                    <span class="modal-meta-label">📅 Tahun Ajaran</span>
                    <span id="modal-year" class="modal-meta-val"></span>
                </div>
            </div>

            <!-- Description -->
            <div class="modal-section-heading">
                <span>📖</span>
                <span>Tentang Proyek & Latar Belakang</span>
            </div>
            <p id="modal-desc" class="modal-desc-text"></p>

            <!-- Key Features -->
            <div class="modal-section-heading">
                <span>✨</span>
                <span>Fitur Utama & Inovasi Karya</span>
            </div>
            <ul id="modal-features" class="modal-features-list"></ul>

            <!-- Tech Stack & Tools -->
            <div class="modal-section-heading">
                <span>🛠️</span>
                <span>Teknologi & Tools Digunakan</span>
            </div>
            <div id="modal-tech" class="modal-tech-pills"></div>

            <!-- Action Buttons -->
            <div class="modal-actions-row">
                <a id="modal-wa-link" href="#" target="_blank" class="modal-btn-wa">
                    <span>💬</span>
                    <span>Tertarik Proyek Serupa? Hubungi TEFA</span>
                </a>
                <button type="button" class="modal-btn-close-secondary" onclick="closePortfolioModal()">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let activeFilter = 'all';

    function setFilterCategory(category) {
        activeFilter = category;

        // Update pills active status
        document.querySelectorAll('.filter-pill-btn').forEach(btn => {
            if (btn.getAttribute('data-filter') === category) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });

        // Update dropdown selector
        const select = document.getElementById('portfolio-jurusan-select');
        if (select) {
            select.value = category;
        }

        filterPortfolios();
    }

    function handleSelectJurusan(val) {
        setFilterCategory(val);
    }

    function resetAllFilters() {
        const searchInput = document.getElementById('portfolio-search-input');
        if (searchInput) searchInput.value = '';
        setFilterCategory('all');
    }

    function filterPortfolios() {
        const query = (document.getElementById('portfolio-search-input')?.value || '').toLowerCase().trim();
        const cards = document.querySelectorAll('.portfolio-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const cardJurusan = card.getAttribute('data-jurusan') || '';
            const cardTitle = card.getAttribute('data-title') || '';
            const cardDesc = card.getAttribute('data-desc') || '';

            const matchesCategory = (activeFilter === 'all' || cardJurusan.toUpperCase() === activeFilter.toUpperCase());
            const matchesQuery = (!query || cardTitle.includes(query) || cardDesc.includes(query));

            if (matchesCategory && matchesQuery) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const noMatchAlert = document.getElementById('no-filter-match-alert');
        if (noMatchAlert) {
            noMatchAlert.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    }

    // Modal Interactions
    function openPortfolioModal(item) {
        const modal = document.getElementById('portfolio-modal');
        if (!modal) return;

        // Set Banner & Badges
        const banner = document.getElementById('modal-banner');
        if (banner) banner.style.background = item.image_bg;

        const icon = document.getElementById('modal-icon');
        if (icon) icon.textContent = item.icon;

        const deptBadge = document.getElementById('modal-dept-badge');
        if (deptBadge) {
            deptBadge.textContent = item.jurusan_name + ' (' + item.jurusan_code + ')';
            deptBadge.style.background = item.badge_bg;
            deptBadge.style.color = item.badge_color;
            deptBadge.style.border = '1px solid ' + item.badge_border;
        }

        // Set Information
        document.getElementById('modal-title').textContent = item.title;
        document.getElementById('modal-team').textContent = item.team;
        document.getElementById('modal-mentor').textContent = item.mentor;
        document.getElementById('modal-year').textContent = item.year;
        document.getElementById('modal-desc').textContent = item.full_desc || item.short_desc;

        // Populate Features List
        const featuresContainer = document.getElementById('modal-features');
        if (featuresContainer) {
            featuresContainer.innerHTML = '';
            if (item.features && item.features.length) {
                item.features.forEach(feat => {
                    const li = document.createElement('li');
                    li.className = 'modal-feature-item';
                    li.innerHTML = '<span class="modal-feature-icon">✓</span><span>' + feat + '</span>';
                    featuresContainer.appendChild(li);
                });
            }
        }

        // Populate Tech Stack
        const techContainer = document.getElementById('modal-tech');
        if (techContainer) {
            techContainer.innerHTML = '';
            if (item.tech_stack && item.tech_stack.length) {
                item.tech_stack.forEach(tech => {
                    const span = document.createElement('span');
                    span.className = 'modal-tech-pill';
                    span.textContent = tech;
                    techContainer.appendChild(span);
                });
            }
        }

        // WhatsApp Direct Link
        const waLink = document.getElementById('modal-wa-link');
        if (waLink) {
            const message = encodeURIComponent('Halo Admin Teaching Factory SMKN 4 Tanjungpinang, saya melihat portofolio "' + item.title + '" (' + item.jurusan_code + ') di katalog TEFA. Saya tertarik untuk berkonsultasi mengenai pemesanan / pembuatan proyek serupa.');
            waLink.href = 'https://wa.me/628781948317?text=' + message;
        }

        modal.style.display = 'grid';
        document.body.style.overflow = 'hidden';
    }

    function closePortfolioModal() {
        const modal = document.getElementById('portfolio-modal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    function handleBackdropClick(event) {
        if (event.target.id === 'portfolio-modal') {
            closePortfolioModal();
        }
    }

    // Escape Key Listener to dismiss modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePortfolioModal();
        }
    });
</script>
@endsection
