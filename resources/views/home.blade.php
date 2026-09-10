@extends('layouts.public')

@section('content')
    <!-- HERO SECTION DENGAN VIDEO BACKGROUND -->
    <section class="hero-section">
        <video autoplay loop muted playsinline class="hero-video">
            <!-- SUMBER VIDEO KAMU -->
            <source src="{{ asset('asset/video/profil-smk.mp4') }}" type="video/mp4">
        </video>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <p class="hero-subtitle">SEKOLAH MENENGAH KEJURUAN NEGERI</p>
            <h1 class="hero-title">
                Selamat Datang di <span class="text-highlight">KATALOG</span><br>
                <span class="text-highlight">TEFA</span> SMKN 4<br>
                Tanjungpinang
            </h1>
            <p class="hero-description">
                Temukan berbagai karya, produk kreatif, teknologi, dan layanan<br>
                jasa hasil Teaching Factory siswa SMKN 4 Tanjungpinang.
            </p>
            <div class="hero-buttons">
                <a href="{{ route('produk') }}" class="btn btn-primary">Jelajahi Produk &rarr;</a>
                <a href="{{ route('jasa') }}" class="btn btn-outline">Lihat Layanan Jasa</a>
            </div>
        </div>
    </section>

    <!-- SECTION TENTANG SEKOLAH -->
    <div class="container about-layout">
        <div class="about-img-wrapper">
            <!-- FOTO SEKOLAH KAMU -->
            <img src="{{ asset('asset/img/foto-sekolahmu.jpg') }}" alt="Gedung SMKN 4 Tanjungpinang">
        </div>
        <div class="about-text">
            <span class="section-label">— Tentang Sekolah</span>
            <h2 class="section-title">Tentang SMKN 4 Tanjungpinang</h2>
            <p class="section-subtitle">
                SMKN 4 Tanjungpinang adalah sekolah menengah kejuruan negeri yang berfokus pada pendidikan berbasis teknologi dan kreativitas, memadukan pembelajaran teori dengan praktik nyata melalui program Teaching Factory di enam bidang keahlian.
            </p>
            <a href="{{ route('profil') }}" class="btn-blue">Kenali TEFA &rarr;</a>
        </div>
    </div>

    <!-- SECTION CARA PEMESANAN -->
    <div class="container text-center">
        <span class="section-label">— Cara Pemesanan</span>
        <h2 class="section-title">Pesan Produk & Layanan<br>Cuma 4 Langkah</h2>
        <p class="section-subtitle">Mudah, cepat, dan terpercaya. Ikuti 4 langkah berikut untuk memesan produk atau layanan favoritmu.</p>

        <div class="steps-grid">
            <div class="step-card" style="border-color: #2563eb;">
                <div class="step-number">01</div>
                <h3>Pilih Produk / Layanan</h3>
                <p>Jelajahi katalog, pilih produk atau layanan jasa yang kamu butuhkan.</p>
            </div>
            <div class="step-card" style="border-color: #f97316;">
                <div class="step-number" style="background:#f97316">02</div>
                <h3>Tambah ke Keranjang</h3>
                <p>Klik pesan, item otomatis masuk ke keranjang kamu.</p>
            </div>
            <div class="step-card" style="border-color: #10b981;">
                <div class="step-number" style="background:#10b981">03</div>
                <h3>Pilih Pembayaran</h3>
                <p>Di halaman keranjang, pilih metode pembayaran yang paling gampang.</p>
            </div>
            <div class="step-card" style="border-color: #8b5cf6;">
                <div class="step-number" style="background:#8b5cf6">04</div>
                <h3>Konfirmasi & Selesai</h3>
                <p>Buat pesanan, tim TEFA akan segera menghubungi kamu.</p>
            </div>
        </div>
    </div>

    <!-- SECTION BANNER BANTUAN -->
    <div class="container">
        <div class="help-banner">
            <span class="section-label" style="background: rgba(255,255,255,0.2); padding: 5px 15px; border-radius: 20px; color: white;">✨ Butuh Bantuan?</span>
            <h2>Punya Kebutuhan? Kami Siap Membantu.</h2>
            <p>Temukan produk atau layanan TEFA yang sesuai dengan kebutuhan Anda.</p>
            <div class="help-buttons">
                <a href="{{ route('produk') }}" class="help-card">
                    <div style="width: 15px; height: 15px; background: #2563eb; border-radius: 3px;"></div>
                    <div>
                        <b style="color:white">Jelajahi Produk</b><br>
                        <small style="color:#cbd5e1">Temukan produk TEFA</small>
                    </div>
                </a>
                <a href="{{ route('jasa') }}" class="help-card">
                    <div style="width: 15px; height: 15px; background: #94a3b8; border-radius: 50%;"></div>
                    <div>
                        <b style="color:white">Konsultasi Layanan</b><br>
                        <small style="color:#cbd5e1">Konsultasikan kebutuhanmu</small>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
