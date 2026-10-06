<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $judul }} — TeFa SMKN 4 Tanjung Pinang</title>
    <style>
        @page {
            size: A4 portrait;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: content-box;
        }

        /* @page margin tidak dipakai dompdf — margin halaman dibuat lewat body */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #000000;
            background: #ffffff;
            font-size: 7pt;
            line-height: 1.35;
            padding: 15mm;
        }

        /* Pastikan warna latar & teks tetap tercetak saat pratinjau dicetak
           dari browser (browser default membuang background). */
        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
        }

        /* ---------- Sampul biru ---------- */
        .cover {
            width: 100%;
            background: #06449b;
            border-collapse: collapse;
            border-bottom: 2pt solid #0a4aa6;
            margin-bottom: 6mm;
        }

        .cover td {
            padding: 5mm 6mm;
            vertical-align: middle;
        }

        .cover .logo {
            width: 26mm;
        }

        .cover .logo img {
            width: 26mm;
            height: auto;
        }

        .cover .teks {
            color: #ffffff;
        }

        .cover .meta {
            font-size: 7.5pt;
            text-align: right;
            color: #c9e0ff;
            margin-bottom: 1.5mm;
        }

        .cover .eyebrow {
            font-size: 9pt;
            letter-spacing: 0.6pt;
            color: #b9d8ff;
            margin-bottom: 1.5mm;
        }

        .cover .judul-utama {
            font-size: 21pt;
            font-weight: bold;
            line-height: 1.1;
            margin-bottom: 1.5mm;
        }

        .cover .judul-sub {
            font-size: 17pt;
            font-weight: bold;
            line-height: 1.1;
            color: #d7e8f8;
        }

        /* ---------- Grid 3 kolom ---------- */
        .grid-wrap {
            padding: 0;
        }

        /* Batasi isi: 3 baris x 3 kartu per halaman, sisanya pindah halaman baru */
        .grid-wrap.putus {
            page-break-after: always;
        }

        .grid {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            border-spacing: 0;
        }

        .grid td.kol {
            width: 31.33%;
            vertical-align: top;
            padding: 0 0 5mm 0;
        }

        .grid td.sp {
            width: 3%;
            padding: 0;
        }

        /* Baris terakhir tidak butuh jarak bawah — beri ruang untuk catatan kaki */
        .grid tr.baris-akhir td.kol {
            padding-bottom: 0;
        }

        .grid td.penuh {
            width: 100%;
            padding: 0;
            vertical-align: middle;
        }

        .kartu {
            background: #ffffff;
            border: 1px solid #d0d7e2;
            border-radius: 3mm;
            overflow: hidden;
            page-break-inside: avoid;
        }

        /* ---------- Pill ---------- */
        .pills {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.2mm;
        }

        .pills td {
            padding: 0;
        }

        .pills .kanan {
            text-align: right;
        }

        .pill {
            display: inline-block;
            font-size: 7pt;
            font-weight: bold;
            line-height: 1;
            color: #ffffff;
            background: #06449b;
            border: 0.5pt solid #01326f;
            border-radius: 3mm;
            padding: 0.5mm 2.5mm;
        }

        .pill.tipe {
            background: #06449b;
        }

        .pill.tipe.jasa {
            background: #b30d35;
            border: 0.5pt solid #7d0a28;
        }

        /* ---------- Gambar ----------
           Kotak foto seragam 4:5 (55,7 x 70 mm di dalam kartu) — ramah foto
           potret sekaligus landscape. dompdf tidak mendukung object-fit,
           jadi skala "contain" dihitung per gambar di controller (foto_fit)
           dan dipasang sebagai style inline: gambar selalu muat penuh tanpa
           distorsi, area kosong di sekitarnya ditutupi warna latar.
           Fallback width:100%/height:auto bila dimensi tak terbaca; overflow
           dipotong rapi oleh overflow:hidden. */
        .foto {
            width: 100%;
            height: 70mm;
            margin-top: 3mm;
            background: #e2edf7;
            text-align: center;
            overflow: hidden;
        }

        .foto img {
            width: 100%;
            height: auto;
            vertical-align: top;
        }

        /* ---------- Teks kartu ----------
           Tinggi kotak = tinggi maksimum teks (nama 2 baris, deskripsi 3 baris)
           agar teks tidak pernah meluber dan menimpa elemen lain.
           Garis aksen biru di atas badan kartu + garis tipis pemisah
           deskripsi/harga memberi struktur visual. */
        .nama {
            font-size: 14pt;
            font-weight: bold;
            color: #00357f;
            line-height: 1.15;
            border-top: 2px solid #0a4aa6;
            padding: 1mm 2.2mm 0 2.2mm;
            height: 13mm;
            overflow: hidden;
        }

        .deskripsi {
            font-size: 10pt;
            color: #666666;
            line-height: 1.3;
            padding: 1mm 2.2mm 0 2.2mm;
            height: 17mm;
            overflow: hidden;
        }

        .harga {
            font-size: 13pt;
            font-weight: bold;
            line-height: 1;
            color: #00357f;
            background: #e2edf7;
            border-top: 1px solid #e0e6ef;
            padding: 1.2mm 2.2mm;
            margin-top: 1.5mm;
        }

        /* ---------- Elemen kosong ---------- */
        .kosong {
            padding: 30mm 8mm;
            text-align: center;
            color: #475569;
            font-size: 9pt;
            border: 0.5pt solid #d7e8f8;
        }

        /* ---------- Catatan kaki ---------- */
        .catatan-kaki {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            border-top: 1pt solid #cfd6e0;
            margin-top: 2mm;
        }

        .catatan-kaki td {
            padding: 2mm 0;
            font-size: 9pt;
            line-height: 1;
            color: #475569;
        }

        .catatan-kaki .kanan {
            text-align: right;
        }

        /* ---------- Toolbar pratinjau (hanya saat dibuka di browser) ---------- */
        .toolbar-pratinjau {
            position: sticky;
            top: 0;
            z-index: 10;
            display: none;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            background: #00357f;
            color: #ffffff;
            padding: 12px 24px;
            margin: -15mm -15mm 6mm;
        }

        .toolbar-pratinjau.tampil {
            display: flex;
        }

        .toolbar-pratinjau .judul-pratinjau {
            font-size: 10pt;
            font-weight: bold;
            letter-spacing: 0.4pt;
        }

        .toolbar-pratinjau .aksi {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .toolbar-pratinjau a,
        .toolbar-pratinjau button {
            display: inline-block;
            font-size: 8pt;
            font-weight: bold;
            color: #ffffff;
            background: #06449b;
            border: 1px solid #7fa8d9;
            border-radius: 3mm;
            padding: 1.6mm 4mm;
            cursor: pointer;
            text-decoration: none;
            line-height: 1.2;
        }

        .toolbar-pratinjau button.utama,
        .toolbar-pratinjau a.utama {
            background: #f2b630;
            border-color: #f2b630;
            color: #00357f;
        }

        .toolbar-pratinjau a:hover,
        .toolbar-pratinjau button:hover {
            filter: brightness(1.12);
        }

        @media print {
            .toolbar-pratinjau {
                display: none !important;
            }
        }
    </style>
</head>
<body>

{{-- Toolbar pratinjau browser — tidak ikut tercetak / tidak ada di PDF --}}
@if (!empty($preview))
    <div class="toolbar-pratinjau tampil" role="toolbar" aria-label="Toolbar pratinjau katalog">
        <span class="judul-pratinjau">PRATINJAU — {{ $judul }}</span>
        <div class="aksi">
            <a href="{{ $preview['unduhUrl'] }}" class="utama" aria-label="{{ $preview['unduhLabel'] }} dalam format PDF">
                ⬇ Unduh PDF
            </a>
            <button type="button" onclick="window.print()" aria-label="Cetak pratinjau katalog ini langsung dari browser">
                🖨 Cetak Langsung
            </button>
            <a href="{{ route('superadmin.dashboard') }}" aria-label="Kembali ke dashboard Super Admin">
                ← Kembali ke Dashboard
            </a>
        </div>
    </div>
@endif

{{-- Sampul biru — mengalir langsung ke baris pertama di halaman 1 --}}
<div class="sampul">
    <table class="cover">
        <tr>
            <td class="logo">
                <img src="{{ !empty($preview) ? asset('asset/img/logo-smkn4.png') : 'asset/img/logo-smkn4.png' }}" alt="Logo SMK Negeri 4 Tanjung Pinang">
            </td>
            <td class="teks">
                <div class="meta">Dicetak {{ $tanggal }}</div>
                <div class="eyebrow">UNIT TEACHING FACTORY</div>
                <div class="judul-utama">TEFA SMK N 4 TANJUNG PINANG</div>
                <div class="judul-sub">KATALOG PRODUK DAN JASA</div>
            </td>
        </tr>
    </table>
</div>

{{-- Grid katalog: 3 kartu per baris. Halaman 1 berbagi dengan sampul sehingga
     hanya 1 baris (baris pertama muncul tepat di bawah banner); halaman
     berikutnya memuat 2 baris penuh agar muat di satu halaman A4. --}}
@php
    $barisSemua = array_chunk($items, 3);        // 3 kartu per baris
    $halamanSemua = [];

    if ($barisSemua !== []) {
        $halamanSemua[] = [array_shift($barisSemua)];    // halaman 1: sampul + 1 baris

        foreach (array_chunk($barisSemua, 2) as $sisa) { // halaman berikutnya: 2 baris
            $halamanSemua[] = $sisa;
        }
    }
@endphp

@foreach ($halamanSemua as $halaman)
    <div class="grid-wrap @if(! $loop->last) putus @endif">
        <table class="grid">
            @foreach ($halaman as $baris)
                <tr @if($loop->last) class="baris-akhir" @endif>
                    @foreach ($baris as $item)
                        <td class="kol">
                            <div class="kartu">
                                <table class="pills">
                                    <tr>
                                        <td><span class="pill kode">{{ $item['kode'] }}</span></td>
                                        <td class="kanan">
                                            <span class="pill tipe {{ $item['isJasa'] ? 'jasa' : '' }}">{{ $item['tipe'] }}</span>
                                        </td>
                                    </tr>
                                </table>

                                <div class="foto">
                                    @if ($item['foto'])
                                        <img src="{{ !empty($preview) ? asset($item['foto']) : $item['foto'] }}" alt="{{ $item['nama'] }}"@if($item['foto_fit']) style="width: {{ $item['foto_fit']['w'] }}mm; height: {{ $item['foto_fit']['h'] }}mm; margin-top: {{ $item['foto_fit']['dy'] }}mm;" @endif>
                                    @endif
                                </div>

                                <div class="nama">{{ $item['nama'] }}</div>
                                <div class="deskripsi">{{ $item['deskripsi'] }}</div>
                                <div class="harga">{{ $item['harga'] }}</div>
                            </div>
                        </td>
                        @if (!$loop->last)
                            <td class="sp"></td>
                        @endif
                    @endforeach

                    @for ($s = count($baris); $s < 3; $s++)
                        <td class="sp"></td>
                        <td class="kol"></td>
                    @endfor
                </tr>
            @endforeach
        </table>
    </div>
@endforeach

@if (empty($items))
    <div class="grid-wrap">
        <table class="grid">
            <tr>
                <td class="penuh">
                    <div class="kosong">Belum ada data {{ $tipe === 'JASA' ? 'layanan jasa' : 'produk' }} untuk dicetak.</div>
                </td>
            </tr>
        </table>
    </div>
@endif

{{-- Catatan kaki --}}
<table class="catatan-kaki">
    <tr>
        <td>TeFa SMK Negeri 4 Tanjung Pinang — {{ $judul }}</td>
        <td class="kanan">{{ count($items) }} data ditampilkan</td>
    </tr>
</table>

</body>
</html>
