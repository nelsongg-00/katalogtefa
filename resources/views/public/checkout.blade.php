@extends('layouts.public')

@section('title', 'Checkout Produk Fisik — ' . $product->nama_produk)

@section('content')
<style>
    .co {
        --blue: #0b60cf;
        --blue-dark: #094ca3;
        --blue-tint: #eef4fd;
        --green: #15803d;
        --green-tint: #effaf3;
        --red: #b91c1c;
        --ink: #0f172a;
        --text: #334155;
        --muted: #64748b;
        --line: #e2e8f0;
        --bg: #f5f8fc;
        background: var(--bg);
        color: var(--ink);
        padding-bottom: 64px;
    }
    .co *, .co *::before, .co *::after { box-sizing: border-box; }

    /* Banner */
    .co-banner { background: #0b60cf; color: #fff; padding: 44px 20px 90px; }
    .co-banner__inner { max-width: 1040px; margin: 0 auto; }
    .co-crumb { margin: 0 0 14px; font-size: 13px; color: rgba(255,255,255,.75); }
    .co-crumb a { color: #fff; text-decoration: none; }
    .co-crumb a:hover { text-decoration: underline; }
    .co-banner h1 { margin: 0 0 8px; font-size: clamp(1.5rem, 3vw, 2rem); font-weight: 800; letter-spacing: -.4px; line-height: 1.2; }
    .co-banner p { margin: 0; max-width: 620px; font-size: 14.5px; line-height: 1.65; color: rgba(255,255,255,.85); }

    .co-wrap { max-width: 1040px; margin: -56px auto 0; padding: 0 20px; position: relative; }

    /* Alert */
    .co-alert { margin-bottom: 18px; padding: 14px 18px; background: #fff; border: 1px solid #fecaca; border-left: 4px solid #dc2626; border-radius: 12px; font-size: 13.5px; color: var(--red); }
    .co-alert strong { display: block; margin-bottom: 4px; }
    .co-alert ul { margin: 0; padding-left: 18px; }

    .co-grid { display: grid; grid-template-columns: minmax(0, 1fr) 340px; gap: 22px; align-items: start; }
    .co-main { display: flex; flex-direction: column; gap: 18px; }

    /* Card */
    .co-card { background: #fff; border: 1px solid var(--line); border-radius: 16px; padding: 24px; box-shadow: 0 1px 2px rgba(15,23,42,.04), 0 8px 24px -16px rgba(15,23,42,.12); }
    .co-card__head { display: flex; align-items: center; gap: 12px; margin-bottom: 18px; }
    .co-card__ico { width: 36px; height: 36px; flex: 0 0 auto; display: grid; place-items: center; border-radius: 10px; background: var(--blue-tint); color: var(--blue); }
    .co-card__ico svg { width: 19px; height: 19px; }
    .co-card__head h2 { margin: 0; font-size: 1.02rem; font-weight: 800; }
    .co-card__head span.step { margin-left: auto; font-size: 12px; font-weight: 700; color: var(--muted); }

    /* Produk */
    .co-product { display: flex; gap: 18px; align-items: flex-start; }
    .co-product__img { width: 104px; height: 104px; flex: 0 0 auto; display: grid; place-items: center; overflow: hidden; background: var(--bg); border: 1px solid var(--line); border-radius: 12px; color: #94a3b8; }
    .co-product__img img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .co-product__img svg { width: 36px; height: 36px; }
    .co-product__body { min-width: 0; flex: 1; }
    .co-badge { display: inline-block; margin-bottom: 8px; padding: 3px 11px; font-size: 11.5px; font-weight: 700; color: var(--blue); background: var(--blue-tint); border-radius: 999px; }
    .co-product h3 { margin: 0 0 6px; font-size: 1.1rem; font-weight: 800; line-height: 1.3; }
    .co-price { font-size: 1.1rem; font-weight: 800; color: var(--blue); }
    .co-price small { font-size: 12px; font-weight: 500; color: var(--muted); }
    .co-stock { margin-top: 6px; font-size: 12.5px; color: var(--muted); }
    .co-stock strong.ok { color: var(--green); }
    .co-stock strong.out { color: var(--red); }

    .co-qty { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-top: 20px; padding-top: 18px; border-top: 1px solid #f1f5f9; }
    .co-qty label { font-size: 13.5px; font-weight: 700; color: var(--text); }
    .co-stepper { display: inline-flex; align-items: center; border: 1px solid #cbd5e1; border-radius: 10px; overflow: hidden; background: #fff; }
    .co-stepper button { width: 38px; height: 38px; border: 0; background: #f8fafc; color: var(--ink); font-size: 18px; font-weight: 700; line-height: 1; cursor: pointer; }
    .co-stepper button:hover { background: var(--blue-tint); color: var(--blue); }
    .co-stepper input { width: 62px; height: 38px; border: 0; border-left: 1px solid #cbd5e1; border-right: 1px solid #cbd5e1; border-radius: 0; text-align: center; font-size: 15px; font-weight: 700; color: var(--ink); -moz-appearance: textfield; appearance: textfield; }
    .co-stepper input::-webkit-outer-spin-button, .co-stepper input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

    /* Opsi terpilih */
    .co-option { display: flex; gap: 14px; align-items: flex-start; padding: 16px 18px; border-radius: 12px; border: 1.5px solid var(--green); background: var(--green-tint); }
    .co-option--blue { border-color: var(--blue); background: var(--blue-tint); }
    .co-option__check { width: 22px; height: 22px; flex: 0 0 auto; margin-top: 1px; display: grid; place-items: center; border-radius: 50%; background: var(--green); color: #fff; }
    .co-option--blue .co-option__check { background: var(--blue); }
    .co-option__check svg { width: 13px; height: 13px; }
    .co-option__title { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin-bottom: 4px; }
    .co-option__title strong { font-size: 14.5px; color: var(--ink); }
    .co-tag { padding: 2px 9px; font-size: 10.5px; font-weight: 800; letter-spacing: .4px; color: #fff; background: var(--green); border-radius: 999px; }
    .co-option--blue .co-tag { background: var(--blue); }
    .co-option p { margin: 0; font-size: 13px; line-height: 1.6; color: var(--text); }
    .co-option .loc { margin-bottom: 4px; font-size: 13.5px; font-weight: 700; color: #14532d; }

    /* Form */
    .co-field + .co-field { margin-top: 16px; }
    .co-field label { display: block; margin-bottom: 6px; font-size: 13px; font-weight: 700; color: var(--text); }
    .co-field .hint { display: block; margin-top: 5px; font-size: 12px; color: var(--muted); }
    .co-field input, .co-field textarea { width: 100%; padding: 11px 14px; font: inherit; font-size: 14px; color: var(--ink); background: #fff; border: 1px solid #cbd5e1; border-radius: 10px; }
    .co-field textarea { resize: vertical; min-height: 92px; line-height: 1.55; }
    .co-field input::placeholder, .co-field textarea::placeholder { color: #94a3b8; }
    .co-field input:focus, .co-field textarea:focus, .co-stepper input:focus { outline: 2px solid var(--blue); outline-offset: 0; border-color: var(--blue); }
    .co-stepper button:focus-visible, .co-submit:focus-visible, .co-back:focus-visible { outline: 2px solid var(--blue); outline-offset: 2px; }

    /* Ringkasan */
    .co-summary { position: sticky; top: 90px; }
    .co-summary h2 { margin: 0 0 16px; padding-bottom: 14px; font-size: 1.02rem; font-weight: 800; border-bottom: 1px solid #f1f5f9; }
    .co-row { display: flex; justify-content: space-between; gap: 12px; margin-bottom: 11px; font-size: 13.5px; color: var(--muted); }
    .co-row b { font-weight: 700; color: var(--ink); text-align: right; }
    .co-row b.free { color: var(--green); }
    .co-total { display: flex; justify-content: space-between; align-items: baseline; gap: 10px; margin: 16px 0; padding-top: 16px; border-top: 1px dashed #cbd5e1; }
    .co-total span { font-size: 14.5px; font-weight: 800; }
    .co-total strong { font-size: 1.4rem; font-weight: 800; color: var(--blue); letter-spacing: -.3px; }
    .co-note { margin-bottom: 18px; padding: 12px 14px; font-size: 12.5px; line-height: 1.6; color: var(--text); background: var(--bg); border-radius: 10px; }
    .co-note strong { color: var(--ink); }
    .co-submit { width: 100%; padding: 14px; font: inherit; font-size: 15px; font-weight: 800; color: #fff; background: var(--blue); border: 0; border-radius: 12px; cursor: pointer; }
    .co-submit:hover { background: var(--blue-dark); }
    .co-submit:disabled { background: #94a3b8; cursor: not-allowed; }
    .co-back { display: block; margin-top: 14px; text-align: center; font-size: 13px; color: var(--muted); text-decoration: none; }
    .co-back:hover { color: var(--blue); }

    @media (max-width: 860px) {
        .co-grid { grid-template-columns: 1fr; }
        .co-summary { position: static; }
    }
    @media (max-width: 520px) {
        .co-banner { padding: 34px 18px 80px; }
        .co-card { padding: 18px; }
        .co-product { flex-direction: column; }
        .co-qty { flex-direction: column; align-items: flex-start; }
    }
</style>

<div class="co">
    <header class="co-banner">
        <div class="co-banner__inner">
            <p class="co-crumb"><a href="{{ route('produk') }}">Katalog Produk</a> &nbsp;/&nbsp; Checkout</p>
            <h1>Checkout Pemesanan Produk Fisik</h1>
            <p>Layanan Teaching Factory SMKN 4 Tanjungpinang. Pembayaran dilakukan di kasir lab saat pengambilan barang.</p>
        </div>
    </header>

    <div class="co-wrap">
        @if ($errors->any())
            <div class="co-alert" role="alert">
                <strong>Terjadi kesalahan:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('checkout.store', $product->id) }}" id="checkout-form">
            @csrf

            <div class="co-grid">

                {{-- ============ KOLOM KIRI ============ --}}
                <div class="co-main">

                    {{-- 1. Produk --}}
                    <section class="co-card">
                        <div class="co-card__head">
                            <div class="co-card__ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8l-9-5-9 5v8l9 5 9-5V8z"/><path d="M3.3 7.5L12 12.5l8.7-5M12 22V12.5"/></svg>
                            </div>
                            <h2>Produk yang Dipesan</h2>
                            <span class="step">1 dari 4</span>
                        </div>

                        <div class="co-product">
                            <div class="co-product__img">
                                @if($product->foto)
                                    <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nama_produk }}">
                                @else
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 8l-9-5-9 5v8l9 5 9-5V8z"/><path d="M3.3 7.5L12 12.5l8.7-5M12 22V12.5"/></svg>
                                @endif
                            </div>
                            <div class="co-product__body">
                                <span class="co-badge">{{ $product->jurusan->nama_jurusan ?? 'Unit Produksi TeFa' }}</span>
                                <h3>{{ $product->nama_produk }}</h3>
                                <div class="co-price">Rp {{ number_format($product->harga, 0, ',', '.') }} <small>/ unit</small></div>
                                <div class="co-stock">
                                    Stok tersedia:
                                    <strong class="{{ $product->stok > 0 ? 'ok' : 'out' }}">{{ $product->stok }} unit</strong>
                                </div>
                            </div>
                        </div>

                        <div class="co-qty">
                            <label for="jumlah_input">Jumlah pembelian</label>
                            <div class="co-stepper">
                                <button type="button" onclick="adjustQty(-1)" aria-label="Kurangi jumlah">&minus;</button>
                                <input type="number" id="jumlah_input" name="jumlah" value="{{ old('jumlah', 1) }}" min="1" max="{{ max(1, $product->stok) }}" inputmode="numeric" oninput="calculateTotal()">
                                <button type="button" onclick="adjustQty(1)" aria-label="Tambah jumlah">+</button>
                            </div>
                        </div>
                    </section>

                    {{-- 2. Pengambilan --}}
                    <section class="co-card">
                        <div class="co-card__head">
                            <div class="co-card__ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.2 7-11.5A7 7 0 005 9.5C5 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/></svg>
                            </div>
                            <h2>Metode Pengambilan Barang</h2>
                            <span class="step">2 dari 4</span>
                        </div>

                        <div class="co-option">
                            <div class="co-option__check" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
                            </div>
                            <div>
                                <div class="co-option__title">
                                    <strong>Ambil di Tempat (Self Pick-up)</strong>
                                    <span class="co-tag">TERPILIH</span>
                                </div>
                                <div class="loc">Lokasi pengambilan: {{ $lokasiPengambilan }}</div>
                                <p>SMKN 4 Tanjungpinang — Jl. Nusantara No.KM.14 Batu IX. Barang dapat diambil di lab jurusan setelah dikonfirmasi oleh Admin.</p>
                            </div>
                        </div>
                    </section>

                    {{-- 3. Pembayaran --}}
                    <section class="co-card">
                        <div class="co-card__head">
                            <div class="co-card__ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="3"/><path d="M2 10h20M6 15h4"/></svg>
                            </div>
                            <h2>Metode Pembayaran</h2>
                            <span class="step">3 dari 4</span>
                        </div>

                        <div class="co-option co-option--blue">
                            <div class="co-option__check" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
                            </div>
                            <div>
                                <div class="co-option__title">
                                    <strong>Bayar di Tempat / Cash on Delivery (COD)</strong>
                                    <span class="co-tag">DEFAULT</span>
                                </div>
                                <p>Pembayaran tunai dilakukan langsung di kasir/lab jurusan saat mengambil produk fisik. Tidak perlu transfer sekarang.</p>
                            </div>
                        </div>
                    </section>

                    {{-- 4. Kontak & catatan --}}
                    <section class="co-card">
                        <div class="co-card__head">
                            <div class="co-card__ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20h4L19 9a2.8 2.8 0 00-4-4L4 16v4z"/><path d="M13.5 6.5l4 4"/></svg>
                            </div>
                            <h2>Informasi Penerima &amp; Catatan</h2>
                            <span class="step">4 dari 4</span>
                        </div>

                        <div class="co-field">
                            <label for="customer_phone">Nomor WhatsApp pemesan</label>
                            <input type="tel" id="customer_phone" name="customer_phone" inputmode="tel" autocomplete="tel"
                                   value="{{ old('customer_phone', auth()->user()->phone ?? '') }}" placeholder="Contoh: 081234567890">
                            <span class="hint">Digunakan untuk notifikasi saat pesanan siap diambil.</span>
                        </div>

                        <div class="co-field">
                            <label for="catatan_pelanggan">Catatan untuk Admin Jurusan (opsional)</label>
                            <textarea id="catatan_pelanggan" name="catatan_pelanggan" rows="3"
                                      placeholder="Contoh: Ambil hari Rabu siang jam istirahat, tolong pastikan packaging rapi...">{{ old('catatan_pelanggan') }}</textarea>
                        </div>
                    </section>
                </div>

                {{-- ============ KOLOM KANAN ============ --}}
                <aside class="co-card co-summary" aria-label="Rincian pembayaran">
                    <h2>Rincian Pembayaran</h2>

                    <div class="co-row"><span>Harga satuan</span><b>Rp {{ number_format($product->harga, 0, ',', '.') }}</b></div>
                    <div class="co-row"><span>Jumlah unit</span><b id="summary_qty">1 unit</b></div>
                    <div class="co-row"><span>Ongkos pengambilan</span><b class="free">Gratis (ambil sendiri)</b></div>

                    <div class="co-total">
                        <span>Total Tagihan COD</span>
                        <strong id="summary_total">Rp {{ number_format($product->harga, 0, ',', '.') }}</strong>
                    </div>

                    <div class="co-note">
                        Cukup bawa uang pas sebesar <strong id="summary_cod_amount">Rp {{ number_format($product->harga, 0, ',', '.') }}</strong> saat mengambil barang di lab jurusan.
                    </div>

                    <button type="submit" class="co-submit" @if($product->stok < 1) disabled @endif>Konfirmasi Pesanan (COD) &rarr;</button>

                    <a href="{{ route('produk') }}" class="co-back">&larr; Batalkan dan kembali ke katalog</a>
                </aside>

            </div>
        </form>
    </div>
</div>

<script>
const unitPrice = {{ $product->harga }};
const maxStock = {{ max(1, $product->stok) }};

function formatRupiah(amount) {
    return 'Rp ' + amount.toLocaleString('id-ID');
}

function adjustQty(delta) {
    const input = document.getElementById('jumlah_input');
    let next = (parseInt(input.value) || 1) + delta;
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

calculateTotal();
</script>
@endsection