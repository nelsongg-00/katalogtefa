<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - Katalog TEFA SMKN 4 Tanjungpinang</title>
    
    <style>
        /* --- RESET & FONT BASE --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f8fafc;
            overflow-x: hidden;
        }

        /* --- NAVBAR --- */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 50px;
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 999;
        }
        .navbar-brand { font-weight: 800; color: #0a215e; display: flex; align-items: center; gap: 10px; }
        .navbar-brand img { height: 40px; }
        .navbar-links a { margin: 0 15px; text-decoration: none; color: #333; font-weight: 600; }
        .navbar-links a.active { color: #2563eb; background: #eff6ff; padding: 8px 15px; border-radius: 20px; }
        .navbar-auth { display: flex; align-items: center; gap: 15px; }
        .navbar-auth a { text-decoration: none; font-weight: 600; color: #333; }
        .btn-hamburger { background: none; border: 1px solid #ccc; padding: 5px 10px; border-radius: 5px; cursor: pointer; font-size: 18px; }

        /* --- HERO SECTION (VIDEO BACKGROUND) --- */
        .hero-section {
            position: relative;
            width: 100%;
            min-height: 85vh;
            display: flex;
            align-items: center;
            overflow: hidden;
            padding: 0 8%;
            color: #ffffff;
        }
        .hero-video {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            transform: translateX(-50%) translateY(-50%);
            object-fit: cover;
            z-index: 1;
        }
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, rgba(10, 33, 94, 0.95) 0%, rgba(10, 33, 94, 0.65) 100%);
            z-index: 2;
        }
        .hero-content {
            position: relative;
            z-index: 3;
            max-width: 750px;
        }
        .hero-subtitle { color: #ffb703; font-size: 14px; font-weight: 700; letter-spacing: 2px; margin-bottom: 15px; text-transform: uppercase; }
        .hero-title { font-size: 52px; font-weight: 800; line-height: 1.2; margin-bottom: 20px; }
        .text-highlight { color: #ffb703; }
        .hero-description { font-size: 17px; line-height: 1.7; color: #e2e8f0; margin-bottom: 35px; }
        .hero-buttons { display: flex; gap: 15px; }
        .btn { padding: 14px 30px; border-radius: 50px; font-weight: 600; text-decoration: none; transition: all 0.3s ease; display: inline-flex; align-items: center; justify-content: center; }
        .btn-primary { background-color: #ffb703; color: #021235; }
        .btn-primary:hover { background-color: #fb8500; transform: translateY(-2px); }
        .btn-outline { background-color: transparent; color: #fff; border: 2px solid rgba(255, 255, 255, 0.6); }
        .btn-outline:hover { background-color: #fff; color: #0a215e; transform: translateY(-2px); }

        /* --- KONTEN BAWAH (BERANDA LENGKAP) --- */
        .container { max-width: 1200px; margin: 0 auto; padding: 80px 20px; }
        .text-center { text-align: center; }
        .section-label { color: #ffb703; font-weight: 700; font-size: 14px; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 10px; display: inline-block; }
        h2.section-title { font-size: 36px; color: #0a215e; margin-bottom: 20px; font-weight: 800; }
        p.section-subtitle { color: #64748b; font-size: 16px; margin-bottom: 40px; }

        /* Tentang Sekolah */
        .about-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; align-items: center; }
        .about-img-wrapper img { width: 100%; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); object-fit: cover; }
        .btn-blue { background-color: #2563eb; color: white; margin-top: 15px; }
        .btn-blue:hover { background-color: #1d4ed8; transform: translateY(-2px); }

        /* Cara Pemesanan */
        .steps-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
        .step-card { background: #fff; border-radius: 20px; padding: 30px 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border-top: 5px solid #2563eb; transition: 0.3s; }
        .step-card:hover { transform: translateY(-10px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .step-number { background: #2563eb; color: white; width: 35px; height: 35px; display: flex; justify-content: center; align-items: center; border-radius: 50%; font-weight: bold; margin-bottom: 20px; }
        .step-card h3 { font-size: 18px; color: #0f172a; margin: 15px 0 10px 0; }
        .step-card p { font-size: 14px; color: #64748b; line-height: 1.6; }

        /* Banner Bantuan */
        .help-banner { background-color: #0a215e; border-radius: 30px; padding: 60px; color: white; margin-bottom: 80px; }
        .help-banner h2 { color: white; font-size: 32px; margin: 15px 0; }
        .help-buttons { display: flex; gap: 20px; margin-top: 30px; }
        .help-card { background: rgba(255,255,255,0.1); padding: 20px 30px; border-radius: 15px; display: flex; align-items: center; gap: 15px; cursor: pointer; transition: 0.3s; border: 1px solid rgba(255,255,255,0.1); }
        .help-card:hover { background: rgba(255,255,255,0.2); }

        /* Footer */
        footer { background-color: #020617; color: #94a3b8; padding: 60px 20px 20px 20px; font-size: 14px; }
        .footer-grid { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 40px; margin-bottom: 40px; }
        .footer-col h4 { color: white; font-size: 18px; margin-bottom: 20px; }
        .footer-col ul { list-style: none; }
        .footer-col ul li { margin-bottom: 10px; }
        .footer-col ul li a { color: #94a3b8; text-decoration: none; transition: 0.3s; }
        .footer-col ul li a:hover { color: white; }
        .footer-bottom { text-align: center; border-top: 1px solid #1e293b; padding-top: 20px; margin-top: 20px; }
    </style>
</head>
<body>

    <!-- NAVBAR (Logika Guest & Auth Ala Tokopedia) -->
    <nav class="navbar">
        <div class="navbar-brand">
            SMKN 4 TANJUNGPINANG<br><small style="font-size: 10px; color:#2563eb;">KATALOG TEFA</small>
        </div>
        <div class="navbar-links">
            <a href="/" class="active">Beranda</a>
            <a href="/profil-sekolah">Profil Sekolah</a>
            <a href="/produk">Produk</a>
            <a href="/layanan-jasa">Layanan Jasa</a>
        </div>
        <div class="navbar-auth">
            @guest
                <!-- Tampil Jika Belum Login -->
                <a href="{{ route('login') }}" style="color: #0a215e;">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary" style="padding: 8px 20px;">Daftar</a>
            @endguest
            @auth
                <!-- Tampil Jika Sudah Login -->
                <a href="/keranjang" style="font-size: 20px;">🛒<span style="background:red;color:white;border-radius:50%;font-size:10px;padding:2px 5px;position:relative;top:-10px;left:-5px;">3</span></a>
                <button class="btn-hamburger">☰</button>
            @endauth
        </div>
    </nav>

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
                <a href="/produk" class="btn btn-primary">Jelajahi Produk &rarr;</a>
                <a href="/layanan-jasa" class="btn btn-outline">Lihat Layanan Jasa</a>
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
            <a href="/profil-sekolah" class="btn btn-blue">Kenali TEFA &rarr;</a>
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
                <div class="help-card">
                    <div style="width: 15px; height: 15px; background: #2563eb; border-radius: 3px;"></div>
                    <div>
                        <b style="color:white">Jelajahi Produk</b><br>
                        <small>Temukan produk TEFA</small>
                    </div>
                </div>
                <div class="help-card">
                    <div style="width: 15px; height: 15px; background: #94a3b8; border-radius: 50%;"></div>
                    <div>
                        <b style="color:white">Konsultasi Layanan</b><br>
                        <small>Konsultasikan kebutuhanmu</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer>
        <div class="footer-grid">
            <div class="footer-col">
                <h4 style="display: flex; align-items: center; gap: 10px;">
                    SMKN 4<br>TANJUNGPINANG
                </h4>
                <p style="color: #ffb703; font-weight: bold; margin-bottom: 10px;">Katalog TEFA</p>
                <p>Katalog digital karya, produk, dan layanan jasa hasil Teaching Factory siswa dari enam program keahlian.</p>
            </div>
            <div class="footer-col">
                <h4>🧭 Navigasi</h4>
                <ul>
                    <li><a href="/">> Beranda</a></li>
                    <li><a href="/profil-sekolah">> Profil Sekolah</a></li>
                    <li><a href="/produk">> Produk</a></li>
                    <li><a href="/layanan-jasa">> Layanan Jasa</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>💡 TEFA</h4>
                <ul>
                    <li><a href="#">• RPL</a></li>
                    <li><a href="#">• TKJ</a></li>
                    <li><a href="#">• DKV</a></li>
                    <li><a href="#">• PSPT</a></li>
                    <li><a href="#">• Animasi</a></li>
                    <li><a href="#">• GIM</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>📞 Kontak</h4>
                <ul>
                    <li>📍 Jl. Nusantara No.KM.14 Batu IX, Kec. Tanjungpinang</li>
                    <li>✉️ smkntpi4@gmail.com</li>
                    <li>📞 +62 878-1948-317</li>
                    <li>🌐 @smkn4tgpinang</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 SMKN 4 Tanjungpinang — Katalog TEFA. Semua hak cipta dilindungi.</p>
        </div>
    </footer>

</body>
</html>     