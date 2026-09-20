@extends('layouts.admin')

@section('title', 'Catat Pesanan Manual WhatsApp — Admin Jurusan')

@section('content')
  <div class="page-head" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
      <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
        <h1 style="font-size: 22px; font-weight: 800; color: #16234a; margin: 0;">CATAT PESANAN WHATSAPP</h1>
        <span style="background: #dcfce7; color: #15803d; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 999px;">
          💬 WhatsApp Workflow
        </span>
      </div>
      <p style="color: #7a839c; font-size: 13.5px; margin: 0;">Input pesanan yang masuk dari WhatsApp pelanggan. Sistem akan otomatis men-generate Kode Pelacakan (TEFA-XXXX), membuat tugas projek untuk worker, dan menyiapkan link tracking publik.</p>
    </div>

    <div>
      <a href="{{ route('admin.orders.index') }}" style="background: #fff; border: 1px solid #cbd5e1; padding: 8px 16px; border-radius: 20px; font-weight: 700; font-size: 13px; color: #334155; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
        &larr; Kembali ke Daftar Pesanan
      </a>
    </div>
  </div>

  <div class="card" style="max-width: 820px; background: #fff; border-radius: 14px; border: 1px solid #e5e9f2; overflow: hidden; box-shadow: 0 2px 8px rgba(22,35,74,0.04);">
    <div style="padding: 16px 20px; border-bottom: 1px solid #e5e9f2; background: #f8fafd;">
      <h3 style="margin: 0; font-size: 15px; font-weight: 700; color: #16234a;">Formulir Pemesanan Layanan Jasa</h3>
    </div>

    <div style="padding: 24px 20px;">
      @if ($errors->any())
        <div style="background: #fef2f2; border-left: 4px solid #ef4444; color: #b91c1c; padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 20px;">
          <ul style="margin: 0; padding-left: 18px;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('admin.orders.store') }}">
        @csrf

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
          <div>
            <label style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px;">Nama Pelanggan / Klien <span style="color: #ef4444;">*</span></label>
            <input type="text" name="customer_name" value="{{ old('customer_name') }}" required placeholder="Contoh: Ibu Rina Permata"
                   style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 14px; font-size: 13.5px; outline: none; box-sizing: border-box;">
          </div>

          <div>
            <label style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px;">Nomor WhatsApp Pelanggan <span style="color: #ef4444;">*</span></label>
            <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" required placeholder="Contoh: 081234567890"
                   style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 14px; font-size: 13.5px; outline: none; box-sizing: border-box;">
            <small style="color: #64748b; font-size: 11.5px;">Bisa diawali 08... atau 62...</small>
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 18px; margin-bottom: 18px;">
          <div>
            <label style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px;">Pilih Layanan Jasa <span style="color: #ef4444;">*</span></label>
            <select name="service_id" id="service_select" required style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 14px; font-size: 13.5px; outline: none; box-sizing: border-box;">
              <option value="">-- Pilih Layanan Jasa --</option>
              @foreach($services as $srv)
                <option value="{{ $srv->id }}" data-price="{{ $srv->estimasi_harga }}" {{ old('service_id') == $srv->id ? 'selected' : '' }}>
                  {{ $srv->nama_layanan }} (Mulai Rp {{ number_format($srv->estimasi_harga, 0, ',', '.') }})
                </option>
              @endforeach
            </select>
          </div>

          <div>
            <label style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px;">Total Kesepakatan Biaya (Rp) <span style="color: #ef4444;">*</span></label>
            <input type="number" name="total_biaya" id="total_biaya" value="{{ old('total_biaya') }}" min="0" required placeholder="Contoh: 1500000"
                   style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 14px; font-size: 13.5px; outline: none; box-sizing: border-box;">
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
          <div>
            <label style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px;">Tugaskan Worker Penanggung Jawab</label>
            <select name="worker_id" style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 14px; font-size: 13.5px; outline: none; box-sizing: border-box;">
              <option value="">-- Pilih Worker (Opsional, bisa nanti) --</option>
              @foreach($workers as $worker)
                <option value="{{ $worker->id }}" {{ old('worker_id') == $worker->id ? 'selected' : '' }}>
                  {{ $worker->name }} ({{ $worker->email }})
                </option>
              @endforeach
            </select>
            <small style="color: #64748b; font-size: 11.5px;">Tugas pengerjaan akan otomatis tampil di dashboard worker ini.</small>
          </div>

          <div>
            <label style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px;">Target Tenggat Waktu (Opsional)</label>
            <input type="date" name="tenggat_waktu" value="{{ old('tenggat_waktu') }}"
                   style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 14px; font-size: 13.5px; outline: none; box-sizing: border-box;">
          </div>
        </div>

        <div style="margin-bottom: 24px;">
          <label style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px;">Catatan Singkat / Brief dari Pelanggan</label>
          <textarea name="catatan" rows="3" placeholder="Contoh: Klien minta revisi warna primer menjadi navy, logo format SVG dikirim via email, dsb..."
                    style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 14px; font-size: 13.5px; outline: none; box-sizing: border-box;">{{ old('catatan') }}</textarea>
        </div>

        <div style="background: #f0fdf4; border: 1px dashed #86efac; border-radius: 10px; padding: 14px 18px; margin-bottom: 24px;">
          <div style="display: flex; align-items: center; gap: 8px; color: #166534; font-weight: 700; font-size: 13.5px; margin-bottom: 4px;">
            <span>💡</span> Otomatisasi Sistem
          </div>
          <p style="margin: 0; font-size: 12.5px; color: #15803d; line-height: 1.5;">
            Saat tombol Simpan ditekan, sistem otomatis menghasilkan <strong>Kode Pelacakan Unik (TEFA-XXXX)</strong>, membuat antrean projek di sistem, dan menyediakan tombol instan <em>"Salin Pesan WA"</em> untuk Anda kirimkan ke pelanggan. Pelanggan dapat memantau pengerjaan secara real-time tanpa perlu akun / login.
          </p>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px; padding-top: 12px; border-top: 1px solid #f1f5f9;">
          <a href="{{ route('admin.orders.index') }}" style="padding: 10px 20px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; color: #475569; font-weight: 700; font-size: 13.5px; text-decoration: none;">
            Batal
          </a>
          <button type="submit" style="padding: 10px 24px; border-radius: 8px; border: none; background: #16a34a; color: #fff; font-weight: 700; font-size: 13.5px; cursor: pointer; box-shadow: 0 4px 12px rgba(22,163,74,0.25);">
            Catat & Generate Kode Pelacakan
          </button>
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
