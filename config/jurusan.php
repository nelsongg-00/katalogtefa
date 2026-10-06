<?php

/*
|--------------------------------------------------------------------------
| Konten Statis Program Keahlian (Jurusan)
|--------------------------------------------------------------------------
| Sumber data untuk halaman publik /jurusan/{slug}. Statik sengaja dipilih:
| kolom jurusans.deskripsi di database masih kosong dan kode jurusan tidak
| unik (RPL & DKV dobel), sehingga konten halaman tidak bisa diandalkan
| dari DB. Urutan key = slug yang dipakai di URL & slider homepage.
|
| 'gradasi' = warna banner [dari, ke]; mengikuti palet kartu fallback
| di halaman katalog produk (resources/views/public/produk.blade.php).
*/

return [
    'dkv' => [
        'kode' => 'DKV',
        'nama_jurusan' => 'Desain Komunikasi dan Visual',
        'deskripsi' => 'Jurusan Desain Komunikasi dan Visual membekali siswa dengan keterampilan desain grafis, ilustrasi digital, videografi, dan produksi media cetak. Siswa belajar menciptakan karya visual yang komunikatif untuk berbagai kebutuhan industri kreatif.',
        'gradasi' => ['#6b21a8', '#7c3aed'],
    ],

    'rpl' => [
        'kode' => 'RPL',
        'nama_jurusan' => 'Rekayasa Perangkat Lunak',
        'deskripsi' => 'Jurusan Rekayasa Perangkat Lunak mempelajari pengembangan aplikasi web, mobile, dan desktop dengan standar industri. Siswa dibekali kemampuan pemrograman, basis data, hingga deployment aplikasi modern.',
        'gradasi' => ['#1e3a8a', '#0284c7'],
    ],

    'animasi' => [
        'kode' => 'ANIMASI',
        'nama_jurusan' => 'Animasi',
        'deskripsi' => 'Jurusan Animasi fokus pada pembuatan animasi 2D dan 3D, mulai dari konsep cerita, storyboard, hingga produksi akhir. Siswa menguasai perangkat lunak animasi profesional dan alur produksi studio.',
        'gradasi' => ['#c2410c', '#b45309'],
    ],

    'tkj' => [
        'kode' => 'TKJ',
        'nama_jurusan' => 'Teknik Komputer dan Jaringan',
        'deskripsi' => 'Jurusan Teknik Komputer dan Jaringan mendidik siswa dalam instalasi jaringan, konfigurasi perangkat, keamanan siber, dan administrasi server. Lulusan siap bekerja di bidang infrastruktur TI.',
        'gradasi' => ['#0e7490', '#0891b2'],
    ],

    'pspt' => [
        'kode' => 'PSPT',
        'nama_jurusan' => 'Produksi dan Siaran Program Televisi',
        'deskripsi' => 'Jurusan Produksi dan Siaran Program Televisi membekali siswa dengan kemampuan produksi video, penyiaran, jurnalistik, dan konten digital. Siswa belajar dari pra-produksi hingga pasca-produksi.',
        'gradasi' => ['#be123c', '#e11d48'],
    ],

    'gim' => [
        'kode' => 'GIM',
        'nama_jurusan' => 'Pengembangan Gim',
        'deskripsi' => 'Jurusan Pengembangan Gim mempelajari desain game, pemrograman game engine, asset 3D, dan monetisasi. Siswa menciptakan gim interaktif yang siap dipublikasikan.',
        'gradasi' => ['#15803d', '#16a34a'],
    ],
];
