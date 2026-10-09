@extends('layouts.admin')

@section('title', 'Edit Layanan Jasa — Admin Jurusan')

@section('content')
<div class="page-head">
    <div>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px">
            <h1 class="page-title">EDIT LAYANAN JASA</h1>
            <span class="badge-dept">Perbarui Data</span>
        </div>
        <p class="page-sub">Perbarui detail informasi penawaran jasa, estimasi harga, atau foto katalog.</p>
    </div>
    <div class="actions">
        <a href="{{ route('admin.services.index') }}" class="btn-outline">&larr; Kembali ke Daftar Jasa</a>
    </div>
</div>

<div class="card" style="max-width:780px">
    <div class="card-head"><h3>Edit Layanan: {{ $service->nama_layanan }}</h3></div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert-error" style="display:block;background:var(--red-soft);border-left:4px solid var(--red);color:var(--red-ink);padding:12px 16px;border-radius:var(--r-md);font-size:13px;margin-bottom:20px">
                <ul style="margin:0;padding-left:18px">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.services.update', $service) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Nama Layanan Jasa <span style="color:var(--red)">*</span></label>
                <input type="text" name="nama_layanan" value="{{ old('nama_layanan', $service->nama_layanan) }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi Jasa & Lingkup Kerja</label>
                <textarea name="deskripsi" rows="4" class="form-control">{{ old('deskripsi', $service->deskripsi) }}</textarea>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Estimasi Harga Mulai Dari (Rp) <span style="color:var(--red)">*</span></label>
                    <input type="number" name="estimasi_harga" value="{{ old('estimasi_harga', (int)$service->estimasi_harga) }}" min="0" class="form-control" required>
                    <small class="hint">Masukkan nominal angka tanpa titik.</small>
                </div>
                @php $isActive = old('is_active', $service->is_active); @endphp
                <div class="form-group">
                    <label class="form-label">Status Publikasi</label>
                    <div class="sw">
                        <span>Tampilkan di Halaman Jasa Publik</span>
                        <div class="tg {{ $isActive ? 'on' : '' }}" data-toggle="is_active" data-on="1" data-off="0" onclick="toggleSwitch(this)"></div>
                        <input type="hidden" name="is_active" id="is_active" value="{{ $isActive ? '1' : '0' }}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Foto / Banner Layanan</label>
                @if($service->foto)
                    <div style="margin-bottom:12px;display:flex;align-items:center;gap:12px">
                        <img src="{{ asset('storage/' . $service->foto) }}" alt="{{ $service->nama_layanan }}" style="width:80px;height:80px;object-fit:cover;border-radius:var(--r-md);border:1px solid var(--line)">
                        <span class="hint">Foto saat ini. Pilih file baru di bawah untuk mengganti.</span>
                    </div>
                @endif
                <input type="file" name="foto" class="form-control" accept="image/*">
                <small class="hint">Format gambar PNG, JPG, JPEG, WEBP. Maksimal 2MB.</small>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:12px;padding-top:18px;border-top:1px solid var(--line)">
                <a href="{{ route('admin.services.index') }}" class="btn-outline">Batal</a>
                <button type="submit" class="btn-gold">Perbarui Layanan</button>
            </div>
        </form>
    </div>
</div>
@endsection
