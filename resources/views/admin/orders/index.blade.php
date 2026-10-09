@extends('layouts.admin')

@section('title', 'Manajemen Pesanan — Admin Jurusan')

@section('content')
<div class="content-head">
    <div>
        <h1>Manajemen Pesanan TeFa</h1>
        <p>Kelola pesanan produk fisik (COD & Ambil di Tempat) dan pencatatan pesanan jasa WhatsApp jurusan.</p>
    </div>
    <div>
        <a href="{{ route('admin.orders.create') }}" class="btn-gold" style="background:var(--green);box-shadow:0 4px 14px rgba(22,163,74,.25)">Catat Pesanan Jasa Baru</a>
    </div>
</div>

@if(session('success'))
    <div class="card" style="padding:14px 18px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;border-left:4px solid var(--green);margin-bottom:20px">
        <div style="font-weight:600;font-size:13.5px;color:var(--green-ink)">{{ session('success') }}</div>
        @if(session('new_order_code'))
            <a href="{{ route('order.track', session('new_order_code')) }}" target="_blank" class="btn sm" style="background:var(--green);color:#fff">Lihat Halaman Tracking</a>
        @endif
    </div>
@endif

<div style="display:flex;gap:10px;margin-bottom:20px;border-bottom:2px solid var(--line);padding-bottom:2px">
    <a href="{{ route('admin.orders.index') }}" class="tab {{ !request('tipe') ? 'active' : '' }}" style="border-bottom:3px solid {{ !request('tipe') ? 'var(--blue)' : 'transparent' }};color:{{ !request('tipe') ? 'var(--blue)' : 'var(--muted)' }}">Semua Pesanan</a>
    <a href="{{ route('admin.orders.index', ['tipe' => 'fisik']) }}" class="tab {{ request('tipe') === 'fisik' ? 'active' : '' }}" style="border-bottom:3px solid {{ request('tipe') === 'fisik' ? 'var(--blue)' : 'transparent' }};color:{{ request('tipe') === 'fisik' ? 'var(--blue)' : 'var(--muted)' }}">Pesanan Produk Fisik (COD)</a>
    <a href="{{ route('admin.orders.index', ['tipe' => 'jasa']) }}" class="tab {{ request('tipe') === 'jasa' ? 'active' : '' }}" style="border-bottom:3px solid {{ request('tipe') === 'jasa' ? 'var(--blue)' : 'transparent' }};color:{{ request('tipe') === 'jasa' ? 'var(--blue)' : 'var(--muted)' }}">Pesanan Layanan Jasa WA</a>
</div>

<div class="card">
    <div class="card-head" style="flex-wrap:wrap">
        <form method="GET" action="{{ route('admin.orders.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
            @if(request('tipe'))<input type="hidden" name="tipe" value="{{ request('tipe') }}">@endif
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kode / Nama / No HP..." class="form-control" style="width:230px;padding:7px 14px">
            <select name="status" class="form-control" style="padding:7px 12px;width:auto">
                <option value="">Semua Status</option>
                <option value="menunggu_konfirmasi" {{ request('status') === 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                <option value="sedang_dikemas" {{ request('status') === 'sedang_dikemas' ? 'selected' : '' }}>Sedang Dikemas</option>
                <option value="bisa_diambil" {{ request('status') === 'bisa_diambil' ? 'selected' : '' }}>Bisa Diambil di Lab</option>
                <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai / Lunas</option>
                <option value="dibatalkan" {{ request('status') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
            <button type="submit" class="btn primary sm">Filter</button>
            @if(request('search') || request('status') || request('tipe'))
                <a href="{{ route('admin.orders.index') }}" style="color:var(--muted);font-size:13px;text-decoration:underline;margin-left:6px">Reset</a>
            @endif
        </form>
        <span style="font-size:13px;color:var(--muted);font-weight:600">Total: {{ $orders->total() }} Pesanan</span>
    </div>

    <div class="table-responsive">
        <table class="order-table">
            <thead>
                <tr>
                    <th>Kode & Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Item & Total Tagihan</th>
                    <th>Lokasi / Penanggung Jawab</th>
                    <th>Status</th>
                    <th style="text-align:right">Aksi Workflow</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    @php
                        $isPhysical = $order->isPhysicalProduct();
                        $trackingUrl = route('order.track', $order->order_code);
                        $cleanPhone = preg_replace('/[^0-9]/', '', $order->customer_phone);
                        if (str_starts_with($cleanPhone, '0')) {
                            $cleanPhone = '62' . substr($cleanPhone, 1);
                        }
                    @endphp
                    <tr style="background: {{ $order->status === 'menunggu_konfirmasi' ? '#fffdf7' : 'inherit' }}">
                        <td>
                            <a href="{{ $trackingUrl }}" target="_blank" class="order-id" style="background:var(--blue-l);padding:3px 8px;border-radius:var(--r-md);display:inline-block;font-size:13.5px;font-weight:800">
                                {{ $order->order_code }}
                            </a>
                            <span class="order-date">{{ $order->created_at->format('d M Y, H:i') }}</span>
                            <span style="font-size:11px;font-weight:700;color:{{ $isPhysical ? 'var(--green-ink)' : 'var(--blue)' }}">
                                {{ $isPhysical ? 'Produk Fisik (COD)' : 'Jasa WA' }}
                            </span>
                        </td>
                        <td>
                            <div class="cust">
                                <div class="cust-av">{{ strtoupper(substr($order->customer_name ?? 'G', 0, 2)) }}</div>
                                <div>
                                    <b>{{ $order->customer_name }}</b>
                                    <small>{{ $order->customer_phone }}</small>
                                </div>
                            </div>
                            @if($order->catatan_pelanggan || $order->catatan)
                                <small style="display:block;color:var(--muted);font-size:11.5px;margin-top:4px;font-style:italic">
                                    "{{ Str::limit($order->catatan_pelanggan ?: $order->catatan, 45) }}"
                                </small>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight:700;color:var(--ink)">
                                {{ $order->product->nama_produk ?? ($order->service->nama_layanan ?? 'Item TeFa') }}
                            </div>
                            @if($isPhysical)
                                <div style="font-size:12px;color:var(--muted)">Jumlah: <strong>{{ $order->jumlah }} unit</strong></div>
                            @endif
                            <div style="color:var(--blue);font-weight:800;font-size:13px;margin-top:2px">
                                Rp {{ number_format($order->total_harga ?: $order->total_biaya, 0, ',', '.') }}
                            </div>
                        </td>
                        <td>
                            @if($isPhysical)
                                <div style="font-size:12.5px;font-weight:700;color:var(--green-ink)">{{ $order->lokasi_pengambilan ?? 'Lab Jurusan' }}</div>
                                <div style="font-size:11.5px;color:var(--muted)">Ambil Sendiri di Lab</div>
                            @else
                                @if($order->worker)
                                    <div style="font-weight:600;color:var(--ink-2)">{{ $order->worker->name }}</div>
                                @else
                                    <span style="color:var(--faint);font-style:italic;font-size:12px">Tim Produksi</span>
                                @endif
                            @endif
                        </td>
                        <td>
                            @php
                                $badgeClass = match($order->status) {
                                    'selesai', 'completed' => 'badge-done',
                                    'bisa_diambil' => 'badge-review',
                                    'sedang_dikemas', 'in_progress' => 'badge-wait',
                                    'dibatalkan', 'cancelled' => 'badge-cancel',
                                    default => 'b-gray',
                                };
                                $statusLabel = match($order->status) {
                                    'selesai', 'completed' => 'Selesai (Lunas)',
                                    'bisa_diambil' => 'Siap Diambil',
                                    'sedang_dikemas' => 'Sedang Dikemas',
                                    'in_progress' => 'Pengerjaan',
                                    'dibatalkan', 'cancelled' => 'Dibatalkan',
                                    default => 'Menunggu Konfirmasi',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                        </td>
                        <td style="text-align:right">
                            @if($isPhysical)
                                <div class="row-actions">
                                    @if($order->status === 'menunggu_konfirmasi')
                                        <form method="POST" action="{{ route('admin.orders.physicalStatus', $order) }}" style="display:inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="sedang_dikemas">
                                            <button type="submit" class="btn-action-validate">Terima & Proses</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.orders.physicalStatus', $order) }}" onsubmit="return confirm('Batalkan pesanan ini? Stok produk akan dikembalikan.')" style="display:inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="dibatalkan">
                                            <button type="submit" class="btn-sm-delete">Tolak</button>
                                        </form>
                                    @elseif($order->status === 'sedang_dikemas')
                                        <form method="POST" action="{{ route('admin.orders.physicalStatus', $order) }}" style="display:inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="bisa_diambil">
                                            <button type="submit" class="btn-action-assign">Siap Diambil</button>
                                        </form>
                                    @elseif($order->status === 'bisa_diambil')
                                        <form method="POST" action="{{ route('admin.orders.physicalStatus', $order) }}" onsubmit="return confirm('Pastikan pembayaran tunai (COD) sebesar Rp {{ number_format($order->total_harga ?: $order->total_biaya, 0, ',', '.') }} sudah diterima di kasir lab.')" style="display:inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="selesai">
                                            <button type="submit" class="btn-action-validate">Selesaikan Transaksi (COD Lunas)</button>
                                        </form>
                                    @elseif($order->status === 'selesai')
                                        <span style="color:var(--green-ink);font-weight:700;font-size:11.5px">Transaksi Tuntas</span>
                                    @endif
                                    <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" onsubmit="return confirm('Hapus data pesanan ini?')" style="display:inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="icon-btn del" title="Hapus">
                                            <svg class="i i-16" viewBox="0 0 24 24"><path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2m3 0v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6"/></svg>
                                        </button>
                                    </form>
                                </div>
                            @else
                                @php
                                    $serviceName = $order->service->nama_layanan ?? 'Layanan Jasa';
                                    $waText = "Halo Kak {$order->customer_name}! Pesanan pengerjaan {$serviceName} sudah kami proses. Kakak bisa memantau perkembangan pengerjaannya secara real-time kapan saja melalui link berikut: {$trackingUrl}";
                                    $waUrl = "https://wa.me/{$cleanPhone}?text=" . rawurlencode($waText);
                                @endphp
                                <div class="row-actions">
                                    <a href="{{ $waUrl }}" target="_blank" class="btn-action-assign" style="background:#25d366">WA</a>
                                    <button type="button" onclick="openStatusModal('{{ $order->id }}', '{{ $order->order_code }}', '{{ $order->status }}')" class="btn-sm-edit">Status</button>
                                    <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" onsubmit="return confirm('Hapus pesanan ini?')" style="display:inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="icon-btn del" title="Hapus">
                                            <svg class="i i-16" viewBox="0 0 24 24"><path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2m3 0v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6"/></svg>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty">
                            <div class="empty-ic">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            </div>
                            <b>Belum Ada Pesanan yang Terdaftar</b>
                            Pesanan produk fisik dari website publik atau input manual WhatsApp akan tampil di sini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
        <div class="pagination-wrapper">{{ $orders->links() }}</div>
    @endif
</div>

<div class="modal-overlay" id="statusModal">
    <div class="modal-box">
        <div class="modal-head">
            <h3 id="modalOrderTitle">Update Status Pesanan</h3>
            <button type="button" class="modal-close" onclick="closeStatusModal()">&times;</button>
        </div>
        <form id="modalStatusForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Status Progres</label>
                    <select name="status" id="modalStatusSelect" class="form-control">
                        <option value="pending">Pending (Antrean Masuk)</option>
                        <option value="in_progress">Dalam Pengerjaan (Worker Aktif)</option>
                        <option value="review">Review / Verifikasi File Hasil</option>
                        <option value="completed">Selesai (Pesanan Tuntas)</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                </div>
                <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:20px">
                    <button type="button" class="btn-outline" onclick="closeStatusModal()">Batal</button>
                    <button type="submit" class="btn-gold">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function openStatusModal(orderId, orderCode, currentStatus) {
    document.getElementById('modalOrderTitle').innerText = 'Update Status: ' + orderCode;
    document.getElementById('modalStatusSelect').value = currentStatus;
    document.getElementById('modalStatusForm').action = '/admin/orders/' + orderId;
    document.getElementById('statusModal').classList.add('active');
}
function closeStatusModal() {
    document.getElementById('statusModal').classList.remove('active');
}
</script>
@endsection
