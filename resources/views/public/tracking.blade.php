@extends('layouts.public')

@section('title', 'Lacak Pesanan Layanan Jasa — Teaching Factory SMKN 4 Tanjungpinang')

@section('content')
<div class="page-header" style="background: linear-gradient(135deg, #0a215e 0%, #1e3a8a 100%); color: #fff; padding: 50px 20px; text-align: center;">
    <h1 style="font-size: 28px; font-weight: 800; margin-bottom: 8px;">Lacak Status Pesanan Layanan Jasa</h1>
    <p style="color: #cbd5e1; font-size: 15px; max-width: 620px; margin: 0 auto 24px;">
        Pantau progres pengerjaan pesanan unit Teaching Factory SMKN 4 Tanjungpinang secara transparan & real-time tanpa perlu login.
    </p>

    <!-- Search Box -->
    <div style="max-width: 520px; margin: 0 auto;">
        <form method="POST" action="{{ route('order.search') }}" style="display: flex; background: #fff; border-radius: 999px; padding: 6px 8px 6px 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
            @csrf
            <input type="text" name="order_code" value="{{ $searchedCode ?? ($order->order_code ?? '') }}" placeholder="Masukkan Kode Tracking (Contoh: TEFA-9821)" required
                   style="flex: 1; border: none; outline: none; font-size: 14.5px; color: #0f172a; font-weight: 600; text-transform: uppercase;">
            <button type="submit" style="background: #2563eb; color: #fff; border: none; padding: 10px 24px; border-radius: 999px; font-weight: 700; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                <span>🔍</span> Lacak
            </button>
        </form>
    </div>
</div>

<div class="container" style="max-width: 960px; margin: 40px auto; padding: 0 20px;">

    @if(isset($searchedCode) && !isset($order))
        <!-- Order Not Found -->
        <div style="background: #fff; border-radius: 16px; border: 1px solid #fee2e2; padding: 40px 24px; text-align: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <div style="font-size: 48px; margin-bottom: 12px;">🔎</div>
            <h3 style="font-size: 20px; font-weight: 800; color: #1e293b; margin-bottom: 8px;">Pesanan Tidak Ditemukan</h3>
            <p style="color: #64748b; font-size: 14.5px; max-width: 500px; margin: 0 auto 20px; line-height: 1.6;">
                Kode tracking <strong style="color: #dc2626; font-family: monospace;">{{ $searchedCode }}</strong> tidak terdaftar dalam database kami. Pastikan format kode sudah sesuai (contoh: <code>TEFA-9821</code>).
            </p>
            <div style="display: flex; justify-content: center; gap: 12px; flex-wrap: wrap;">
                <a href="{{ route('order.tracking.index') }}" style="background: #f1f5f9; color: #334155; padding: 10px 20px; border-radius: 10px; font-weight: 700; text-decoration: none; font-size: 13.5px;">Coba Lagi</a>
                <a href="https://wa.me/6281234567890?text=Halo%20Admin%20TeFa,%20saya%20ingin%20menanyakan%20kode%20pelacakan%20{{ $searchedCode }}" target="_blank" style="background: #25d366; color: #fff; padding: 10px 20px; border-radius: 10px; font-weight: 700; text-decoration: none; font-size: 13.5px; display: inline-flex; align-items: center; gap: 6px;">
                    <span>💬</span> Hubungi Admin WhatsApp
                </a>
            </div>
        </div>

    @elseif(isset($order))
        <!-- Order Details Card -->
        @php
            $progressPercent = match($order->status) {
                'completed' => 100,
                'review' => 85,
                'in_progress' => max(30, $order->project->progress ?? 50),
                'cancelled' => 0,
                default => 15,
            };

            $statusBg = match($order->status) {
                'completed' => '#ecfdf5',
                'review' => '#fef3c7',
                'in_progress' => '#eff6ff',
                'cancelled' => '#fef2f2',
                default => '#f1f5f9',
            };
            $statusColor = match($order->status) {
                'completed' => '#059669',
                'review' => '#b45309',
                'in_progress' => '#2563eb',
                'cancelled' => '#dc2626',
                default => '#475569',
            };
            $statusLabel = match($order->status) {
                'completed' => 'Pesanan Selesai 🎉',
                'review' => 'Tahap Review & Approval File',
                'in_progress' => 'Sedang Dikerjakan oleh Worker',
                'cancelled' => 'Pesanan Dibatalkan',
                default => 'Menunggu Antrean Pengerjaan',
            };
        @endphp

        <div style="background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 30px;">
            <!-- Header Banner -->
            <div style="padding: 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; background: #fafcff;">
                <div>
                    <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Nomor Resi / Pelacakan</span>
                    <div style="display: flex; align-items: center; gap: 10px; margin-top: 2px;">
                        <span style="font-family: monospace; font-size: 24px; font-weight: 800; color: #1e3a8a;">{{ $order->order_code }}</span>
                        <button onclick="navigator.clipboard.writeText('{{ $order->order_code }}'); alert('Kode tracking tersalin!');" title="Salin Kode" style="background: #eff6ff; border: 1px solid #bfdbfe; color: #2563eb; border-radius: 6px; padding: 4px 8px; font-size: 12px; cursor: pointer; font-weight: 700;">
                            📋 Salin
                        </button>
                    </div>
                </div>
                <div>
                    <span style="background: {{ $statusBg }}; color: {{ $statusColor }}; font-weight: 800; font-size: 13px; padding: 8px 16px; border-radius: 999px; display: inline-flex; align-items: center; gap: 6px;">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: {{ $statusColor }};"></span>
                        {{ $statusLabel }}
                    </span>
                </div>
            </div>

            <!-- Progress Bar -->
            <div style="padding: 20px 24px; background: #fff; border-bottom: 1px solid #f1f5f9;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; font-size: 13px; font-weight: 700; color: #334155;">
                    <span>Progres Pengerjaan Keseluruhan</span>
                    <span style="color: #2563eb;">{{ $progressPercent }}%</span>
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
                    <strong style="color: #0f172a; font-size: 15px;">{{ $order->service->nama_layanan ?? '-' }}</strong>
                    <div style="font-size: 12px; color: #059669; font-weight: 600;">
                        {{ $order->service->department->nama_jurusan ?? 'Unit Teaching Factory' }}
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
                    <strong style="color: #0f172a; font-size: 14px;">{{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB</strong>
                </div>
            </div>

            @if($order->catatan)
                <div style="padding: 14px 24px; background: #f8fafc; border-top: 1px solid #f1f5f9; font-size: 13px; color: #475569;">
                    <strong style="color: #1e293b;">Catatan Khusus Klien:</strong> {{ $order->catatan }}
                </div>
            @endif
        </div>

        <!-- Timeline Log Pengerjaan (Tracking Resi Style) -->
        <div style="background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 28px 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px;">
                <div>
                    <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 4px;">Lini Masa Progres Pengerjaan</h3>
                    <p style="font-size: 13px; color: #64748b; margin: 0;">Catatan aktivitas pengerjaan oleh siswa dan instruktur Teaching Factory.</p>
                </div>
                <span style="font-size: 12px; background: #eff6ff; color: #2563eb; font-weight: 700; padding: 4px 12px; border-radius: 999px;">
                    Real-time Tracking
                </span>
            </div>

            @php
                $logs = $order->project ? $order->project->projectLogs()->with('worker')->orderBy('created_at', 'desc')->get() : collect();
            @endphp

            @if($logs->count() > 0)
                <div style="position: relative; padding-left: 28px;">
                    <!-- Vertical Line -->
                    <div style="position: absolute; left: 11px; top: 10px; bottom: 10px; width: 2px; background: #e2e8f0;"></div>

                    @foreach($logs as $index => $log)
                        <div style="position: relative; margin-bottom: 28px;">
                            <!-- Node Dot -->
                            <div style="position: absolute; left: -28px; top: 4px; width: 22px; height: 22px; border-radius: 50%; background: {{ $index === 0 ? '#2563eb' : '#fff' }}; border: 3px solid {{ $index === 0 ? '#93c5fd' : '#cbd5e1' }}; display: flex; align-items: center; justify-content: center;">
                                @if($index === 0)
                                    <div style="width: 6px; height: 6px; border-radius: 50%; background: #fff;"></div>
                                @endif
                            </div>

                            <!-- Log Content Card -->
                            <div style="background: {{ $index === 0 ? '#f8fafd' : '#fff' }}; border: 1px solid {{ $index === 0 ? '#bfdbfe' : '#e2e8f0' }}; border-radius: 12px; padding: 16px 20px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; margin-bottom: 8px;">
                                    <span style="font-size: 13.5px; font-weight: 700; color: {{ $index === 0 ? '#1e3a8a' : '#1e293b' }};">
                                        {{ $log->worker->name ?? 'Admin / Instruktur' }}
                                    </span>
                                    <span style="font-size: 12px; color: #64748b; font-weight: 500;">
                                        {{ $log->created_at->translatedFormat('d M Y, H:i') }} WIB
                                    </span>
                                </div>

                                <p style="font-size: 14px; color: #334155; line-height: 1.6; margin: 0 0 12px;">
                                    {{ $log->catatan }}
                                </p>

                                @if($log->lampiran_file || $log->link_eksternal)
                                    <div style="display: flex; gap: 10px; flex-wrap: wrap; padding-top: 10px; border-top: 1px dashed #e2e8f0;">
                                        @if($log->lampiran_file)
                                            <a href="{{ asset('storage/' . $log->lampiran_file) }}" target="_blank" style="background: #eff6ff; color: #2563eb; font-size: 12.5px; font-weight: 700; padding: 6px 12px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; border: 1px solid #bfdbfe;">
                                                <span>📎</span> Lihat Lampiran File
                                            </a>
                                        @endif

                                        @if($log->link_eksternal)
                                            <a href="{{ $log->link_eksternal }}" target="_blank" style="background: #f1f5f9; color: #334155; font-size: 12.5px; font-weight: 700; padding: 6px 12px; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; border: 1px solid #cbd5e1;">
                                                <span>🔗</span> Buka Tautan Eksternal ↗
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 32px; color: #64748b;">
                    <div style="font-size: 32px; margin-bottom: 8px;">⏳</div>
                    <p style="margin: 0; font-size: 14px;">Belum ada catatan log perkembangan untuk pesanan ini. Mohon menunggu pembaruan dari tim teknis kami.</p>
                </div>
            @endif

            <!-- Help Contact Footer -->
            <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                <div style="font-size: 13px; color: #64748b;">
                    Punya pertanyaan atau ingin menyampaikan revisi terkait pesanan ini?
                </div>
                <a href="https://wa.me/6281234567890?text=Halo%20Admin%20TeFa,%20saya%20ingin%20konsultasi%20mengenai%20pesanan%20dengan%20kode%20{{ $order->order_code }}" target="_blank" style="background: #25d366; color: #fff; font-size: 13px; font-weight: 700; padding: 8px 18px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                    <span>💬</span> Hubungi Tim via WhatsApp
                </a>
            </div>
        </div>

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
