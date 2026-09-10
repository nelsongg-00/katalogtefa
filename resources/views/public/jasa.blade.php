@extends('layouts.public')

@section('title', 'Layanan Jasa - Katalog TEFA SMKN 4 Tanjungpinang')

@section('content')
    <div class="page-header">
        <h1>Layanan Jasa</h1>
        <p>Solusi profesional dari siswa SMKN 4 Tanjungpinang untuk kebutuhan Anda</p>
    </div>

    <div class="container">
        <div class="produk-grid">
            @foreach($jasas as $jasa)
                <div class="produk-card">
                    @if($jasa->foto_produk)
                        <img src="{{ Storage::url($jasa->foto_produk) }}" alt="{{ $jasa->nama_produk }}" class="produk-img">
                    @else
                        <div class="produk-img" style="display: flex; align-items: center; justify-content: center; background: #e2e8f0; color: #94a3b8; font-size: 40px;">
                            🤝
                        </div>
                    @endif
                    <div class="produk-info">
                        <h3>{{ $jasa->nama_produk }}</h3>
                        <span style="font-size: 12px; color: #10b981; background: #d1fae5; padding: 3px 10px; border-radius: 10px; display: inline-block; margin-bottom: 10px; width: fit-content;">
                            {{ $jasa->jurusan->nama_jurusan ?? 'Layanan Jasa' }}
                        </span>
                        <p>{{ Str::limit($jasa->deskripsi, 80) }}</p>
                        <div class="produk-price">Mulai Rp {{ number_format($jasa->harga, 0, ',', '.') }}</div>
                        
                        <a href="https://wa.me/6281234567890?text=Halo%20saya%20ingin%20memesan%20jasa%20{{ urlencode($jasa->nama_produk) }}" target="_blank" class="btn-blue text-center" style="width: 100%; border-radius: 10px; background: #10b981; border:none;">Tanya via WhatsApp</a>
                    </div>
                </div>
            @endforeach

            <!-- DUMMY JASA 1 -->
            <div class="produk-card">
                <div class="produk-img" style="display: flex; align-items: center; justify-content: center; background: #e2e8f0; color: #94a3b8; font-size: 40px;">💻</div>
                <div class="produk-info">
                    <h3>Pembuatan Website Company Profile</h3>
                    <span style="font-size: 12px; color: #10b981; background: #d1fae5; padding: 3px 10px; border-radius: 10px; display: inline-block; margin-bottom: 10px; width: fit-content;">Rekayasa Perangkat Lunak</span>
                    <p>Pembuatan website profil perusahaan responsif dan modern dengan CMS.</p>
                    <div class="produk-price">Mulai Rp 1.500.000</div>
                    <a href="https://wa.me/6281234567890?text=Halo%20saya%20ingin%20memesan%20jasa%20Pembuatan%20Website" target="_blank" class="btn-blue text-center" style="width: 100%; border-radius: 10px; background: #10b981; border:none;">Tanya via WhatsApp</a>
                </div>
            </div>

            <!-- DUMMY JASA 2 -->
            <div class="produk-card">
                <div class="produk-img" style="display: flex; align-items: center; justify-content: center; background: #e2e8f0; color: #94a3b8; font-size: 40px;">🎥</div>
                <div class="produk-info">
                    <h3>Dokumentasi Video Acara</h3>
                    <span style="font-size: 12px; color: #10b981; background: #d1fae5; padding: 3px 10px; border-radius: 10px; display: inline-block; margin-bottom: 10px; width: fit-content;">Produksi Program Siaran Televisi</span>
                    <p>Jasa liputan dan dokumentasi video untuk acara pernikahan, perpisahan, atau seminar.</p>
                    <div class="produk-price">Mulai Rp 2.000.000</div>
                    <a href="https://wa.me/6281234567890?text=Halo%20saya%20ingin%20memesan%20jasa%20Dokumentasi%20Video" target="_blank" class="btn-blue text-center" style="width: 100%; border-radius: 10px; background: #10b981; border:none;">Tanya via WhatsApp</a>
                </div>
            </div>

            <!-- DUMMY JASA 3 -->
            <div class="produk-card">
                <div class="produk-img" style="display: flex; align-items: center; justify-content: center; background: #e2e8f0; color: #94a3b8; font-size: 40px;">🔧</div>
                <div class="produk-info">
                    <h3>Instalasi Jaringan & WiFi Kantor</h3>
                    <span style="font-size: 12px; color: #10b981; background: #d1fae5; padding: 3px 10px; border-radius: 10px; display: inline-block; margin-bottom: 10px; width: fit-content;">Teknik Komputer Jaringan</span>
                    <p>Jasa pemasangan jaringan kabel LAN dan setup WiFi router untuk kantor atau lab.</p>
                    <div class="produk-price">Mulai Rp 500.000</div>
                    <a href="https://wa.me/6281234567890?text=Halo%20saya%20ingin%20memesan%20jasa%20Instalasi%20Jaringan" target="_blank" class="btn-blue text-center" style="width: 100%; border-radius: 10px; background: #10b981; border:none;">Tanya via WhatsApp</a>
                </div>
            </div>

            <!-- DUMMY JASA 4 -->
            <div class="produk-card">
                <div class="produk-img" style="display: flex; align-items: center; justify-content: center; background: #e2e8f0; color: #94a3b8; font-size: 40px;">🎬</div>
                <div class="produk-info">
                    <h3>Pembuatan Video Animasi Iklan</h3>
                    <span style="font-size: 12px; color: #10b981; background: #d1fae5; padding: 3px 10px; border-radius: 10px; display: inline-block; margin-bottom: 10px; width: fit-content;">Animasi</span>
                    <p>Pembuatan video promosi animasi 2D berdurasi 1 menit untuk media sosial.</p>
                    <div class="produk-price">Mulai Rp 800.000</div>
                    <a href="https://wa.me/6281234567890?text=Halo%20saya%20ingin%20memesan%20jasa%20Video%20Animasi" target="_blank" class="btn-blue text-center" style="width: 100%; border-radius: 10px; background: #10b981; border:none;">Tanya via WhatsApp</a>
                </div>
            </div>

        </div>
    </div>
@endsection
