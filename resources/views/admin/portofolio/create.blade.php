@extends('layouts.admin')

@section('title', 'TAMBAH PORTOFOLIO — Admin')

@section('content')
  <div class="page-head">
    <div>
      <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
        <h1 class="page-title">TAMBAH PORTOFOLIO</h1>
        <span class="badge-dept">Input Baru</span>
      </div>
      <p class="page-sub">Isi judul, deskripsi, nama tim, dan unggah hingga 10 gambar portofolio.</p>
    </div>

    <div class="actions">
      <a href="{{ route('admin.portofolio.index') }}" class="btn-outline">&larr; Kembali ke Daftar Portofolio</a>
    </div>
  </div>

  <div class="card" style="max-width: 780px;">
    <div class="card-head"><h3>Formulir Portofolio Baru</h3></div>
    <div class="card-body">
      @if ($errors->any())
        <div class="alert-error" style="display:block;background:var(--red-soft);border-left:4px solid var(--red);color:var(--red-ink);padding:12px 16px;border-radius:var(--r-md);font-size:13px;margin-bottom:20px">
          <ul style="margin:0;padding-left:18px">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('admin.portofolio.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
          <label class="form-label">Judul Portofolio <span style="color: var(--red);">*</span></label>
          <input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="Contoh: Aplikasi Presensi RFID &amp; Face Recognition">
        </div>

        <div class="form-group">
          <label class="form-label">Deskripsi Lengkap <span style="color: var(--red);">*</span></label>
          <textarea name="description" class="form-control" rows="5" required placeholder="Jelaskan latar belakang, fitur utama, dan hasil karya portofolio ini...">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Nama Tim (Opsional)</label>
          <input type="text" name="team_name" class="form-control" value="{{ old('team_name') }}" placeholder="Contoh: Tim Siswa RPL SMKN 4">
        </div>

        <div class="form-group">
          <label class="form-label">Pembimbing (Opsional)</label>
          <input type="text" name="mentor_name" class="form-control" value="{{ old('mentor_name') }}" placeholder="Contoh: Bu Ratna Sari, S.Kom &amp; Instruktur IoT TEFA">
          <small class="hint">Nama guru pembimbing atau instruktur pendamping proyek.</small>
        </div>

        <div class="form-group">
          <label class="form-label">Gambar Portofolio (Maksimal 10)</label>
          <input type="file" name="images[]" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp" multiple>
          <small class="hint">Urutan gambar mengikuti urutan file yang dipilih. Format JPG, JPEG, PNG, WEBP. Maksimal 2MB per gambar.</small>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:12px;padding-top:18px;border-top:1px solid var(--line)">
          <a href="{{ route('admin.portofolio.index') }}" class="btn-outline">Batal</a>
          <button type="submit" class="btn-gold">Simpan Portofolio</button>
        </div>
      </form>
    </div>
  </div>
@endsection
