<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Katalog TEFA SMKN 4 Tanjungpinang') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
                font-family: 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
            }

            body {
                background: linear-gradient(135deg, #050f26 0%, #0a215e 50%, #0f172a 100%);
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                color: #0f172a;
                position: relative;
                overflow-x: hidden;
            }

            /* Ambient Glow Background Effect */
            body::before {
                content: '';
                position: fixed;
                top: -150px;
                left: -150px;
                width: 600px;
                height: 600px;
                background: radial-gradient(circle, rgba(37, 99, 235, 0.25) 0%, rgba(37, 99, 235, 0) 70%);
                pointer-events: none;
                z-index: 0;
            }

            body::after {
                content: '';
                position: fixed;
                bottom: -200px;
                right: -200px;
                width: 700px;
                height: 700px;
                background: radial-gradient(circle, rgba(255, 183, 3, 0.12) 0%, rgba(255, 183, 3, 0) 70%);
                pointer-events: none;
                z-index: 0;
            }

            /* Top Bar */
            .guest-topbar {
                width: 100%;
                max-width: 1200px;
                margin: 0 auto;
                padding: 24px 24px 10px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                position: relative;
                z-index: 10;
            }

            .btn-back-home {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                color: #e2e8f0;
                text-decoration: none;
                font-size: 13.5px;
                font-weight: 600;
                background: rgba(255, 255, 255, 0.08);
                padding: 8px 18px;
                border-radius: 999px;
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.15);
                transition: all 0.25s ease;
            }

            .btn-back-home:hover {
                background: rgba(255, 255, 255, 0.18);
                color: #ffffff;
                transform: translateX(-3px);
                border-color: rgba(255, 255, 255, 0.3);
            }

            .guest-top-tag {
                color: rgba(226, 232, 240, 0.75);
                font-size: 12.5px;
                font-weight: 600;
                display: flex;
                align-items: center;
                gap: 6px;
            }

            /* Split Showcase Canvas */
            .guest-main-container {
                width: 100%;
                max-width: 1160px;
                margin: 20px auto 40px;
                padding: 0 24px;
                display: grid;
                grid-template-columns: 1.15fr 1fr;
                gap: 40px;
                align-items: center;
                position: relative;
                z-index: 10;
            }

            @media (max-width: 960px) {
                .guest-main-container {
                    grid-template-columns: 1fr;
                    gap: 30px;
                    margin-top: 10px;
                }
            }

            /* Left Panel: Hero Showcase */
            .guest-showcase-panel {
                color: #ffffff;
                padding: 20px 10px;
            }

            .showcase-badge-pill {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: rgba(255, 183, 3, 0.15);
                color: #ffb703;
                border: 1px solid rgba(255, 183, 3, 0.35);
                padding: 6px 16px;
                border-radius: 999px;
                font-size: 12px;
                font-weight: 800;
                letter-spacing: 0.5px;
                text-transform: uppercase;
                margin-bottom: 24px;
            }

            .showcase-brand-header {
                display: flex;
                align-items: center;
                gap: 16px;
                margin-bottom: 24px;
            }

            .showcase-brand-logo {
                height: 64px;
                width: auto;
                object-fit: contain;
                filter: drop-shadow(0 8px 16px rgba(0,0,0,0.3));
            }

            .showcase-brand-text h2 {
                font-size: 22px;
                font-weight: 900;
                color: #ffffff;
                letter-spacing: 0.5px;
                line-height: 1.2;
            }

            .showcase-brand-text span {
                font-size: 13px;
                font-weight: 800;
                color: #60a5fa;
                letter-spacing: 1.5px;
                text-transform: uppercase;
            }

            .showcase-heading {
                font-size: 32px;
                font-weight: 900;
                line-height: 1.25;
                margin-bottom: 16px;
                letter-spacing: -0.5px;
            }

            .showcase-heading .highlight {
                color: #ffb703;
            }

            .showcase-desc {
                font-size: 15px;
                line-height: 1.65;
                color: #cbd5e1;
                margin-bottom: 32px;
                max-width: 520px;
            }

            /* Showcase Feature Cards */
            .showcase-features {
                display: flex;
                flex-direction: column;
                gap: 14px;
                margin-bottom: 32px;
            }

            .showcase-feature-item {
                display: flex;
                align-items: center;
                gap: 14px;
                background: rgba(255, 255, 255, 0.06);
                border: 1px solid rgba(255, 255, 255, 0.12);
                border-radius: 14px;
                padding: 12px 18px;
                backdrop-filter: blur(8px);
                transition: transform 0.2s ease, background 0.2s ease;
            }

            .showcase-feature-item:hover {
                transform: translateX(4px);
                background: rgba(255, 255, 255, 0.1);
            }

            .showcase-feature-icon {
                width: 38px;
                height: 38px;
                border-radius: 10px;
                background: linear-gradient(135deg, rgba(37, 99, 235, 0.4), rgba(59, 130, 246, 0.2));
                border: 1px solid rgba(147, 197, 253, 0.3);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 18px;
                flex-shrink: 0;
            }

            .showcase-feature-text h4 {
                font-size: 13.5px;
                font-weight: 700;
                color: #ffffff;
                margin-bottom: 2px;
            }

            .showcase-feature-text p {
                font-size: 12px;
                color: #94a3b8;
                margin: 0;
                line-height: 1.35;
            }

            /* Right Panel: Form Card Container */
            .guest-card-wrapper {
                width: 100%;
                display: flex;
                justify-content: center;
            }

            .guest-form-card {
                width: 100%;
                background: #ffffff;
                border-radius: 24px;
                box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.15);
                padding: 38px 36px;
                color: #0f172a;
                position: relative;
            }

            @media (max-width: 600px) {
                .guest-form-card {
                    padding: 28px 22px;
                    border-radius: 20px;
                }
                .showcase-heading {
                    font-size: 26px;
                }
                .guest-topbar {
                    flex-direction: column;
                    gap: 12px;
                    align-items: flex-start;
                }
            }

            /* Footer */
            .guest-footer {
                text-align: center;
                padding: 20px 24px;
                color: rgba(226, 232, 240, 0.6);
                font-size: 12.5px;
                position: relative;
                z-index: 10;
            }
        </style>
    </head>
    <body>
        <!-- Header Bar -->
        <div class="guest-topbar">
            <a href="{{ route('home') }}" class="btn-back-home">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali ke Beranda</span>
            </a>

            <div class="guest-top-tag">
                <span>🏫</span> Teaching Factory • SMKN 4 Tanjungpinang
            </div>
        </div>

        <!-- Main Split Container -->
        <div class="guest-main-container">
            <!-- Left Hero Showcase Panel -->
            <div class="guest-showcase-panel">
                <span class="showcase-badge-pill">
                    ✨ PORTAL RESMI TEACHING FACTORY
                </span>

                <div class="showcase-brand-header">
                    <a href="{{ route('home') }}" style="text-decoration: none; display: inline-block;">
                        <img src="{{ asset('asset/img/logo-smkn4.png') }}" alt="Logo SMKN 4" class="showcase-brand-logo">
                    </a>
                    <div class="showcase-brand-text">
                        <h2>SMKN 4 TANJUNGPINANG</h2>
                        <span>KATALOG TEACHING FACTORY</span>
                    </div>
                </div>

                <h1 class="showcase-heading">
                    Pusat Inovasi & Produksi Nyata <span class="highlight">Standar Industri</span>
                </h1>

                <p class="showcase-desc">
                    Akses ekosistem produksi kejuruan modern: pesan produk fisik, konsultasi layanan jasa, dan pantau progres pengerjaan pesanan secara transparan.
                </p>

                <!-- 3 Feature Cards -->
                <div class="showcase-features">
                    <div class="showcase-feature-item">
                        <div class="showcase-feature-icon">🚀</div>
                        <div class="showcase-feature-text">
                            <h4>6 Program Keahlian Unggulan</h4>
                            <p>RPL, DKV, TKJ, Animasi, PSPT, dan Pengembangan Gim siap melayani kebutuhan Anda.</p>
                        </div>
                    </div>

                    <div class="showcase-feature-item">
                        <div class="showcase-feature-icon">🛡️</div>
                        <div class="showcase-feature-text">
                            <h4>Kualitas Terstandarisasi Industri</h4>
                            <p>Dikerjakan siswa berprestasi di bawah bimbingan langsung instruktur berpengalaman.</p>
                        </div>
                    </div>

                    <div class="showcase-feature-item">
                        <div class="showcase-feature-icon">📦</div>
                        <div class="showcase-feature-text">
                            <h4>Pelacakan Pesanan Real-time</h4>
                            <p>Pantau tahapan pengerjaan dan bukti progres proyek Anda secara langsung di sistem.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Form Panel Card -->
            <div class="guest-card-wrapper">
                <div class="guest-form-card">
                    {{ $slot }}
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="guest-footer">
            &copy; {{ date('Y') }} Teaching Factory SMKN 4 Tanjungpinang. Hak Cipta Dilindungi.
        </div>
    </body>
</html>
