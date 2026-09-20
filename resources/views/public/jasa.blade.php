@extends('layouts.public')

@section('title', 'Layanan Jasa - Katalog TEFA SMKN 4 Tanjungpinang')

@section('content')
    <div class="page-header" style="background: linear-gradient(135deg, #0a215e 0%, #1e3a8a 100%); color: #fff; padding: 50px 20px; text-align: center;">
        <h1 style="font-size: 30px; font-weight: 800; margin-bottom: 8px;">Layanan Jasa Kejuruan TeFa</h1>
        <p style="color: #cbd5e1; font-size: 15px; max-width: 600px; margin: 0 auto;">Solusi profesional karya siswa dan unit Teaching Factory SMKN 4 Tanjungpinang berstandar industri.</p>
    </div>

    <div class="container" style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
        <div class="produk-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 24px;">
            @forelse($services as $service)
                <div class="produk-card" style="background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.04); display: flex; flex-direction: column; transition: transform .2s, box-shadow .2s;">
                    @if($service->foto)
                        <img src="{{ asset('storage/' . $service->foto) }}" alt="{{ $service->nama_layanan }}" class="produk-img" style="width: 100%; height: 200px; object-fit: cover;">
                    @else
                        <div class="produk-img" style="height: 180px; display: flex; align-items: center; justify-content: center; background: #f1f5f9; color: #94a3b8; font-size: 48px;">
                            @php
                                $kode = strtoupper($service->department->kode ?? '');
                                $icon = match($kode) {
                                    'RPL' => '💻',
                                    'DKV' => '🎨',
                                    'ANI', 'PSPT' => '🎥',
                                    'TKJ' => '🔧',
                                    'GIM' => '🎮',
                                    default => '🤝',
                                };
                            @endphp
                            <span>{{ $icon }}</span>
                        </div>
                    @endif

                    <div class="produk-info" style="padding: 22px; display: flex; flex-direction: column; flex: 1;">
                        <span style="font-size: 12px; font-weight: 700; color: #059669; background: #ecfdf5; padding: 4px 12px; border-radius: 999px; width: fit-content; margin-bottom: 10px;">
                            {{ $service->department->nama_jurusan ?? 'Unit Teaching Factory' }}
                        </span>

                        <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 8px; line-height: 1.35;">
                            {{ $service->nama_layanan }}
                        </h3>

                        <p style="font-size: 13.5px; color: #64748b; line-height: 1.5; margin-bottom: 16px; flex: 1;">
                            {{ Str::limit($service->deskripsi, 120) }}
                        </p>

                        <div class="produk-price" style="font-size: 18px; font-weight: 800; color: #2563eb; margin-bottom: 16px;">
                            Mulai Rp {{ number_format($service->estimasi_harga, 0, ',', '.') }}
                        </div>

                        <a href="https://wa.me/6281234567890?text=Halo%20Admin%20TeFa,%20saya%20tertarik%20untuk%20konsultasi/memesan%20jasa%20{{ urlencode($service->nama_layanan) }}" 
                           target="_blank" 
                           class="btn-blue text-center" 
                           style="width: 100%; border-radius: 12px; background: #10b981; border: none; text-decoration: none; padding: 12px; font-weight: 700; font-size: 14px; color: #ffffff; display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: background .15s; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);">
                            <span>💬</span> Konsultasi via WhatsApp
                        </a>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: #fff; border-radius: 16px; border: 1px solid #e2e8f0;">
                    <div style="font-size: 48px; margin-bottom: 12px;">🤝</div>
                    <h3 style="font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">Belum Ada Layanan Jasa</h3>
                    <p style="color: #64748b; font-size: 14px; max-width: 480px; margin: 0 auto;">Saat ini belum ada data layanan jasa yang aktif. Silakan kembali lagi nanti atau hubungi pihak sekolah.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
