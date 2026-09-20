@extends('layouts.admin')

@section('title', 'TAMBAH PRODUK FISIK — Admin')

@section('content')
  <div class="page-head">
    <div>
      <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
        <h1 class="page-title">TAMBAH PRODUK FISIK</h1>
        <span class="badge-dept">
          ★ Input Baru
        </span>
      </div>
      <p class="page-sub">Lengkapi informasi produk fisik, harga, stok, serta foto katalog.</p>
    </div>

    <div>
      <a href="{{ route('admin.products.index') }}" class="btn-outline" style="background:#fff; border:1px solid var(--border); padding: 8px 16px; border-radius: 20px; font-weight:700; font-size:13px; color:var(--text); display:inline-flex; align-items:center; gap:6px;">
        &larr; Kembali ke Daftar Produk
      </a>
    </div>
  </div>

  <div class="card" style="max-width: 780px;">
    <div class="card-head">
      <h3>Formulir Produk Baru</h3>
    </div>

    <div class="card-body">
      @if ($errors->any())
        <div class="alert-error">
          <ul style="margin: 0; padding-left: 18px;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
          <label class="form-label">Nama Produk Fisik <span style="color: var(--red);">*</span></label>
          <input type="text" name="nama_produk" class="form-control" value="{{ old('nama_produk') }}" required placeholder="Contoh: Buku Jurnal Desain Tefa atau Kaus Merchandise RPL">
        </div>

        <div class="form-group">
          <label class="form-label">Deskripsi Lengkap Produk</label>
          <textarea name="deskripsi" class="form-control" rows="4" placeholder="Jelaskan spesifikasi material, ukuran, atau fitur unggulan produk...">{{ old('deskripsi') }}</textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px;">
          <div class="form-group">
            <label class="form-label">Harga Satuan (Rp) <span style="color: var(--red);">*</span></label>
            <input type="number" name="harga" class="form-control" value="{{ old('harga') }}" min="0" required placeholder="Contoh: 75000">
            <small style="color: var(--muted); font-size: 11.5px;">Masukkan angka tanpa titik atau koma.</small>
          </div>

          <div class="form-group">
            <label class="form-label">Stok Awal <span style="color: var(--red);">*</span></label>
            <input type="number" name="stok" class="form-control" value="{{ old('stok', 10) }}" min="0" required placeholder="Contoh: 20">
          </div>
        </div>

        <div class="form-group" style="margin-top: 6px;">
          <label class="form-label">Foto Produk (Opsional)</label>
          <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp" onchange="previewImage(this)">
          <small style="color: var(--muted); font-size: 11.5px;">Format didukung: JPG, JPEG, PNG, WEBP. Maksimal 2MB.</small>

          <div id="imagePreviewWrap" style="display: none; margin-top: 14px;">
            <div style="font-size: 12px; font-weight: 700; color: var(--muted); margin-bottom: 6px;">Preview Foto:</div>
            <img id="imagePreview" src="" alt="Preview" style="max-height: 180px; border-radius: 8px; border: 1px solid var(--border);">
          </div>
        </div>

        <div style="margin-top: 24px; padding-top: 18px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px;">
          <a href="{{ route('admin.products.index') }}" class="btn-outline" style="border-radius: 8px; padding: 10px 18px; border: 1px solid var(--border); background: #fff; font-size: 13px; font-weight: 700;">
            Batal
          </a>
          <button type="submit" class="btn-gold" style="border-radius: 8px; padding: 10px 22px;">
            ✓ Simpan Produk Baru
          </button>
        </div>
      </form>
    </div>
  </div>

  @push('scripts')
  <script>
    function previewImage(input) {
      const previewWrap = document.getElementById('imagePreviewWrap');
      const preview = document.getElementById('imagePreview');
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.src = e.target.result;
          previewWrap.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
      } else {
        previewWrap.style.display = 'none';
      }
    }
  </script>
  @endpush
@endsection
