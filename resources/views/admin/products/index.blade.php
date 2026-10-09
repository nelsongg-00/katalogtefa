@extends('layouts.admin')

@section('title', 'PRODUK FISIK — Admin')

@section('content')
  <div class="page-head">
    <div>
      <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
        <h1 class="page-title">PRODUK FISIK TEFA</h1>
        <span class="badge-dept">
          Katalog Produk Jurusan
        </span>
      </div>
      <p class="page-sub">Kelola inventaris produk fisik, harga katalog, stok barang, dan unggahan foto produk.</p>
    </div>

    <div style="display: flex; gap: 10px;">
      <a href="{{ route('admin.products.create') }}" class="btn-gold" style="border-radius: 20px;">
        <span>+ Tambah Produk Baru</span>
      </a>
    </div>
  </div>

  <div class="card">
    <div class="card-head">
      <div>
        <h3>Daftar Produk Fisik</h3>
        <p style="margin: 3px 0 0; font-size: 12px; color: var(--muted);">Data produk fisik yang tampil pada katalog publik siswa SMKN 4.</p>
      </div>
    </div>

    <div class="table-responsive">
      <table class="order-table">
        <thead>
          <tr>
            <th style="width: 70px;">Foto</th>
            <th>Nama & Deskripsi Produk</th>
            <th>Harga (Rp)</th>
            <th>Stok</th>
            <th>Terakhir Diperbarui</th>
            <th style="text-align: right;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($products as $product)
            <tr>
              <td>
                @if($product->foto)
                  <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nama_produk }}" class="prod-thumb">
                @else
                  <div class="prod-thumb-placeholder"></div>
                @endif
              </td>
              <td>
                <strong style="color: var(--text); font-size: 14px;">{{ $product->nama_produk }}</strong>
                @if($product->jurusan)
                  <span style="font-size: 11px; background: #eef1f7; color: var(--navy-900); padding: 2px 8px; border-radius: 6px; margin-left: 6px; font-weight: 700;">
                    {{ $product->jurusan->nama_jurusan }}
                  </span>
                @endif
                <div style="color: var(--muted); font-size: 12px; margin-top: 4px; line-height: 1.4; max-width: 420px;">
                  {{ Str::limit($product->deskripsi, 90) }}
                </div>
              </td>
              <td>
                <span style="font-weight: 800; color: var(--text); font-size: 13.5px;">
                  Rp {{ number_format($product->harga, 0, ',', '.') }}
                </span>
              </td>
              <td>
                <span class="stock-badge {{ $product->stok <= 0 ? 'empty' : '' }}">
                  {{ $product->stok > 0 ? $product->stok . ' unit' : 'Habis' }}
                </span>
              </td>
              <td>
                <span style="color: var(--muted); font-size: 12px;">
                  {{ $product->updated_at ? $product->updated_at->format('d M Y, H:i') : '-' }}
                </span>
              </td>
              <td style="text-align: right;">
                <div style="display: inline-flex; align-items: center; gap: 8px;">
                  <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-sm-edit">
                    Edit
                  </a>
                  <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" onsubmit="return confirm('Hapus produk fisik {{ $product->nama_produk }}?');" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-sm-delete" title="Hapus Produk">
                      Hapus
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="text-align: center; padding: 48px 20px; color: var(--muted);">
                <div style="font-weight: 700; color: var(--text); font-size: 14px;">Belum ada produk fisik terdaftar</div>
                <div style="font-size: 12px; margin-top: 4px;">Klik "+ Tambah Produk Baru" untuk menambahkan produk fisik perdana.</div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($products->hasPages())
      <div style="padding: 16px 22px; border-top: 1px solid var(--border);">
        {{ $products->links() }}
      </div>
    @endif
  </div>
@endsection
