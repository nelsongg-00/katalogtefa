@extends('layouts.admin')

@section('title', 'Manajemen Layanan Jasa — Admin Jurusan')

@section('content')
<div class="content-head">
    <div>
        <h1>Katalog Layanan Jasa</h1>
        <p>Kelola daftar penawaran jasa dan unit produksi jurusan yang tampil di website publik.</p>
    </div>
    <div>
        <a href="{{ route('admin.services.create') }}" class="btn-gold">Tambah Layanan Jasa</a>
    </div>
</div>

<div class="card">
    <div class="card-head">
        <div>
            <h3>Daftar Layanan Jasa</h3>
            <p style="margin:3px 0 0;font-size:12px;color:var(--muted)">Penawaran jasa yang tampil pada katalog publik.</p>
        </div>
    </div>
    <div class="table-responsive">
        <table class="order-table">
            <thead>
                <tr>
                    <th>Layanan Jasa</th>
                    <th>Estimasi Harga</th>
                    <th>Status Publik</th>
                    <th style="text-align:right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $service)
                    <tr>
                        <td>
                            <div class="cust">
                                <div class="cust-av" style="overflow:hidden">
                                    @if($service->foto)
                                        <img src="{{ asset('storage/' . $service->foto) }}" alt="{{ $service->nama_layanan }}" style="width:100%;height:100%;object-fit:cover">
                                    @else
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 00.3 1.9l.1.1a2 2 0 11-2.8 2.8"/></svg>
                                    @endif
                                </div>
                                <div>
                                    <b>{{ $service->nama_layanan }}</b>
                                    <small>{{ Str::limit($service->deskripsi, 65) }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <strong style="color:var(--blue)">Rp {{ number_format($service->estimasi_harga, 0, ',', '.') }}</strong>
                        </td>
                        <td>
                            @if($service->is_active)
                                <span class="badge badge-done">Aktif</span>
                            @else
                                <span class="badge b-gray">Nonaktif</span>
                            @endif
                        </td>
                        <td style="text-align:right">
                            <div class="row-actions">
                                <a href="{{ route('admin.services.edit', $service->id) }}" class="btn-sm-edit">Edit</a>
                                <form method="POST" action="{{ route('admin.services.destroy', $service->id) }}" style="display:inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus layanan jasa ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-sm-delete">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="empty">
                            <div class="empty-ic">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 00.3 1.9l.1.1a2 2 0 11-2.8 2.8"/></svg>
                            </div>
                            <b>Belum Ada Layanan Jasa</b>
                            Klik tombol "Tambah Layanan Jasa" untuk menambahkan penawaran jasa jurusan Anda.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($services->hasPages())
        <div class="pagination-wrapper">
            {{ $services->links() }}
        </div>
    @endif
</div>
@endsection
