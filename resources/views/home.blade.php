@extends('layouts.public')

@section('title', 'Katalog TEFA SMKN 4 Tanjungpinang')

@section('content')
    {{-- CSS halaman ini di-scope (Tailwind v3 tanpa CSS layer — spesifisitas yang menentukan). --}}
    <style>
        /* Scroll reveal: hanya aktif jika JavaScript berjalan (gerbang .js). */
        .js [data-scroll-reveal] {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .js [data-scroll-reveal].is-visible {
            opacity: 1;
            transform: none;
        }

        /* Stagger: hanya anak berulang di dalam section (grid nilai, kartu slider).
            --stagger-index diisi script di bawah, jarak 80ms per sibling. */
        .js [data-stagger-item] {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
            transition-delay: calc(var(--stagger-index, 0) * 80ms);
        }

        .js [data-stagger-item].is-visible {
            opacity: 1;
            transform: none;
        }

        /* ================= HERO (desain baru) =================
           Token hero di-scope ke .tefa-hero (bukan :root) supaya tidak bocor ke halaman lain.
           Gradient 5 stop sama persis dengan desain: #6c9ddd -> #0b60cf. */
        .tefa-hero {
            --color-hero-top: #6c9ddd;
            --color-hero-bottom: #0b60cf;
            /* Tinggi gelombang di dasar hero (fraksi tinggi hero). Dinaikkan ~50%
               (39% -> 58%); SVG memakai preserveAspectRatio="none" jadi ikut meregang. */
            --hero-wave-height: 58%;

            position: relative;
            min-height: 600px;
            overflow: hidden;
            isolation: isolate;
            background: linear-gradient(
                to bottom,
                var(--color-hero-top) 0%,
                #5c91da 18%,
                #3c79d5 42%,
                #1c6bd2 68%,
                var(--color-hero-bottom) 100%
            );
        }

        /* Siluet gelombang di dasar hero; mengisi ruang ke warna surface (#fafafa). */
        .tefa-hero__wave {
            position: absolute;
            inset: auto 0 0 0;
            width: 100%;
            height: var(--hero-wave-height);
            z-index: 1;
            pointer-events: none;
        }

        .tefa-hero__wave svg {
            display: block;
            width: 100%;
            height: 100%;
        }

        .tefa-hero__content {
            position: relative;
            z-index: 2;
            width: auto;
            max-width: 815px;
            margin: 0 0 0 8%;
            padding-top: 66px;
        }

        .tefa-hero h1 {
            margin: 0;
            width: 430px;
            max-width: 100%;
            color: #ffffff;
            font-family: 'Open Sauce One', 'Plus Jakarta Sans', sans-serif;
            font-size: 40px;
            line-height: 0.96;
            font-weight: 700;
            letter-spacing: -1.05px;
            text-transform: uppercase;
        }

        .tefa-hero__actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 25px;
        }

        /* Ukuran tombol diadaptasi dari mockup (10px/26px terlalu kecil) agar tetap
           nyaman disentuh, sambil mempertahankan bentuk pil dan warna desain. */
        .tefa-hero__btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 34px;
            padding: 0 16px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
            line-height: 1;
            text-decoration: none;
            white-space: nowrap;
        }

        .tefa-hero__btn--primary {
            background: #ffffff;
            color: #111111;
        }

        .tefa-hero__btn--outline {
            color: #ffffff;
            border: 1px solid #ffffff;
            background: transparent;
        }

        /* Tablet: judul sedikit lebih kecil dari desktop. */
        @media (min-width: 601px) and (max-width: 1023px) {
            .tefa-hero h1 {
                font-size: 34px;
            }
        }

        @media (max-width: 600px) {
            .tefa-hero {
                min-height: 100vh;
                --hero-wave-height: 50%;
            }

            .tefa-hero__content {
                width: calc(100% - 44px);
                margin: 0 auto;
                padding-top: 68px;
            }

            .tefa-hero h1 {
                font-size: clamp(26px, 7vw, 30px);
            }
        }

        /* Fokus keyboard mengikuti aksen kuning dari desain. */
        main a:focus-visible,
        main button:focus-visible {
            outline: 3px solid #f2b630;
            outline-offset: 2px;
        }

        @media (prefers-reduced-motion: reduce) {
            .js [data-scroll-reveal],
            .js [data-stagger-item] {
                opacity: 1;
                transform: none;
                transition: none;
                transition-delay: 0s;
            }
        }
    </style>

    <main class="bg-surface font-body">
        {{-- ================= HERO =================
             Tidak diberi data-scroll-reveal: hero ada di atas fold dan harus tampil langsung. --}}
        <section class="tefa-hero" aria-label="Hero">
            <div class="tefa-hero__content">
                <h1>
                    SELAMAT DATANG DI<br>
                    KATALOG TEFA SMKN 4<br>
                    TANJUNGPINANG
                </h1>

                <div class="tefa-hero__actions">
                    <a href="{{ route('produk') }}" class="tefa-hero__btn tefa-hero__btn--primary">Jelajahi Produk</a>
                    <a href="{{ route('jasa') }}" class="tefa-hero__btn tefa-hero__btn--outline">Lihat Layanan Jasa</a>
                </div>
            </div>

            <div class="tefa-hero__wave" aria-hidden="true">
                <svg viewBox="0 0 815 145" preserveAspectRatio="none">
                    <path
                        fill="#fafafa"
                        d="
                            M 0 75
                            C 28 89, 58 93, 91 92
                            C 143 90, 193 75, 235 54
                            C 285 29, 336 19, 376 19
                            C 416 19, 472 28, 516 51
                            C 579 84, 652 82, 712 72
                            C 754 65, 786 54, 815 30
                            L 815 145
                            L 0 145
                            Z
                        "
                    />
                </svg>
            </div>
        </section>

        {{-- ================= ABOUT ================= --}}
        <section class="py-20" data-scroll-reveal>
            <div class="mx-auto grid w-full max-w-shell items-center gap-8 px-4 sm:px-8 lg:grid-cols-2 lg:gap-20">
                <div>
                    <h2 class="text-lg font-extrabold">Kreatif Inovatif Bersama</h2>
                    <p class="mb-3 text-base font-extrabold text-brand-blue">TEFA SMKN 4 TANJUNGPINANG</p>
                    <p class="text-sm text-ink-muted lg:max-w-[26rem]">
                        Teaching Factory (TEFA) SMKN 4 Tanjungpinang adalah ekosistem pembelajaran berbasis produksi
                        nyata dengan standar industri. Kami menghadirkan produk unggulan dan layanan profesional yang
                        siap menjawab kebutuhan masyarakat serta dunia usaha.
                    </p>
                </div>
                <img class="aspect-[7/5] w-full rounded-card bg-[#ddd] object-cover"
                     src="{{ asset('asset/img/about-school.jpg') }}"
                     alt="Siswa SMKN 4 Tanjungpinang berkumpul di halaman sekolah">
                <!-- TODO: add image -->
            </div>
        </section>

        {{-- ================= WHY TEFA ================= --}}
        <section class="py-20 text-center" data-scroll-reveal>
            <div class="mx-auto w-full max-w-shell px-4 sm:px-8">
                <h2 class="mb-4 text-[clamp(1.5rem,3vw,2rem)] font-bold">
                    <span class="text-brand-blue">Mengapa</span> TEFA SMKN 4 Tanjungpinang?
                </h2>
                <p class="mx-auto max-w-[44rem] font-medium">
                    Hadir untuk mendukung kebutuhan masyarakat dan dunia usaha dengan produk bernilai jual tinggi serta
                    layanan jasa profesional yang inovatif, andal, dan berstandar industri modern.
                </p>
            </div>
        </section>

        {{-- ================= PROGRAM KEAHLIAN (SLIDER) ================= --}}
        <section class="py-20" data-scroll-reveal aria-labelledby="programs-title">
            <div class="mx-auto w-full max-w-shell px-4 sm:px-8">
                <h2 id="programs-title" class="mb-8 text-center text-[clamp(1.5rem,3vw,2rem)] font-bold">
                    Program Keahlian TEFA SMKN 4 Tanjungpinang
                </h2>

                @include('partials.home.slider')
            </div>
        </section>

        {{-- ================= SIAP MENJADI MITRA ================= --}}
        <section class="py-20" data-scroll-reveal>
            <div class="mx-auto w-full max-w-shell px-4 sm:px-8">
                <h2 class="mb-8 text-center text-[clamp(1.5rem,3vw,2rem)] font-extrabold">
                    TEFA SMKN 4 TANJUNGPINANG <span class="block text-brand-blue">SIAP MENJADI MITRA YANG</span>
                </h2>

                <ul class="mx-auto grid max-w-[760px] gap-6 sm:grid-cols-2 lg:grid-cols-4" data-stagger-group>
                    @include('partials.home.value-card', [
                        'icon' => 'icon-kreatif.png',
                        'title' => 'Kreatif',
                        'tag' => 'Ide dari siswa',
                        'tagClass' => 'text-brand-blue',
                        'text' => 'Karya dibuat berdasarkan kreativitas dan inovasi siswa sendiri.',
                    ])
                    @include('partials.home.value-card', [
                        'icon' => 'icon-kompeten.png',
                        'title' => 'Kompeten',
                        'tag' => 'Proses terarah',
                        'tagClass' => 'text-brand-orange',
                        'text' => 'Dikerjakan melalui proses pembelajaran kejuruan yang terstruktur.',
                    ])
                    @include('partials.home.value-card', [
                        'icon' => 'icon-kolaboratif.png',
                        'title' => 'Kolaboratif',
                        'tag' => 'Lintas bidang',
                        'tagClass' => 'text-brand-green',
                        'text' => 'Melibatkan berbagai bidang keahlian dalam satu proses produksi.',
                    ])
                    @include('partials.home.value-card', [
                        'icon' => 'icon-siap-industri.png',
                        'title' => 'Siap Industri',
                        'tag' => 'Standar kerja',
                        'tagClass' => 'text-brand-purple',
                        'text' => 'Mengenalkan siswa pada proses kerja yang mendekati dunia industri.',
                    ])
                </ul>
            </div>
        </section>
    </main>

    <script>
        /* ===== Slider Program Keahlian (komponen Alpine) =====
         * 1 kartu (mobile) / 2 (tablet) / 3 (desktop) per tampilan, langkah satu kartu,
         * 6 kartu + 3 klon untuk loop tak hingga, autoplay 4 detik,
         * pause saat hover/focus, hormati prefers-reduced-motion.
         */
        function homeSlider() {
            var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            var DURATION = 450;   /* samakan dengan transition di [data-slider-track] */
            var INTERVAL = 4000;
            var CLONES = 3;       /* jumlah kartu per tampilan maksimum (desktop) */

            return {
                index: 0,          /* kartu paling kiri; boleh > total karena memakai klon */
                total: 6,
                moving: false,
                timer: null,

                init: function () {
                    this.addClones();

                    if (!reduceMotion) {
                        this.start();
                    }
                },

                /* Klon 3 kartu pertama supaya loop tidak pernah menyisakan celah kosong. */
                addClones: function () {
                    var track = this.$refs.track;
                    var head = Array.prototype.slice.call(track.children).slice(0, CLONES);

                    head.forEach(function (slide) {
                        var clone = slide.cloneNode(true);
                        clone.setAttribute('aria-hidden', 'true');

                        var link = clone.querySelector('a');

                        if (link) {
                            link.setAttribute('tabindex', '-1');
                        }

                        track.appendChild(clone);
                    });
                },

                /* Tulis --index dan is-instant langsung ke DOM (bukan lewat :style/:class
                   Alpine) supaya reflow paksa di prev() benar-benar melakukan commit state
                   sebelum transisi berikutnya dimulai. */
                render: function (i, animate) {
                    this.index = i;
                    this.$refs.track.classList.toggle('is-instant', !animate);
                    this.$refs.track.style.setProperty('--index', i);
                },

                start: function () {
                    if (!this.timer) {
                        var self = this;
                        this.timer = setInterval(function () { self.next(); }, INTERVAL);
                    }
                },

                stop: function () {
                    clearInterval(this.timer);
                    this.timer = null;
                },

                /* Klik kontrol: hentikan timer lalu mulai ulang. */
                restart: function () {
                    this.stop();
                    if (!reduceMotion) {
                        this.start();
                    }
                },

                pause: function () {
                    this.stop();
                },

                resume: function () {
                    if (!reduceMotion) {
                        this.start();
                    }
                },

                next: function () {
                    if (this.moving) {
                        return;
                    }

                    this.moving = true;
                    this.render(this.index + 1, true);

                    var self = this;
                    setTimeout(function () {
                        if (self.index >= self.total) {
                            self.render(0, false);
                        }
                        self.moving = false;
                    }, reduceMotion ? 0 : DURATION + 20);
                },

                prev: function () {
                    if (this.moving) {
                        return;
                    }

                    this.moving = true;

                    if (this.index === 0) {
                        this.render(this.total, false);    /* lompat ke klon kartu 1 */
                        void this.$refs.track.offsetWidth; /* paksa reflow tanpa transisi */
                    }

                    this.render(this.index - 1, true);

                    var self = this;
                    setTimeout(function () {
                        self.moving = false;
                    }, reduceMotion ? 0 : DURATION + 20);
                },

                goTo: function (i) {
                    if (this.moving) {
                        return;
                    }

                    this.moving = true;
                    this.render(i, true);

                    var self = this;
                    setTimeout(function () {
                        self.moving = false;
                    }, reduceMotion ? 0 : DURATION + 20);
                }
            };
        }

        /* ===== Scroll reveal + stagger: IntersectionObserver, sekali per elemen ===== */
        (function () {
            document.documentElement.classList.add('js');

            var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            /* Tandai anak berulang di dalam section (grid nilai, kartu slider) supaya
               muncul bertahap; klon slider dilewati karena bukan kartu asli. */
            function markStagger(section) {
                var groups = section.querySelectorAll('[data-stagger-group]');

                Array.prototype.forEach.call(groups, function (group) {
                    Array.prototype.forEach.call(group.children, function (child, i) {
                        if (child.getAttribute('aria-hidden') === 'true') {
                            return;
                        }

                        child.setAttribute('data-stagger-item', '');
                        child.style.setProperty('--stagger-index', Math.min(i, 5));
                    });
                });
            }

            function reveal(section) {
                section.classList.add('is-visible');

                Array.prototype.forEach.call(section.querySelectorAll('[data-stagger-item]'), function (item) {
                    item.classList.add('is-visible');
                });
            }

            function init() {
                var sections = document.querySelectorAll('[data-scroll-reveal]');

                Array.prototype.forEach.call(sections, markStagger);

                if (reduceMotion || !('IntersectionObserver' in window)) {
                    Array.prototype.forEach.call(sections, reveal);
                    return;
                }

                var io = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            reveal(entry.target);
                            io.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.15 });

                Array.prototype.forEach.call(sections, function (section) { io.observe(section); });
            }

            /* Ditunda sampai DOMContentLoaded supaya Alpine selesai menyalin kartu slider
               lebih dulu (script Alpine dimuat sebagai modul yang ditunda). */
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
        })();
    </script>
@endsection
