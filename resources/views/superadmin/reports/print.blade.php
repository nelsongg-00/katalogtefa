<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Transaksi Global — TeFa SMKN 4</title>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/400.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/500.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/600.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/700.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/800.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/900.css" rel="stylesheet">
    <style>
      @page {
        size: A4 landscape;
        margin: 12mm 15mm;
      }
      * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
      }
      body {
        font-family: 'Open Sauce Sans', 'Plus Jakarta Sans', system-ui, sans-serif;
        color: #0f172a;
        background: #f6f8fa;
        font-size: 11pt;
        line-height: 1.4;
        padding: 15px;
        -webkit-font-smoothing: antialiased;
      }

      /* Toolbar layar (tidak ikut tercetak) */
      .no-print {
        background: #ffffff;
        color: #0f172a;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, .04);
        padding: 12px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 24px;
      }
      .no-print strong { font-weight: 700; }
      .no-print span { color: #64748b; font-size: 13px; }

      .btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(180deg, #0b60cf, #0a4fa8);
        color: #ffffff;
        font-weight: 600;
        font-size: 13px;
        padding: 8px 16px;
        border-radius: 8px;
        border: 1px solid #0a4fa8;
        cursor: pointer;
        text-decoration: none;
        transition: background .12s ease-out;
      }
      .btn:hover { background: linear-gradient(180deg, #0a4fa8, #0c4284); }
      .btn-back {
        background: #ffffff;
        color: #0f172a;
        border: 1px solid #cbd5e1;
      }
      .btn-back:hover { background: #f8fafc; }

      /* KOP SURAT */
      .kop {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        border-bottom: 3px double #000;
        padding-bottom: 12px;
        margin-bottom: 16px;
        text-align: center;
        background: #ffffff;
        border-radius: 10px;
        padding-top: 4px;
      }
      .kop-logo {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
      .kop-logo img {
        width: 60px;
        height: 60px;
        object-fit: contain;
      }
      .kop-text h1 {
        font-size: 16pt;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
      }
      .kop-text h2 {
        font-size: 13pt;
        font-weight: 700;
        margin-top: 2px;
        color: #0b60cf;
      }
      .kop-text p {
        font-size: 9.5pt;
        color: #475569;
        margin-top: 2px;
      }

      /* METADATA */
      .meta-box {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 14px;
        font-size: 10pt;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 12px;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
      .meta-left div { margin-bottom: 3px; }
      .meta-summary {
        display: grid;
        grid-template-columns: repeat(3, auto);
        gap: 12px;
        text-align: right;
      }
      .summary-card {
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        padding: 6px 12px;
        border-radius: 8px;
        text-align: left;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
      .summary-card small {
        display: block;
        font-size: 8pt;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .04em;
      }
      .summary-card strong {
        font-size: 12pt;
        font-variant-numeric: tabular-nums;
      }

      /* TABEL */
      table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-size: 9.5pt;
      }
      th, td {
        border: 1px solid #94a3b8;
        padding: 6px 9px;
        vertical-align: middle;
      }
      th {
        background-color: #eef2f7 !important;
        color: #0f172a;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 8.5pt;
        letter-spacing: .03em;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
      tr:nth-child(even) td {
        background-color: #f8fafc;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
      tfoot th, tfoot td {
        background-color: #eef4ff !important;
        color: #0b2448;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
      .r { text-align: right; }
      .c { text-align: center; }

      /* TANDA TANGAN */
      .signatures {
        display: flex;
        justify-content: flex-end;
        margin-top: 30px;
        page-break-inside: avoid;
      }
      .sign-box {
        width: 240px;
        text-align: center;
        font-size: 10pt;
      }
      .sign-line {
        margin-top: 65px;
        border-top: 1px solid #000;
        padding-top: 4px;
        font-weight: 700;
      }

      @media print {
        .no-print {
          display: none !important;
        }
        body {
          padding: 0;
          background: #ffffff;
        }
        .kop, .meta-box {
          border-radius: 0;
          background: #ffffff;
        }
      }
    </style>
</head>
<body>

<div class="no-print">
  <div>
    <strong>Mode Cetak Laporan Global TeFa</strong> <span>— Format telah dioptimalkan untuk kertas A4.</span>
  </div>
  <div style="display:flex; gap:8px">
    <a href="{{ route('superadmin.reports.index') }}" class="btn btn-back">Kembali</a>
    <button onclick="window.print()" class="btn">Cetak Dokumen (Ctrl+P)</button>
  </div>
</div>

<!-- KOP RESMI -->
<div class="kop">
  <div class="kop-logo"><img src="{{ asset('asset/img/logo-smkn4.png') }}" alt="Logo SMK Negeri 4 Tanjungpinang"></div>
  <div class="kop-text">
    <h1>TEACHING FACTORY (TeFa) SMK NEGERI 4</h1>
    <h2>LAPORAN REKAPITULASI TRANSAKSI GLOBAL</h2>
    <p>Unit Produksi Lintas Jurusan: RPL · TKJ · DKV · PSPT · Animasi · Pengembangan Gim</p>
  </div>
</div>

<!-- METADATA & FILTER AKTIF -->
<div class="meta-box">
  <div class="meta-left">
    <div><strong>Jurusan:</strong> {{ $selectedJurusan ? $selectedJurusan->nama_jurusan : 'Semua Jurusan (Lintas TeFa)' }}</div>
    <div><strong>Status:</strong> {{ $status ? $status : 'Semua Status Transaksi' }}</div>
    <div><strong>Periode Transaksi:</strong> {{ $fromDate ? \Carbon\Carbon::parse($fromDate)->format('d/m/Y') : 'Awal' }} s/d {{ $toDate ? \Carbon\Carbon::parse($toDate)->format('d/m/Y') : 'Sekarang' }}</div>
    <div><strong>Dicetak Pada:</strong> {{ $printedAt }}</div>
  </div>

  <div class="meta-summary">
    <div class="summary-card">
      <small>Total Transaksi</small>
      <strong>{{ $totalTransactions }}</strong>
    </div>
    <div class="summary-card">
      <small>Total Omzet Selesai</small>
      <strong style="color:#15803d">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</strong>
    </div>
  </div>
</div>

<!-- TABEL REKAP -->
<table>
  <thead>
    <tr>
      <th class="c" style="width:40px">No</th>
      <th style="width:75px">ID Order</th>
      <th style="width:85px">Tanggal</th>
      <th>Pelanggan</th>
      <th>Item / Layanan Jasa</th>
      <th>Jurusan</th>
      <th>Tipe</th>
      <th class="r" style="width:110px">Total Harga</th>
      <th class="c" style="width:90px">Status</th>
    </tr>
  </thead>
  <tbody>
    @forelse($transactions as $index => $trx)
      @php
        $firstProduct = $trx->detailPesanans->first()?->produk;
        $jurName = $firstProduct?->jurusan?->nama_jurusan ?? '—';
        $pType = $firstProduct?->tipe ?? ($trx->is_service_via_wa ? 'Jasa' : 'Fisik');
      @endphp
      <tr>
        <td class="c">{{ $index + 1 }}</td>
        <td><strong>#{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
        <td>{{ $trx->created_at->format('d/m/Y') }}</td>
        <td>{{ $trx->user->name ?? 'Pelanggan Umum' }}</td>
        <td>
          @forelse($trx->detailPesanans as $d)
            {{ $d->produk?->nama_produk ?? 'Item TeFa' }} ({{ $d->jumlah }}x)<br>
          @empty
            Layanan TeFa via WA
          @endforelse
        </td>
        <td>{{ $jurName }}</td>
        <td>{{ $pType }}</td>
        <td class="r">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
        <td class="c"><strong>{{ $trx->status_pesanan }}</strong></td>
      </tr>
    @empty
      <tr>
        <td colspan="9" class="c" style="padding:20px">
          Tidak ada data transaksi yang sesuai dengan parameter laporan ini.
        </td>
      </tr>
    @endforelse
  </tbody>
  <tfoot>
    <tr>
      <th colspan="7" class="r" style="font-size:10pt">AKUMULASI TOTAL PENDAPATAN (TRANSAKSI SELESAI):</th>
      <th class="r" style="font-size:10.5pt">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</th>
      <th></th>
    </tr>
  </tfoot>
</table>

<!-- TANDA TANGAN RESMI -->
<div class="signatures">
  <div class="sign-box">
    <div>Mengetahui,</div>
    <div><strong>Super Admin Unit Teaching Factory</strong></div>
    <div class="sign-line">{{ auth()->user()->name ?? 'Super Admin' }}</div>
    <div style="font-size:8.5pt; color:#555">NIP / ID Sistem: SA-{{ auth()->id() ?? '001' }}</div>
  </div>
</div>

<script>
window.onload = function() {
  // Hanya picu otomatis jika bukan di mode preview dev tanpa user interaction
  setTimeout(function() {
    window.print();
  }, 600);
};
</script>
</body>
</html>
