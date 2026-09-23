@extends('layouts.public')

@section('title', 'Lacak Pesanan — Teaching Factory SMKN 4 Tanjungpinang')

@section('content')
<div class="page-header" style="background: linear-gradient(135deg, #0a215e 0%, #1e3a8a 100%); color: #fff; padding: 48px 20px; text-align: center;">
    <h1 style="font-size: 28px; font-weight: 800; margin-bottom: 8px; letter-spacing: -0.5px;">Lacak Status & Progres Pesanan</h1>
    <p style="color: #cbd5e1; font-size: 15px; max-width: 640px; margin: 0 auto 24px; line-height: 1.5;">
        Pantau tahapan pengerjaan layanan jasa secara transparan oleh talenta siswa, atau cek status pengambilan pesanan produk fisik di unit Teaching Factory SMKN 4 Tanjungpinang.
    </p>

    <!-- Search Box -->
    <div style="max-width: 520px; margin: 0 auto;">
        <form method="POST" action="{{ route('order.search') }}" style="display: flex; background: #fff; border-radius: 999px; padding: 6px 8px 6px 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
            @csrf
            <input type="text" name="order_code" value="{{ $searchedCode ?? ($order->order_code ?? '') }}" placeholder="Masukkan Kode Tracking (Contoh: TEFA-9821 atau TEFA-FISIK-1234)" required
                   style="flex: 1; border: none; outline: none; font-size: 14px; color: #0f172a; font-weight: 600; text-transform: uppercase;">
            <button type="submit" style="background: #2563eb; color: #fff; border: none; padding: 10px 24px; border-radius: 999px; font-weight: 700; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: background 0.15s;">
                <span>🔍</span> Lacak
            </button>
        </form>
    </div>
</div>

<div class="container" style="max-width: 960px; margin: 40px auto 70px; padding: 0 20px;">

    @if(isset($searchedCode) && !isset($order))
        <!-- Order Not Found -->
        <div style="background: #fff; border-radius: 16px; border: 1px solid #fee2e2; padding: 48px 24px; text-align: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <div style="font-size: 48px; margin-bottom: 14px;">🔎</div>
            <h3 style="font-size: 20px; font-weight: 800; color: #1e293b; margin-bottom: 8px;">Pesanan Tidak Ditemukan</h3>
            <p style="color: #64748b; font-size: 14.5px; max-width: 520px; margin: 0 auto 24px; line-height: 1.6;">
                Kode tracking <strong style="color: #dc2626; font-family: monospace; background: #fef2f2; padding: 2px 8px; border-radius: 4px;">{{ $searchedCode }}</strong> tidak terdaftar dalam database kami. Pastikan format kode sudah sesuai (contoh: <code>TEFA-9821</code> untuk jasa atau <code>TEFA-FISIK-XXXX</code> untuk produk fisik).
            </p>
            <div style="display: flex; justify-content: center; gap: 12px; flex-wrap: wrap;">
                <a href="{{ route('order.tracking.index') }}" style="background: #f1f5f9; color: #334155; padding: 10px 20px; border-radius: 10px; font-weight: 700; text-decoration: none; font-size: 13.5px;">
                    Cari Kode Lain
                </a>
                <a href="https://wa.me/6281234567890?text=Halo%20Admin%20TeFa,%20saya%20ingin%20menanyakan%20kode%20pelacakan%20{{ $searchedCode }}" target="_blank" style="background: #25d366; color: #fff; padding: 10px 20px; border-radius: 10px; font-weight: 700; text-decoration: none; font-size: 13.5px; display: inline-flex; align-items: center; gap: 6px;">
                    <span>💬</span> Hubungi Admin WhatsApp
                </a>
            </div>
        </div>

    @elseif(isset($order))

        @if($order->service_id || !$order->product_id)
            {{-- ======================================================== --}}
            {{-- 1. KONTEKS PESANAN LAYANAN JASA (WORKER MILESTONE FEED)   --}}
            {{-- ======================================================== --}}
            @php
                $statusNormalized = strtolower($order->status);
                $progressPercent = match($statusNormalized) {
                    'completed', 'selesai' => 100,
                    'review' => 85,
                    'in_progress' => max(25, $order->project->progress ?? 50),
                    'cancelled', 'dibatalkan' => 0,
                    default => 10,
                };

                $statusBg = match($statusNormalized) {
                    'completed', 'selesai' => '#ecfdf5',
                    'review' => '#fef3c7',
                    'in_progress' => '#eff6ff',
                    'cancelled', 'dibatalkan' => '#fef2f2',
                    default => '#f1f5f9',
                };
                $statusColor = match($statusNormalized) {
                    'completed', 'selesai' => '#059669',
                    'review' => '#b45309',
                    'in_progress' => '#2563eb',
                    'cancelled', 'dibatalkan' => '#dc2626',
                    default => '#475569',
                };
                $statusLabel = match($statusNormalized) {
                    'completed', 'selesai' => 'Pesanan Selesai 🎉',
                    'review' => 'Tahap Review & Approval File 🔍',
                    'in_progress' => 'Sedang Dikerjakan oleh Worker ⚙️',
                    'cancelled', 'dibatalkan' => 'Pesanan Dibatalkan ✕',
                    default => 'Menunggu Antrean Pengerjaan ⏳',
                };

                $progressLogs = $order->progressLogs ?? collect();
            @endphp

            <div style="background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 28px;">
                <!-- Header Banner -->
                <div style="padding: 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; background: #fafcff;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 11px; font-weight: 800; background: #e0e7ff; color: #3730a3; padding: 2px 8px; border-radius: 4px; text-transform: uppercase;">
                                Layanan Jasa
                            </span>
                            <span style="font-size: 12px; font-weight: 600; color: #64748b;">Nomor Resi / Pelacakan</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px; margin-top: 4px;">
                            <span style="font-family: monospace; font-size: 24px; font-weight: 800; color: #1e3a8a;">{{ $order->order_code }}</span>
                            <button type="button" onclick="navigator.clipboard.writeText('{{ $order->order_code }}'); alert('Kode tracking tersalin!');" title="Salin Kode" style="background: #eff6ff; border: 1px solid #bfdbfe; color: #2563eb; border-radius: 6px; padding: 4px 8px; font-size: 12px; cursor: pointer; font-weight: 700;">
                                📋 Salin
                            </button>
                        </div>
                    </div>
                    <div>
                        <span style="background: {{ $statusBg }}; color: {{ $statusColor }}; font-weight: 800; font-size: 13px; padding: 8px 16px; border-radius: 999px; display: inline-flex; align-items: center; gap: 6px; border: 1px solid rgba(0,0,0,0.05);">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: {{ $statusColor }};"></span>
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div style="padding: 20px 24px; background: #fff; border-bottom: 1px solid #f1f5f9;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; font-size: 13px; font-weight: 700; color: #334155;">
                        <span>Progres Pengerjaan Keseluruhan</span>
                        <span style="color: #2563eb; font-weight: 800;">{{ $progressPercent }}%</span>
                    </div>
                    <div style="width: 100%; height: 10px; background: #f1f5f9; border-radius: 999px; overflow: hidden;">
                        <div style="height: 100%; width: {{ $progressPercent }}%; background: linear-gradient(90deg, #2563eb, #3b82f6); border-radius: 999px; transition: width 0.5s ease;"></div>
                    </div>
                </div>

                <!-- Meta Information Grid -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; padding: 24px; background: #fff;">
                    <div>
                        <span style="font-size: 12px; color: #64748b; display: block; margin-bottom: 4px;">Pemesan</span>
                        <strong style="color: #0f172a; font-size: 15px;">{{ $order->customer_name }}</strong>
                    </div>

                    <div>
                        <span style="font-size: 12px; color: #64748b; display: block; margin-bottom: 4px;">Layanan Jasa</span>
                        <strong style="color: #0f172a; font-size: 15px;">{{ $order->service->nama_layanan ?? 'Layanan Jasa' }}</strong>
                        <div style="font-size: 12px; color: #059669; font-weight: 600; margin-top: 2px;">
                            {{ $order->service->department->nama_jurusan ?? ($order->department->nama_jurusan ?? 'Unit Teaching Factory') }}
                        </div>
                    </div>

                    <div>
                        <span style="font-size: 12px; color: #64748b; display: block; margin-bottom: 4px;">Talenta Siswa (Worker PJ)</span>
                        @if($order->worker)
                            <div style="display: flex; align-items: center; gap: 8px; margin-top: 2px;">
                                <div style="width: 28px; height: 28px; border-radius: 50%; background: #2563eb; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700;">
                                    {{ strtoupper(substr($order->worker->name, 0, 1)) }}
                                </div>
                                <strong style="color: #0f172a; font-size: 14px;">{{ $order->worker->name }}</strong>
                            </div>
                        @else
                            <span style="color: #94a3b8; font-style: italic; font-size: 13.5px;">Tim Produksi Jurusan</span>
                        @endif
                    </div>

                    <div>
                        <span style="font-size: 12px; color: #64748b; display: block; margin-bottom: 4px;">Tanggal Pemesanan</span>
                        <strong style="color: #0f172a; font-size: 14px;">{{ $order->created_at ? $order->created_at->translatedFormat('d F Y, H:i') . ' WIB' : '-' }}</strong>
                    </div>
                </div>

                @if($order->catatan || $order->catatan_pelanggan)
                    <div style="padding: 14px 24px; background: #f8fafc; border-top: 1px solid #f1f5f9; font-size: 13px; color: #475569;">
                        <strong style="color: #1e293b;">Catatan Khusus Klien:</strong> {{ $order->catatan ?: $order->catatan_pelanggan }}
                    </div>
                @endif
            </div>

            <!-- Timeline Log Pengerjaan Dinamis oleh Worker -->
            <div style="background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 28px 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 4px;">Lini Masa Progres Pengerjaan</h3>
                        <p style="font-size: 13px; color: #64748b; margin: 0;">Catatan progres dan milestone pengerjaan oleh siswa worker dan instruktur TeFa.</p>
                    </div>
                    <span style="font-size: 12px; background: #eff6ff; color: #2563eb; font-weight: 700; padding: 4px 12px; border-radius: 999px;">
                        Real-time Update
                    </span>
                </div>

                <div style="position: relative; padding-left: 28px;">
                    <!-- Vertical Line -->
                    <div style="position: absolute; left: 11px; top: 10px; bottom: 10px; width: 2px; background: #e2e8f0;"></div>

                    @forelse($progressLogs as $index => $log)
                        <div style="position: relative; margin-bottom: 28px;">
                            <!-- Node Dot -->
                            <div style="position: absolute; left: -28px; top: 4px; width: 22px; height: 22px; border-radius: 50%; background: {{ $index === 0 ? '#2563eb' : '#fff' }}; border: 3px solid {{ $index === 0 ? '#93c5fd' : '#cbd5e1' }}; display: flex; align-items: center; justify-content: center;">
                                @if($index === 0)
                                    <div style="width: 6px; height: 6px; border-radius: 50%; background: #fff;"></div>
                                @endif
                            </div>

                            <!-- Log Content Card -->
                            <div style="background: {{ $index === 0 ? '#f8fafd' : '#fff' }}; border: 1px solid {{ $index === 0 ? '#bfdbfe' : '#e2e8f0' }}; border-radius: 12px; padding: 18px 20px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; margin-bottom: 8px;">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 24px; height: 24px; border-radius: 50%; background: #e0e7ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700;">
                                            👤
                                        </div>
                                        <span style="font-size: 13.5px; font-weight: 700; color: {{ $index === 0 ? '#1e3a8a' : '#1e293b' }};">
                                            {{ $log->worker->name ?? ($order->worker->name ?? 'Tim TeFa') }}
                                        </span>
                                    </div>
                                    <span style="font-size: 12px; color: #64748b; font-weight: 500;">
                                        {{ $log->created_at ? $log->created_at->translatedFormat('d M Y, H:i') . ' WIB' : '-' }}
                                    </span>
                                </div>

                                <p style="font-size: 14px; color: #334155; line-height: 1.6; margin: 0 0 12px;">
                                    {{ $log->catatan }}
                                </p>

                                @if($log->lampiran_file || $log->link_eksternal)
                                    <div style="display: flex; gap: 10px; flex-wrap: wrap; padding-top: 10px; border-top: 1px dashed #e2e8f0;">
                                        @if($log->lampiran_file)
                                            @php
                                                $isImage = preg_match('/\.(jpg|jpeg|png|webp|gif)$/i', $log->lampiran_file);
                                            @endphp
                                            @if($isImage)
                                                <div style="width: 100%; margin-bottom: 8px;">
                                                    <a href="{{ asset('storage/' . $log->lampiran_file) }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $log->lampiran_file) }}" alt="Lampiran Progres" style="max-height: 180px; border-radius: 8px; border: 1px solid #cbd5e1; object-fit: cover;">
                                                    </a>
                                                </div>
                                            @endif
                                            <a href="{{ asset('storage/' . $log->lampiran_file) }}" target="_blank" style="background: #eff6ff; color: #2563eb; font-size: 12.5px; font-weight: 700; padding: 6px 14px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; border: 1px solid #bfdbfe;">
                                                <span>📎</span> Lihat / Unduh Berkas
                                            </a>
                                        @endif

                                        @if($log->link_eksternal)
                                            <a href="{{ $log->link_eksternal }}" target="_blank" style="background: #f1f5f9; color: #1e293b; font-size: 12.5px; font-weight: 700; padding: 6px 14px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; border: 1px solid #cbd5e1;">
                                                <span>🔗</span> Buka Tautan (Figma / GitHub / Drive) ↗
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 36px 20px; color: #64748b;">
                            <div style="font-size: 34px; margin-bottom: 8px;">⏳</div>
                            <strong style="color: #1e293b; display: block; font-size: 14.5px; margin-bottom: 4px;">Belum Ada Catatan Log Terbaru</strong>
                            <p style="margin: 0; font-size: 13.5px;">Worker sedang mempersiapkan tahap pengerjaan. Catatan perkembangan dan milestone akan muncul di sini secara otomatis.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Help Contact Footer -->
                <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <div style="font-size: 13px; color: #64748b;">
                        Punya pertanyaan atau ingin menyampaikan revisi terkait pesanan ini?
                    </div>
                    <a href="https://wa.me/6281234567890?text=Halo%20Admin%20TeFa,%20saya%20ingin%20konsultasi%20mengenai%20pesanan%20jasa%20dengan%20kode%20{{ $order->order_code }}" target="_blank" style="background: #25d366; color: #fff; font-size: 13px; font-weight: 700; padding: 8px 18px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                        <span>💬</span> Hubungi Tim via WhatsApp
                    </a>
                </div>
            </div>

        @else
            {{-- ======================================================== --}}
            {{-- 2. KONTEKS PESANAN PRODUK FISIK (ALUR COD & PENGAMBILAN)  --}}
            {{-- ======================================================== --}}
            @php
                $statusKey = $order->status;
                $stepIndex = match($statusKey) {
                    'sedang_dikemas' => 2,
                    'bisa_diambil' => 3,
                    'selesai' => 4,
                    'dibatalkan' => -1,
                    default => 1, // menunggu_konfirmasi
                };

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

            <!-- Kartu Status Produk Fisik -->
            <div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 28px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 24px; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 11px; font-weight: 800; background: #ecfdf5; color: #059669; padding: 2px 8px; border-radius: 4px; text-transform: uppercase;">
                                Produk Fisik (COD)
                            </span>
                            <span style="font-size: 12px; color: #64748b; font-weight: 600;">Nomor Pelacakan</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px; margin-top: 4px;">
                            <span style="font-family: monospace; font-size: 24px; font-weight: 800; color: #1e3a8a;">{{ $order->order_code }}</span>
                            <button type="button" onclick="navigator.clipboard.writeText('{{ $order->order_code }}'); alert('Kode tracking tersalin!');" title="Salin Kode" style="background: #eff6ff; border: 1px solid #bfdbfe; color: #2563eb; border-radius: 6px; padding: 4px 8px; font-size: 12px; cursor: pointer; font-weight: 700;">
                                📋 Salin
                            </button>
                        </div>
                    </div>
                    <div>
                        <span style="{{ $badgeStyle }} font-size: 13px; font-weight: 800; padding: 8px 16px; border-radius: 999px; display: inline-block;">
                            {{ $badgeText }}
                        </span>
                    </div>
                </div>

                @if($statusKey === 'dibatalkan')
                    <div style="background: #fef2f2; border-radius: 12px; padding: 18px; color: #b91c1c; font-size: 13.5px; line-height: 1.6;">
                        <strong>Pesanan Dibatalkan.</strong> Stok produk telah dikembalikan ke sistem. Jika terdapat kekeliruan, silakan hubungi admin jurusan terkait.
                    </div>
                @else
                    <!-- Stepper Alur Fisik: Menunggu Konfirmasi -> Sedang Dikemas -> Siap Diambil di Lab -> Selesai -->
                    <div style="margin: 20px 0 10px;">
                        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; position: relative;">
                            <!-- Step 1: Menunggu Konfirmasi -->
                            <div style="text-align: center;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; margin: 0 auto 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px;
                                            {{ $stepIndex >= 1 ? 'background: #2563eb; color: #fff; box-shadow: 0 3px 10px rgba(37,99,235,0.3);' : 'background: #f1f5f9; color: #94a3b8;' }}">
                                    {{ $stepIndex > 1 ? '✓' : '1' }}
                                </div>
                                <div style="font-size: 12.5px; font-weight: {{ $stepIndex == 1 ? '800' : '600' }}; color: {{ $stepIndex >= 1 ? '#0f172a' : '#94a3b8' }};">
                                    Menunggu Konfirmasi
                                </div>
                            </div>

                            <!-- Step 2: Sedang Dikemas -->
                            <div style="text-align: center;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; margin: 0 auto 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px;
                                            {{ $stepIndex >= 2 ? 'background: #2563eb; color: #fff; box-shadow: 0 3px 10px rgba(37,99,235,0.3);' : 'background: #f1f5f9; color: #94a3b8;' }}">
                                    {{ $stepIndex > 2 ? '✓' : '2' }}
                                </div>
                                <div style="font-size: 12.5px; font-weight: {{ $stepIndex == 2 ? '800' : '600' }}; color: {{ $stepIndex >= 2 ? '#0f172a' : '#94a3b8' }};">
                                    Sedang Dikemas
                                </div>
                            </div>

                            <!-- Step 3: Siap Diambil di Lab -->
                            <div style="text-align: center;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; margin: 0 auto 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px;
                                            {{ $stepIndex >= 3 ? 'background: #2563eb; color: #fff; box-shadow: 0 3px 10px rgba(37,99,235,0.3);' : 'background: #f1f5f9; color: #94a3b8;' }}">
                                    {{ $stepIndex > 3 ? '✓' : '3' }}
                                </div>
                                <div style="font-size: 12.5px; font-weight: {{ $stepIndex == 3 ? '800' : '600' }}; color: {{ $stepIndex >= 3 ? '#0f172a' : '#94a3b8' }};">
                                    Siap Diambil di Lab
                                </div>
                            </div>

                            <!-- Step 4: Selesai -->
                            <div style="text-align: center;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; margin: 0 auto 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px;
                                            {{ $stepIndex >= 4 ? 'background: #10b981; color: #fff; box-shadow: 0 3px 10px rgba(16,185,129,0.3);' : 'background: #f1f5f9; color: #94a3b8;' }}">
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

            <!-- Petunjuk Pengambilan jika status Siap Diambil -->
            @if($statusKey === 'bisa_diambil')
                <div style="background: #ecfdf5; border-left: 5px solid #10b981; border-radius: 12px; padding: 20px; margin-bottom: 24px; box-shadow: 0 4px 12px rgba(16,185,129,0.15);">
                    <div style="display: flex; gap: 14px; align-items: flex-start;">
                        <div style="font-size: 28px; line-height: 1;">🏢🎉</div>
                        <div>
                            <h3 style="font-size: 16px; font-weight: 800; color: #065f46; margin: 0 0 6px;">
                                Pesanan Siap Diambil di Lab Jurusan!
                            </h3>
                            <p style="font-size: 14px; color: #047857; line-height: 1.6; margin: 0 0 8px;">
                                Pesanan Anda sudah siap diambil di <strong>{{ $order->lokasi_pengambilan ?? 'Lab Jurusan TeFa' }}</strong>. Mohon siapkan uang tunai pas sebesar <strong style="color: #064e3b; font-size: 15px;">Rp {{ number_format($order->total_harga ?: $order->total_biaya, 0, ',', '.') }}</strong> saat pengambilan di kasir lab.
                            </p>
                            <div style="font-size: 12.5px; color: #065f46;">
                                📍 Lokasi Kampus: SMKN 4 Tanjungpinang (Jl. Nusantara No.KM.14 Batu IX). Jam Operasional: 08.00 - 15.30 WIB (Hari Kerja).
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Rincian Produk & Lokasi Pengambilan -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 24px;">
                <!-- Info Barang -->
                <div style="background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0; padding: 20px;">
                    <h4 style="font-size: 13px; font-weight: 800; color: #64748b; margin: 0 0 14px; text-transform: uppercase; letter-spacing: 0.5px;">
                        Rincian Barang Pesanan
                    </h4>
                    <div style="display: flex; gap: 14px; align-items: center; margin-bottom: 14px;">
                        <div style="width: 56px; height: 56px; border-radius: 8px; overflow: hidden; background: #f8fafc; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            @if($order->product && $order->product->foto)
                                <img src="{{ asset('storage/' . $order->product->foto) }}" alt="{{ $order->product->nama_produk }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <span style="font-size: 24px;">📦</span>
                            @endif
                        </div>
                        <div>
                            <strong style="font-size: 15px; color: #1e293b; display: block;">{{ $order->product->nama_produk ?? 'Produk Fisik TeFa' }}</strong>
                            <span style="font-size: 12.5px; color: #64748b;">{{ $order->jumlah }} unit &times; Rp {{ number_format($order->product->harga ?? ($order->total_harga / max(1, $order->jumlah)), 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div style="border-top: 1px solid #f1f5f9; padding-top: 10px; display: flex; justify-content: space-between; font-size: 14px; font-weight: 800;">
                        <span>Total Tagihan COD:</span>
                        <span style="color: #2563eb;">Rp {{ number_format($order->total_harga ?: $order->total_biaya, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Info Pengambilan & Metode -->
                <div style="background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0; padding: 20px;">
                    <h4 style="font-size: 13px; font-weight: 800; color: #64748b; margin: 0 0 14px; text-transform: uppercase; letter-spacing: 0.5px;">
                        Lokasi & Metode Transaksi
                    </h4>
                    <div style="margin-bottom: 8px;">
                        <span style="font-size: 12px; color: #64748b;">Metode Pengambilan:</span>
                        <div style="font-size: 13.5px; font-weight: 700; color: #0f172a;">Ambil di Tempat (Self Pick-up)</div>
                    </div>
                    <div style="margin-bottom: 8px;">
                        <span style="font-size: 12px; color: #64748b;">Lokasi Lab Jurusan:</span>
                        <div style="font-size: 13.5px; font-weight: 800; color: #16a34a;">
                            {{ $order->lokasi_pengambilan ?? ($order->product?->jurusan?->lokasi_pengambilan ?? 'Lab Teaching Factory SMKN 4') }}
                        </div>
                    </div>
                    <div>
                        <span style="font-size: 12px; color: #64748b;">Metode Pembayaran:</span>
                        <div style="font-size: 13px; font-weight: 700; color: #2563eb;">Cash on Delivery (Bayar Tunai di Kasir Lab)</div>
                    </div>
                </div>
            </div>

            <!-- Kotak Informasi Khusus Pemantauan Produk Fisik -->
            <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 14px; padding: 22px 24px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                <div style="display: flex; gap: 14px; align-items: center; max-width: 600px;">
                    <span style="font-size: 28px;">💡</span>
                    <div>
                        <h4 style="margin: 0 0 4px; font-size: 14.5px; font-weight: 800; color: #1e3a8a;">
                            Kelola Pesanan Lebih Mudah Lewat Akun Anda
                        </h4>
                        <p style="margin: 0; font-size: 13px; color: #1e40af; line-height: 1.5;">
                            Untuk produk fisik, Anda juga dapat memantau status pesanan dan rincian pengambilan langsung melalui menu <strong>Pesanan Saya</strong> di akun Anda.
                        </p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('client.orders') }}" style="background: #2563eb; color: #fff; padding: 10px 20px; border-radius: 10px; font-weight: 700; text-decoration: none; font-size: 13.5px; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 10px rgba(37,99,235,0.25);">
                        <span>Buka Pesanan Saya &rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Help Contact Footer -->
            <div style="background: #fff; border-radius: 14px; border: 1px solid #e2e8f0; padding: 18px 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                <div style="font-size: 13px; color: #64748b;">
                    Ingin koordinasi atau konfirmasi kedatangan pengambilan pesanan fisik?
                </div>
                <a href="https://wa.me/6281234567890?text=Halo%20Admin%20TeFa,%20saya%20ingin%20konfirmasi%20pengambilan%20pesanan%20fisik%20dengan%20kode%20{{ $order->order_code }}" target="_blank" style="background: #25d366; color: #fff; font-size: 13px; font-weight: 700; padding: 8px 18px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                    <span>💬</span> Chat Admin Pengambilan via WA
                </a>
            </div>
        @endif

    @else
        <!-- Initial Landing Explainer -->
        <div style="background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 48px 24px; text-align: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <div style="font-size: 54px; margin-bottom: 16px;">📦✨</div>
            <h3 style="font-size: 22px; font-weight: 800; color: #0f172a; margin-bottom: 10px;">Pelacakan Progres Pengerjaan Transparan</h3>
            <p style="color: #64748b; font-size: 15px; max-width: 580px; margin: 0 auto 28px; line-height: 1.6;">
                Setiap pemesanan jasa yang disepakati melalui WhatsApp akan diberikan <strong>Kode Tracking Unik (TEFA-XXXX)</strong> oleh Admin Jurusan. Masukkan kode tersebut pada kolom pencarian di atas untuk memantau status secara langsung.
            </p>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; max-width: 800px; margin: 0 auto; text-align: left;">
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px;">
                    <div style="font-size: 24px; margin-bottom: 8px;">1️⃣</div>
                    <strong style="color: #1e293b; font-size: 14px; display: block; margin-bottom: 4px;">Konsultasi & Order via WA</strong>
                    <span style="color: #64748b; font-size: 12.5px;">Pilih layanan di katalog, lalu hubungi admin jurusan untuk kesepakatan spesifikasi.</span>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px;">
                    <div style="font-size: 24px; margin-bottom: 8px;">2️⃣</div>
                    <strong style="color: #1e293b; font-size: 14px; display: block; margin-bottom: 4px;">Dapatkan Kode Tracking</strong>
                    <span style="color: #64748b; font-size: 12.5px;">Admin akan mencatat order dan mengirimkan tautan pelacakan langsung ke nomor WA Anda.</span>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px;">
                    <div style="font-size: 24px; margin-bottom: 8px;">3️⃣</div>
                    <strong style="color: #1e293b; font-size: 14px; display: block; margin-bottom: 4px;">Pantau & Review Hasil</strong>
                    <span style="color: #64748b; font-size: 12.5px;">Lihat tahapan pengerjaan oleh talenta siswa, lampiran revisi, hingga hasil akhir disetujui.</span>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
