@extends('layouts.admin')

@section('title', 'PORTOFOLIO — Admin')

@section('content')
  <div class="page-head">
    <div>
      <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
        <h1 class="page-title">KELOLA PORTOFOLIO</h1>
        <span class="badge-dept">Portofolio Jurusan</span>
      </div>
      <p class="page-sub">Kelola karya dan proyek portofolio jurusan Anda yang tampil pada halaman publik /portofolio.</p>
    </div>

    <div class="actions">
      <a href="{{ route('admin.portofolio.create') }}" class="btn-gold" style="border-radius: 20px;">
        <span>+ Tambah Portofolio</span>
      </a>
    </div>
  </div>

  <div class="card">
    <div class="card-head">
      <div>
        <h3>Daftar Portofolio</h3>
        <p>Hanya portofolio milik jurusan Anda yang ditampilkan di sini.</p>
      </div>
    </div>

    <div class="table-responsive">
      <table class="order-table">
        <thead>
          <tr>
            <th style="width: 70px;">Cover</th>
            <th>Judul &amp; Tim</th>
            <th>Gambar</th>
            <th>Terakhir Diperbarui</th>
            <th style="text-align: right;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($portfolios as $portfolio)
            <tr>
              <td>
                @if($portfolio->images->first())
                  <img src="{{ asset('storage/'.$portfolio->images->first()->image_path) }}" alt="{{ $portfolio->title }}" class="prod-thumb">
                @else
                  <div class="prod-thumb-placeholder">🖼️</div>
                @endif
              </td>
              <td>
                <strong style="color: var(--text); font-size: 14px;">{{ $portfolio->title }}</strong>
                <div style="color: var(--muted); font-size: 12px; margin-top: 4px; line-height: 1.4; max-width: 420px;">
                  {{ Str::limit($portfolio->description, 90) }}
                </div>
                <div style="color: var(--muted); font-size: 11.5px; margin-top: 4px;">
                  👥 {{ $portfolio->team_name ?: 'Belum diisi' }}
                </div>
              </td>
              <td>
                <span class="stock-badge">{{ $portfolio->images_count }} gambar</span>
              </td>
              <td>
                <span style="color: var(--muted); font-size: 12px;">
                  {{ $portfolio->updated_at ? $portfolio->updated_at->format('d M Y, H:i') : '-' }}
                </span>
              </td>
              <td style="text-align: right;">
                <div style="display: inline-flex; align-items: center; gap: 8px;">
                  <a href="{{ route('admin.portofolio.edit', $portfolio->id) }}" class="btn-sm-edit">Edit</a>
                  <form method="POST" action="{{ route('admin.portofolio.destroy', $portfolio->id) }}" onsubmit="return confirm('Hapus portofolio {{ $portfolio->title }} beserta seluruh gambarnya?');" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-sm-delete" title="Hapus Portofolio">Hapus</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" style="text-align: center; padding: 48px 20px; color: var(--muted);">
                <div style="font-weight: 700; color: var(--text); font-size: 14px;">Belum ada portofolio terdaftar</div>
                <div style="font-size: 12px; margin-top: 4px;">Klik "+ Tambah Portofolio" untuk membuat portofolio pertama jurusan Anda.</div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($portfolios->hasPages())
      <div style="padding: 16px 22px; border-top: 1px solid var(--border);">
        {{ $portfolios->links() }}
      </div>
    @endif
  </div>
@endsection
