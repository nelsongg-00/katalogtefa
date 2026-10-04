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
        {{-- ================= HERO ================= --}}
        <section class="min-h-[420px] bg-brand py-12 text-white sm:min-h-[520px] sm:pb-12 sm:pt-20" data-scroll-reveal>
            <div class="mx-auto w-full max-w-shell px-4 sm:px-8">
                <h1 class="max-w-[14em] text-[clamp(1.75rem,5vw,3.25rem)] font-bold uppercase leading-[1.15]">
                    Selamat Datang di Katalog TEFA SMKN 4 Tanjungpinang
                </h1>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('produk') }}" class="inline-block rounded-pill border border-white bg-white px-4 py-2 text-sm font-semibold text-ink transition-colors hover:bg-slate-100">Jelajahi Produk</a>
                    <a href="{{ route('jasa') }}" class="inline-block rounded-pill border border-white px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-white hover:text-ink">Lihat Layanan Jasa</a>
                </div>
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
