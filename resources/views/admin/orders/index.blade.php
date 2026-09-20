@extends('layouts.admin')

@section('title', 'Pencatatan Pesanan WhatsApp & Tracking — Admin Jurusan')

@section('content')
<div class="content-head" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 22px; font-weight: 800; color: #16234a; margin-bottom: 4px;">Pencatatan Pesanan WhatsApp</h1>
        <p style="color: #7a839c; font-size: 13.5px;">Input pesanan manual dari WhatsApp, dapatkan link pelacakan publik real-time untuk dibagikan ke pelanggan.</p>
    </div>
    <div>
        <a href="{{ route('admin.orders.create') }}" class="btn btn-primary" style="background: #16a34a; color: #fff; padding: 10px 20px; border-radius: 10px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-size: 13.5px; box-shadow: 0 4px 12px rgba(22,163,74,0.25);">
            <span>💬 +</span> Catat Pesanan Baru
        </a>
    </div>
</div>

@if(session('success'))
    <div style="background: #dcfce7; border-left: 4px solid #16a34a; color: #15803d; padding: 14px 18px; border-radius: 8px; font-weight: 600; font-size: 13.5px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <div>{{ session('success') }}</div>
        @if(session('new_order_code'))
            <a href="{{ route('order.track', session('new_order_code')) }}" target="_blank" style="background: #16a34a; color: #fff; padding: 6px 14px; border-radius: 6px; font-size: 12.5px; text-decoration: none; font-weight: 700;">
                Lihat Halaman Tracking &rarr;
            </a>
        @endif
    </div>
@endif

<div class="card" style="background: #fff; border-radius: 14px; border: 1px solid #e5e9f2; overflow: hidden; box-shadow: 0 2px 8px rgba(22,35,74,0.04);">
    <div style="padding: 16px 20px; border-bottom: 1px solid #e5e9f2; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <form method="GET" action="{{ route('admin.orders.index') }}" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kode TEFA / Nama / No HP..." style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 7px 14px; font-size: 13px; width: 240px;">
            <select name="status" style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 7px 12px; font-size: 13px;">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Antrean)</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Dalam Pengerjaan</option>
                <option value="review" {{ request('status') === 'review' ? 'selected' : '' }}>Review / Verifikasi</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
            <button type="submit" style="background: #2563eb; color: #fff; border: none; padding: 7px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">Filter</button>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.orders.index') }}" style="color: #64748b; font-size: 13px; text-decoration: underline; margin-left: 6px;">Reset</a>
            @endif
        </form>
        <span style="font-size: 13px; color: #64748b; font-weight: 600;">Total: {{ $orders->total() }} Pesanan</span>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
            <thead>
                <tr style="background: #f8fafd; border-bottom: 1px solid #e5e9f2; color: #64748b; font-size: 12px; font-weight: 700; text-transform: uppercase;">
                    <th style="padding: 14px 18px;">Kode Tracking</th>
                    <th style="padding: 14px 18px;">Pelanggan</th>
                    <th style="padding: 14px 18px;">Layanan & Total Biaya</th>
                    <th style="padding: 14px 18px;">Worker PJ</th>
                    <th style="padding: 14px 18px;">Status</th>
                    <th style="padding: 14px 18px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    @php
                        $trackingUrl = route('order.track', $order->order_code);
                        $cleanPhone = preg_replace('/[^0-9]/', '', $order->customer_phone);
                        if (str_starts_with($cleanPhone, '0')) {
                            $cleanPhone = '62' . substr($cleanPhone, 1);
                        }
                        $serviceName = $order->service->nama_layanan ?? 'Layanan Jasa';
                        $waText = "Halo Kak {$order->customer_name}! Pesanan pengerjaan {$serviceName} sudah kami proses. Kakak bisa memantau perkembangan pengerjaannya secara real-time kapan saja melalui link berikut: {$trackingUrl}";
                        $waUrl = "https://wa.me/{$cleanPhone}?text=" . rawurlencode($waText);
                    @endphp
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 14px 18px;">
                            <a href="{{ $trackingUrl }}" target="_blank" style="font-family: monospace; font-size: 13.5px; font-weight: 800; color: #2563eb; text-decoration: underline; background: #eff6ff; padding: 4px 8px; border-radius: 6px; display: inline-block;">
                                {{ $order->order_code }} ↗
                            </a>
                            <div style="font-size: 11px; color: #94a3b8; margin-top: 4px;">{{ $order->created_at->format('d M Y, H:i') }}</div>
                        </td>
                        <td style="padding: 14px 18px;">
                            <strong style="color: #0f172a; font-size: 13.5px; display: block;">{{ $order->customer_name }}</strong>
                            <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" style="color: #16a34a; font-size: 12px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                <span>📱</span> {{ $order->customer_phone }}
                            </a>
                        </td>
                        <td style="padding: 14px 18px;">
                            <div style="font-weight: 700; color: #1e293b;">{{ $serviceName }}</div>
                            <div style="color: #2563eb; font-weight: 800; font-size: 12.5px;">Rp {{ number_format($order->total_biaya, 0, ',', '.') }}</div>
                        </td>
                        <td style="padding: 14px 18px;">
                            @if($order->worker)
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <div style="width: 26px; height: 26px; border-radius: 999px; background: #2563eb; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700;">
                                        {{ strtoupper(substr($order->worker->name, 0, 1)) }}
                                    </div>
                                    <span style="font-weight: 600; color: #334155;">{{ $order->worker->name }}</span>
                                </div>
                            @else
                                <span style="color: #94a3b8; font-style: italic; font-size: 12px;">Belum ditugaskan</span>
                            @endif
                        </td>
                        <td style="padding: 14px 18px;">
                            @php
                                $badgeStyle = match($order->status) {
                                    'completed' => 'background: #ecfdf5; color: #059669;',
                                    'in_progress' => 'background: #eff6ff; color: #2563eb;',
                                    'review' => 'background: #fef3c7; color: #b45309;',
                                    'cancelled' => 'background: #fef2f2; color: #dc2626;',
                                    default => 'background: #f1f5f9; color: #475569;',
                                };
                                $statusLabel = match($order->status) {
                                    'completed' => 'Selesai',
                                    'in_progress' => 'Pengerjaan',
                                    'review' => 'Review File',
                                    'cancelled' => 'Dibatalkan',
                                    default => 'Antrean',
                                };
                            @endphp
                            <span style="{{ $badgeStyle }} font-weight: 700; font-size: 11.5px; padding: 4px 10px; border-radius: 999px; display: inline-block;">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td style="padding: 14px 18px; text-align: right;">
                            <div style="display: flex; justify-content: flex-end; align-items: center; gap: 6px; flex-wrap: wrap;">
                                <a href="{{ $waUrl }}" target="_blank" title="Kirim Pesan Pelacakan WhatsApp" style="background: #25d366; color: #fff; border: none; padding: 6px 10px; border-radius: 6px; font-size: 11.5px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                    💬 Kirim WA
                                </a>
                                <button type="button" onclick="copyTrackingText('{{ addslashes($waText) }}', this)" title="Salin Template Pesan" style="background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; padding: 6px 10px; border-radius: 6px; font-size: 11.5px; font-weight: 700; cursor: pointer;">
                                    📋 Salin
                                </button>
                                <button type="button" onclick="openStatusModal('{{ $order->id }}', '{{ $order->order_code }}', '{{ $order->status }}', '{{ $order->worker_id }}')" style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; padding: 6px 10px; border-radius: 6px; font-size: 11.5px; font-weight: 700; cursor: pointer;">
                                    ⚙️ Status
                                </button>
                                <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" onsubmit="return confirm('Hapus pesanan {{ $order->order_code }} beserta riwayat pelacakannya?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: #fff; color: #ef4444; border: 1px solid #fecaca; padding: 6px 10px; border-radius: 6px; font-size: 11.5px; font-weight: 700; cursor: pointer;">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 48px; color: #64748b;">
                            <div style="font-size: 32px; margin-bottom: 8px;">📦</div>
                            <div style="font-weight: 700; font-size: 15px; color: #1e293b;">Belum Ada Pesanan WhatsApp yang Dicatat</div>
                            <p style="margin: 6px 0 16px; font-size: 13px;">Klik tombol "Catat Pesanan Baru" untuk membuat order dan generate kode tracking publik.</p>
                            <a href="{{ route('admin.orders.create') }}" style="background: #16a34a; color: #fff; padding: 8px 18px; border-radius: 8px; font-weight: 700; text-decoration: none; font-size: 13px;">+ Buat Pesanan Baru</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
        <div style="padding: 16px 20px; border-top: 1px solid #e5e9f2;">
            {{ $orders->links() }}
        </div>
    @endif
</div>

<!-- Modal Update Status & Worker -->
<div id="statusModal" style="display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.6); z-index: 9999; align-items: center; justify-content: center; padding: 16px;">
    <div style="background: #fff; border-radius: 14px; width: 100%; max-width: 460px; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="padding: 16px 20px; border-bottom: 1px solid #e5e9f2; display: flex; justify-content: space-between; align-items: center;">
            <h4 style="margin: 0; font-size: 16px; font-weight: 700; color: #1e293b;" id="modalOrderTitle">Update Status Pesanan</h4>
            <button type="button" onclick="closeStatusModal()" style="background: none; border: none; font-size: 18px; cursor: pointer; color: #64748b;">✕</button>
        </div>
        <form id="modalStatusForm" method="POST" action="" style="padding: 20px;">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px;">Status Progres</label>
                <select name="status" id="modalStatusSelect" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 12px; font-size: 13.5px;">
                    <option value="pending">Pending (Antrean Masuk)</option>
                    <option value="in_progress">Dalam Pengerjaan (Worker Aktif)</option>
                    <option value="review">Review / Verifikasi File Hasil</option>
                    <option value="completed">Selesai (Pesanan Tuntas)</option>
                    <option value="cancelled">Dibatalkan</option>
                </select>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button type="button" onclick="closeStatusModal()" style="padding: 8px 16px; border: 1px solid #cbd5e1; background: #fff; border-radius: 6px; font-weight: 600; font-size: 13px; cursor: pointer;">Batal</button>
                <button type="submit" style="padding: 8px 18px; background: #2563eb; color: #fff; border: none; border-radius: 6px; font-weight: 700; font-size: 13px; cursor: pointer;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function copyTrackingText(text, btn) {
    navigator.clipboard.writeText(text).then(function() {
        const originalText = btn.innerText;
        btn.innerText = '✅ Tersalin!';
        btn.style.background = '#dcfce7';
        btn.style.color = '#15803d';
        setTimeout(() => {
            btn.innerText = originalText;
            btn.style.background = '#f1f5f9';
            btn.style.color = '#334155';
        }, 2000);
    }).catch(function() {
        alert('Gagal menyalin. Silakan salin manual.');
    });
}

function openStatusModal(orderId, orderCode, currentStatus) {
    document.getElementById('modalOrderTitle').innerText = 'Update Status: ' + orderCode;
    document.getElementById('modalStatusSelect').value = currentStatus;
    document.getElementById('modalStatusForm').action = '/admin/orders/' + orderId;
    document.getElementById('statusModal').style.display = 'flex';
}

function closeStatusModal() {
    document.getElementById('statusModal').style.display = 'none';
}
</script>
@endsection
