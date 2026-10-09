@extends('layouts.admin')

@section('title', 'Tambah Layanan Jasa — Admin Jurusan')

@section('content')
<div class="page-head">
    <div>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px">
            <h1 class="page-title">TAMBAH LAYANAN JASA</h1>
            <span class="badge-dept">Katalog Baru</span>
        </div>
        <p class="page-sub">Lengkapi informasi penawaran jasa, estimasi harga mulai dari, dan foto/banner portofolio.</p>
    </div>
    <div class="actions">
        <a href="{{ route('admin.services.index') }}" class="btn-outline">&larr; Kembali ke Daftar Jasa</a>
    </div>
</div>

<div class="card" style="max-width:780px">
    <div class="card-head"><h3>Formulir Penawaran Jasa</h3></div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert-error" style="display:block;background:var(--red-soft);border-left:4px solid var(--red);color:var(--red-ink);padding:12px 16px;border-radius:var(--r-md);font-size:13px;margin-bottom:20px">
                <ul style="margin:0;padding-left:18px">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Layanan Jasa <span style="color:var(--red)">*</span></label>
                <input type="text" name="nama_layanan" value="{{ old('nama_layanan') }}" class="form-control" required placeholder="Contoh: Pembuatan Website Profil Sekolah, Desain Kemasan Produk, dsb">
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi Jasa & Lingkup Kerja</label>
                <textarea name="deskripsi" rows="4" class="form-control" placeholder="Jelaskan apa saja yang didapatkan klien, fitur, proses revisi, dan ketentuan pengerjaan...">{{ old('deskripsi') }}</textarea>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Estimasi Harga Mulai Dari (Rp) <span style="color:var(--red)">*</span></label>
                    <input type="number" name="estimasi_harga" value="{{ old('estimasi_harga') }}" min="0" class="form-control" required placeholder="Contoh: 500000">
                    <small class="hint">Masukkan nominal angka tanpa titik.</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Status Publikasi</label>
                    <div class="sw">
                        <span>Tampilkan di Halaman Jasa Publik</span>
                        <div class="tg on" data-toggle="is_active" data-on="1" data-off="0" onclick="toggleSwitch(this)"></div>
                        <input type="hidden" name="is_active" id="is_active" value="1">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Foto / Banner Layanan (Opsional)</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
                <small class="hint">Format gambar PNG, JPG, JPEG, WEBP. Maksimal 2MB.</small>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:12px;padding-top:18px;border-top:1px solid var(--line)">
                <a href="{{ route('admin.services.index') }}" class="btn-outline">Batal</a>
                <button type="submit" class="btn-gold">Simpan Layanan Jasa</button>
            </div>
        </form>
    </div>
</div>
@endsection
