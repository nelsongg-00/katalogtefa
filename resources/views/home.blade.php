@extends('layouts.public')

@section('title', 'Katalog TEFA — SMKN 4 Tanjungpinang')

@section('content')
    <style>
        /* Scoped Enhancements for Home Page */
        .home-feature-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            padding: 30px 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            text-align: left;
            position: relative;
            overflow: hidden;
        }

        .home-feature-card:hover {
            transform: translateY(-6px);
            border-color: #93c5fd;
            box-shadow: 0 16px 30px rgba(37, 99, 235, 0.1);
        }

        .home-feature-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin-bottom: 20px;
        }

        .home-feature-card h3 {
            font-size: 1.2rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 10px;
        }

        .home-feature-card p {
            font-size: 14px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 20px;
            flex-grow: 1;
        }

        .home-feature-link {
            font-size: 13.5px;
            font-weight: 700;
            color: #2563eb;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: gap 0.2s ease;
        }

        .home-feature-card:hover .home-feature-link {
            gap: 10px;
            color: #1d4ed8;
        }

        .hero-trust-bar {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
        }

        .hero-trust-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13.5px;
            color: #e2e8f0;
            font-weight: 600;
        }
    </style>

    <!-- 1. HERO SECTION DENGAN VIDEO BACKGROUND -->
    <section class="hero-section">
        <video autoplay loop muted playsinline class="hero-video">
            <!-- SUMBER VIDEO -->
            <source src="{{ asset('asset/vid/profil-smk.mp4') }}" type="video/mp4">
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
                Temukan berbagai karya inovasi, produk fisik kreatif, teknologi terapan, dan layanan jasa kustom hasil unit Teaching Factory siswa SMKN 4 Tanjungpinang.
            </p>
            <div class="hero-buttons">
                <a href="{{ route('produk') }}" class="btn btn-primary">Jelajahi Produk</a>
                <a href="{{ route('jasa') }}" class="btn btn-outline">Lihat Layanan Jasa</a>
            </div>

            <!-- Trust Badges -->
            <div class="hero-trust-bar">
                <div class="hero-trust-item">
                    <span>✨</span> 6 Program Keahlian
                </div>
                <div class="hero-trust-item">
                    <span>🏭</span> Standar Kualitas Industri
                </div>
                <div class="hero-trust-item">
                    <span>⚡</span> Bimbingan Guru Ahli & Praktisi
                </div>
            </div>
        </div>
    </section>

    <!-- 2. SECTION TENTANG TEFA -->
    <div class="container about-layout">
        <div class="about-img-wrapper">
            <!-- FOTO SEKOLAH / TEFA -->
            <img src="{{ asset('asset/img/foto-sekolahmu.jpg') }}" alt="Teaching Factory SMKN 4 Tanjungpinang"
                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=800&auto=format&fit=crop';">
        </div>
        <div class="about-text">
            <span class="section-label">Tentang Teaching Factory</span>
            <h2 class="section-title">TEFA SMKN 4 TANJUNGPINANG</h2>
            <p class="section-subtitle">
                Teaching Factory (TEFA) SMKN 4 Tanjungpinang adalah ekosistem pembelajaran berbasis produksi riil yang dirancang sesuai standar industri modern. Kami menghasilkan produk bernilai jual tinggi dan layanan jasa profesional yang siap melayani masyarakat serta pelaku usaha.
            </p>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="{{ route('profil') }}" class="btn-blue" style="border-radius: 999px; padding: 12px 26px; font-weight: 700; text-decoration: none;">
                    Kenali TEFA Lebih Dekat &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- 3. SECTION SOLUSI & BIDANG KEAHLIAN UNGGULAN (TASTEFUL & TIDAK OVER) -->
    <div class="container" style="padding-top: 20px; padding-bottom: 60px;">
        <div class="text-center" style="max-width: 650px; margin: 0 auto 40px;">
            <span class="section-label">Solusi & Layanan</span>
            <h2 class="section-title">Bidang Layanan Unggulan TEFA</h2>
            <p class="section-subtitle" style="margin-bottom: 0;">Layanan produksi dan jasa profesional yang dikerjakan langsung oleh siswa berprestasi dengan standar industri.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 24px;">
            
            <!-- Card 1: Software & Web -->
            <div class="home-feature-card">
                <div class="home-feature-icon" style="background: #eff6ff; color: #2563eb;">
                    💻
                </div>
                <h3>Software & Web Systems</h3>
                <p>Pembuatan website profile, sistem informasi manajemen, aplikasi mobile Android/iOS, dan game edukasi interaktif.</p>
                <a href="{{ route('jasa') }}" class="home-feature-link">
                    <span>Lihat Layanan IT</span>
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <!-- Card 2: Networking & Hardware -->
            <div class="home-feature-card">
                <div class="home-feature-icon" style="background: #f0fdf4; color: #16a34a;">
                    🌐
                </div>
                <h3>Jaringan & Maintenance PC</h3>
                <p>Instalasi kabel LAN/Fiber Optic/WiFi, perakitan komputer PC, troubleshooting hardware, dan perawatan server instansi.</p>
                <a href="{{ route('jasa') }}" class="home-feature-link" style="color: #16a34a;">
                    <span>Lihat Layanan Jaringan</span>
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <!-- Card 3: Visual Design & Branding -->
            <div class="home-feature-card">
                <div class="home-feature-icon" style="background: #fdf2f8; color: #db2777;">
                    🎨
                </div>
                <h3>Desain Grafis & Branding</h3>
                <p>Pembuatan identitas merek, logo perusahaan, kemasan produk (packaging) UMKM, poster, banner, dan merchandise.</p>
                <a href="{{ route('jasa') }}" class="home-feature-link" style="color: #db2777;">
                    <span>Lihat Layanan Desain</span>
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <!-- Card 4: Video & Multimedia -->
            <div class="home-feature-card">
                <div class="home-feature-icon" style="background: #fff7ed; color: #ea580c;">
                    📹
                </div>
                <h3>Videografi & Animasi 3D</h3>
                <p>Dokumentasi liputan event, pembuatan video profile perusahaan, video iklan produk, dan animasi edukatif 2D/3D.</p>
                <a href="{{ route('jasa') }}" class="home-feature-link" style="color: #ea580c;">
                    <span>Lihat Layanan Media</span>
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

        </div>
    </div>

    <!-- 4. SECTION BANNER BANTUAN -->
    <div class="container" style="padding-top: 0;">
        <div class="help-banner">
            <span class="section-label" style="background: rgba(255,255,255,0.2); padding: 5px 15px; border-radius: 20px; color: white;">✨ Konsultasi & Kolaborasi</span>
            <h2>Punya Kebutuhan Project atau Pesanan Khusus?</h2>
            <p style="max-width: 650px; margin: 0 auto 20px; color: #cbd5e1; line-height: 1.7;">
                Tim Teaching Factory SMKN 4 Tanjungpinang siap berkolaborasi menghasilkan karya berkualitas sesuai kebutuhan bisnis dan lembaga Anda.
            </p>
            <div class="help-buttons">
                <a href="{{ route('produk') }}" class="help-card">
                    <div style="width: 15px; height: 15px; background: #2563eb; border-radius: 3px;"></div>
                    <div>
                        <b style="color:white">Katalog Produk Fisik</b><br>
                        <small style="color:#cbd5e1">Beli karya siswa langsung</small>
                    </div>
                </a>
                <a href="{{ route('jasa') }}" class="help-card">
                    <div style="width: 15px; height: 15px; background: #ffb703; border-radius: 50%;"></div>
                    <div>
                        <b style="color:white">Konsultasi Layanan Jasa</b><br>
                        <small style="color:#cbd5e1">Diskusikan kebutuhan project</small>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
