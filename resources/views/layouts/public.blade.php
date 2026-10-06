<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Katalog TEFA SMKN 4 Tanjungpinang')</title>
    
    <!-- Fonts dari desain homepage (Plus Jakarta Sans untuk teks, Anton untuk label kartu program) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Anton&display=swap" rel="stylesheet">

    {{-- Open Sauce One (judul hero). Tidak tersedia di Google Fonts — diambil dari
         Fontsource CDN (npm @fontsource/open-sauce-one, lisensi OFL-1.1).
         Hanya dipakai di .tefa-hero h1, bukan global. --}}
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-one@5.3.0/400.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-one@5.3.0/600.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-one@5.3.0/700.css" rel="stylesheet">

    {{-- Tailwind + Alpine. Harus sebelum <style> di bawah: Tailwind v3 tidak memakai CSS
         native layer (outputnya CSS biasa), jadi layout sengaja TIDAK dibungkus @layer —
         spesifisitas dan urutan dokumen yang menentukan: preflight (selector element)
         kalah dari gaya layout (selector class), sementara utility Tailwind (class) tetap
         mengalahkan reset * milik layout. --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
        .btn-primary { background-color: #ffb703; color: #021235; border: none; cursor: pointer; }
        .btn-primary:hover { background-color: #fb8500; transform: translateY(-2px); }
        .btn-outline { background-color: transparent; color: #fff; border: 2px solid rgba(255, 255, 255, 0.6); }
        .btn-outline:hover { background-color: #fff; color: #0a215e; transform: translateY(-2px); }
        .btn-blue { background-color: #2563eb; color: white; display: inline-block; padding: 10px 20px; border-radius: 20px; text-decoration: none; border: none; cursor: pointer;}
        .btn-blue:hover { background-color: #1d4ed8; transform: translateY(-2px); }

        /* --- KONTEN BAWAH (BERANDA LENGKAP) --- */
        .container { max-width: 1200px; margin: 0 auto; padding: 80px 20px; }
        .container-sm { max-width: 900px; margin: 0 auto; padding: 80px 20px; }
        .text-center { text-align: center; }
        .section-label { color: #ffb703; font-weight: 700; font-size: 14px; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 10px; display: inline-block; }
        h2.section-title { font-size: 36px; color: #0a215e; margin-bottom: 20px; font-weight: 800; }
        p.section-subtitle { color: #64748b; font-size: 16px; margin-bottom: 40px; }

        /* Tentang Sekolah */
        .about-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; align-items: center; }
        .about-img-wrapper img { width: 100%; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); object-fit: cover; }
        
        /* Profil Layout */
        .profil-content { font-size: 16px; line-height: 1.8; color: #475569; margin-bottom: 40px; }
        .jurusan-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 40px; }
        .jurusan-card { background: #fff; border-radius: 15px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; transition: 0.3s; border-top: 4px solid #2563eb; }
        .jurusan-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .jurusan-icon { font-size: 40px; margin-bottom: 15px; color: #0a215e; }
        .jurusan-card h3 { font-size: 18px; color: #0f172a; margin-bottom: 10px; }

        /* Produk & Jasa Grid */
        .produk-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 25px; }
        .produk-card { background: #fff; border-radius: 15px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: 0.3s; display: flex; flex-direction: column; text-decoration: none; }
        .produk-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .produk-img { width: 100%; height: 200px; object-fit: cover; background: #eee; }
        .produk-info { padding: 20px; display: flex; flex-direction: column; flex-grow: 1; }
        .produk-info h3 { font-size: 18px; color: #0f172a; margin-bottom: 5px; }
        .produk-info p { font-size: 14px; color: #64748b; margin-bottom: 15px; flex-grow: 1; }
        .produk-price { font-weight: bold; color: #2563eb; font-size: 18px; margin-bottom: 15px; }

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
        .help-buttons { display: flex; gap: 20px; margin-top: 30px; justify-content: center;}
        .help-card { background: rgba(255,255,255,0.1); padding: 20px 30px; border-radius: 15px; display: flex; align-items: center; gap: 15px; cursor: pointer; transition: 0.3s; border: 1px solid rgba(255,255,255,0.1); text-decoration: none; }
        .help-card:hover { background: rgba(255,255,255,0.2); }

        /* Footer -> resources/views/partials/footer.blade.php (scoping & token milik partial) */

        /* Page Header Content */
        .page-header {
            background-color: #0a215e;
            color: white;
            padding: 60px 20px;
            text-align: center;
        }
        .page-header h1 { font-size: 40px; font-weight: 800; margin-bottom: 10px; }
        .page-header p { font-size: 18px; color: #cbd5e1; }

        /* --- PAGINATION (ditulis global untuk view bersama vendor/pagination/custom;
             hanya muncul di halaman yang memang paginate — katalog produk & layanan) --- */
        .pagination-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 40px;
            padding: 6px 0;
            width: 100%;
            font-size: 13px;
            color: #64748b;
        }

        .pagination-info {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12.5px;
            color: #64748b;
        }

        .pagination-info strong {
            color: #0f172a;
            font-weight: 700;
        }

        .pagination-links {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .page-numbers {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .page-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            background: #ffffff;
            border: 1px solid #e6eaef;
            border-radius: 8px;
            color: #334155;
            font-size: 12.5px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: all .15s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
            user-select: none;
        }

        .page-btn:hover:not(.disabled) {
            background: #f8fafd;
            border-color: #0a4aa6;
            color: #0a4aa6;
            transform: translateY(-1px);
        }

        .page-btn.disabled {
            background: #f8fafc;
            color: #94a3b8;
            border-color: #e2e8f0;
            cursor: not-allowed;
            opacity: 0.65;
            box-shadow: none;
        }

        .page-num {
            min-width: 34px;
            height: 34px;
            padding: 0 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #475569;
            text-decoration: none;
            border: 1px solid #e6eaef;
            background: #fff;
            transition: all .15s ease;
            user-select: none;
        }

        .page-num:hover:not(.active):not(.dots) {
            border-color: #0a4aa6;
            color: #0a4aa6;
            background: #eff6ff;
        }

        .page-num.active {
            background: #0a4aa6;
            border-color: #0a4aa6;
            color: #fff;
            box-shadow: 0 2px 6px rgba(10, 74, 166, 0.3);
        }

        .page-num.dots {
            border: none;
            background: transparent;
            color: #94a3b8;
            cursor: default;
        }
    </style>
</head>
<body>

    @include('partials.navbar')


    @yield('content')

    <!-- FOOTER (partial bersama untuk semua halaman publik) -->
    @include('partials.footer')

    <!-- Toast Notification (Smart Redirect Feedback) -->
    @if(session('toast_success'))
        <div id="smart-toast-public" 
             style="position: fixed; top: 24px; right: 24px; z-index: 99999; display: flex; align-items: center; gap: 12px; background: #0f172a; color: #ffffff; padding: 14px 20px; border-radius: 12px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.2); border-left: 5px solid #10b981; animation: toastSlideInPub 0.35s cubic-bezier(0.16, 1, 0.3, 1); max-width: 420px;">
            <div style="width: 28px; height: 28px; border-radius: 50%; background: #10b981; display: grid; place-items: center; color: #fff; font-weight: 800; font-size: 14px; flex-shrink: 0;">
                ✓
            </div>
            <div style="flex: 1; font-size: 13.5px; font-weight: 600; line-height: 1.4;">
                {{ session('toast_success') }}
            </div>
            <button type="button" onclick="dismissPublicToast()" style="background: none; border: none; color: #94a3b8; font-size: 20px; cursor: pointer; line-height: 1; padding: 0 4px;" aria-label="Tutup">
                &times;
            </button>
        </div>

        <style>
        @keyframes toastSlideInPub {
            from { transform: translateX(110%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes toastSlideOutPub {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(110%); opacity: 0; }
        }
        </style>

        <script>
        function dismissPublicToast() {
            const toast = document.getElementById('smart-toast-public');
            if (toast) {
                toast.style.animation = 'toastSlideOutPub 0.3s forwards';
                setTimeout(() => toast.remove(), 300);
            }
        }
        setTimeout(dismissPublicToast, 3500);
        </script>
    @endif

</body>
</html> 