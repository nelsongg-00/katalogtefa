@extends('layouts.public')

@section('title', 'Checkout Produk Fisik — ' . $product->nama_produk)

@section('content')
<div class="page-header" style="background: linear-gradient(135deg, #0a215e 0%, #1e3a8a 100%); color: #fff; padding: 40px 20px; text-align: center;">
    <h1 style="font-size: 26px; font-weight: 800; margin-bottom: 6px;">Checkout Pemesanan Produk Fisik</h1>
    <p style="color: #cbd5e1; font-size: 14.5px; max-width: 600px; margin: 0 auto;">
        Layanan Teaching Factory SMKN 4 Tanjungpinang. Pembayaran dilakukan di kasir lab saat pengambilan barang.
    </p>
</div>

<div class="container" style="max-width: 900px; margin: 35px auto 60px; padding: 0 20px;">
    @if ($errors->any())
        <div style="background: #fef2f2; border-left: 4px solid #ef4444; color: #b91c1c; padding: 14px 18px; border-radius: 10px; font-size: 13.5px; margin-bottom: 24px;">
            <div style="font-weight: 700; margin-bottom: 4px;">Terjadi Kesalahan:</div>
            <ul style="margin: 0; padding-left: 18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('checkout.store', $product->id) }}" id="checkout-form">
        @csrf

        <div style="display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start;">
            
            <!-- KOLOM KIRI: Detail Produk & Metode -->
            <div style="display: flex; flex-direction: column; gap: 20px;">

                <!-- 1. Ringkasan Produk Fisik -->
                <div style="background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0; padding: 22px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                    <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                        <span>📦</span> Produk yang Dipesan
                    </h3>

                    <div style="display: flex; gap: 18px; align-items: flex-start; flex-wrap: wrap;">
                        <div style="width: 100px; height: 100px; border-radius: 10px; overflow: hidden; background: #f8fafc; border: 1px solid #e2e8f0; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                            @if($product->foto)
                                <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nama_produk }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <span style="font-size: 36px;">📦</span>
                            @endif
                        </div>

                        <div style="flex: 1; min-width: 200px;">
                            <span style="font-size: 11.5px; font-weight: 700; color: #2563eb; background: #eff6ff; padding: 3px 10px; border-radius: 999px; display: inline-block; margin-bottom: 6px;">
                                {{ $product->jurusan->nama_jurusan ?? 'Unit Produksi TeFa' }}
                            </span>
                            <h4 style="font-size: 17px; font-weight: 800; color: #0f172a; margin-bottom: 6px;">
                                {{ $product->nama_produk }}
                            </h4>
                            <div style="font-size: 16px; font-weight: 800; color: #2563eb; margin-bottom: 8px;">
                                Rp {{ number_format($product->harga, 0, ',', '.') }} <span style="font-size: 12px; color: #64748b; font-weight: 500;">/ unit</span>
                            </div>
                            <div style="font-size: 12px; color: #64748b;">
                                Stok Tersedia: <strong style="color: {{ $product->stok > 0 ? '#10b981' : '#ef4444' }};">{{ $product->stok }} unit</strong>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 18px; padding-top: 16px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                        <label for="jumlah_input" style="font-size: 13.5px; font-weight: 700; color: #334155;">
                            Tentukan Jumlah Pembelian:
                        </label>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <button type="button" onclick="adjustQty(-1)" style="width: 34px; height: 34px; border-radius: 8px; border: 1px solid #cbd5e1; background: #f8fafc; font-weight: 800; font-size: 16px; cursor: pointer;">-</button>
                            <input type="number" id="jumlah_input" name="jumlah" value="1" min="1" max="{{ max(1, $product->stok) }}" 
                                   style="width: 70px; text-align: center; padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 15px; font-weight: 700; color: #0f172a;"
                                   oninput="calculateTotal()">
                            <button type="button" onclick="adjustQty(1)" style="width: 34px; height: 34px; border-radius: 8px; border: 1px solid #cbd5e1; background: #f8fafc; font-weight: 800; font-size: 16px; cursor: pointer;">+</button>
                        </div>
                    </div>
                </div>

                <!-- 2. Section Metode Pengambilan (Ambil di Tempat / Dynamic Pickup) -->
                <div style="background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0; padding: 22px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                    <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
                        <span>📍</span> Metode Pengambilan Barang
                    </h3>

                    <!-- Card Ambil di Tempat Terpilih -->
                    <div style="background: #f0fdf4; border: 2px solid #16a34a; border-radius: 12px; padding: 18px; position: relative;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;">
                            <div style="display: flex; gap: 14px; align-items: flex-start;">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                                    🏢
                                </div>
                                <div>
                                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                        <strong style="font-size: 15px; color: #14532d;">Ambil di Tempat (Self Pick-up)</strong>
                                        <span style="background: #16a34a; color: #fff; font-size: 10.5px; font-weight: 800; padding: 2px 8px; border-radius: 999px;">Terpilih</span>
                                    </div>
                                    <div style="font-size: 13.5px; color: #166534; font-weight: 700; margin-bottom: 4px;">
                                        📍 Lokasi Pengambilan: <span style="text-decoration: underline;">{{ $lokasiPengambilan }}</span>
                                    </div>
                                    <p style="margin: 0; font-size: 12.5px; color: #334155; line-height: 1.5;">
                                        SMKN 4 Tanjungpinang — Jl. Nusantara No.KM.14 Batu IX. Barang dapat diambil di lab jurusan setelah dikonfirmasi oleh Admin.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Section Metode Pembayaran (COD Kasir Lab Terpilih) -->
                <div style="background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0; padding: 22px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                    <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
                        <span>💳</span> Metode Pembayaran
                    </h3>

                    <!-- Card COD Terpilih -->
                    <div style="background: #eff6ff; border: 2px solid #2563eb; border-radius: 12px; padding: 18px;">
                        <div style="display: flex; gap: 14px; align-items: flex-start;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: #dbeafe; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
                                💵
                            </div>
                            <div>
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                    <strong style="font-size: 15px; color: #1e3a8a;">Bayar di Tempat / Cash on Delivery (COD)</strong>
                                    <span style="background: #2563eb; color: #fff; font-size: 10.5px; font-weight: 800; padding: 2px 8px; border-radius: 999px;">Default</span>
                                </div>
                                <p style="margin: 0; font-size: 13px; color: #1e40af; line-height: 1.5;">
                                    Pembayaran tunai dilakukan langsung di kasir/lab jurusan saat mengambil produk fisik. Tidak perlu transfer sekarang.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Kontak & Catatan Pelanggan -->
                <div style="background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0; padding: 22px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                    <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 14px;">
                        📝 Informasi Penerima & Catatan
                    </h3>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px;">
                            Nomor WhatsApp Pemesan (Untuk Notifikasi Pengambilan)
                        </label>
                        <input type="text" name="customer_phone" value="{{ old('customer_phone', auth()->user()->phone ?? '') }}" placeholder="Contoh: 081234567890"
                               style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13.5px; box-sizing: border-box;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px;">
                            Catatan untuk Admin Jurusan (Opsional)
                        </label>
                        <textarea name="catatan_pelanggan" rows="3" placeholder="Contoh: Ambil hari Rabu siang jam istirahat, tolong pastikan packaging rapi..."
                                  style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13.5px; box-sizing: border-box;">{{ old('catatan_pelanggan') }}</textarea>
                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN: Ringkasan Total & Konfirmasi -->
            <div style="position: sticky; top: 90px; background: #ffffff; border-radius: 14px; border: 1px solid #e2e8f0; padding: 24px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);">
                <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 18px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                    Rincian Pembayaran
                </h3>

                <div style="display: flex; justify-content: space-between; font-size: 13.5px; color: #64748b; margin-bottom: 10px;">
                    <span>Harga Satuan:</span>
                    <span style="font-weight: 600; color: #1e293b;">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                </div>

                <div style="display: flex; justify-content: space-between; font-size: 13.5px; color: #64748b; margin-bottom: 10px;">
                    <span>Jumlah Unit:</span>
                    <span style="font-weight: 700; color: #1e293b;" id="summary_qty">1 unit</span>
                </div>

                <div style="display: flex; justify-content: space-between; font-size: 13.5px; color: #64748b; margin-bottom: 10px;">
                    <span>Ongkos Pengambilan:</span>
                    <span style="font-weight: 700; color: #10b981;">Gratis (Ambil Sendiri)</span>
                </div>

                <div style="border-top: 1px dashed #e2e8f0; margin: 16px 0; padding-top: 14px; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 15px; font-weight: 800; color: #0f172a;">Total Tagihan COD:</span>
                    <span style="font-size: 20px; font-weight: 800; color: #2563eb;" id="summary_total">
                        Rp {{ number_format($product->harga, 0, ',', '.') }}
                    </span>
                </div>

                <div style="background: #f8fafc; border-radius: 8px; padding: 12px; font-size: 12px; color: #64748b; line-height: 1.4; margin-bottom: 20px;">
                    💡 Anda cukup membawa uang pas sebesar <strong id="summary_cod_amount" style="color: #0f172a;">Rp {{ number_format($product->harga, 0, ',', '.') }}</strong> saat mengambil barang di lab jurusan.
                </div>

                <button type="submit" style="width: 100%; padding: 14px; border: none; border-radius: 10px; background: #2563eb; color: #ffffff; font-size: 15px; font-weight: 800; cursor: pointer; transition: background 0.2s; box-shadow: 0 4px 12px rgba(37,99,235,0.25);">
                    Konfirmasi Pesanan (COD) &rarr;
                </button>

                <div style="text-align: center; margin-top: 14px;">
                    <a href="{{ route('produk') }}" style="font-size: 13px; color: #64748b; text-decoration: none;">
                        &larr; Batalkan dan kembali ke katalog
                    </a>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
const unitPrice = {{ $product->harga }};
const maxStock = {{ max(1, $product->stok) }};

function formatRupiah(amount) {
    return 'Rp ' + amount.toLocaleString('id-ID');
}

function adjustQty(delta) {
    const input = document.getElementById('jumlah_input');
    let current = parseInt(input.value) || 1;
    let next = current + delta;
    if (next < 1) next = 1;
    if (next > maxStock) next = maxStock;
    input.value = next;
    calculateTotal();
}

function calculateTotal() {
    const input = document.getElementById('jumlah_input');
    let qty = parseInt(input.value) || 1;
    if (qty < 1) qty = 1;
    if (qty > maxStock) qty = maxStock;
    input.value = qty;

    const total = qty * unitPrice;
    document.getElementById('summary_qty').innerText = qty + ' unit';
    document.getElementById('summary_total').innerText = formatRupiah(total);
    document.getElementById('summary_cod_amount').innerText = formatRupiah(total);
}
</script>
@endsection
