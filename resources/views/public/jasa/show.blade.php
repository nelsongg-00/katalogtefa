@extends('layouts.public')

@section('title', $service->nama_layanan . ' — Layanan Jasa TEFA SMKN 4 Tanjungpinang')

@section('content')
<style>
    /* ==========================================================
       DETAIL LAYANAN JASA PAGE — restyle mengikuti desain halaman
       Katalog Produk (resources/views/public/produk.blade.php =
       source of truth), konsisten dengan detail produk. Token
       identik, di-scope ke .jasa-detail-page agar tidak bocor ke
       halaman lain.
       ========================================================== */
    .jasa-detail-page {
        --color-primary: #0a4aa6;
        --color-primary-dark: #00357f;
        --color-accent-blue: #1414c8;
        --color-text: #111111;
        --color-muted: #666666;
        --color-bg: #fafafa;
        --color-white: #ffffff;
        --color-green: #10b981;
        --color-green-dark: #059669;
        --color-placeholder: #cad2db;
        --radius-card: 15px;
        --radius-pill: 999px;

        background-color: var(--color-bg);
        color: var(--color-text);
        padding-bottom: 90px;
    }

    .jasa-detail-page .visually-hidden {
        position: absolute;
        width: 1px;
        height: 1px;
        overflow: hidden;
        clip: rect(0 0 0 0);
        white-space: nowrap;
    }

    .jasa-detail-page .detail-main {
        padding: 28px 16px 0;
    }

    /* ---------- BREADCRUMB ---------- */
    .jasa-detail-page .breadcrumb {
        max-width: 966px;
        margin: 0 auto 28px;
        font-size: 13px;
        color: var(--color-muted);
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .jasa-detail-page .breadcrumb a {
        color: var(--color-primary);
        font-weight: 700;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .jasa-detail-page .breadcrumb a:hover {
        color: var(--color-primary-dark);
        text-decoration: underline;
    }

    .jasa-detail-page .breadcrumb .breadcrumb-sep {
        color: var(--color-placeholder);
        user-select: none;
    }

    .jasa-detail-page .breadcrumb .breadcrumb-current {
        font-weight: 700;
        color: var(--color-text);
    }

    /* ---------- DETAIL CARD ---------- */
    .jasa-detail-page .detail-card {
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

    .jasa-detail-page .detail-thumb {
        position: relative;
        width: 100%;
        aspect-ratio: 16 / 9;
        overflow: hidden;
        background: var(--color-placeholder);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .jasa-detail-page .detail-thumb-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .jasa-detail-page .detail-thumb-fallback {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 88px;
        color: var(--color-white);
        position: relative;
    }

    .jasa-detail-page .thumb-pattern {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.15) 1px, transparent 1px);
        background-size: 16px 16px;
    }

    .jasa-detail-page .thumb-dept-badge {
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

    .jasa-detail-page .thumb-status-badge {
        position: absolute;
        top: 14px;
        right: 14px;
        font-size: 11px;
        font-weight: 800;
        padding: 5px 12px;
        border-radius: var(--radius-pill);
        background: rgba(16, 185, 129, 0.9);
        color: var(--color-white);
        backdrop-filter: blur(6px);
        z-index: 2;
    }

    /* ---------- DETAIL BODY ---------- */
    .jasa-detail-page .detail-body {
        padding: 28px 30px 30px;
        display: flex;
        flex-direction: column;
    }

    .jasa-detail-page .detail-badges-row {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 12px;
    }

    .jasa-detail-page .detail-badge {
        font-size: 12px;
        font-weight: 800;
        padding: 4px 12px;
        border-radius: var(--radius-pill);
    }

    .jasa-detail-page .detail-badge-tefa {
        color: #059669;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
    }

    .jasa-detail-page .detail-title {
        font-size: clamp(1.4rem, 3.5vw, 1.9rem);
        font-weight: 900;
        color: var(--color-primary);
        line-height: 1.3;
        margin-bottom: 10px;
    }

    .jasa-detail-page .detail-price {
        font-size: clamp(1.4rem, 3.5vw, 1.9rem);
        font-weight: 900;
        color: var(--color-primary);
        margin-bottom: 22px;
    }

    .jasa-detail-page .detail-desc-label {
        font-size: 12px;
        font-weight: 700;
        color: #666666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 6px;
    }

    .jasa-detail-page .detail-desc-box {
        background: var(--color-bg);
        border-radius: 14px;
        padding: 16px 18px;
        border: 1px solid #e6eaef;
        font-size: 14px;
        color: #334155;
        line-height: 1.65;
        margin-bottom: 22px;
    }

    .jasa-detail-page .detail-wa-notice {
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

    /* ---------- CTA ROW ---------- */
    .jasa-detail-page .detail-actions-row {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .jasa-detail-page .btn-detail-wa {
        flex: 1;
        min-width: 220px;
        background: var(--color-green);
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

    .jasa-detail-page .btn-detail-wa:hover {
        background: var(--color-green-dark);
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(16, 185, 129, 0.4);
    }

    .jasa-detail-page .btn-detail-back {
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

    .jasa-detail-page .btn-detail-back:hover {
        background: #e2e8f0;
        color: #111111;
    }

    /* ---------- BREAKPOINT >= 640px (tablet) ---------- */
    @media (min-width: 640px) {
        .jasa-detail-page .detail-main {
            padding: 40px 16px 0;
        }

        .jasa-detail-page .detail-body {
            padding: 32px 36px 36px;
        }
    }
</style>

<div class="jasa-detail-page">
    <main class="detail-main">
        <!-- ================= BREADCRUMB ================= -->
        <nav class="breadcrumb" aria-label="Navigasi breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <span class="breadcrumb-sep" aria-hidden="true">/</span>
            <a href="{{ route('jasa') }}">Layanan Jasa</a>
            <span class="breadcrumb-sep" aria-hidden="true">/</span>
            <span class="breadcrumb-current">{{ $service->nama_layanan }}</span>
        </nav>

        <!-- ================= DETAIL CARD ================= -->
        <article class="detail-card" aria-labelledby="detail-title">
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
            @endphp

            <!-- Hero Thumbnail -->
            <div class="detail-thumb">
                @if($photoUrl)
                    <img src="{{ $photoUrl }}"
                         alt="{{ $service->nama_layanan }}"
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

                <!-- Status Badge -->
                <span class="thumb-status-badge">
                    Tersedia
                </span>
            </div>

            <!-- Konten Detail Jasa -->
            <div class="detail-body">
                <div class="detail-badges-row">
                    <span class="detail-badge" style="{{ $badgeStyle }}">
                        {{ $deptNama }} ({{ $deptKode }})
                    </span>
                    <span class="detail-badge detail-badge-tefa">
                        Dikerjakan Siswa &amp; Instruktur TEFA
                    </span>
                </div>

                <h1 id="detail-title" class="detail-title">
                    {{ $service->nama_layanan }}
                </h1>

                <div class="detail-price">
                    Mulai Rp {{ number_format($service->estimasi_harga, 0, ',', '.') }}
                </div>

                <div>
                    <label class="detail-desc-label">Deskripsi Lengkap &amp; Lingkup Layanan</label>
                    <div class="detail-desc-box">
                        {{ $service->deskripsi ?: 'Tidak ada keterangan detail untuk layanan ini.' }}
                    </div>
                </div>

                <div class="detail-wa-notice">
                    <span style="font-size: 20px;" aria-hidden="true">💬</span>
                    <span><strong>Konsultasi Langsung via WhatsApp:</strong> Tim instruktur dan admin jurusan TEFA akan mendiskusikan kebutuhan spesifikasi, estimasi waktu, serta penugasan siswa untuk proyek Anda.</span>
                </div>

                <!-- Tombol Aksi -->
                <div class="detail-actions-row">
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="btn-detail-wa">
                        <span aria-hidden="true">💬</span>
                        <span>Konsultasi via WhatsApp</span>
                    </a>
                    <a href="{{ route('jasa') }}" class="btn-detail-back">
                        <span aria-hidden="true">←</span>
                        <span>Kembali ke Katalog</span>
                    </a>
                </div>
            </div>
        </article>
    </main>
</div>
@endsection
