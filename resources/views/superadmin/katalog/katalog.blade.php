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
            padding: 8mm;
        }

        /* ---------- Sampul biru ---------- */
        .cover {
            width: 100%;
            background: #06449b;
            border-collapse: collapse;
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
            padding: 0 2.9mm;
        }

        .grid {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            border-spacing: 0;
        }

        .grid td.kol {
            width: 30.33%;
            vertical-align: top;
            padding: 0 0 5.5mm 0;
        }

        .grid td.sp {
            width: 4.5%;
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
            border: 0.5pt solid #d7e8f8;
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
            border-radius: 3mm;
            padding: 0.5mm 2.5mm;
        }

        .pill.tipe {
            background: #06449b;
        }

        .pill.tipe.jasa {
            background: #b30d35;
        }

        /* ---------- Gambar ---------- */
        .foto {
            width: 100%;
            height: 16mm;
            background: #e2edf7;
            text-align: center;
        }

        .foto img {
            width: 100%;
            height: 16mm;
        }

        /* ---------- Teks kartu ----------
           Tinggi kotak = tinggi maksimum teks (nama 2 baris, deskripsi 3 baris)
           agar teks tidak pernah meluber dan menimpa elemen lain. */
        .nama {
            font-size: 9pt;
            font-weight: bold;
            color: #07377d;
            line-height: 1;
            padding: 0.5mm 2.2mm 0 2.2mm;
            height: 9.5mm;
            overflow: hidden;
        }

        .deskripsi {
            font-size: 7pt;
            color: #475569;
            line-height: 1;
            padding: 0.4mm 2.2mm 0 2.2mm;
            height: 11mm;
            overflow: hidden;
        }

        .harga {
            font-size: 9pt;
            font-weight: bold;
            line-height: 1;
            color: #06449b;
            background: #e2edf7;
            padding: 0.6mm 2.2mm;
            margin-top: 0.8mm;
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
            background: #e9eef5;
            margin-top: 2mm;
        }

        .catatan-kaki td {
            padding: 2mm 3mm;
            font-size: 7pt;
            line-height: 1;
            color: #475569;
        }

        .catatan-kaki .kanan {
            text-align: right;
        }
    </style>
</head>
<body>

{{-- Sampul biru --}}
<table class="cover">
    <tr>
        <td class="logo">
            <img src="asset/img/logo-smkn4.png" alt="Logo SMK Negeri 4 Tanjung Pinang">
        </td>
        <td class="teks">
            <div class="meta">Dicetak {{ $tanggal }}</div>
            <div class="eyebrow">UNIT TEACHING FACTORY</div>
            <div class="judul-utama">TEFA SMK N 4 TANJUNG PINANG</div>
            <div class="judul-sub">KATALOG PRODUK DAN JASA</div>
        </td>
    </tr>
</table>

{{-- Grid katalog: 3 kartu + 2 sel spasi agar lebar kartu seragam --}}
<div class="grid-wrap">
    <table class="grid">
        @forelse (array_chunk($items, 3) as $baris)
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
                                    <img src="{{ $item['foto'] }}" alt="{{ $item['nama'] }}">
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
        @empty
            <tr>
                <td class="penuh">
                    <div class="kosong">Belum ada data {{ $tipe === 'JASA' ? 'layanan jasa' : 'produk' }} untuk dicetak.</div>
                </td>
            </tr>
        @endforelse
    </table>
</div>

{{-- Catatan kaki --}}
<table class="catatan-kaki">
    <tr>
        <td>TeFa SMK Negeri 4 Tanjung Pinang — {{ $judul }}</td>
        <td class="kanan">{{ count($items) }} data ditampilkan</td>
    </tr>
</table>

</body>
</html>
