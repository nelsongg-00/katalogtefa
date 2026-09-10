@extends('layouts.public')

@section('title', 'Profil Sekolah - Katalog TEFA SMKN 4 Tanjungpinang')

@section('content')
    <div class="page-header">
        <h1>Profil Sekolah</h1>
        <p>Mengenal lebih dekat SMKN 4 Tanjungpinang dan Program Teaching Factory</p>
    </div>

    <div class="container-sm">
        <div class="profil-content">
            <h2 class="section-title text-center" style="margin-bottom: 30px;">Tentang SMKN 4 Tanjungpinang</h2>
            <p>
                SMKN 4 Tanjungpinang adalah salah satu sekolah menengah kejuruan unggulan yang berfokus pada bidang teknologi informasi, komunikasi, dan industri kreatif. Kami berkomitmen untuk menghasilkan lulusan yang kompeten, profesional, dan siap menghadapi tantangan dunia kerja maupun wirausaha di era digital.
            </p>
            <p style="margin-top: 15px;">
                Melalui program <strong>Teaching Factory (TEFA)</strong>, sekolah kami memadukan kurikulum pendidikan dengan standar industri yang sesungguhnya. Siswa tidak hanya belajar teori di kelas, tetapi juga langsung mempraktikkan keahlian mereka dalam memproduksi barang dan jasa yang memiliki nilai jual dan bermanfaat bagi masyarakat luas.
            </p>
        </div>

        <div class="text-center" style="margin-top: 60px;">
            <span class="section-label">— Program Keahlian</span>
            <h2 class="section-title">6 Jurusan Unggulan Kami</h2>
            <p class="section-subtitle">Setiap jurusan memiliki unit produksi Teaching Factory yang siap melayani kebutuhan Anda.</p>
        </div>

        <div class="jurusan-grid">
            <div class="jurusan-card">
                <div class="jurusan-icon">💻</div>
                <h3>Rekayasa Perangkat Lunak</h3>
                <p style="font-size: 14px; color: #64748b;">Pembuatan aplikasi website, desktop, mobile, dan sistem informasi manajemen.</p>
            </div>
            
            <div class="jurusan-card">
                <div class="jurusan-icon">🔌</div>
                <h3>Teknik Komputer Jaringan</h3>
                <p style="font-size: 14px; color: #64748b;">Instalasi jaringan LAN/WAN, perbaikan komputer, dan konfigurasi server.</p>
            </div>
            
            <div class="jurusan-card">
                <div class="jurusan-icon">🎨</div>
                <h3>Desain Komunikasi Visual</h3>
                <p style="font-size: 14px; color: #64748b;">Desain logo, banner, poster, packaging produk, dan branding perusahaan.</p>
            </div>
            
            <div class="jurusan-card">
                <div class="jurusan-icon">🎥</div>
                <h3>Produksi Program Siaran Televisi</h3>
                <p style="font-size: 14px; color: #64748b;">Jasa shooting video, liputan acara, editing video, dan produksi iklan liputan.</p>
            </div>
            
            <div class="jurusan-card">
                <div class="jurusan-icon">🎬</div>
                <h3>Animasi</h3>
                <p style="font-size: 14px; color: #64748b;">Pembuatan aset animasi 2D/3D, motion graphic, dan video explainer interaktif.</p>
            </div>
            
            <div class="jurusan-card">
                <div class="jurusan-icon">🎮</div>
                <h3>Pengembangan Gim</h3>
                <p style="font-size: 14px; color: #64748b;">Desain karakter game, environment, dan pengembangan game edukasi/hiburan.</p>
            </div>
        </div>
    </div>
@endsection
