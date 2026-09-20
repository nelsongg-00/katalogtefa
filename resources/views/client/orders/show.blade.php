@extends('layouts.public')

@section('title', 'Status Pesanan ' . $order->order_code . ' — Katalog TeFa')

@section('content')
<div class="page-header" style="background: linear-gradient(135deg, #0a215e 0%, #1e3a8a 100%); color: #fff; padding: 40px 20px; text-align: center;">
    <h1 style="font-size: 26px; font-weight: 800; margin-bottom: 6px;">Status Pesanan Produk Fisik</h1>
    <p style="color: #cbd5e1; font-size: 14.5px;">Pantau tahapan proses pengemasan dan instruksi pengambilan barang di lab jurusan.</p>
</div>

<div class="container" style="max-width: 860px; margin: 35px auto 60px; padding: 0 20px;">

    @if(session('success'))
        <div style="background: #dcfce7; border-left: 4px solid #16a34a; color: #15803d; padding: 14px 18px; border-radius: 10px; font-weight: 600; font-size: 13.5px; margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif

    <!-- 1. KARTU STATUS & VISUAL STEPPER -->
    @php
        $statusKey = $order->status;
        $stepIndex = match($statusKey) {
            'sedang_dikemas' => 2,
            'bisa_diambil' => 3,
            'selesai' => 4,
            'dibatalkan' => -1,
            default => 1, // menunggu_konfirmasi
        };
    @endphp

    <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 28px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 24px; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px;">
            <div>
                <span style="font-size: 12px; color: #64748b; font-weight: 700; text-transform: uppercase;">Kode Pesanan</span>
                <div style="font-family: monospace; font-size: 22px; font-weight: 800; color: #1e3a8a;">
                    {{ $order->order_code }}
                </div>
            </div>
            <div>
                @php
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
                        default => 'Menunggu Konfirmasi Admin ⏳',
                    };
                @endphp
                <span style="{{ $badgeStyle }} font-size: 13px; font-weight: 800; padding: 6px 14px; border-radius: 999px; display: inline-block;">
                    {{ $badgeText }}
                </span>
            </div>
        </div>

        @if($statusKey === 'dibatalkan')
            <div style="background: #fef2f2; border-radius: 12px; padding: 18px; color: #b91c1c; font-size: 13.5px; line-height: 1.6;">
                <strong>Pesanan Dibatalkan.</strong> Stok produk telah dikembalikan ke sistem. Jika ini merupakan kekeliruan, silakan hubungi admin jurusan.
            </div>
        @else
            <!-- Visual Stepper Progress Bar (4 Tahap) -->
            <div style="margin: 20px 0 10px;">
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; position: relative;">
                    <!-- Step 1: Menunggu Konfirmasi -->
                    <div style="text-align: center;">
                        <div style="width: 38px; height: 38px; border-radius: 50%; margin: 0 auto 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px;
                                    {{ $stepIndex >= 1 ? 'background: #2563eb; color: #fff;' : 'background: #f1f5f9; color: #94a3b8;' }}">
                            {{ $stepIndex > 1 ? '✓' : '1' }}
                        </div>
                        <div style="font-size: 12.5px; font-weight: {{ $stepIndex == 1 ? '800' : '600' }}; color: {{ $stepIndex >= 1 ? '#0f172a' : '#94a3b8' }};">
                            Menunggu Konfirmasi
                        </div>
                    </div>

                    <!-- Step 2: Sedang Dikemas -->
                    <div style="text-align: center;">
                        <div style="width: 38px; height: 38px; border-radius: 50%; margin: 0 auto 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px;
                                    {{ $stepIndex >= 2 ? 'background: #2563eb; color: #fff;' : 'background: #f1f5f9; color: #94a3b8;' }}">
                            {{ $stepIndex > 2 ? '✓' : '2' }}
                        </div>
                        <div style="font-size: 12.5px; font-weight: {{ $stepIndex == 2 ? '800' : '600' }}; color: {{ $stepIndex >= 2 ? '#0f172a' : '#94a3b8' }};">
                            Sedang Dikemas
                        </div>
                    </div>

                    <!-- Step 3: Bisa Diambil -->
                    <div style="text-align: center;">
                        <div style="width: 38px; height: 38px; border-radius: 50%; margin: 0 auto 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px;
                                    {{ $stepIndex >= 3 ? 'background: #2563eb; color: #fff;' : 'background: #f1f5f9; color: #94a3b8;' }}">
                            {{ $stepIndex > 3 ? '✓' : '3' }}
                        </div>
                        <div style="font-size: 12.5px; font-weight: {{ $stepIndex == 3 ? '800' : '600' }}; color: {{ $stepIndex >= 3 ? '#0f172a' : '#94a3b8' }};">
                            Bisa Diambil
                        </div>
                    </div>

                    <!-- Step 4: Selesai -->
                    <div style="text-align: center;">
                        <div style="width: 38px; height: 38px; border-radius: 50%; margin: 0 auto 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px;
                                    {{ $stepIndex >= 4 ? 'background: #10b981; color: #fff;' : 'background: #f1f5f9; color: #94a3b8;' }}">
                            {{ $stepIndex >= 4 ? '✓' : '4' }}
                        </div>
                        <div style="font-size: 12.5px; font-weight: {{ $stepIndex == 4 ? '800' : '600' }}; color: {{ $stepIndex >= 4 ? '#059669' : '#94a3b8' }};">
                            Selesai (COD)
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- 2. ALERT PETUNJUK PENGAMBILAN (MUNCUL JIKA STATUS BISA_DIAMBIL) -->
    @if($statusKey === 'bisa_diambil')
        <div style="background: #ecfdf5; border-left: 5px solid #10b981; border-radius: 12px; padding: 20px; margin-bottom: 24px; box-shadow: 0 4px 12px rgba(16,185,129,0.15);">
            <div style="display: flex; gap: 14px; align-items: flex-start;">
                <div style="font-size: 28px; line-height: 1;">🎉</div>
                <div>
                    <h3 style="font-size: 16px; font-weight: 800; color: #065f46; margin: 0 0 6px;">
                        Pesanan Anda Sudah Siap Diambil!
                    </h3>
                    <p style="font-size: 14px; color: #047857; line-height: 1.6; margin: 0 0 10px;">
                        Pesanan Anda siap diambil di <strong>{{ $order->lokasi_pengambilan }}</strong>. Silakan siapkan uang pas sebesar <strong style="color: #064e3b; font-size: 15px;">Rp {{ number_format($order->total_harga ?: $order->total_biaya, 0, ',', '.') }}</strong> saat pengambilan di kasir lab.
                    </p>
                    <div style="font-size: 12.5px; color: #065f46;">
                        📍 Lokasi Kampus: SMKN 4 Tanjungpinang (Jl. Nusantara No.KM.14 Batu IX). Jam Layanan: 08.00 - 15.30 WIB (Hari Kerja).
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- 3. DETAIL BARANG & LOKASI PENGAMBILAN -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
        
        <!-- Info Barang -->
        <div style="background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0; padding: 20px;">
            <h4 style="font-size: 14px; font-weight: 800; color: #0f172a; margin-bottom: 14px; text-transform: uppercase; letter-spacing: 0.5px;">
                Rincian Barang
            </h4>
            <div style="display: flex; gap: 14px; align-items: center; margin-bottom: 14px;">
                <div style="width: 60px; height: 60px; border-radius: 8px; overflow: hidden; background: #f8fafc; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    @if($order->product && $order->product->foto)
                        <img src="{{ asset('storage/' . $order->product->foto) }}" alt="{{ $order->product->nama_produk }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <span style="font-size: 24px;">📦</span>
                    @endif
                </div>
                <div>
                    <strong style="font-size: 14.5px; color: #1e293b; display: block;">{{ $order->product->nama_produk ?? 'Produk Fisik TeFa' }}</strong>
                    <span style="font-size: 12px; color: #64748b;">{{ $order->jumlah }} unit &times; Rp {{ number_format($order->product->harga ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>

            <div style="border-top: 1px solid #f1f5f9; padding-top: 10px; display: flex; justify-content: space-between; font-size: 14px; font-weight: 800;">
                <span>Total Tagihan COD:</span>
                <span style="color: #2563eb;">Rp {{ number_format($order->total_harga ?: $order->total_biaya, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Info Pengambilan & Kontak -->
        <div style="background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0; padding: 20px;">
            <h4 style="font-size: 14px; font-weight: 800; color: #0f172a; margin-bottom: 14px; text-transform: uppercase; letter-spacing: 0.5px;">
                Metode & Lokasi
            </h4>
            <div style="margin-bottom: 8px;">
                <span style="font-size: 12px; color: #64748b;">Metode Pengambilan:</span>
                <div style="font-size: 13.5px; font-weight: 700; color: #0f172a;">Ambil di Tempat (Self Pick-up)</div>
            </div>
            <div style="margin-bottom: 8px;">
                <span style="font-size: 12px; color: #64748b;">Lokasi Lab Jurusan:</span>
                <div style="font-size: 13.5px; font-weight: 800; color: #16a34a;">{{ $order->lokasi_pengambilan }}</div>
            </div>
            <div>
                <span style="font-size: 12px; color: #64748b;">Metode Pembayaran:</span>
                <div style="font-size: 13px; font-weight: 700; color: #2563eb;">Cash on Delivery (Tunai di Kasir Lab)</div>
            </div>
        </div>

    </div>

    <!-- 4. TIMELINE RIWAYAT AKTIVITAS PESANAN -->
    <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 24px;">
        <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 16px;">
            📜 Riwayat Log Pesanan
        </h3>

        @php
            $logs = $order->orderLogs;
        @endphp

        @if($logs->count() > 0)
            <div style="position: relative; padding-left: 24px;">
                <div style="position: absolute; left: 9px; top: 8px; bottom: 8px; width: 2px; background: #e2e8f0;"></div>

                @foreach($logs as $idx => $log)
                    <div style="position: relative; margin-bottom: 18px;">
                        <div style="position: absolute; left: -24px; top: 4px; width: 18px; height: 18px; border-radius: 50%; background: {{ $idx === 0 ? '#2563eb' : '#fff' }}; border: 2px solid {{ $idx === 0 ? '#93c5fd' : '#cbd5e1' }};"></div>
                        <div style="font-size: 13.5px; font-weight: 700; color: #1e293b;">
                            {{ $log->keterangan_log }}
                        </div>
                        <div style="font-size: 11.5px; color: #64748b;">
                            {{ $log->created_at->translatedFormat('d M Y, H:i') }} WIB
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p style="font-size: 13px; color: #64748b; margin: 0;">Belum ada riwayat aktivitas terbaru.</p>
        @endif

        <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <a href="{{ route('client.orders') }}" style="font-size: 13px; font-weight: 700; color: #2563eb; text-decoration: none;">
                &larr; Kembali ke Riwayat Pesanan Saya
            </a>
            <a href="https://wa.me/6281234567890?text=Halo%20Admin%20TeFa,%20saya%20ingin%20menanyakan%20status%20pesanan%20fisik%20dengan%20kode%20{{ $order->order_code }}" target="_blank" style="background: #25d366; color: #fff; font-size: 12.5px; font-weight: 700; padding: 7px 14px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <span>💬</span> Hubungi Admin via WhatsApp
            </a>
        </div>
    </div>

</div>
@endsection
