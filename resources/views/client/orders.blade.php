@extends('layouts.public')

@section('title', 'Riwayat Pesanan Saya — Katalog TEFA SMKN 4 Tanjungpinang')

@section('content')
{{-- Font Open Sauce Sans — cara yang sama dengan profile/partials/card.blade.php --}}
<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
<link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/400.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/500.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/600.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/700.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/800.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/900.css" rel="stylesheet">

<style>
    /* ==========================================================
       PESANAN SAYA — restyle sesuai desain baru.
       Token di-scope ke .my-orders-page (bukan :root) supaya tidak
       bocor ke halaman lain. Semua selector di-prefix .my-orders-page
       agar menang spesifisitas atas aturan global di layouts/public.
       ========================================================== */
    .my-orders-page {
        /* Warna */
        --color-primary: #0a4aa6;
        --color-primary-dark: #00357f;
        --color-accent-blue: #1414c8;
        --color-action: #00a3ff;
        --color-text: #111111;
        --color-muted: #666666;
        --color-bg: #fafafa;
        --color-white: #ffffff;
        --color-yellow: #f2b630;
        --color-border: #cfcfcf;
        --color-placeholder: #cad2db;
        --color-placeholder-text: #f04848;

        /* Warna badge status (membedakan tiap status sekali lihat) */
        --status-process: #ffb400;   /* Sedang Diproses   — kuning/amber */
        --status-waiting: #cdcdcd;   /* Menunggu Konfirmasi — abu-abu   */
        --status-ready: #4fb7f5;     /* Siap Diambil      — biru muda  */
        --status-done: #62e89c;      /* Selesai           — hijau      */
        --status-rejected: #ff1f2d;  /* Dibatalkan        — merah      */

        /* Tipografi */
        --font-body: "Open Sauce Sans", "Hanken Grotesk", "Helvetica Neue", Arial, sans-serif;
        --fs-xs: 0.8125rem;
        --fs-sm: 0.875rem;
        --fs-base: 0.9375rem;
        --fs-md: 1rem;
        --fs-price: 1.375rem;
        --fs-order-title: 1.5rem;
        --fs-hero-title: clamp(1.75rem, 5vw, 3rem);
        --fs-hero-text: clamp(1rem, 2.5vw, 1.375rem);

        /* Jarak & bentuk */
        --space-2: 0.5rem;
        --space-3: 0.75rem;
        --space-4: 1rem;
        --space-5: 1.5rem;
        --space-6: 2rem;
        --radius-card: 22px;
        --radius-thumb: 10px;
        --radius-badge: 8px;
        --shadow-card: 0 2px 10px rgba(0, 0, 0, 0.12);
        --orders-width: 983px;

        background: var(--color-bg);
        color: var(--color-text);
        font-family: var(--font-body);
        font-size: var(--fs-base);
        line-height: 1.5;
    }

    /* Font desain harus menang atas reset `*` global di layouts/public */
    .my-orders-page * { font-family: var(--font-body); }

    /* Reset ringkas (hanya di dalam halaman ini) */
    .my-orders-page *::before,
    .my-orders-page *::after { box-sizing: border-box; }

    .my-orders-page h1,
    .my-orders-page h2,
    .my-orders-page h3,
    .my-orders-page p { margin: 0; }

    .my-orders-page ul { margin: 0; padding: 0; list-style: none; }
    .my-orders-page img { max-width: 100%; display: block; }
    .my-orders-page a { color: inherit; text-decoration: none; }
    .my-orders-page :focus-visible { outline: 3px solid var(--color-yellow); outline-offset: 2px; }

    /* ==========================================================
       1. HERO
       ========================================================== */
    .my-orders-page .hero {
        padding: 40px var(--space-4) 48px;
        background: var(--color-primary);
        color: var(--color-white);
        text-align: center;
    }

    .my-orders-page .hero__title {
        font-size: var(--fs-hero-title);
        font-weight: 700;
        line-height: 1.2;
        text-transform: uppercase;
    }

    .my-orders-page .hero__text {
        max-width: 41rem;
        margin: 23px auto 0;
        font-size: var(--fs-hero-text);
        line-height: 1.1;
    }

    /* ==========================================================
       2. DAFTAR PESANAN
       ========================================================== */
    .my-orders-page .main { padding: 43px var(--space-4) 149px; }

    .my-orders-page .orders {
        display: grid;
        gap: 35px;
        max-width: var(--orders-width);
        margin: 0 auto;
    }

    .my-orders-page .order {
        display: grid;
        grid-template-columns: 80px 1fr;
        gap: var(--space-4);
        padding: var(--space-4);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-card);
        background: var(--color-white);
        box-shadow: var(--shadow-card);
    }

    .my-orders-page .order__thumb {
        display: grid;
        place-items: center;
        width: 80px;
        height: 80px;
        border-radius: var(--radius-thumb);
        overflow: hidden;
        background: var(--color-placeholder);
        font-size: var(--fs-md);
        font-weight: 700;
        color: var(--color-placeholder-text);
    }

    .my-orders-page .order__thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .my-orders-page .order__title {
        font-size: 1.25rem;
        font-weight: 600;
        line-height: 1.2;
    }

    .my-orders-page .order__meta {
        margin-top: 12px;
        font-size: var(--fs-md);
        line-height: 1.3;
    }

    .my-orders-page .order__meta strong { font-weight: 700; }

    .my-orders-page .order__code {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        margin-top: 10px;
        font-size: var(--fs-xs);
        color: var(--color-muted);
    }

    .my-orders-page .order__code-chip {
        font-family: inherit;
        font-size: 12px;
        font-weight: 700;
        color: var(--color-primary);
        background: rgba(10, 74, 166, 0.08);
        border: 1px solid rgba(10, 74, 166, 0.2);
        padding: 3px 9px;
        border-radius: 6px;
    }

    .my-orders-page .order__side {
        grid-column: 1 / -1;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: var(--space-4);
    }

    .my-orders-page .order__total { text-align: right; line-height: 1.3; }

    .my-orders-page .order__total-label {
        font-size: var(--fs-sm);
        font-weight: 700;
    }

    .my-orders-page .order__total-price {
        display: block;
        margin-top: 6px;
        font-size: var(--fs-price);
        font-weight: 700;
        line-height: 1.2;
        color: var(--color-accent-blue);
    }

    /* Tautan detail — mekanisme lama dipertahankan (explicit <a>) */
    .my-orders-page .order__detail {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 10px;
        font-size: 13px;
        font-weight: 700;
        color: var(--color-action);
    }

    .my-orders-page .order__detail:hover {
        color: var(--color-primary);
        text-decoration: underline;
    }

    /* ==========================================================
       3. BADGE STATUS — 5 warna membedakan status
       ========================================================== */
    .my-orders-page .status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 136px;
        height: 36px;
        padding: 0 14px;
        border-radius: var(--radius-badge);
        font-size: var(--fs-sm);
        font-weight: 700;
        color: #000000;
        white-space: nowrap;
    }

    .my-orders-page .status--process { background: var(--status-process); }
    .my-orders-page .status--waiting { background: var(--status-waiting); }
    .my-orders-page .status--ready { background: var(--status-ready); }
    .my-orders-page .status--done { background: var(--status-done); }
    .my-orders-page .status--rejected { background: var(--status-rejected); }

    /* ==========================================================
       4. FLASH SUKSES
       ========================================================== */
    .my-orders-page .orders-flash {
        max-width: var(--orders-width);
        margin: 0 auto 24px;
    }

    /* ==========================================================
       5. EMPTY STATE
       ========================================================== */
    .my-orders-page .empty-card {
        background: var(--color-white);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-card);
        box-shadow: var(--shadow-card);
        padding: 60px 24px;
        text-align: center;
    }

    .my-orders-page .empty-card__icon {
        width: 72px;
        height: 72px;
        border-radius: var(--radius-card);
        background: var(--color-placeholder);
        display: grid;
        place-items: center;
        font-size: 34px;
        margin: 0 auto 18px;
    }

    .my-orders-page .empty-card__title {
        font-size: 1.25rem;
        font-weight: 800;
    }

    .my-orders-page .empty-card__text {
        max-width: 480px;
        margin: 8px auto 25px;
        color: var(--color-muted);
        font-size: 14.5px;
        line-height: 1.6;
    }

    .my-orders-page .empty-card__actions {
        display: flex;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .my-orders-page .empty-card__actions a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
    }

    .my-orders-page .empty-card__actions .btn-ghost {
        background: var(--color-white);
        color: var(--color-text);
        border: 1px solid var(--color-border);
    }

    .my-orders-page .empty-card__actions .btn-ghost:hover { background: var(--color-bg); }

    /* ==========================================================
       6. BREAKPOINT >= 640px (tablet)
       ========================================================== */
    @media (min-width: 640px) {
        .my-orders-page .hero { padding: 59px var(--space-4) 85px; }

        .my-orders-page .order {
            grid-template-columns: 130px 1fr auto;
            padding: 24px;
            gap: 0 26px;
            min-height: 177px;
        }

        .my-orders-page .order__thumb {
            width: 130px;
            height: 130px;
            font-size: 1.375rem;
        }

        .my-orders-page .order__title { font-size: var(--fs-order-title); }
        .my-orders-page .order__meta { margin-top: 17px; }

        .my-orders-page .order__side {
            grid-column: auto;
            flex-direction: column;
            align-items: flex-end;
            justify-content: space-between;
        }

        .my-orders-page .order__total { margin-right: 8px; }
    }
</style>

<div class="my-orders-page">

    {{-- 1. HERO --}}
    <section class="hero">
        <h1 class="hero__title">Pesanan Saya</h1>
        <p class="hero__text">Kelola dan pantau seluruh riwayat transaksi produk fisik maupun layanan jasa TEFA Anda secara praktis dan terpusat</p>
    </section>

    {{-- 2. DAFTAR PESANAN --}}
    <div class="main">

        @if(session('success'))
            <div class="orders-flash" style="background: #ecfdf5; border-left: 4px solid #10b981; color: #065f46; padding: 14px 18px; border-radius: 12px; font-weight: 600; font-size: 13.5px; display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 16px;">✓</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <ul class="orders">
            @forelse($orders as $order)
                @php
                    // Pemetaan status: nilai asli di orders.status → label + warna desain.
                    // Default (termasuk nilai tak dikenal) = Menunggu Konfirmasi (abu-abu).
                    $statusKey = $order->status;
                    $badgeClass = match (true) {
                        in_array($statusKey, ['selesai', 'completed'], true) => 'status--done',
                        in_array($statusKey, ['bisa_diambil'], true) => 'status--ready',
                        in_array($statusKey, ['sedang_dikemas', 'in_progress', 'review'], true) => 'status--process',
                        in_array($statusKey, ['dibatalkan', 'cancelled'], true) => 'status--rejected',
                        default => 'status--waiting',
                    };
                    $badgeText = match (true) {
                        in_array($statusKey, ['selesai', 'completed'], true) => 'Selesai',
                        in_array($statusKey, ['bisa_diambil'], true) => 'Siap Diambil',
                        in_array($statusKey, ['sedang_dikemas', 'in_progress', 'review'], true) => 'Sedang Diproses',
                        in_array($statusKey, ['dibatalkan', 'cancelled'], true) => 'Dibatalkan',
                        default => 'Menunggu Konfirmasi',
                    };
                @endphp

                <li>
                    <article class="order">
                        <div class="order__thumb">
                            @if($order->product && $order->product->foto)
                                <img src="{{ asset('storage/' . $order->product->foto) }}" alt="{{ $order->product->nama_produk }}">
                            @elseif($order->service && $order->service->foto)
                                <img src="{{ asset('storage/' . $order->service->foto) }}" alt="{{ $order->service->nama_layanan }}">
                            @else
                                <span>Gambar</span>
                            @endif
                        </div>

                        <div class="order__info">
                            <h2 class="order__title">
                                {{ $order->product->nama_produk ?? ($order->service->nama_layanan ?? 'Item Pesanan TEFA') }}
                            </h2>
                            <p class="order__meta">
                                Unit : <strong>{{ $order->department->nama_jurusan ?? ($order->product->jurusan->nama_jurusan ?? 'Unit TEFA') }}</strong><br>
                                Lokasi Pengambilan : <strong>{{ $order->lokasi_pengambilan ?? 'Lab / Unit Produksi SMKN 4 Tanjungpinang' }}</strong>
                            </p>
                            <p class="order__code">
                                <span class="order__code-chip">{{ $order->order_code }}</span>
                                <span>{{ $order->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                            </p>
                        </div>

                        <div class="order__side">
                            <span class="status {{ $badgeClass }}">{{ $badgeText }}</span>
                            <div class="order__total">
                                <p>
                                    <span class="order__total-label">Total Biaya :</span>
                                    <span class="order__total-price">Rp. {{ number_format($order->total_harga ?: $order->total_biaya, 0, ',', '.') }}</span>
                                </p>
                                <a class="order__detail"
                                   href="{{ route('client.orders.show', $order->id) }}"
                                   aria-label="Detail status pesanan {{ $order->order_code }}">
                                    <span>Detail Status &amp; Lokasi</span>
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        </div>
                    </article>
                </li>
            @empty
                <li>
                    <div class="empty-card">
                        <div class="empty-card__icon" aria-hidden="true">🛍️</div>
                        <h2 class="empty-card__title">Belum Ada Riwayat Pesanan</h2>
                        <p class="empty-card__text">
                            Anda belum memiliki riwayat pemesanan produk fisik maupun layanan jasa di Katalog Teaching Factory SMKN 4 Tanjungpinang.
                        </p>
                        <div class="empty-card__actions">
                            <a href="{{ route('produk') }}" class="btn-blue">
                                <span>🛒</span> Jelajahi Produk Fisik
                            </a>
                            <a href="{{ route('jasa') }}" class="btn-ghost">
                                <span>🛠️</span> Lihat Layanan Jasa
                            </a>
                        </div>
                    </div>
                </li>
            @endforelse
        </ul>

    </div>
</div>
@endsection
