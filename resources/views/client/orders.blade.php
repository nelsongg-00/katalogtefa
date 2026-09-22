@extends('layouts.public')

@section('title', 'Riwayat Pesanan Saya — Katalog TEFA SMKN 4 Tanjungpinang')

@section('content')
<style>
    .orders-hero {
        background: linear-gradient(135deg, #0a215e 0%, #1e3a8a 100%);
        color: #ffffff;
        padding: 50px 20px;
        text-align: center;
        position: relative;
    }

    .orders-badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 183, 3, 0.15);
        color: #ffb703;
        padding: 4px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.5px;
        border: 1px solid rgba(255, 183, 3, 0.3);
        margin-bottom: 12px;
    }

    .orders-hero h1 {
        font-size: 1.85rem;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .orders-hero p {
        color: #cbd5e1;
        font-size: 14.5px;
        max-width: 580px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .order-card-item {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        padding: 24px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        transition: all 0.25s ease;
    }

    .order-card-item:hover {
        border-color: #cbd5e1;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
        transform: translateY(-2px);
    }

    .empty-orders-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        padding: 60px 24px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }

    .empty-icon-box {
        width: 72px;
        height: 72px;
        border-radius: 20px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 34px;
        margin: 0 auto 18px;
    }
</style>

<!-- 1. HEADER HERO -->
<div class="orders-hero">
    <div class="orders-badge-pill">
        <span>✨ RIWAYAT TRANSAKSI</span>
    </div>
    <h1>Pesanan Saya</h1>
    <p>Pantau riwayat pemesanan produk fisik dan layanan jasa Teaching Factory Anda secara terpusat.</p>
</div>

<!-- 2. CONTENT CONTAINER -->
<div class="container" style="max-width: 960px; margin: 35px auto 70px; padding: 0 20px;">

    @if(session('success'))
        <div style="background: #ecfdf5; border-left: 4px solid #10b981; color: #065f46; padding: 14px 18px; border-radius: 12px; font-weight: 600; font-size: 13.5px; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 16px;">✓</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div style="display: flex; flex-direction: column; gap: 20px;">
        @forelse($orders as $order)
            @php
                $statusKey = $order->status;
                $badgeStyle = match($statusKey) {
                    'selesai' => 'background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0;',
                    'bisa_diambil' => 'background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe;',
                    'sedang_dikemas' => 'background: #fef3c7; color: #b45309; border: 1px solid #fde68a;',
                    'dibatalkan' => 'background: #fef2f2; color: #dc2626; border: 1px solid #fecaca;',
                    default => 'background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;',
                };
                $badgeText = match($statusKey) {
                    'selesai' => 'Selesai (Lunas) ✓',
                    'bisa_diambil' => 'Siap Diambil 🏢',
                    'sedang_dikemas' => 'Sedang Diproses 📦',
                    'dibatalkan' => 'Dibatalkan ✕',
                    default => 'Menunggu Konfirmasi ⏳',
                };
            @endphp

            <div class="order-card-item">
                <!-- Top Bar: Order Code & Date & Status -->
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; border-bottom: 1px solid #f1f5f9; padding-bottom: 14px; margin-bottom: 18px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-family: monospace; font-size: 14px; font-weight: 800; color: #1e3a8a; background: #eff6ff; padding: 4px 10px; border-radius: 8px; border: 1px solid #bfdbfe;">
                            {{ $order->order_code }}
                        </span>
                        <span style="font-size: 12.5px; color: #64748b;">
                            {{ $order->created_at->translatedFormat('d M Y, H:i') }} WIB
                        </span>
                    </div>

                    <span style="{{ $badgeStyle }} font-size: 12px; font-weight: 800; padding: 5px 14px; border-radius: 999px;">
                        {{ $badgeText }}
                    </span>
                </div>

                <!-- Main Body: Product Details + Price & Action -->
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                    
                    <div style="display: flex; gap: 16px; align-items: center; min-width: 260px;">
                        <div style="width: 70px; height: 70px; border-radius: 14px; overflow: hidden; background: #f8fafc; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            @if($order->product && $order->product->foto)
                                <img src="{{ asset('storage/' . $order->product->foto) }}" alt="{{ $order->product->nama_produk }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @elseif($order->service && $order->service->foto)
                                <img src="{{ asset('storage/' . $order->service->foto) }}" alt="{{ $order->service->nama_layanan }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <span style="font-size: 30px;">📦</span>
                            @endif
                        </div>

                        <div>
                            <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0 0 5px;">
                                {{ $order->product->nama_produk ?? ($order->service->nama_layanan ?? 'Item Pesanan TEFA') }}
                            </h3>
                            <div style="font-size: 12.5px; color: #64748b; margin-bottom: 4px;">
                                Unit: <strong style="color: #334155;">{{ $order->department->nama_jurusan ?? ($order->product->jurusan->nama_jurusan ?? 'Unit TEFA') }}</strong>
                            </div>
                            <div style="font-size: 12.5px; color: #059669; font-weight: 600;">
                                📍 {{ $order->lokasi_pengambilan ?? 'Lab / Unit Produksi SMKN 4 Tanjungpinang' }}
                            </div>
                        </div>
                    </div>

                    <!-- Price & CTA -->
                    <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 10px; margin-left: auto;">
                        <div style="text-align: right;">
                            <span style="font-size: 11.5px; color: #64748b; display: block; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Total Biaya:</span>
                            <span style="font-size: 1.25rem; font-weight: 800; color: #2563eb;">
                                Rp {{ number_format($order->total_harga ?: $order->total_biaya, 0, ',', '.') }}
                            </span>
                        </div>

                        <a href="{{ route('client.orders.show', $order->id) }}" style="display: inline-flex; align-items: center; gap: 6px; background: #2563eb; color: #ffffff; padding: 9px 18px; border-radius: 10px; font-weight: 700; font-size: 13px; text-decoration: none; transition: 0.2s; box-shadow: 0 2px 8px rgba(37,99,235,0.25);">
                            <span>Detail Status & Lokasi</span>
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-orders-card">
                <div class="empty-icon-box">
                    🛍️
                </div>
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 8px;">Belum Ada Riwayat Pesanan</h3>
                <p style="color: #64748b; font-size: 14.5px; max-width: 480px; margin: 0 auto 25px; line-height: 1.6;">
                    Anda belum memiliki riwayat pemesanan produk fisik maupun layanan jasa di Katalog Teaching Factory SMKN 4 Tanjungpinang.
                </p>
                <div style="display: flex; justify-content: center; gap: 12px; flex-wrap: wrap;">
                    <a href="{{ route('produk') }}" class="btn-blue" style="padding: 12px 24px; border-radius: 12px; font-weight: 700; font-size: 14px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                        <span>🛒</span> Jelajahi Produk Fisik
                    </a>
                    <a href="{{ route('jasa') }}" style="background: #f1f5f9; color: #334155; padding: 12px 24px; border-radius: 12px; font-weight: 700; font-size: 14px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; border: 1px solid #e2e8f0; transition: 0.2s;">
                        <span>🛠️</span> Lihat Layanan Jasa
                    </a>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection
