@extends('layouts.public')

@section('title', $jurusan['nama_jurusan'].' — TEFA SMKN 4 Tanjungpinang')

@section('content')
<style>
    /* ==========================================================
       HALAMAN JURUSAN — mengikuti pola halaman Katalog Produk.
       Token di-scope ke .jurusan-page (bukan :root) supaya tidak
       bocor ke halaman lain. Semua selector di-prefix .jurusan-page
       agar menang spesifisitas atas aturan global di layouts/public.
       ========================================================== */
    .jurusan-page {
        --color-primary: #0a4aa6;
        --color-primary-dark: #00357f;
        --color-text: #111111;
        --color-muted: #666666;
        --color-bg: #fafafa;
        --color-white: #ffffff;
        --color-yellow: #f2b630;
        --radius-banner: 40px;
        --radius-card: 15px;
        --radius-pill: 999px;
        --font-display: 'Open Sauce Sans', 'Anton', 'Impact', 'Arial Narrow', sans-serif;

        background-color: var(--color-bg);
        color: var(--color-text);
        padding-bottom: 90px;
    }

    .jurusan-page .visually-hidden {
        position: absolute;
        width: 1px;
        height: 1px;
        overflow: hidden;
        clip: rect(0 0 0 0);
        white-space: nowrap;
    }

    .jurusan-page .jurusan-main {
        padding: 32px 16px 0;
    }

    /* ---------- BANNER (pola banner halaman Katalog Produk) ---------- */
    .jurusan-page .banner {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        max-width: 966px;
        min-height: 220px;
        margin: 0 auto;
        overflow: hidden;
        border-radius: var(--radius-banner);
        /* Gradien per jurusan dikirim dari controller (config/jurusan.php). */
        background: linear-gradient(120deg, var(--banner-from) 0%, var(--banner-to) 100%);
        color: var(--color-white);
    }

    .jurusan-page .banner__content {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 24px 16px;
        text-align: center;
    }

    .jurusan-page .banner__kode {
        display: inline-flex;
        align-items: center;
        height: 26px;
        margin-bottom: 14px;
        padding: 0 14px;
        border: 1px solid var(--color-white);
        border-radius: var(--radius-pill);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.5px;
    }

    .jurusan-page .banner__title {
        font-family: var(--font-display);
        font-size: clamp(2rem, 6vw, 3.75rem);
        /* Anton (font display lama) berbobot berat pada 400 — Open Sauce Sans perlu
           bobot eksplisit agar judul banner tetap setebal sebelumnya. */
        font-weight: 800;
        line-height: 1.05;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* ---------- DESKRIPSI ---------- */
    .jurusan-page .deskripsi {
        max-width: 800px;
        margin: 44px auto 0;
        padding: 28px 24px;
        background: var(--color-white);
        border: 1px solid #e6eaef;
        border-radius: var(--radius-card);
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.05);
    }

    .jurusan-page .deskripsi__heading {
        margin: 0 0 12px;
        font-size: 19px;
        font-weight: 800;
        color: var(--color-primary);
    }

    .jurusan-page .deskripsi__text {
        margin: 0;
        font-size: 14.5px;
        line-height: 1.7;
        color: var(--color-muted);
    }

    /* ---------- BREAKPOINT >= 640px (tablet) ---------- */
    @media (min-width: 640px) {
        .jurusan-page .banner {
            min-height: 260px;
        }

        .jurusan-page .deskripsi {
            padding: 32px 28px;
        }
    }

    /* ---------- BREAKPOINT >= 1024px (desktop) ---------- */
    @media (min-width: 1024px) {
        .jurusan-page .jurusan-main {
            padding-top: 56px;
        }

        .jurusan-page .banner {
            height: 310px;
        }

        .jurusan-page .deskripsi__text {
            font-size: 15px;
        }
    }
</style>

<div class="jurusan-page"
     style="--banner-from: {{ $jurusan['gradasi'][0] }}; --banner-to: {{ $jurusan['gradasi'][1] }};">
    <main class="jurusan-main">
        <!-- ================= BANNER ================= -->
        <section class="banner" aria-labelledby="banner-title">
            <div class="banner__content">
                <span class="banner__kode">Program Keahlian — {{ $jurusan['kode'] }}</span>
                <h1 class="banner__title" id="banner-title">{{ $jurusan['nama_jurusan'] }}</h1>
            </div>
        </section>

        <!-- ================= DESKRIPSI ================= -->
        <section class="deskripsi" aria-labelledby="deskripsi-title">
            <h2 class="deskripsi__heading" id="deskripsi-title">Tentang Program Keahlian {{ $jurusan['kode'] }}</h2>
            <p class="deskripsi__text">{{ $jurusan['deskripsi'] }}</p>
        </section>
    </main>
</div>

@endsection
