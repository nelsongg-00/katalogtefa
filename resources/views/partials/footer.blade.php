{{-- Footer publik (wave + 4 kolom + copyright). Dipakai oleh layouts/public.blade.php,
     sehingga satu include ini menutupi semua halaman publik (beranda, produk, jasa,
     portofolio, profil, tracking, checkout, pesanan saya, edit profil).
     Sengaja berdiri sendiri: font + CSS ikut di dalam partial agar tampil & berperilaku
     identik walau halaman pemanggil memakai font lain (konvensi yang sama dengan
     partials/navbar.blade.php). Token ditaruh di .site-footer, BUKAN :root, supaya tidak
     menimpa token global halaman (mis. --color-yellow yang dipakai outline :focus-visible
     di halaman produk/jasa/tracking/profil). --}}
<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
<link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/400.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/500.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/600.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/700.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/800.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/900.css" rel="stylesheet">

<footer class="site-footer">
    <style>
        /* ==========================================================
           1. DESIGN TOKENS (diukur dari tangkapan layar)
           ========================================================== */
        .site-footer {
            --color-footer: #0b5ed7;
            --color-yellow: #fcbb2e;
            --color-white: #ffffff;
            --color-icon-red: #e53935;

            --font-body: "Open Sauce Sans", "Hanken Grotesk", Arial, "Helvetica Neue", sans-serif;
            --fs-sm: 0.75rem;       /* 12px: teks footer */
            --fs-title: 1.375rem;   /* 22px: judul kuning */

            --wave-height: clamp(40px, 7vw, 85px);
            --footer-width: 1003px;
        }

        /* ==========================================================
           2. DASAR (di-scope ke footer; layout punya reset sendiri)
           ========================================================== */
        .site-footer,
        .site-footer * {
            font-family: var(--font-body);
        }

        .site-footer {
            background: transparent;
            color: var(--color-white);
            font-size: var(--fs-sm);
            font-weight: 600;
        }

        .site-footer a { color: inherit; text-decoration: none; }
        .site-footer ul { margin: 0; padding: 0; list-style: none; }
        .site-footer h2,
        .site-footer p { margin: 0; }
        .site-footer :focus-visible { outline: 3px solid var(--color-yellow); outline-offset: 2px; }

        /* ==========================================================
           3. FOOTER: GELOMBANG + ISI
           ========================================================== */
        .site-footer .footer__wave {
            display: block;
            width: 100%;
            height: var(--wave-height);
            fill: var(--color-footer);
        }

        .site-footer .footer__body {
            margin-top: -1px;
            padding: 40px 24px 35px;
            background: var(--color-footer);
        }

        .site-footer .footer__grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 32px;
            max-width: var(--footer-width);
            margin: 0 auto;
        }

        /* Kolom 1: tentang */
        .site-footer .footer__brand-name {
            margin-bottom: 12px;
            font-size: var(--fs-title);
            font-weight: 800;
            line-height: 1.2;
            color: var(--color-yellow);
        }
        .site-footer .footer__about p:not(.footer__brand-name) { max-width: 13.5rem; line-height: 1.55; }
        .site-footer .footer__social { display: flex; gap: 15px; margin-top: 28px; }
        .site-footer .footer__social a { display: block; }
        .site-footer .footer__social svg { display: block; width: 30px; height: 30px; }

        /* Kolom 2-4: daftar tautan */
        .site-footer .footer__heading {
            margin-bottom: 14px;
            font-size: var(--fs-sm);
            font-weight: 700;
            line-height: 1.25;
        }
        .site-footer .footer__list { display: grid; gap: 11px; line-height: 1.25; }
        .site-footer .footer__contact-item { display: flex; align-items: flex-start; gap: 6px; line-height: 1.3; }
        .site-footer .footer__contact-item svg {
            flex: none;
            width: 10px;
            height: 10px;
            margin-top: 3px;
            fill: var(--color-icon-red);
        }
        .site-footer .footer__contact-list { gap: 12px; }

        /* Hak cipta */
        .site-footer .footer__copy {
            margin-top: 56px;
            text-align: center;
            color: var(--color-yellow);
        }

        /* ==========================================================
           4. BREAKPOINT >= 640px (tablet)
           ========================================================== */
        @media (min-width: 640px) {
            .site-footer .footer__grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 40px 32px;
            }
        }

        /* ==========================================================
           5. BREAKPOINT >= 1024px (desktop)
           ========================================================== */
        @media (min-width: 1024px) {
            .site-footer .footer__grid {
                grid-template-columns: 231fr 134fr 181fr 114fr;
                gap: 0;
            }
        }
    </style>

    <!-- Tepi atas bergelombang (bentuk gelombang adalah perkiraan dari tangkapan layar) -->
    <svg class="footer__wave" viewBox="0 0 1440 100" preserveAspectRatio="none" aria-hidden="true">
        <path d="M0,30 C60,8 140,8 230,30 C330,58 400,96 520,92 C700,86 800,60 960,30 C1090,6 1200,2 1300,12 C1370,20 1410,38 1440,55 L1440,100 L0,100 Z"/>
    </svg>

    <div class="footer__body">
        <div class="footer__grid">
            <div class="footer__about">
                <p class="footer__brand-name">SMKN 4 TANJUNGPINANG</p>
                <p>Etalase digital resmi yang menghubungkan karya nyata, inovasi, dan layanan jasa dari talenta vokasi SMKN 4 Tanjungpinang dengan kebutuhan industri masa kini.</p>
                <div class="footer__social">
                    <a href="https://www.tiktok.com/@smkn4tanjungpinang?is_from_webapp=1&amp;sender_device=pc" target="_blank" rel="noopener noreferrer" aria-label="TikTok">
                        <svg viewBox="0 0 32 32" aria-hidden="true">
                            <circle cx="16" cy="16" r="16" fill="#000000"/>
                            <path d="M17.500 8v10.300a2.600 2.600 0 1 1-2.600-2.600" fill="none" stroke="#25f4ee" stroke-width="2.200" stroke-linecap="round" transform="translate(-0.800 0.600)"/>
                            <path d="M17.500 8v10.300a2.600 2.600 0 1 1-2.600-2.600" fill="none" stroke="#fe2c55" stroke-width="2.200" stroke-linecap="round" transform="translate(0.800 -0.400)"/>
                            <path d="M17.500 8v10.300a2.600 2.600 0 1 1-2.600-2.600M17.500 8c.4 2.200 1.800 3.600 4 3.800" fill="none" stroke="#ffffff" stroke-width="2.200" stroke-linecap="round"/>
                        </svg>
                    </a>
                    <a href="https://www.instagram.com/smkn4tgpinang/" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                        <svg viewBox="0 0 32 32" aria-hidden="true">
                            <defs>
                                <linearGradient id="footer-ig" x1="0" y1="1" x2="1" y2="0">
                                    <stop offset="0" stop-color="#feda75"/>
                                    <stop offset="0.35" stop-color="#fa7e1e"/>
                                    <stop offset="0.65" stop-color="#d62976"/>
                                    <stop offset="1" stop-color="#4f5bd5"/>
                                </linearGradient>
                            </defs>
                            <rect width="32" height="32" rx="9" fill="url(#footer-ig)"/>
                            <rect x="8" y="8" width="16" height="16" rx="5" fill="none" stroke="#ffffff" stroke-width="2"/>
                            <circle cx="16" cy="16" r="3.800" fill="none" stroke="#ffffff" stroke-width="2"/>
                            <circle cx="21" cy="11" r="1.200" fill="#ffffff"/>
                        </svg>
                    </a>
                </div>
            </div>

            <nav aria-label="Navigasi footer">
                <h2 class="footer__heading">Navigasi</h2>
                <ul class="footer__list">
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li><a href="{{ route('profil') }}">Tentang Kami</a></li>
                    <li><a href="{{ route('produk') }}">Produk</a></li>
                    <li><a href="{{ route('jasa') }}">Layanan Jasa</a></li>
                    <li><a href="{{ route('portofolio') }}">Portofolio</a></li>
                    <li><a href="{{ route('order.tracking.index') }}">Status Pesanan</a></li>
                </ul>
            </nav>

            <nav aria-label="Program keahlian">
                <h2 class="footer__heading">Program Keahlian</h2>
                <ul class="footer__list">
                    <li><a href="{{ route('produk') }}">Pengembangan GIM</a></li>
                    <li><a href="{{ route('produk') }}">Animasi</a></li>
                    <li><a href="{{ route('produk') }}">Teknik Komputer dan Jaringan</a></li>
                    <li><a href="{{ route('produk') }}">Rekayasa Perangkat Lunak</a></li>
                    <li><a href="{{ route('produk') }}">Desain Komunikasi dan Visual</a></li>
                    <li><a href="{{ route('produk') }}">Produksi dan Siaran Program Televisi</a></li>
                </ul>
            </nav>

            <div>
                <h2 class="footer__heading">Hubungi Kami</h2>
                <ul class="footer__list footer__contact-list">
                    <li class="footer__contact-item">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a7 7 0 0 0-7 7c0 5 7 13 7 13s7-8 7-13a7 7 0 0 0-7-7Zm0 9.500A2.500 2.500 0 1 1 12 6.500a2.500 2.500 0 0 1 0 5Z"/></svg>
                        <span>Jl. Nusantara No.KM.14 Batu IX, Kec. Tanjungpinang</span>
                    </li>
                    <li class="footer__contact-item">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.600 10.800a15 15 0 0 0 6.600 6.600l2.200-2.200a1 1 0 0 1 1-.25c1.100.37 2.300.57 3.600.57a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C10.600 21 3 13.400 3 4a1 1 0 0 1 1-1h3.500a1 1 0 0 1 1 1c0 1.300.2 2.500.57 3.600a1 1 0 0 1-.25 1Z"/></svg>
                        <a href="tel:+628781948317">+62 878-1948-317</a>
                    </li>
                    <li class="footer__contact-item">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="2" y="5" width="20" height="14" rx="2"/></svg>
                        <a href="mailto:smkntpi4@gmail.com">smkntpi4@gmail.com</a>
                    </li>
                </ul>
            </div>
        </div>

        <p class="footer__copy">&copy; {{ date('Y') }} SMKN 4 Tanjungpinang Katalog TEFA. Semua hak cipta dilindungi.</p>
    </div>
</footer>
