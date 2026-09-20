<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Transaksi Global — TeFa SMKN 4</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
        font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        color: #111;
        background: #fff;
        font-size: 11pt;
        line-height: 1.4;
        padding: 15px;
      }
      .no-print {
        background: #16224a;
        color: #fff;
        padding: 12px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 8px;
        margin-bottom: 24px;
      }
      .btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #fbbf24;
        color: #16224a;
        font-weight: 700;
        font-size: 13px;
        padding: 8px 16px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        text-decoration: none;
      }
      .btn:hover { background: #f59e0b; }
      .btn-back {
        background: rgba(255,255,255,0.15);
        color: #fff;
      }
      .btn-back:hover { background: rgba(255,255,255,0.25); }

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
      }
      .kop-logo {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: #16224a;
        color: #fbbf24;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 20px;
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
      }
      .kop-text p {
        font-size: 9.5pt;
        color: #444;
        margin-top: 2px;
      }

      /* METADATA */
      .meta-box {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 14px;
        font-size: 10pt;
      }
      .meta-left div {
        margin-bottom: 3px;
      }
      .meta-summary {
        display: grid;
        grid-template-columns: repeat(3, auto);
        gap: 12px;
        text-align: right;
      }
      .summary-card {
        border: 1px solid #ccc;
        padding: 6px 12px;
        border-radius: 6px;
        text-align: left;
      }
      .summary-card small {
        display: block;
        font-size: 8pt;
        color: #555;
        text-transform: uppercase;
      }
      .summary-card strong {
        font-size: 12pt;
      }

      /* TABEL */
      table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-size: 9.5pt;
      }
      th, td {
        border: 1px solid #777;
        padding: 6px 9px;
        vertical-align: middle;
      }
      th {
        background-color: #f1f3f7 !important;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 8.5pt;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
      tr:nth-child(even) td {
        background-color: #fafbfd;
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
        }
      }
    </style>
</head>
<body>

<div class="no-print">
  <div>
    <strong>Mode Cetak Laporan Global TeFa</strong> — Format telah dioptimalkan untuk kertas A4.
  </div>
  <div style="display:flex; gap:10px">
    <a href="{{ route('superadmin.reports.index') }}" class="btn btn-back">← Kembali</a>
    <button onclick="window.print()" class="btn">Cetak Dokumen (Ctrl+P)</button>
  </div>
</div>

<!-- KOP RESMI -->
<div class="kop">
  <div class="kop-logo">DB</div>
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
      <strong style="color:#059669">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</strong>
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
