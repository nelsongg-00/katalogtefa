@extends('layouts.public')

@section('title', $portfolio->title.' — Portofolio TEFA SMKN 4 Tanjungpinang')

@section('content')
<style>
    /* ==========================================================
       DETAIL PORTOFOLIO — struktur mengikuti referensi desain.
       Di-scope ke .portofolio-detail agar tidak bocor ke halaman lain.
       Header & footer memakai layout publik, bukan markup referensi.
       ========================================================== */
    .portofolio-detail {
        --biru: #0b60cf;
        --biru-tua: #0a3c7d;
        --biru-cta: #00a1ff;
        --biru-ringkas: #4ebeff;
        --teks: #14213d;
        --teks-muted: #3d4a5c;
        --abu-gambar: #c9d0da;
        --putih: #ffffff;
        background: #fafbfc;
    }
    .portofolio-detail .wadah {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 24px;
    }

    .portofolio-detail .kembali {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 40px;
        font-size: 13px;
        font-weight: 700;
        color: var(--biru);
        transition: transform .2s ease;
    }
    .portofolio-detail .kembali:hover { transform: translateX(-3px); }

    .portofolio-detail .judul-utama {
        padding: 20px 0 28px;
        font-size: 34px;
        font-weight: 800;
        line-height: 1.2;
        color: var(--teks);
    }

    /* ---------- SLIDESHOW ---------- */
    .portofolio-detail .gambar-utama {
        position: relative;
        aspect-ratio: 16 / 9;
        border-radius: 22px;
        background: var(--abu-gambar);
        overflow: hidden;
    }
    .portofolio-detail .gambar-utama::after {
        content: "";
        position: absolute;
        top: 0; left: 0;
        width: 50%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,.35), transparent);
        animation: pd-kilau 2.8s ease-in-out infinite !important;
        pointer-events: none;
        z-index: 1;
    }
    .portofolio-detail .slide {
        position: absolute;
        inset: 0;
    }
    .portofolio-detail .slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .portofolio-detail .slide-kosong {
        width: 100%;
        height: 100%;
        display: grid;
        place-items: center;
        font-size: 64px;
        color: var(--putih);
        background: linear-gradient(135deg, #0b60cf, #0a2f63);
    }
    .portofolio-detail .slide-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 42px; height: 42px;
        border: none;
        border-radius: 50%;
        background: rgba(20,33,61,.55);
        color: var(--putih);
        font-size: 20px;
        line-height: 1;
        cursor: pointer;
        display: grid;
        place-items: center;
        z-index: 3;
        transition: background .25s ease, transform .2s ease;
    }
    .portofolio-detail .slide-nav:hover { background: var(--biru); }
    .portofolio-detail .slide-nav.prev { left: 14px; }
    .portofolio-detail .slide-nav.next { right: 14px; }
    .portofolio-detail .slide-nav:hover.prev { transform: translateY(-50%) translateX(-2px); }
    .portofolio-detail .slide-nav:hover.next { transform: translateY(-50%) translateX(2px); }
    .portofolio-detail .slide-dots {
        position: absolute;
        bottom: 14px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 8px;
        z-index: 3;
    }
    .portofolio-detail .slide-dots button {
        width: 10px; height: 10px;
        padding: 0;
        border: none;
        border-radius: 50%;
        background: rgba(255,255,255,.55);
        cursor: pointer;
        transition: background .25s ease, transform .25s ease;
    }
    .portofolio-detail .slide-dots button.aktif {
        background: var(--putih);
        transform: scale(1.25);
    }

    /* ---------- DUA KOLOM ---------- */
    .portofolio-detail .dua-kolom {
        display: grid;
        grid-template-columns: 1.25fr 1fr;
        gap: 22px;
        margin-top: 28px;
    }
    .portofolio-detail .kartu-tentang {
        border: 2px solid #111;
        border-radius: 22px;
        padding: 28px 30px 34px;
        background: var(--putih);
        transition: transform .3s ease, box-shadow .3s ease;
    }
    .portofolio-detail .kartu-tentang:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(20,33,61,.12); }
    .portofolio-detail .kartu-tentang h2 {
        font-size: 18px;
        font-weight: 800;
        margin-bottom: 16px;
        letter-spacing: .2px;
    }
    .portofolio-detail .kartu-tentang p {
        font-size: 14px;
        color: var(--teks-muted);
        line-height: 1.75;
        white-space: pre-line;
    }
    .portofolio-detail .kartu-ringkas {
        background: var(--biru-ringkas);
        border-radius: 22px;
        padding: 28px 28px 30px;
        color: var(--teks);
        transition: transform .3s ease, box-shadow .3s ease;
    }
    .portofolio-detail .kartu-ringkas:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(0,120,200,.25); }
    .portofolio-detail .kartu-ringkas h2 {
        font-size: 18px;
        font-weight: 800;
        margin-bottom: 18px;
        letter-spacing: .2px;
    }
    .portofolio-detail .kartu-ringkas .item { margin-bottom: 14px; }
    .portofolio-detail .kartu-ringkas .item:last-child { margin-bottom: 0; }
    .portofolio-detail .kartu-ringkas dt {
        font-size: 12.5px;
        font-weight: 800;
        letter-spacing: .2px;
    }
    .portofolio-detail .kartu-ringkas dd {
        font-size: 13.5px;
        color: var(--teks);
        margin-top: 2px;
        line-height: 1.5;
    }

    /* ---------- CTA ---------- */
    .portofolio-detail .cta {
        margin: 96px auto 80px;
        max-width: 780px;
        background: var(--biru-cta);
        border-radius: 26px;
        padding: 56px 40px 52px;
        text-align: center;
        color: var(--putih);
        position: relative;
        overflow: hidden;
        box-shadow: 0 16px 36px rgba(0,161,255,.28);
    }
    .portofolio-detail .cta::before,
    .portofolio-detail .cta::after {
        content: "";
        position: absolute;
        border-radius: 50%;
        background: rgba(255,255,255,.12);
        pointer-events: none;
    }
    .portofolio-detail .cta::before { width: 220px; height: 220px; top: -90px; left: -60px;
        animation: pd-melayang 6s ease-in-out infinite !important; }
    .portofolio-detail .cta::after  { width: 160px; height: 160px; bottom: -70px; right: -40px;
        animation: pd-melayang 7s ease-in-out infinite reverse !important; }
    .portofolio-detail .cta h2 {
        font-size: 36px;
        font-weight: 800;
        line-height: 1.25;
        margin-bottom: 26px;
        position: relative;
        z-index: 1;
    }
    .portofolio-detail .cta .tombol-grup {
        display: flex;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
        position: relative;
        z-index: 1;
    }
    .portofolio-detail .tombol {
        display: inline-block;
        font-size: 13px;
        font-weight: 600;
        padding: 9px 22px;
        border-radius: 999px;
        transition: transform .25s ease, background .25s, color .25s;
    }
    .portofolio-detail .tombol-putih { background: var(--putih); color: var(--biru-cta); }
    .portofolio-detail .tombol-putih:hover { transform: translateY(-2px) scale(1.03); box-shadow: 0 6px 16px rgba(0,0,0,.15); }
    .portofolio-detail .tombol-garis {
        border: 1px solid var(--putih);
        color: var(--putih);
        font-size: 12px;
        padding: 8px 18px;
    }
    .portofolio-detail .tombol-garis:hover { background: var(--putih); color: var(--biru-cta); transform: translateY(-2px); }

    /* ---------- RESPONSIF ---------- */
    @media (max-width: 800px) {
        .portofolio-detail .dua-kolom { grid-template-columns: 1fr; }
        .portofolio-detail .judul-utama { font-size: 26px; }
        .portofolio-detail .cta h2 { font-size: 26px; }
    }
    @media (max-width: 520px) {
        .portofolio-detail .cta { margin: 64px 16px 56px; padding: 40px 22px; }
    }

    /* ==========================================================
       ANIMASI (FORCE) — reveal saat scroll.
       Sengaja TIDAK memakai @media (prefers-reduced-motion) supaya
       animasi tetap berjalan walau perangkat mematikan efek gerak;
       animation memakai !important agar menang atas aturan global.
       ========================================================== */
    @keyframes pd-naik {
        from { opacity: 0; transform: translateY(30px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes pd-kiri {
        from { opacity: 0; transform: translateX(-30px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    @keyframes pd-kanan {
        from { opacity: 0; transform: translateX(30px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    @keyframes pd-kilau {
        0%   { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    @keyframes pd-melayang {
        0%, 100% { transform: translateY(0); }
        50%      { transform: translateY(-6px); }
    }

    .portofolio-detail .judul-utama { animation: pd-kiri .8s ease both !important; }
    .portofolio-detail .gambar-utama { animation: pd-naik .9s ease .15s both !important; }

    /* Elemen reveal: disetel JS dengan menambah kelas .tampil */
    .portofolio-detail .reveal { opacity: 0; }
    .portofolio-detail .reveal.tampil { animation: pd-naik .7s ease both !important; }
    .portofolio-detail .reveal.kiri.tampil { animation-name: pd-kiri !important; }
    .portofolio-detail .reveal.kanan.tampil { animation-name: pd-kanan !important; }
    .portofolio-detail .reveal.tunda-1.tampil { animation-delay: .12s !important; }
    .portofolio-detail .reveal.tunda-2.tampil { animation-delay: .24s !important; }
    .portofolio-detail .reveal.tunda-3.tampil { animation-delay: .36s !important; }
</style>

<div class="portofolio-detail">
    <div class="wadah">

        <a href="{{ route('portofolio') }}" class="kembali">&larr; Kembali ke Portofolio</a>

        <h1 class="judul-utama">{{ $portfolio->title }}</h1>

        {{-- SLIDESHOW GAMBAR --}}
        @php
            $slides = $portfolio->images
                ->map(fn ($image) => asset('storage/'.$image->image_path))
                ->values();
        @endphp

        <div class="gambar-utama"
             x-data="{
                i: 0,
                imgs: @js($slides),
                next() { this.i = (this.i + 1) % this.imgs.length },
                prev() { this.i = (this.i - 1 + this.imgs.length) % this.imgs.length }
             }">
            <template x-if="imgs.length === 0">
                <div class="slide slide-kosong" aria-hidden="true">🏆</div>
            </template>

            <template x-for="(img, idx) in imgs" :key="img">
                <div class="slide" x-show="i === idx" x-transition.opacity.duration.500ms
                     :aria-hidden="i !== idx">
                    <img :src="img" :alt="'Gambar ' + (idx + 1) + ' — {{ $portfolio->title }}'">
                </div>
            </template>

            <template x-if="imgs.length > 1">
                <div>
                    <button type="button" class="slide-nav prev" @click="prev()" aria-label="Gambar sebelumnya">&lsaquo;</button>
                    <button type="button" class="slide-nav next" @click="next()" aria-label="Gambar berikutnya">&rsaquo;</button>
                    <div class="slide-dots">
                        <template x-for="(img, idx) in imgs" :key="'dot-' + idx">
                            <button type="button" :class="{ 'aktif': i === idx }"
                                    @click="i = idx"
                                    :aria-label="'Gambar ' + (idx + 1)"></button>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        <div class="dua-kolom">
            <section class="kartu-tentang reveal kiri">
                <h2>TENTANG PROYEK</h2>
                <p>{{ $portfolio->description ?: 'Deskripsi proyek belum diisi.' }}</p>
            </section>

            <aside class="kartu-ringkas reveal kanan tunda-1">
                <h2>RINGKASAN PROYEK</h2>
                <dl>
                    <div class="item">
                        <dt>NAMA PROYEK</dt>
                        <dd>{{ $portfolio->title }}</dd>
                    </div>
                    <div class="item">
                        <dt>PENGEMBANG</dt>
                        <dd>{{ $portfolio->team_name ?: 'Belum diisi' }}</dd>
                    </div>
                    <div class="item">
                        <dt>PEMBIMBING</dt>
                        <dd>{{ $portfolio->mentor_name ?: 'Belum diisi' }}</dd>
                    </div>
                    <div class="item">
                        <dt>KATEGORI</dt>
                        <dd>{{ $portfolio->jurusan->kode ?? 'TEFA' }}</dd>
                    </div>
                </dl>
            </aside>
        </div>

        <section class="cta reveal tunda-1">
            <h2>Kreatif, Inovatif, Solutif<br>Bersama TEFA SMKN 4<br>Tanjungpinang</h2>
            <div class="tombol-grup">
                <a href="{{ route('produk') }}" class="tombol tombol-putih">Jelajahi Produk</a>
                <a href="{{ route('jasa') }}" class="tombol tombol-garis">Lihat Layanan Jasa</a>
            </div>
        </section>

    </div>
</div>

<script>
    // Animasi muncul saat elemen masuk layar (dipaksa jalan, tanpa prefers-reduced-motion)
    (function () {
        var elemen = document.querySelectorAll('.portofolio-detail .reveal');
        if (!elemen.length) return;

        if (!('IntersectionObserver' in window)) {
            elemen.forEach(function (el) { el.classList.add('tampil'); });
            return;
        }

        var pengamat = new IntersectionObserver(function (entri) {
            entri.forEach(function (e) {
                if (e.isIntersecting) {
                    e.target.classList.add('tampil');
                    pengamat.unobserve(e.target);
                }
            });
        }, { threshold: 0.15 });

        elemen.forEach(function (el) { pengamat.observe(el); });

        // Pengaman: pastikan elemen tetap tampil bila observer tidak memicu
        setTimeout(function () {
            elemen.forEach(function (el) { el.classList.add('tampil'); });
        }, 2000);
    })();
</script>
@endsection
