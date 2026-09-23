@extends('layouts.admin')

@section('title', 'RINGKASAN — Admin ' . ($jurusan->nama_jurusan ?? 'Jurusan'))

@section('content')
  <div class="page-head">
    <div>
      <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
        <h1 class="page-title">RINGKASAN DASHBOARD</h1>
        <span class="badge-dept">
          ★ Jurusan {{ $jurusan->nama_jurusan ?? 'TEFA' }}
        </span>
      </div>
      <p class="page-sub">Ringkasan performa pesanan, tren aktivitas bulanan, dan statistik unit produksi TEFA.</p>
    </div>

    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
      <a href="{{ route('admin.products.index') }}" class="btn-gold" style="border-radius: 20px;">
        <span>📦 Kelola Produk Fisik</span>
      </a>
      <a href="{{ route('produk') }}" target="_blank" class="btn-outline" style="background:#fff; border:1px solid var(--border); padding: 8px 16px; border-radius: 20px; font-weight:700; font-size:13px; color:var(--text); display:inline-flex; align-items:center; gap:6px;">
        <span>Lihat Katalog Publik &rarr;</span>
      </a>
    </div>
  </div>

  <!-- 1. Stats Metrik -->
  @include('admin.partials.stats')

  <!-- 2. Grafik Tren Aktivitas Bulanan -->
  @include('admin.partials.charts')

  <!-- 3. Tabel Daftar Pesanan Masuk -->
  @include('admin.partials.table')
@endsection
