@extends('layouts.public')

@section('title', 'Riwayat Pesanan Saya — Katalog TeFa SMKN 4 Tanjungpinang')

@section('content')
<div class="page-header" style="background: linear-gradient(135deg, #0a215e 0%, #1e3a8a 100%); color: #fff; padding: 40px 20px; text-align: center;">
    <h1 style="font-size: 26px; font-weight: 800; margin-bottom: 6px;">Pesanan Saya</h1>
    <p style="color: #cbd5e1; font-size: 14.5px;">Daftar pemesanan produk fisik dan layanan jasa Teaching Factory Anda.</p>
</div>

<div class="container" style="max-width: 960px; margin: 35px auto 60px; padding: 0 20px;">

    @if(session('success'))
        <div style="background: #dcfce7; border-left: 4px solid #16a34a; color: #15803d; padding: 14px 18px; border-radius: 10px; font-weight: 600; font-size: 13.5px; margin-bottom: 24px;">
            {{ session('success') }}
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
                    'selesai' => 'Selesai (COD Lunas) ✓',
                    'bisa_diambil' => 'Siap Diambil di Lab 🏢',
                    'sedang_dikemas' => 'Sedang Dikemas 📦',
                    'dibatalkan' => 'Dibatalkan ✕',
                    default => 'Menunggu Konfirmasi ⏳',
                };
            @endphp

            <div style="background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0; padding: 22px; box-shadow: 0 2px 8px rgba(0,0,0,0.03); transition: transform 0.2s;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; border-bottom: 1px solid #f1f5f9; padding-bottom: 14px; margin-bottom: 16px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-family: monospace; font-size: 15px; font-weight: 800; color: #1e3a8a; background: #eff6ff; padding: 3px 8px; border-radius: 6px;">
                            {{ $order->order_code }}
                        </span>
                        <span style="font-size: 12px; color: #64748b;">
                            {{ $order->created_at->translatedFormat('d M Y, H:i') }} WIB
                        </span>
                    </div>

                    <span style="{{ $badgeStyle }} font-size: 12px; font-weight: 800; padding: 4px 12px; border-radius: 999px;">
                        {{ $badgeText }}
                    </span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                    <div style="display: flex; gap: 16px; align-items: center;">
                        <div style="width: 64px; height: 64px; border-radius: 10px; overflow: hidden; background: #f8fafc; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            @if($order->product && $order->product->foto)
                                <img src="{{ asset('storage/' . $order->product->foto) }}" alt="{{ $order->product->nama_produk }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @elseif($order->service && $order->service->foto)
                                <img src="{{ asset('storage/' . $order->service->foto) }}" alt="{{ $order->service->nama_layanan }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <span style="font-size: 28px;">📦</span>
                            @endif
                        </div>

                        <div>
                            <h4 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0 0 4px;">
                                {{ $order->product->nama_produk ?? ($order->service->nama_layanan ?? 'Produk / Jasa TeFa') }}
                            </h4>
                            <div style="font-size: 12.5px; color: #64748b; margin-bottom: 4px;">
                                Jurusan: <strong>{{ $order->department->nama_jurusan ?? ($order->product->jurusan->nama_jurusan ?? '-') }}</strong>
                            </div>
                            <div style="font-size: 12.5px; color: #166534; font-weight: 600;">
                                📍 {{ $order->lokasi_pengambilan ?? 'Lab SMKN 4 Tanjungpinang' }}
                            </div>
                        </div>
                    </div>

                    <div style="text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: 8px;">
                        <div>
                            <span style="font-size: 12px; color: #64748b; display: block;">Total Tagihan COD:</span>
                            <span style="font-size: 18px; font-weight: 800; color: #2563eb;">
                                Rp {{ number_format($order->total_harga ?: $order->total_biaya, 0, ',', '.') }}
                            </span>
                        </div>

                        <a href="{{ route('client.orders.show', $order->id) }}" style="display: inline-flex; align-items: center; gap: 6px; background: #2563eb; color: #ffffff; padding: 8px 16px; border-radius: 8px; font-weight: 700; font-size: 13px; text-decoration: none; box-shadow: 0 2px 6px rgba(37,99,235,0.2);">
                            Lihat Status & Lokasi &rarr;
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 50px 20px; text-align: center;">
                <div style="font-size: 48px; margin-bottom: 12px;">🛍️</div>
                <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 6px;">Belum Ada Pesanan Produk Fisik</h3>
                <p style="color: #64748b; font-size: 14px; max-width: 480px; margin: 0 auto 20px;">
                    Anda belum pernah membuat pesanan produk fisik di katalog TeFa SMKN 4 Tanjungpinang.
                </p>
                <a href="{{ route('produk') }}" style="background: #2563eb; color: #fff; padding: 10px 22px; border-radius: 10px; font-weight: 700; font-size: 14px; text-decoration: none; display: inline-block;">
                    Jelajahi Katalog Produk &rarr;
                </a>
            </div>
        @endforelse
    </div>

</div>
@endsection
