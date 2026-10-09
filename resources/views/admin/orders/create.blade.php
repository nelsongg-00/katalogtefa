@extends('layouts.admin')

@section('title', 'Catat Pesanan WhatsApp — Admin Jurusan')

@section('content')
<div class="page-head">
    <div>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px">
            <h1 class="page-title">CATAT PESANAN WHATSAPP</h1>
            <span class="badge-dept">WhatsApp Workflow</span>
        </div>
        <p class="page-sub">Input pesanan yang masuk dari WhatsApp pelanggan. Sistem akan otomatis men-generate Kode Pelacakan (TEFA-XXXX), membuat tugas projek untuk worker, dan menyiapkan link tracking publik.</p>
    </div>
    <div class="actions">
        <a href="{{ route('admin.orders.index') }}" class="btn-outline">&larr; Kembali ke Daftar Pesanan</a>
    </div>
</div>

<div class="card" style="max-width:820px">
    <div class="card-head"><h3>Formulir Pemesanan Layanan Jasa</h3></div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert-error" style="display:block;background:var(--red-soft);border-left:4px solid var(--red);color:var(--red-ink);padding:12px 16px;border-radius:var(--r-md);font-size:13px;margin-bottom:20px">
                <ul style="margin:0;padding-left:18px">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.orders.store') }}">
            @csrf
            <div class="form-grid" style="margin-bottom:18px">
                <div class="form-group">
                    <label class="form-label">Nama Pelanggan / Klien <span style="color:var(--red)">*</span></label>
                    <input type="text" name="customer_name" value="{{ old('customer_name') }}" class="form-control" required placeholder="Contoh: Ibu Rina Permata">
                </div>
                <div class="form-group">
                    <label class="form-label">Nomor WhatsApp Pelanggan <span style="color:var(--red)">*</span></label>
                    <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" class="form-control" required placeholder="Contoh: 081234567890">
                    <small class="hint">Bisa diawali 08... atau 62...</small>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1.2fr 0.8fr;gap:18px;margin-bottom:18px">
                <div class="form-group">
                    <label class="form-label">Pilih Layanan Jasa <span style="color:var(--red)">*</span></label>
                    <select name="service_id" id="service_select" class="form-control" required>
                        <option value="">-- Pilih Layanan Jasa --</option>
                        @foreach($services as $srv)
                            <option value="{{ $srv->id }}" data-price="{{ $srv->estimasi_harga }}" {{ old('service_id') == $srv->id ? 'selected' : '' }}>
                                {{ $srv->nama_layanan }} (Mulai Rp {{ number_format($srv->estimasi_harga, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Total Kesepakatan Biaya (Rp) <span style="color:var(--red)">*</span></label>
                    <input type="number" name="total_biaya" id="total_biaya" value="{{ old('total_biaya') }}" min="0" class="form-control" required placeholder="Contoh: 1500000">
                </div>
            </div>
            <div class="form-grid" style="margin-bottom:18px">
                <div class="form-group">
                    <label class="form-label">Tugaskan Worker Penanggung Jawab</label>
                    <select name="worker_id" class="form-control">
                        <option value="">-- Pilih Worker (Opsional, bisa nanti) --</option>
                        @foreach($workers as $worker)
                            <option value="{{ $worker->id }}" {{ old('worker_id') == $worker->id ? 'selected' : '' }}>
                                {{ $worker->name }} ({{ $worker->email }})
                            </option>
                        @endforeach
                    </select>
                    <small class="hint">Tugas pengerjaan akan otomatis tampil di dashboard worker ini.</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Target Tenggat Waktu (Opsional)</label>
                    <input type="date" name="tenggat_waktu" value="{{ old('tenggat_waktu') }}" class="form-control">
                </div>
            </div>
            <div class="form-group" style="margin-bottom:24px">
                <label class="form-label">Catatan Singkat / Brief dari Pelanggan</label>
                <textarea name="catatan" rows="3" class="form-control" placeholder="Contoh: Klien minta revisi warna primer menjadi navy, logo format SVG dikirim via email, dsb...">{{ old('catatan') }}</textarea>
            </div>
            <div style="background:var(--green-soft);border:1px dashed var(--green);border-radius:var(--r-md);padding:14px 18px;margin-bottom:24px">
                <div style="color:var(--green-ink);font-weight:700;font-size:13.5px;margin-bottom:4px">Otomatisasi Sistem</div>
                <p style="margin:0;font-size:12.5px;color:var(--green-ink);line-height:1.5">
                    Saat tombol Simpan ditekan, sistem otomatis menghasilkan <strong>Kode Pelacakan Unik (TEFA-XXXX)</strong>, membuat antrean projek di sistem, dan menyediakan tombol instan <em>"Salin Pesan WA"</em> untuk Anda kirimkan ke pelanggan. Pelanggan dapat memantau pengerjaan secara real-time tanpa perlu akun / login.
                </p>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:12px;padding-top:18px;border-top:1px solid var(--line)">
                <a href="{{ route('admin.orders.index') }}" class="btn-outline">Batal</a>
                <button type="submit" class="btn-gold" style="background:var(--green);box-shadow:0 4px 14px rgba(22,163,74,.25)">Catat & Generate Kode Pelacakan</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('service_select').addEventListener('change', function() {
      const selected = this.options[this.selectedIndex];
      const price = selected.getAttribute('data-price');
      const totalInput = document.getElementById('total_biaya');
      if (price && (!totalInput.value || totalInput.value === '0')) {
        totalInput.value = price;
      }
    });
</script>
@endsection
