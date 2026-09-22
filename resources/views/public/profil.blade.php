@extends('layouts.public')

@section('title', 'Profil Teaching Factory (TEFA) — SMKN 4 Tanjungpinang')

@section('content')
<style>
    /* Custom Scoped Styling for Profil TEFA Page */
    .profil-hero {
        background: linear-gradient(135deg, #0a215e 0%, #1e3a8a 100%);
        color: #ffffff;
        padding: 70px 20px 80px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .profil-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -20%;
        width: 140%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0) 70%);
        pointer-events: none;
    }

    .profil-badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 183, 3, 0.15);
        color: #ffb703;
        padding: 6px 18px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.5px;
        border: 1px solid rgba(255, 183, 3, 0.3);
        margin-bottom: 20px;
    }

    .profil-hero h1 {
        font-size: 2.75rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin-bottom: 16px;
        line-height: 1.25;
    }

    .profil-hero p {
        color: #cbd5e1;
        font-size: 1.1rem;
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.7;
    }

    /* Stats Quick Bar */
    .stats-bar-wrapper {
        margin-top: -40px;
        position: relative;
        z-index: 10;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        background: #ffffff;
        padding: 24px 30px;
        border-radius: 20px;
        box-shadow: 0 15px 35px -5px rgba(15, 23, 42, 0.1);
        border: 1px solid #e2e8f0;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 10px;
    }

    .stat-icon-box {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .stat-val {
        font-size: 1.75rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
    }

    .stat-lbl {
        font-size: 13px;
        color: #64748b;
        font-weight: 600;
        margin-top: 2px;
    }

    /* Section Component Styles */
    .section-tag {
        color: #f59e0b;
        font-weight: 800;
        font-size: 13px;
        letter-spacing: 2px;
        text-transform: uppercase;
        display: inline-block;
        margin-bottom: 8px;
    }

    .section-heading {
        color: #0a215e;
        font-weight: 800;
        font-size: 2.1rem;
        margin-bottom: 16px;
        letter-spacing: -0.5px;
    }

    .section-lead {
        color: #64748b;
        font-size: 1.05rem;
        line-height: 1.7;
    }

    /* About TEFA Card */
    .about-card-box {
        background: #ffffff;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        display: grid;
        grid-template-columns: 1fr 1.2fr;
        gap: 40px;
        align-items: center;
    }

    .about-img-container {
        position: relative;
        border-radius: 18px;
        overflow: hidden;
        height: 380px;
        background: linear-gradient(135deg, #0a215e, #1e3a8a);
        box-shadow: 0 12px 25px rgba(15, 23, 42, 0.15);
    }

    .about-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.5s ease;
    }

    .about-img-container:hover img {
        transform: scale(1.03);
    }

    .about-location-badge {
        position: absolute;
        bottom: 16px;
        left: 16px;
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(8px);
        color: #ffffff;
        padding: 8px 16px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    /* Pillars of TEFA */
    .tefa-pillars-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-top: 40px;
    }

    .pillar-card {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        padding: 28px 22px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        text-align: center;
        transition: all 0.3s ease;
    }

    .pillar-card:hover {
        transform: translateY(-6px);
        border-color: #93c5fd;
        box-shadow: 0 15px 30px rgba(37, 99, 235, 0.1);
    }

    .pillar-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        margin: 0 auto 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
    }

    .pillar-card h4 {
        font-size: 1.1rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 10px;
    }

    .pillar-card p {
        font-size: 13.5px;
        color: #64748b;
        line-height: 1.6;
        margin: 0;
    }

    /* 6 Jurusan Grid & Cards */
    .jurusan-grid-6 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        margin-top: 36px;
    }

    .jurusan-card-clean {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        padding: 26px 22px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
        transition: all 0.25s ease;
        display: flex;
        flex-direction: column;
        cursor: pointer;
        text-align: left;
    }

    .jurusan-card-clean:hover {
        transform: translateY(-4px);
        border-color: #93c5fd;
        box-shadow: 0 14px 28px rgba(37, 99, 235, 0.08);
    }

    .jurusan-card-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .jurusan-icon-pill {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .jurusan-unit-badge {
        font-size: 11px;
        font-weight: 700;
        color: #475569;
        background: #f1f5f9;
        padding: 3px 10px;
        border-radius: 999px;
        letter-spacing: 0.5px;
    }

    .jurusan-card-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .jurusan-card-desc {
        font-size: 13.5px;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 16px;
        flex-grow: 1;
    }

    .jurusan-tags-row {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 18px;
    }

    .jurusan-tag-clean {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 6px;
    }

    .jurusan-action-link {
        font-size: 13px;
        font-weight: 700;
        color: #2563eb;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: gap 0.2s ease;
    }

    .jurusan-card-clean:hover .jurusan-action-link {
        gap: 10px;
        color: #1d4ed8;
    }

    /* Upgraded Pop-up Modal */
    .modal-backdrop-custom {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 99999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        padding: 20px;
    }

    .modal-backdrop-custom.active {
        opacity: 1;
        visibility: visible;
    }

    .modal-dialog-custom {
        background: #ffffff;
        width: 100%;
        max-width: 620px;
        border-radius: 24px;
        overflow: hidden;
        position: relative;
        transform: scale(0.92) translateY(20px);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
        max-height: 90vh;
        display: flex;
        flex-direction: column;
    }

    .modal-backdrop-custom.active .modal-dialog-custom {
        transform: scale(1) translateY(0);
    }

    .modal-close-btn {
        position: absolute;
        top: 16px;
        right: 16px;
        background: rgba(255, 255, 255, 0.9);
        color: #0f172a;
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        font-size: 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: all 0.2s ease;
    }

    .modal-close-btn:hover {
        background: #ffffff;
        transform: rotate(90deg);
    }

    .modal-header-banner {
        height: 180px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        flex-shrink: 0;
    }

    .modal-header-icon {
        width: 76px;
        height: 76px;
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 38px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }

    .modal-body-scrollable {
        padding: 30px;
        overflow-y: auto;
    }

    .modal-badge-custom {
        display: inline-block;
        background: #eff6ff;
        color: #2563eb;
        padding: 5px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
        border: 1px solid #bfdbfe;
    }

    .modal-title-custom {
        font-size: 1.65rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 4px;
    }

    .modal-subtitle-custom {
        font-size: 14px;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .modal-output-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px;
        margin-top: 18px;
        margin-bottom: 24px;
    }

    .modal-output-title {
        font-size: 13px;
        font-weight: 800;
        color: #0a215e;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .modal-output-list {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .modal-output-list li {
        font-size: 13px;
        color: #334155;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .modal-actions {
        display: flex;
        gap: 12px;
    }

    .modal-btn-primary {
        flex: 1;
        background: #2563eb;
        color: #ffffff;
        text-align: center;
        padding: 12px 20px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        font-size: 14px;
        transition: background 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .modal-btn-primary:hover {
        background: #1d4ed8;
    }

    .modal-btn-secondary {
        background: #f1f5f9;
        color: #475569;
        text-align: center;
        padding: 12px 20px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .modal-btn-secondary:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    /* Responsive Adjustments */
    @media (max-width: 992px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .about-card-box { grid-template-columns: 1fr; }
        .about-img-container { height: 300px; }
        .tefa-pillars-grid { grid-template-columns: repeat(2, 1fr); }
        .jurusan-grid-6 { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 640px) {
        .profil-hero h1 { font-size: 2rem; }
        .stats-grid { grid-template-columns: 1fr; gap: 12px; }
        .tefa-pillars-grid { grid-template-columns: 1fr; }
        .jurusan-grid-6 { grid-template-columns: 1fr; }
        .modal-output-list { grid-template-columns: 1fr; }
        .modal-actions { flex-direction: column; }
    }
</style>

<!-- 1. HERO HEADER SECTION -->
<div class="profil-hero">
    <div class="profil-badge-pill">
        <span>✨ PROFIL TEACHING FACTORY</span>
    </div>
    <h1>Teaching Factory SMKN 4 Tanjungpinang</h1>
    <p>
        Model pembelajaran berbasis produksi dan jasa nyata yang mengintegrasikan kurikulum sekolah kejuruan dengan standar operasional industri modern di 6 unit keahlian unggulan.
    </p>
</div>

<!-- 2. QUICK STATS BAR -->
<div class="container stats-bar-wrapper" style="padding-top: 0; padding-bottom: 40px;">
    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-icon-box" style="background: #eff6ff; color: #2563eb;">
                🏬
            </div>
            <div>
                <div class="stat-val">6</div>
                <div class="stat-lbl">Unit Produksi TEFA</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon-box" style="background: #f0fdf4; color: #16a34a;">
                🏭
            </div>
            <div>
                <div class="stat-val">100%</div>
                <div class="stat-lbl">Standar Industri</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon-box" style="background: #fffbeb; color: #d97706;">
                ⚡
            </div>
            <div>
                <div class="stat-val">TEFA</div>
                <div class="stat-lbl">Praktik Produksi Real</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon-box" style="background: #f3e8ff; color: #9333ea;">
                📦
            </div>
            <div>
                <div class="stat-val">24+</div>
                <div class="stat-lbl">Produk & Layanan Aktif</div>
            </div>
        </div>
    </div>
</div>

<!-- 3. MAIN ABOUT TEFA SECTION -->
<div class="container" style="padding-top: 20px; padding-bottom: 60px;">
    <div class="about-card-box">
        <!-- Visual Container -->
        <div class="about-img-container">
            <img src="{{ asset('asset/img/foto-sekolahmu.jpg') }}" alt="Teaching Factory SMKN 4 Tanjungpinang" 
                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=800&auto=format&fit=crop';">
            <div class="about-location-badge">
                <span>📍</span> Unit TEFA — SMKN 4 Tanjungpinang
            </div>
        </div>

        <!-- Text Container -->
        <div>
            <span class="section-tag">— TENTANG TEACHING FACTORY</span>
            <h2 class="section-heading">Pusat Inovasi & Produksi Vokasi</h2>
            <p class="section-lead" style="margin-bottom: 16px;">
                <strong>Teaching Factory (TEFA) SMKN 4 Tanjungpinang</strong> adalah sarana pembelajaran berbasis produksi barang dan penyediaan jasa nyata yang dirancang sesuai alur kerja industri profesional.
            </p>
            <p style="color: #475569; font-size: 15px; line-height: 1.7; margin-bottom: 24px;">
                Melalui ekosistem TEFA, siswa tidak hanya belajar teori di kelas, melainkan langsung menangani project riil dari masyarakat, UMKM, instansi pemerintah, dan pelaku usaha. Setiap project dikerjakan dengan bimbingan instruktur ahli untuk memastikan kualitas terbaik berstandar industri.
            </p>

            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="#jurusan-section" class="btn-blue" style="border-radius: 12px; padding: 12px 24px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px;">
                    <span>🚀</span> Jelajahi 6 Unit TEFA
                </a>
                <a href="{{ route('produk') }}" style="background: #f1f5f9; color: #334155; padding: 12px 20px; border-radius: 12px; text-decoration: none; font-weight: 700; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s;">
                    <span>🛒</span> Katalog Produk TEFA
                </a>
            </div>
        </div>
    </div>

    <!-- 4. KEUNGGULAN TEACHING FACTORY (PILARS) -->
    <div style="margin-top: 70px;">
        <div class="text-center" style="max-width: 650px; margin: 0 auto 10px;">
            <span class="section-tag">— KEUNGGULAN TEFA</span>
            <h2 class="section-heading">4 Pilar Keunggulan TEFA</h2>
            <p class="section-lead">Nilai tambah utama yang membuat produk dan layanan jasa TEFA SMKN 4 Tanjungpinang unggul dan terpercaya.</p>
        </div>

        <div class="tefa-pillars-grid">
            <div class="pillar-card">
                <div class="pillar-icon" style="background: #eff6ff; color: #2563eb;">
                    🛠️
                </div>
                <h4>Peralatan Standar Industri</h4>
                <p>Laboratorium & workshop dilengkapi perangkat komputasi, kamera, serta alat produksi industri modern.</p>
            </div>

            <div class="pillar-card">
                <div class="pillar-icon" style="background: #f0fdf4; color: #16a34a;">
                    💼
                </div>
                <h4>Pengalaman Project Real</h4>
                <p>Siswa menggarap pesanan nyata dari klien masyarakat, UMKM, instansi, dan dunia usaha.</p>
            </div>

            <div class="pillar-card">
                <div class="pillar-icon" style="background: #fffbeb; color: #d97706;">
                    👨‍🏫
                </div>
                <h4>Mentor & Guru Sertifikasi</h4>
                <p>Setiap tahapan didampingi oleh instruktur profesional berpengalaman di bidangnya.</p>
            </div>

            <div class="pillar-card">
                <div class="pillar-icon" style="background: #f3e8ff; color: #9333ea;">
                    ✨
                </div>
                <h4>Quality Assurance Presisi</h4>
                <p>Hasil pengerjaan dipastikan memenuhi spesifikasi kebutuhan pemesan dengan harga kompetitif.</p>
            </div>
        </div>
    </div>

    <!-- 5. 6 PROGRAM KEAHLIAN (JURUSAN) GRID -->
    <div id="jurusan-section" style="margin-top: 90px; scroll-margin-top: 100px;">
        <div class="text-center" style="max-width: 700px; margin: 0 auto 10px;">
            <span class="section-tag">— UNIT PRODUKSI TEFA</span>
            <h2 class="section-heading">6 Unit Produksi TEFA Unggulan</h2>
            <p class="section-lead">Setiap program keahlian memiliki unit bisnis Teaching Factory terintegrasi yang siap melayani berbagai kebutuhan solusi teknologi dan industri kreatif Anda.</p>
        </div>

        <div class="jurusan-grid-6">
            
            <!-- KARTU 1: RPL -->
            <div class="jurusan-card-clean" onclick="openModal('rpl')">
                <div class="jurusan-card-top">
                    <div class="jurusan-icon-pill" style="background: #eff6ff; color: #2563eb;">💻</div>
                    <span class="jurusan-unit-badge">TEFA RPL</span>
                </div>
                <h3 class="jurusan-card-title">Rekayasa Perangkat Lunak</h3>
                <p class="jurusan-card-desc">Pembuatan website company profile, sistem informasi manajemen, aplikasi mobile Android/iOS, dan software custom.</p>
                <div class="jurusan-tags-row">
                    <span class="jurusan-tag-clean">Web App</span>
                    <span class="jurusan-tag-clean">Mobile Dev</span>
                    <span class="jurusan-tag-clean">Database</span>
                </div>
                <div class="jurusan-action-link">
                    <span>Lihat Detail Unit TEFA</span>
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
            </div>

            <!-- KARTU 2: TKJ -->
            <div class="jurusan-card-clean" onclick="openModal('tkj')">
                <div class="jurusan-card-top">
                    <div class="jurusan-icon-pill" style="background: #f0fdf4; color: #16a34a;">🌐</div>
                    <span class="jurusan-unit-badge">TEFA TKJ</span>
                </div>
                <h3 class="jurusan-card-title">Teknik Komputer Jaringan</h3>
                <p class="jurusan-card-desc">Instalasi jaringan LAN/Fiber Optic/WiFi, perakitan komputer PC, maintenance server, dan troubleshoot IT.</p>
                <div class="jurusan-tags-row">
                    <span class="jurusan-tag-clean">Infrastruktur</span>
                    <span class="jurusan-tag-clean">Server</span>
                    <span class="jurusan-tag-clean">Repair Hardware</span>
                </div>
                <div class="jurusan-action-link">
                    <span>Lihat Detail Unit TEFA</span>
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
            </div>

            <!-- KARTU 3: DKV -->
            <div class="jurusan-card-clean" onclick="openModal('dkv')">
                <div class="jurusan-card-top">
                    <div class="jurusan-icon-pill" style="background: #fdf2f8; color: #db2777;">🎨</div>
                    <span class="jurusan-unit-badge">TEFA DKV</span>
                </div>
                <h3 class="jurusan-card-title">Desain Komunikasi Visual</h3>
                <p class="jurusan-card-desc">Jasa pembuatan logo, desain identitas branding UMKM, kemasan (packaging), poster, banner, dan barang merchandise.</p>
                <div class="jurusan-tags-row">
                    <span class="jurusan-tag-clean">Brand Logo</span>
                    <span class="jurusan-tag-clean">Packaging</span>
                    <span class="jurusan-tag-clean">Merchandise</span>
                </div>
                <div class="jurusan-action-link">
                    <span>Lihat Detail Unit TEFA</span>
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
            </div>

            <!-- KARTU 4: BROADCASTING -->
            <div class="jurusan-card-clean" onclick="openModal('bc')">
                <div class="jurusan-card-top">
                    <div class="jurusan-icon-pill" style="background: #fff7ed; color: #ea580c;">📹</div>
                    <span class="jurusan-unit-badge">TEFA BROADCASTING</span>
                </div>
                <h3 class="jurusan-card-title">Produksi Siaran Televisi</h3>
                <p class="jurusan-card-desc">Jasa videografi liputan event, pembuatan video profil perusahaan, video iklan produk, dan editing video profesional.</p>
                <div class="jurusan-tags-row">
                    <span class="jurusan-tag-clean">Videografi</span>
                    <span class="jurusan-tag-clean">Video Profile</span>
                    <span class="jurusan-tag-clean">Post Production</span>
                </div>
                <div class="jurusan-action-link">
                    <span>Lihat Detail Unit TEFA</span>
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
            </div>

            <!-- KARTU 5: ANIMASI -->
            <div class="jurusan-card-clean" onclick="openModal('animasi')">
                <div class="jurusan-card-top">
                    <div class="jurusan-icon-pill" style="background: #f5f3ff; color: #7c3aed;">🎬</div>
                    <span class="jurusan-unit-badge">TEFA ANIMASI</span>
                </div>
                <h3 class="jurusan-card-title">Animasi 2D & 3D</h3>
                <p class="jurusan-card-desc">Pembuatan aset karakter 2D/3D, motion graphic untuk media promosi, video explainer interaktif, dan animasi edukasi.</p>
                <div class="jurusan-tags-row">
                    <span class="jurusan-tag-clean">2D/3D Asset</span>
                    <span class="jurusan-tag-clean">Motion Graphic</span>
                    <span class="jurusan-tag-clean">Explainer</span>
                </div>
                <div class="jurusan-action-link">
                    <span>Lihat Detail Unit TEFA</span>
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
            </div>

            <!-- KARTU 6: GIM -->
            <div class="jurusan-card-clean" onclick="openModal('gim')">
                <div class="jurusan-card-top">
                    <div class="jurusan-icon-pill" style="background: #f0f9ff; color: #0284c7;">🎮</div>
                    <span class="jurusan-unit-badge">TEFA GIM</span>
                </div>
                <h3 class="jurusan-card-title">Pengembangan Gim</h3>
                <p class="jurusan-card-desc">Desain karakter game, environment 3D/2D, perancangan game edukasi interaktif, dan game berbasis Unity/Unreal Engine.</p>
                <div class="jurusan-tags-row">
                    <span class="jurusan-tag-clean">Game Design</span>
                    <span class="jurusan-tag-clean">Interactive Media</span>
                    <span class="jurusan-tag-clean">Asset 3D</span>
                </div>
                <div class="jurusan-action-link">
                    <span>Lihat Detail Unit TEFA</span>
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
            </div>

        </div>
    </div>

    <!-- 6. AJAKAN KERJASAMA / KONSULTASI TEFA (TASTEFUL) -->
    <div style="margin-top: 80px; background: linear-gradient(135deg, #0a215e 0%, #1e3a8a 100%); border-radius: 24px; padding: 45px 35px; color: #ffffff; text-align: center;">
        <span class="section-tag" style="color: #ffb703; background: rgba(255, 183, 3, 0.15); padding: 4px 14px; border-radius: 999px; font-size: 12px; border: 1px solid rgba(255, 183, 3, 0.3);">✨ KEMITRAAN & KONSULTASI</span>
        <h2 style="font-size: 1.9rem; font-weight: 800; color: #ffffff; margin: 12px 0 10px;">Siap Bekerjasama dengan Unit TEFA Kami?</h2>
        <p style="color: #cbd5e1; font-size: 14.5px; max-width: 620px; margin: 0 auto 25px; line-height: 1.6;">
            Dapatkan hasil pengerjaan berkualitas standar industri dengan harga yang kompetitif untuk kebutuhan bisnis, instansi, atau UMKM Anda.
        </p>
        <div style="display: flex; justify-content: center; gap: 14px; flex-wrap: wrap;">
            <a href="{{ route('jasa') }}" class="btn btn-primary" style="padding: 12px 26px; font-size: 14px; font-weight: 700; border-radius: 999px; text-decoration: none;">
                Lihat Layanan Jasa
            </a>
            <a href="{{ route('produk') }}" class="btn btn-outline" style="padding: 12px 26px; font-size: 14px; font-weight: 700; border-radius: 999px; text-decoration: none;">
                Jelajahi Produk
            </a>
        </div>
    </div>
</div>

<!-- 6. UPGRADED POP-UP MODAL OVERLAY -->
<div class="modal-backdrop-custom" id="detailModalCustom">
    <div class="modal-dialog-custom">
        <button class="modal-close-btn" onclick="closeModalCustom()" title="Tutup Modal">&times;</button>

        <div class="modal-header-banner" id="modalHeaderBg">
            <div class="modal-header-icon" id="modalHeaderIcon">
                💻
            </div>
        </div>

        <div class="modal-body-scrollable">
            <span class="modal-badge-custom" id="modalBadge">TEFA RPL</span>
            <h3 class="modal-title-custom" id="modalTitle">Rekayasa Perangkat Lunak</h3>
            <div class="modal-subtitle-custom" id="modalSubtitle">Unit Produksi & Pengembangan Perangkat Lunak</div>
            
            <div id="modalDesc" style="color: #475569; font-size: 14.5px; line-height: 1.7;"></div>

            <div class="modal-output-box">
                <div class="modal-output-title">
                    <span>✨ Layanan & Produk Utama TEFA</span>
                </div>
                <ul class="modal-output-list" id="modalOutputList">
                    <!-- Populated via Javascript -->
                </ul>
            </div>

            <div class="modal-actions">
                <a href="{{ route('jasa') }}" class="modal-btn-primary" id="modalBtnJasa">
                    <span>Pesan Layanan Jasa</span>
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <a href="{{ route('produk') }}" class="modal-btn-secondary" id="modalBtnProduk">
                    Katalog Produk
                </a>
            </div>
        </div>
    </div>
</div>

<!-- JAVASCRIPT LOGIC FOR MODAL -->
<script>
    const dataJurusanModal = {
        'rpl': {
            badge: 'TEFA RPL',
            title: 'Rekayasa Perangkat Lunak',
            subtitle: 'Software Development & Web Systems',
            icon: '💻',
            gradient: 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%)',
            desc: '<p>Unit Teaching Factory RPL fokus pada perancangan dan pengembangan perangkat lunak sesuai kebutuhan pasar. Dikerjakan langsung oleh siswa berprestasi di bawah bimbingan programmer profesional, kami siap membantu digitalisasi bisnis Anda.</p>',
            outputs: [
                '🌐 Website Company Profile',
                '📱 Aplikasi Mobile Android/iOS',
                '📊 Sistem Informasi Manajemen',
                '🛒 Toko Online / E-Commerce'
            ]
        },
        'tkj': {
            badge: 'TEFA TKJ',
            title: 'Teknik Komputer & Jaringan',
            subtitle: 'Network Infrastructure & Hardware Repair',
            icon: '🌐',
            gradient: 'linear-gradient(135deg, #065f46 0%, #10b981 100%)',
            desc: '<p>TEFA TKJ memberikan solusi infrastruktur teknologi informasi secara menyeluruh. Mulai dari penarikan kabel LAN/Fiber Optic, konfigurasi Router/Mikrotik, setup server, hingga perakitan dan perawatan PC kantor/sekolah.</p>',
            outputs: [
                '🔌 Instalasi LAN & WiFi',
                '🖥️ Perakitan & Repair PC',
                '🖥️ Maintenance Server & Cloud',
                '🔒 Setup Firewall & Network'
            ]
        },
        'dkv': {
            badge: 'TEFA DKV',
            title: 'Desain Komunikasi Visual',
            subtitle: 'Creative Design & Visual Branding',
            icon: '🎨',
            gradient: 'linear-gradient(135deg, #9d174d 0%, #ec4899 100%)',
            desc: '<p>Pusat kreativitas desain visual! TEFA DKV melayani perancangan identitas merek, desain grafis promosi, packaging produk UMKM, hingga cetak merchandise yang meningkatkan nilai jual bisnis Anda.</p>',
            outputs: [
                '🎨 Desain Logo & Brand Identity',
                '📦 Kemasan Produk (Packaging)',
                '🖼️ Poster, Banner & Spanduk',
                '☕ Custom Merchandise & Mug'
            ]
        },
        'bc': {
            badge: 'TEFA BROADCASTING',
            title: 'Produksi Program Siaran TV',
            subtitle: 'Cinematography & Event Coverage',
            icon: '📹',
            gradient: 'linear-gradient(135deg, #9a3412 0%, #f97316 100%)',
            desc: '<p>Layanan dokumentasi dan produksi video profesional. Dilengkapi dengan kamera standar siaran TV dan tim kreatif yang siap menangkap momen penting acara Anda secara cinematik.</p>',
            outputs: [
                '📹 Video Profile Perusahaan',
                '🎬 Dokumentasi Liputan Event',
                '📺 Video Iklan Komersial',
                '✂️ Jasa Editing Video & Coloring'
            ]
        },
        'animasi': {
            badge: 'TEFA ANIMASI',
            title: 'Animasi 2D & 3D',
            subtitle: 'Digital Animation & Motion Graphics',
            icon: '🎬',
            gradient: 'linear-gradient(135deg, #5b21b6 0%, #8b5cf6 100%)',
            desc: '<p>Mengubah ide dan cerita menjadi karya visual bergerak yang menarik. Kami memproduksi aset animasi 2D/3D, motion graphics untuk presentasi, hingga video penjelasan (explainer video) edukatif.</p>',
            outputs: [
                '✨ Motion Graphics Promosi',
                '💡 Video Explainer Edukatif',
                '🧸 Desain Aset Karakter 3D/2D',
                '📽️ Short Animation Project'
            ]
        },
        'gim': {
            badge: 'TEFA GIM',
            title: 'Pengembangan Gim',
            subtitle: 'Interactive Media & Game Development',
            icon: '🎮',
            gradient: 'linear-gradient(135deg, #075985 0%, #0284c7 100%)',
            desc: '<p>Unit TEFA Game Development berfokus pada rancang bangun media interaktif dan game edukasi/hiburan. Menggunakan game engine populer seperti Unity dan Unreal Engine.</p>',
            outputs: [
                '🎮 Game Edukasi Interaktif',
                '🕹️ Asset Character & Environment',
                '📱 Game Mobile 2D/3D',
                '🕶️ VR/AR Interactive App'
            ]
        }
    };

    const modalBackdrop = document.getElementById('detailModalCustom');

    function openModal(id) {
        const item = dataJurusanModal[id];
        if (!item) return;

        document.getElementById('modalBadge').textContent = item.badge;
        document.getElementById('modalTitle').textContent = item.title;
        document.getElementById('modalSubtitle').textContent = item.subtitle;
        document.getElementById('modalDesc').innerHTML = item.desc;
        document.getElementById('modalHeaderIcon').textContent = item.icon;
        document.getElementById('modalHeaderBg').style.background = item.gradient;

        // Render Output List
        const listEl = document.getElementById('modalOutputList');
        listEl.innerHTML = '';
        item.outputs.forEach(out => {
            const li = document.createElement('li');
            li.textContent = out;
            listEl.appendChild(li);
        });

        modalBackdrop.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModalCustom() {
        modalBackdrop.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    window.addEventListener('click', function(e) {
        if (e.target === modalBackdrop) {
            closeModalCustom();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modalBackdrop.classList.contains('active')) {
            closeModalCustom();
        }
    });
</script>
@endsection