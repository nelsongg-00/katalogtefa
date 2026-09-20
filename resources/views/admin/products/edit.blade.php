@extends('layouts.admin')

@section('title', 'EDIT PRODUK FISIK — Admin')

@section('content')
  <div class="page-head">
    <div>
      <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
        <h1 class="page-title">EDIT PRODUK FISIK</h1>
        <span class="badge-dept">
          ★ ID #{{ $product->id }}
        </span>
      </div>
      <p class="page-sub">Perbarui rincian produk, harga, stok, atau perbarui foto produk.</p>
    </div>

    <div>
      <a href="{{ route('admin.products.index') }}" class="btn-outline" style="background:#fff; border:1px solid var(--border); padding: 8px 16px; border-radius: 20px; font-weight:700; font-size:13px; color:var(--text); display:inline-flex; align-items:center; gap:6px;">
        &larr; Kembali ke Daftar Produk
      </a>
    </div>
  </div>

  <div class="card" style="max-width: 780px;">
    <div class="card-head">
      <h3>Edit: {{ $product->nama_produk }}</h3>
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

      <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
          <label class="form-label">Nama Produk Fisik <span style="color: var(--red);">*</span></label>
          <input type="text" name="nama_produk" class="form-control" value="{{ old('nama_produk', $product->nama_produk) }}" required>
        </div>

        <div class="form-group">
          <label class="form-label">Deskripsi Lengkap Produk</label>
          <textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi', $product->deskripsi) }}</textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px;">
          <div class="form-group">
            <label class="form-label">Harga Satuan (Rp) <span style="color: var(--red);">*</span></label>
            <input type="number" name="harga" class="form-control" value="{{ old('harga', $product->harga) }}" min="0" required>
          </div>

          <div class="form-group">
            <label class="form-label">Stok <span style="color: var(--red);">*</span></label>
            <input type="number" name="stok" class="form-control" value="{{ old('stok', $product->stok) }}" min="0" required>
          </div>
        </div>

        <div class="form-group" style="margin-top: 8px;">
          <label class="form-label">Foto Produk</label>

          @if($product->foto)
            <div style="margin-bottom: 12px; background: #fafbfd; border: 1px solid var(--border); border-radius: 8px; padding: 12px 16px; display: inline-flex; align-items: center; gap: 14px;">
              <img src="{{ asset('storage/' . $product->foto) }}" alt="Foto Lama" style="width: 80px; height: 80px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border);">
              <div>
                <div style="font-size: 12px; font-weight: 700; color: var(--text);">Foto Saat Ini</div>
                <div style="font-size: 11px; color: var(--muted); margin-top: 2px;">Upload foto baru di bawah jika ingin mengganti gambar ini.</div>
              </div>
            </div>
          @else
            <div style="margin-bottom: 10px; font-size: 12px; color: var(--muted);">
              Belum ada foto untuk produk ini.
            </div>
          @endif

          <div>
            <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp" onchange="previewNewImage(this)">
            <small style="color: var(--muted); font-size: 11.5px;">Pilih gambar baru jika ingin mengganti. Maksimal 2MB (JPG, PNG, WEBP).</small>
          </div>

          <div id="newImagePreviewWrap" style="display: none; margin-top: 14px;">
            <div style="font-size: 12px; font-weight: 700; color: var(--blue); margin-bottom: 6px;">Preview Foto Baru:</div>
            <img id="newImagePreview" src="" alt="Preview Baru" style="max-height: 180px; border-radius: 8px; border: 1px solid var(--border);">
          </div>
        </div>

        <div style="margin-top: 24px; padding-top: 18px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px;">
          <a href="{{ route('admin.products.index') }}" class="btn-outline" style="border-radius: 8px; padding: 10px 18px; border: 1px solid var(--border); background: #fff; font-size: 13px; font-weight: 700;">
            Batal
          </a>
          <button type="submit" class="btn-gold" style="border-radius: 8px; padding: 10px 22px;">
            ✓ Perbarui Produk
          </button>
        </div>
      </form>
    </div>
  </div>

  @push('scripts')
  <script>
    function previewNewImage(input) {
      const previewWrap = document.getElementById('newImagePreviewWrap');
      const preview = document.getElementById('newImagePreview');
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
