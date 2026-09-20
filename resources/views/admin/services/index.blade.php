@extends('layouts.admin')

@section('title', 'Manajemen Layanan Jasa — Admin Jurusan')

@section('content')
<div class="content-head" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 style="font-size: 22px; font-weight: 800; color: #16234a; margin-bottom: 4px;">Katalog Layanan Jasa</h1>
        <p style="color: #7a839c; font-size: 13.5px;">Kelola daftar penawaran jasa dan unit produksi jurusan yang tampil di website publik.</p>
    </div>
    <div>
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary" style="background: #2563eb; color: #fff; padding: 10px 20px; border-radius: 10px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-size: 13.5px;">
            <span>+</span> Tambah Layanan Jasa
        </a>
    </div>
</div>

@if(session('success'))
    <div style="background: #dcfce7; border-left: 4px solid #16a34a; color: #15803d; padding: 12px 18px; border-radius: 8px; font-weight: 600; font-size: 13.5px; margin-bottom: 20px;">
        {{ session('success') }}
    </div>
@endif

<div class="card" style="background: #fff; border-radius: 14px; border: 1px solid #e5e9f2; overflow: hidden; box-shadow: 0 2px 8px rgba(22,35,74,0.04);">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13.5px;">
            <thead>
                <tr style="background: #f8fafd; border-bottom: 1px solid #e5e9f2; color: #64748b; font-size: 12px; font-weight: 700; text-transform: uppercase;">
                    <th style="padding: 14px 18px;">Layanan Jasa</th>
                    <th style="padding: 14px 18px;">Estimasi Harga</th>
                    <th style="padding: 14px 18px;">Status Publik</th>
                    <th style="padding: 14px 18px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $service)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 14px 18px;">
                            <div style="display: flex; align-items: center; gap: 14px;">
                                <div style="width: 50px; height: 50px; border-radius: 8px; overflow: hidden; background: #f1f5f9; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 20px;">
                                    @if($service->foto)
                                        <img src="{{ asset('storage/' . $service->foto) }}" alt="{{ $service->nama_layanan }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <span>🤝</span>
                                    @endif
                                </div>
                                <div>
                                    <strong style="color: #0f172a; font-size: 14px; display: block;">{{ $service->nama_layanan }}</strong>
                                    <small style="color: #64748b;">{{ Str::limit($service->deskripsi, 65) }}</small>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 14px 18px; font-weight: 700; color: #2563eb;">
                            Rp {{ number_format($service->estimasi_harga, 0, ',', '.') }}
                        </td>
                        <td style="padding: 14px 18px;">
                            @if($service->is_active)
                                <span style="background: #ecfdf5; color: #059669; font-weight: 700; font-size: 11.5px; padding: 3px 10px; border-radius: 999px;">Aktif</span>
                            @else
                                <span style="background: #f1f5f9; color: #64748b; font-weight: 700; font-size: 11.5px; padding: 3px 10px; border-radius: 999px;">Nonaktif</span>
                            @endif
                        </td>
                        <td style="padding: 14px 18px; text-align: right;">
                            <div style="display: inline-flex; gap: 8px;">
                                <a href="{{ route('admin.services.edit', $service->id) }}" style="padding: 6px 12px; background: #eff6ff; color: #2563eb; border-radius: 6px; font-weight: 700; font-size: 12px; text-decoration: none;">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.services.destroy', $service->id) }}" style="display:inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus layanan jasa ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="padding: 6px 12px; background: #fef2f2; color: #dc2626; border-radius: 6px; font-weight: 700; font-size: 12px; border: none; cursor: pointer;">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 40px; color: #94a3b8;">
                            <div style="font-size: 36px; margin-bottom: 8px;">🤝</div>
                            <strong style="color: #334155; font-size: 15px; display: block;">Belum Ada Layanan Jasa</strong>
                            <p style="font-size: 13px; margin-top: 4px;">Klik tombol "Tambah Layanan Jasa" untuk menambahkan penawaran jasa jurusan Anda.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($services->hasPages())
        <div style="padding: 16px 20px; border-top: 1px solid #e5e9f2;">
            {{ $services->links() }}
        </div>
    @endif
</div>
@endsection
