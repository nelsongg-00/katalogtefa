@extends('layouts.public')

@section('title', 'Konfirmasi Pemesanan - ' . $produk->nama_produk)

@section('content')
<div class="page-header" style="background: linear-gradient(135deg, #0a215e 0%, #1e3a8a 100%); color: #fff; padding: 40px 20px; text-align: center;">
    <h1 style="font-size: 26px; font-weight: 800; margin-bottom: 6px;">Konfirmasi Pemesanan TeFa</h1>
    <p style="color: #cbd5e1; font-size: 14px;">Periksa kembali detail pesanan Anda sebelum membuat pesanan resmi.</p>
</div>

<div class="container" style="max-width: 800px; margin: 40px auto; padding: 0 20px;">
    <div style="background: #ffffff; border-radius: 16px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01); border: 1px solid #e2e8f0; overflow: hidden;">
        
        <div style="display: flex; flex-wrap: wrap; gap: 24px; padding: 30px; border-bottom: 1px solid #f1f5f9;">
            <div style="width: 140px; height: 140px; border-radius: 12px; overflow: hidden; background: #f8fafc; flex-shrink: 0; display: flex; align-items: center; justify-content: center; border: 1px solid #e2e8f0;">
                @if($produk->foto_produk || $produk->foto)
                    <img src="{{ asset('storage/' . ($produk->foto_produk ?: $produk->foto)) }}" alt="{{ $produk->nama_produk }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <span style="font-size: 40px;">{{ str_contains(strtolower($produk->tipe ?? ''), 'jasa') ? '🤝' : '📦' }}</span>
                @endif
            </div>

            <div style="flex: 1; min-width: 250px;">
                <div style="display: flex; gap: 8px; margin-bottom: 8px; flex-wrap: wrap;">
                    @if($produk->jurusan)
                        <span style="font-size: 12px; font-weight: 700; color: #2563eb; background: #eff6ff; padding: 4px 12px; border-radius: 999px;">
                            {{ $produk->jurusan->nama_jurusan }}
                        </span>
                    @endif
                    <span style="font-size: 12px; font-weight: 700; color: #059669; background: #ecfdf5; padding: 4px 12px; border-radius: 999px;">
                        {{ $produk->tipe ?? 'Produk TeFa' }}
                    </span>
                </div>

                <h2 style="font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">{{ $produk->nama_produk }}</h2>
                <p style="font-size: 13.5px; color: #64748b; line-height: 1.5; margin-bottom: 12px;">{{ $produk->deskripsi }}</p>

                <div style="font-size: 20px; font-weight: 800; color: #2563eb;">
                    Rp {{ number_format($produk->harga, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('checkout.store', $produk->id) }}" style="padding: 30px;">
            @csrf
            
            <div style="margin-bottom: 20px;">
                <label for="jumlah" style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 6px;">
                    Jumlah Pesanan
                </label>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <input type="number" id="jumlah" name="jumlah" value="1" min="1" max="{{ $produk->stok > 0 ? $produk->stok : 99 }}" 
                           style="width: 120px; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 15px; font-weight: 700; color: #0f172a;"
                           onchange="updateTotal(this.value)">
                    <span style="font-size: 13px; color: #64748b;">
                        @if($produk->stok > 0)
                            Tersedia: <strong>{{ $produk->stok }}</strong> unit
                        @else
                            Layanan Jasa Unit Produksi
                        @endif
                    </span>
                </div>
            </div>

            <div style="background: #f8fafc; border-radius: 12px; padding: 18px 20px; margin-bottom: 24px; border: 1px solid #e2e8f0;">
                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 14px; color: #475569; margin-bottom: 6px;">
                    <span>Harga Satuan:</span>
                    <span>Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 17px; font-weight: 800; color: #0f172a; padding-top: 8px; border-top: 1px dashed #cbd5e1;">
                    <span>Total Pembayaran:</span>
                    <span id="displayTotal" style="color: #2563eb;">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; gap: 14px; flex-wrap: wrap;">
                <a href="{{ url()->previous() ?: route('produk') }}" style="color: #64748b; font-size: 14px; font-weight: 600; text-decoration: none;">
                    ← Batalkan & Kembali
                </a>
                <button type="submit" class="btn-blue" style="padding: 12px 28px; border-radius: 12px; font-size: 15px; font-weight: 700; background: #2563eb; color: #fff; border: none; cursor: pointer; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);">
                    Konfirmasi & Buat Pesanan Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const unitPrice = {{ $produk->harga }};
function updateTotal(qty) {
    const q = Math.max(1, parseInt(qty) || 1);
    const total = unitPrice * q;
    document.getElementById('displayTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
}
</script>
@endsection
