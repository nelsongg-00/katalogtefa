@extends('layouts.admin')

@section('title', 'EDIT PORTOFOLIO — Admin')

@section('content')
  <div class="page-head">
    <div>
      <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
        <h1 class="page-title">EDIT PORTOFOLIO</h1>
        <span class="badge-dept">Perbarui Data</span>
      </div>
      <p class="page-sub">Perbarui judul, deskripsi, tim, urutan gambar, dan tambah atau hapus gambar portofolio.</p>
    </div>

    <div class="actions">
      <a href="{{ route('admin.portofolio.index') }}" class="btn-outline">&larr; Kembali ke Daftar Portofolio</a>
    </div>
  </div>

  <div class="card" style="max-width: 780px;">
    <div class="card-head"><h3>Edit: {{ $portfolio->title }}</h3></div>
    <div class="card-body">
      @if ($errors->any())
        <div class="alert-error" style="display:block;background:var(--red-soft);border-left:4px solid var(--red);color:var(--red-ink);padding:12px 16px;border-radius:var(--r-md);font-size:13px;margin-bottom:20px">
          <ul style="margin:0;padding-left:18px">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('admin.portofolio.update', $portfolio) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
          <label class="form-label">Judul Portofolio <span style="color: var(--red);">*</span></label>
          <input type="text" name="title" class="form-control" value="{{ old('title', $portfolio->title) }}" required>
        </div>

        <div class="form-group">
          <label class="form-label">Deskripsi Lengkap <span style="color: var(--red);">*</span></label>
          <textarea name="description" class="form-control" rows="5" required>{{ old('description', $portfolio->description) }}</textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Nama Tim (Opsional)</label>
          <input type="text" name="team_name" class="form-control" value="{{ old('team_name', $portfolio->team_name) }}">
        </div>

        <div class="form-group">
          <label class="form-label">Pembimbing (Opsional)</label>
          <input type="text" name="mentor_name" class="form-control" value="{{ old('mentor_name', $portfolio->mentor_name) }}">
          <small class="hint">Nama guru pembimbing atau instruktur pendamping proyek.</small>
        </div>

        @if($portfolio->images->isNotEmpty())
          <div class="form-group">
            <label class="form-label">Gambar Saat Ini ({{ $portfolio->images->count() }}/10)</label>
            <small class="hint" style="display:block;margin-bottom:10px">Ubah angka untuk mengatur urutan tampil (angka kecil tampil lebih dulu), atau centang hapus untuk membuang gambar.</small>

            <div style="display:flex;flex-direction:column;gap:10px">
              @foreach($portfolio->images as $image)
                <div style="display:flex;align-items:center;gap:12px;border:1px solid var(--line);border-radius:var(--r-md);padding:10px">
                  <img src="{{ asset('storage/'.$image->image_path) }}" alt="Gambar portofolio" style="width:76px;height:56px;object-fit:cover;border-radius:8px;border:1px solid var(--line)">
                  <div style="flex:1">
                    <label class="form-label" style="margin-bottom:4px">Urutan</label>
                    <input type="number" name="image_order[{{ $image->id }}]" class="form-control" style="max-width:110px" min="0" max="255" value="{{ old('image_order.'.$image->id, $image->order) }}">
                  </div>
                  <label style="display:flex;align-items:center;gap:6px;font-size:12.5px;font-weight:700;color:var(--red-ink);white-space:nowrap">
                    <input type="checkbox" name="delete_images[]" value="{{ $image->id }}">
                    Hapus
                  </label>
                </div>
              @endforeach
            </div>
          </div>
        @endif

        <div class="form-group">
          <label class="form-label">Tambah Gambar Baru (Maksimal 10 total)</label>
          <input type="file" name="images[]" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp" multiple>
          <small class="hint">Format JPG, JPEG, PNG, WEBP. Maksimal 2MB per gambar.</small>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:12px;padding-top:18px;border-top:1px solid var(--line)">
          <a href="{{ route('admin.portofolio.index') }}" class="btn-outline">Batal</a>
          <button type="submit" class="btn-gold">Perbarui Portofolio</button>
        </div>
      </form>
    </div>
  </div>
@endsection
