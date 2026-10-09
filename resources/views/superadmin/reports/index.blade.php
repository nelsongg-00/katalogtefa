@extends('layouts.superadmin')

@section('title', 'Laporan Transaksi Global')

@section('content')
<div class="stack-y">
<x-sa-page-header
    title="LAPORAN TRANSAKSI GLOBAL"
    subtitle="Rekapitulasi seluruh pesanan dan transaksi dari ke-6 jurusan Teaching Factory SMKN 4.">
    <x-slot name="actions">
        <x-sa-button variant="ghost" icon="download" onclick="exportCsv()">Ekspor CSV</x-sa-button>
        <x-sa-button variant="primary" icon="print" :href="route('superadmin.reports.print', request()->query())" target="_blank">Cetak Laporan</x-sa-button>
    </x-slot>
</x-sa-page-header>

<!-- 4 KARTU MINI STATISTIK LAPORAN -->
<section class="mini-stats">
    <x-sa-stat variant="mini" label="Jumlah Transaksi" :value="$totalTransactions" />
    <x-sa-stat variant="mini" label="Total Nilai Selesai" :value="'Rp '.number_format($totalRevenue, 0, ',', '.')" />
    <x-sa-stat variant="mini" label="Sedang Berjalan" :value="$activeInProgress" />
    <x-sa-stat variant="mini" label="Dibatalkan" :value="$cancelledCount" />
</section>

<!-- FILTER TOOLBAR -->
<x-sa-card body="none">
    <form method="GET" action="{{ route('superadmin.reports.index') }}" class="toolbar">
        <div class="field">
            <label for="fj">Jurusan</label>
            <x-sa-input as="select" id="fj" name="jurusan" onchange="this.form.submit()">
                <option value="">Semua Jurusan</option>
                @foreach($jurusans as $j)
                    <option value="{{ $j->id }}" {{ (string)($jurusanId ?? '') === (string)$j->id ? 'selected' : '' }}>
                        {{ $j->nama_jurusan }}
                    </option>
                @endforeach
            </x-sa-input>
        </div>

        <div class="field">
            <label for="fs">Status</label>
            <x-sa-input as="select" id="fs" name="status" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="Completed" {{ ($status ?? '') === 'Completed' ? 'selected' : '' }}>Selesai / Completed</option>
                <option value="In Progress" {{ ($status ?? '') === 'In Progress' ? 'selected' : '' }}>Diproses / In Progress</option>
                <option value="Pending" {{ ($status ?? '') === 'Pending' ? 'selected' : '' }}>Menunggu / Pending</option>
                <option value="Cancelled" {{ ($status ?? '') === 'Cancelled' ? 'selected' : '' }}>Dibatalkan / Cancelled</option>
            </x-sa-input>
        </div>

        <div class="field">
            <label for="fd">Dari Tanggal</label>
            <x-sa-input type="date" id="fd" name="from_date" :value="$fromDate ?? ''" onchange="this.form.submit()" />
        </div>

        <div class="field">
            <label for="fe">Sampai Tanggal</label>
            <x-sa-input type="date" id="fe" name="to_date" :value="$toDate ?? ''" onchange="this.form.submit()" />
        </div>

        <div class="field grow">
            <label for="fq">Pencarian</label>
            <x-sa-input id="fq" name="q" :value="$q ?? ''" placeholder="ID, nama pelanggan, atau item..." />
        </div>

        <div class="field">
            <label>&nbsp;</label>
            <div class="toolbar-actions">
                <button type="submit" class="btn primary sm">Cari</button>
                @if(!empty($fromDate) || !empty($toDate) || !empty($jurusanId) || !empty($status) || !empty($q))
                    <x-sa-button variant="ghost" size="sm" :href="route('superadmin.reports.index')">Reset</x-sa-button>
                @endif
            </div>
        </div>
    </form>

    <x-sa-table>
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
                    $stTone = 'yellow';
                    if (in_array($stLower, ['completed', 'selesai'])) $stTone = 'green';
                    elseif (in_array($stLower, ['in progress', 'diproses', 'proses'])) $stTone = 'blue';
                    elseif (in_array($stLower, ['cancelled', 'dibatalkan', 'batal'])) $stTone = 'red';
                @endphp
                <tr>
                    <td><strong>#{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
                    <td class="num-cell">{{ $trx->created_at->format('d M Y') }}</td>
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
                        <x-sa-badge tone="blue">{{ $jurName }}</x-sa-badge>
                    </td>
                    <td>
                        <x-sa-badge tone="gray">{{ $pType }}</x-sa-badge>
                    </td>
                    <td class="r">
                        <strong>Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</strong>
                    </td>
                    <td>
                        <x-sa-badge :tone="$stTone">{{ $trx->status_pesanan }}</x-sa-badge>
                    </td>
                    <td>
                        <div class="row-actions">
                            <button class="icon-btn" title="Koreksi Transaksi" onclick='openCorrectModal({{ $trx->id }}, {{ $trx->total_harga }}, "{{ $trx->status_pesanan }}")'>
                                <x-sa-icon name="pencil" />
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <x-sa-empty
                    icon="receipt"
                    title="Tidak ada transaksi ditemukan"
                    message="Coba sesuaikan filter jurusan, status, rentang tanggal, atau kata kunci pencarian."
                    :colspan="9" />
            @endforelse
        </tbody>
    </x-sa-table>
</x-sa-card>
</div>

<!-- MODAL KOREKSI TRANSAKSI -->
<x-sa-dialog id="correctModal" formId="correctForm" title="Koreksi Data Transaksi" titleId="correctModalTitle" method="PATCH">
    <x-slot name="close">
        <button type="button" class="icon-btn" aria-label="Tutup" onclick="closeCorrectModal()"><x-sa-icon name="close" /></button>
    </x-slot>

    <x-slot name="body">
        <div class="form-grid">
            <div class="field {{ $errors->has('total_harga') ? 'has-error' : '' }}">
                <label for="k_total">Total Nilai (Rp)</label>
                <x-sa-input type="number" id="k_total" min="0" step="1000" name="total_harga" required />
                @error('total_harga')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="field {{ $errors->has('status_pesanan') ? 'has-error' : '' }}">
                <label for="k_status">Status Transaksi</label>
                <x-sa-input as="select" id="k_status" name="status_pesanan" required>
                    <option value="Pending">Menunggu (Pending)</option>
                    <option value="In Progress">Diproses (In Progress)</option>
                    <option value="Completed">Selesai (Completed)</option>
                    <option value="Cancelled">Dibatalkan (Cancelled)</option>
                </x-sa-input>
                @error('status_pesanan')<div class="field-error">{{ $message }}</div>@enderror
            </div>
        </div>
    </x-slot>

    <x-slot name="footer">
        <button type="button" class="btn ghost" onclick="closeCorrectModal()">Batal</button>
        <button type="submit" class="btn primary">Simpan Koreksi</button>
    </x-slot>
</x-sa-dialog>
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
