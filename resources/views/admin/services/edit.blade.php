@extends('layouts.admin')

@section('title', 'Edit Layanan Jasa — Admin Jurusan')

@section('content')
  <div class="page-head" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
      <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
        <h1 style="font-size: 22px; font-weight: 800; color: #16234a; margin: 0;">EDIT LAYANAN JASA</h1>
        <span style="background: #eff6ff; color: #2563eb; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 999px;">
          ★ Perbarui Data
        </span>
      </div>
      <p style="color: #7a839c; font-size: 13.5px; margin: 0;">Perbarui detail informasi penawaran jasa, estimasi harga, atau foto katalog.</p>
    </div>

    <div>
      <a href="{{ route('admin.services.index') }}" style="background: #fff; border: 1px solid #cbd5e1; padding: 8px 16px; border-radius: 20px; font-weight: 700; font-size: 13px; color: #334155; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
        &larr; Kembali ke Daftar Jasa
      </a>
    </div>
  </div>

  <div class="card" style="max-width: 780px; background: #fff; border-radius: 14px; border: 1px solid #e5e9f2; overflow: hidden; box-shadow: 0 2px 8px rgba(22,35,74,0.04);">
    <div style="padding: 16px 20px; border-bottom: 1px solid #e5e9f2; background: #f8fafd;">
      <h3 style="margin: 0; font-size: 15px; font-weight: 700; color: #16234a;">Edit Layanan: {{ $service->nama_layanan }}</h3>
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

      <form method="POST" action="{{ route('admin.services.update', $service) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 18px;">
          <label style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px;">Nama Layanan Jasa <span style="color: #ef4444;">*</span></label>
          <input type="text" name="nama_layanan" value="{{ old('nama_layanan', $service->nama_layanan) }}" required
                 style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 14px; font-size: 13.5px; outline: none; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 18px;">
          <label style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px;">Deskripsi Jasa & Lingkup Kerja</label>
          <textarea name="deskripsi" rows="4"
                    style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 14px; font-size: 13.5px; outline: none; box-sizing: border-box;">{{ old('deskripsi', $service->deskripsi) }}</textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
          <div>
            <label style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px;">Estimasi Harga Mulai Dari (Rp) <span style="color: #ef4444;">*</span></label>
            <input type="number" name="estimasi_harga" value="{{ old('estimasi_harga', (int)$service->estimasi_harga) }}" min="0" required
                   style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 10px 14px; font-size: 13.5px; outline: none; box-sizing: border-box;">
            <small style="color: #64748b; font-size: 11.5px;">Masukkan nominal angka tanpa titik.</small>
          </div>

          <div>
            <label style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px;">Status Publikasi</label>
            <label style="display: flex; align-items: center; gap: 8px; margin-top: 10px; cursor: pointer; font-size: 13.5px; font-weight: 600; color: #1e293b;">
              <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #2563eb;">
              Tampilkan di Halaman Jasa Publik
            </label>
          </div>
        </div>

        <div style="margin-bottom: 24px;">
          <label style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px;">Foto / Banner Layanan</label>
          @if($service->foto)
            <div style="margin-bottom: 12px; display: flex; align-items: center; gap: 12px;">
              <img src="{{ asset('storage/' . $service->foto) }}" alt="{{ $service->nama_layanan }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 1px solid #cbd5e1;">
              <span style="font-size: 12px; color: #64748b;">Foto saat ini. Pilih file baru di bawah untuk mengganti.</span>
            </div>
          @endif
          <input type="file" name="foto" accept="image/*"
                 style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px 12px; font-size: 13px; box-sizing: border-box; background: #f8fafc;">
          <small style="color: #64748b; font-size: 11.5px;">Format gambar PNG, JPG, JPEG, WEBP. Maksimal 2MB.</small>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px; padding-top: 12px; border-top: 1px solid #f1f5f9;">
          <a href="{{ route('admin.services.index') }}" style="padding: 10px 20px; border-radius: 8px; border: 1px solid #cbd5e1; background: #fff; color: #475569; font-weight: 700; font-size: 13.5px; text-decoration: none;">
            Batal
          </a>
          <button type="submit" style="padding: 10px 24px; border-radius: 8px; border: none; background: #2563eb; color: #fff; font-weight: 700; font-size: 13.5px; cursor: pointer; box-shadow: 0 4px 12px rgba(37,99,235,0.2);">
            Perbarui Layanan
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection
