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
                background: linear-gradient(135deg, #0a215e 0%, #1e3a8a 60%, #0f172a 100%);
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                color: #0f172a;
            }

            .guest-header-bar {
                width: 100%;
                max-width: 1100px;
                margin: 0 auto;
                padding: 24px 20px 10px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .btn-back-home {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                color: #e2e8f0;
                text-decoration: none;
                font-size: 13.5px;
                font-weight: 600;
                background: rgba(255, 255, 255, 0.1);
                padding: 8px 16px;
                border-radius: 999px;
                backdrop-filter: blur(8px);
                border: 1px solid rgba(255, 255, 255, 0.15);
                transition: all 0.2s ease;
            }

            .btn-back-home:hover {
                background: rgba(255, 255, 255, 0.2);
                color: #ffffff;
                transform: translateX(-2px);
            }

            .guest-content-wrapper {
                width: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 20px 16px 40px;
            }

            .guest-brand-box {
                text-align: center;
                margin-bottom: 24px;
            }

            .guest-brand-box img {
                height: 64px;
                width: auto;
                object-fit: contain;
                filter: drop-shadow(0 4px 10px rgba(0,0,0,0.3));
                transition: transform 0.2s ease;
            }

            .guest-brand-box img:hover {
                transform: scale(1.05);
            }

            .guest-brand-title {
                color: #ffffff;
                font-size: 18px;
                font-weight: 800;
                letter-spacing: 0.5px;
                margin-top: 10px;
            }

            .guest-brand-subtitle {
                color: #ffb703;
                font-size: 11.5px;
                font-weight: 800;
                letter-spacing: 1.5px;
                text-transform: uppercase;
                margin-top: 2px;
                display: block;
            }

            /* Solid White Card for Login/Register */
            .guest-card {
                width: 100%;
                max-width: 440px;
                background: #ffffff;
                border-radius: 20px;
                box-shadow: 0 20px 45px -10px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.1);
                padding: 36px 32px;
                color: #0f172a;
            }

            .guest-footer {
                text-align: center;
                padding: 16px 20px;
                color: rgba(226, 232, 240, 0.6);
                font-size: 12px;
            }
        </style>
    </head>
    <body>
        <!-- Header Bar -->
        <div class="guest-header-bar">
            <a href="{{ route('home') }}" class="btn-back-home">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali ke Beranda</span>
            </a>

            <div style="color: rgba(255,255,255,0.7); font-size: 12px; font-weight: 600;">
                Teaching Factory • SMKN 4 Tanjungpinang
            </div>
        </div>

        <!-- Main Card Container -->
        <div class="guest-content-wrapper">
            <!-- Brand Logo & Header -->
            <div class="guest-brand-box">
                <a href="{{ route('home') }}" style="text-decoration: none; display: inline-block;">
                    <img src="{{ asset('asset/img/logo-smkn4.png') }}" alt="Logo SMKN 4">
                </a>
                <div class="guest-brand-title">SMKN 4 TANJUNGPINANG</div>
                <span class="guest-brand-subtitle">Katalog Teaching Factory</span>
            </div>

            <!-- Card Box Slot -->
            <div class="guest-card">
                {{ $slot }}
            </div>
        </div>

        <!-- Footer -->
        <div class="guest-footer">
            &copy; {{ date('Y') }} Teaching Factory SMKN 4 Tanjungpinang. Hak Cipta Dilindungi.
        </div>
    </body>
</html>
