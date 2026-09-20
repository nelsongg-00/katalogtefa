@extends('layouts.superadmin')

@section('title', 'Laporan Transaksi Global')

@section('content')
<div class="page-head">
  <div>
    <h1>LAPORAN TRANSAKSI GLOBAL</h1>
    <p>Rekapitulasi seluruh pesanan dan transaksi dari ke-6 jurusan Teaching Factory SMKN 4.</p>
  </div>
  <div class="actions">
    <button class="btn ghost" onclick="exportCsv()">
      <svg class="i" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
      Ekspor CSV
    </button>
    <a href="{{ route('superadmin.reports.print', request()->query()) }}" target="_blank" class="btn primary">
      <svg class="i" viewBox="0 0 24 24"><path d="M6 9V3h12v6M6 18H4a1 1 0 01-1-1v-6a2 2 0 012-2h14a2 2 0 012 2v6a1 1 0 01-1 1h-2M7 14h10v7H7z"/></svg>
      Cetak Laporan
    </a>
  </div>
</div>

<!-- 4 KARTU MINI STATISTIK LAPORAN -->
<section class="mini-stats">
  <div class="card mini">
    <small>Jumlah Transaksi</small>
    <b>{{ $totalTransactions }}</b>
  </div>
  <div class="card mini">
    <small>Total Nilai Selesai</small>
    <b>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</b>
  </div>
  <div class="card mini">
    <small>Sedang Berjalan</small>
    <b>{{ $activeInProgress }}</b>
  </div>
  <div class="card mini">
    <small>Dibatalkan</small>
    <b>{{ $cancelledCount }}</b>
  </div>
</section>

<!-- FILTER TOOLBAR -->
<section class="card">
  <form method="GET" action="{{ route('superadmin.reports.index') }}" class="toolbar">
    <div class="field">
      <label for="fj">Jurusan</label>
      <select class="sel" id="fj" name="jurusan" onchange="this.form.submit()">
        <option value="">Semua Jurusan</option>
        @foreach($jurusans as $j)
          <option value="{{ $j->id }}" {{ (string)($jurusanId ?? '') === (string)$j->id ? 'selected' : '' }}>
            {{ $j->nama_jurusan }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="field">
      <label for="fs">Status</label>
      <select class="sel" id="fs" name="status" onchange="this.form.submit()">
        <option value="">Semua Status</option>
        <option value="Completed" {{ ($status ?? '') === 'Completed' ? 'selected' : '' }}>Selesai / Completed</option>
        <option value="In Progress" {{ ($status ?? '') === 'In Progress' ? 'selected' : '' }}>Diproses / In Progress</option>
        <option value="Pending" {{ ($status ?? '') === 'Pending' ? 'selected' : '' }}>Menunggu / Pending</option>
        <option value="Cancelled" {{ ($status ?? '') === 'Cancelled' ? 'selected' : '' }}>Dibatalkan / Cancelled</option>
      </select>
    </div>

    <div class="field">
      <label for="fd">Dari Tanggal</label>
      <input class="inp" type="date" id="fd" name="from_date" value="{{ $fromDate ?? '' }}" onchange="this.form.submit()">
    </div>

    <div class="field">
      <label for="fe">Sampai Tanggal</label>
      <input class="inp" type="date" id="fe" name="to_date" value="{{ $toDate ?? '' }}" onchange="this.form.submit()">
    </div>

    <div class="field" style="flex:1; min-width:180px">
      <label for="fq">Pencarian</label>
      <input class="inp" id="fq" name="q" value="{{ $q ?? '' }}" placeholder="ID, nama pelanggan, atau item...">
    </div>

    <div class="field">
      <label>&nbsp;</label>
      <div style="display:flex; gap:6px">
        <button type="submit" class="btn primary sm" style="padding:10px 16px">Cari</button>
        @if(!empty($fromDate) || !empty($toDate) || !empty($jurusanId) || !empty($status) || !empty($q))
          <a href="{{ route('superadmin.reports.index') }}" class="btn ghost sm" style="padding:10px 16px">Reset</a>
        @endif
      </div>
    </div>
  </form>

  <div class="tbl-wrap">
    <table>
      <thead>
        <tr>
          <th>ID Pesanan</th>
          <th>Tanggal</th>
          <th>Pelanggan</th>
          <th>Produk / Layanan Jasa</th>
          <th>Jurusan</th>
          <th>Tipe</th>
          <th class="r">Total Nilai</th>
          <th>Status</th>
          <th class="r">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($transactions as $trx)
          @php
            $firstProduct = $trx->detailPesanans->first()?->produk;
            $jurName = $firstProduct?->jurusan?->nama_jurusan ?? '—';
            $pType = $firstProduct?->tipe ?? ($trx->is_service_via_wa ? 'Layanan Jasa' : 'Produk Fisik');
            $stLower = strtolower($trx->status_pesanan);
            $stBadge = 'b-yellow';
            if (in_array($stLower, ['completed', 'selesai'])) $stBadge = 'b-green';
            elseif (in_array($stLower, ['in progress', 'diproses', 'proses'])) $stBadge = 'b-blue';
            elseif (in_array($stLower, ['cancelled', 'dibatalkan', 'batal'])) $stBadge = 'b-red';
          @endphp
          <tr>
            <td><strong>#{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
            <td>{{ $trx->created_at->format('d M Y') }}</td>
            <td>
              <strong>{{ $trx->user->name ?? 'Pelanggan Umum' }}</strong>
              @if($trx->user?->email)
                <br><small class="t-muted">{{ $trx->user->email }}</small>
              @endif
            </td>
            <td>
              @forelse($trx->detailPesanans as $d)
                <div>{{ $d->produk?->nama_produk ?? 'Produk/Jasa TeFa' }} ({{ $d->jumlah }}x)</div>
              @empty
                <div>Layanan TeFa via WA</div>
              @endforelse
            </td>
            <td>
              <span class="badge b-blue">{{ $jurName }}</span>
            </td>
            <td>
              <span class="badge b-gray">{{ $pType }}</span>
            </td>
            <td class="r">
              <strong>Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</strong>
            </td>
            <td>
              <span class="badge {{ $stBadge }}">{{ $trx->status_pesanan }}</span>
            </td>
            <td>
              <div class="row-actions">
                <button class="icon-btn" title="Koreksi Transaksi" onclick='openCorrectModal({{ $trx->id }}, {{ $trx->total_harga }}, "{{ $trx->status_pesanan }}")'>
                  <svg class="i" viewBox="0 0 24 24"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4z"/></svg>
                </button>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="9">
              <div class="empty">
                <b>Tidak ada transaksi ditemukan</b>
                Coba sesuaikan filter jurusan, status, rentang tanggal, atau kata kunci pencarian.
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</section>

<!-- MODAL KOREKSI TRANSAKSI -->
<div class="modal" id="correctModal" role="dialog" aria-modal="true">
  <form class="dialog" id="correctForm" method="POST" action="">
    @csrf
    @method('PATCH')
    <header>
      <h3 id="correctModalTitle">Koreksi Data Transaksi</h3>
      <button type="button" class="icon-btn" onclick="closeCorrectModal()"><svg class="i" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg></button>
    </header>
    <div class="body">
      <div class="form-grid">
        <div class="field">
          <label for="k_total">Total Nilai (Rp)</label>
          <input class="inp" id="k_total" type="number" min="0" step="1000" name="total_harga" required>
        </div>
        <div class="field">
          <label for="k_status">Status Transaksi</label>
          <select class="sel" id="k_status" name="status_pesanan" required>
            <option value="Pending">Menunggu (Pending)</option>
            <option value="In Progress">Diproses (In Progress)</option>
            <option value="Completed">Selesai (Completed)</option>
            <option value="Cancelled">Dibatalkan (Cancelled)</option>
          </select>
        </div>
      </div>
    </div>
    <footer>
      <button type="button" class="btn ghost" onclick="closeCorrectModal()">Batal</button>
      <button type="submit" class="btn primary">Simpan Koreksi</button>
    </footer>
  </form>
</div>
@endsection

@push('scripts')
<script>
function openCorrectModal(id, total, status) {
  $('#correctModalTitle').textContent = `Koreksi Transaksi #${id}`;
  $('#k_total').value = total;
  $('#k_status').value = status;

  const form = $('#correctForm');
  form.action = `/superadmin/reports/${id}/koreksi`;

  $('#correctModal').classList.add('open');
}
function closeCorrectModal() {
  $('#correctModal').classList.remove('open');
}

function exportCsv() {
  const table = document.querySelector('table');
  if (!table) return;

  const rows = [];
  const headers = ['ID Pesanan', 'Tanggal', 'Pelanggan', 'Produk', 'Jurusan', 'Tipe', 'Total Harga', 'Status'];
  rows.push(headers.map(h => `"${h}"`).join(','));

  const trs = table.querySelectorAll('tbody tr');
  trs.forEach(tr => {
    const tds = tr.querySelectorAll('td');
    if (tds.length >= 8) {
      const row = [
        tds[0].innerText.trim(),
        tds[1].innerText.trim(),
        tds[2].innerText.replace(/\n/g, ' ').trim(),
        tds[3].innerText.replace(/\n/g, '; ').trim(),
        tds[4].innerText.trim(),
        tds[5].innerText.trim(),
        tds[6].innerText.replace(/[^0-9]/g, ''),
        tds[7].innerText.trim()
      ];
      rows.push(row.map(val => `"${val.replace(/"/g, '""')}"`).join(','));
    }
  });

  const csvContent = '\uFEFF' + rows.join('\n');
  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
  const url = URL.createObjectURL(blob);
  const link = document.createElement('a');
  link.setAttribute('href', url);
  link.setAttribute('download', `laporan-transaksi-tefa-${new Date().toISOString().slice(0,10)}.csv`);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}
</script>
@endpush
