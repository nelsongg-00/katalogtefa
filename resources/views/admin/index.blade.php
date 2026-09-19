@extends('layouts.admin')

@section('title', 'DASHBOARD — Admin ' . ($jurusan->nama_jurusan ?? 'Jurusan'))

@section('content')
  <div class="page-head">
    <div>
      <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
        <h1 class="page-title">DASHBOARD</h1>
        <span class="badge-dept">
          ★ Jurusan {{ $jurusan->nama_jurusan ?? 'TEFA' }}
        </span>
      </div>
      <p class="page-sub">Ringkasan aktivitas pesanan, performa pendapatan, progress projek berjalan, dan penugasan siswa.</p>
    </div>

    <div>
      <a href="{{ route('produk') }}" target="_blank" class="btn-outline" style="background:#fff; border:1px solid var(--border); padding: 8px 16px; border-radius: 20px; font-weight:700; font-size:13px; color:var(--text); display:inline-flex; align-items:center; gap:6px;">
        <span>Lihat Katalog Produk &rarr;</span>
      </a>
    </div>
  </div>

  <!-- 1. Stats Metrik -->
  @include('admin.partials.stats')

  <!-- 2. Grafik Tren Aktivitas Bulanan -->
  @include('admin.partials.charts')

  <!-- 3. Projek Sedang Berjalan (Projects in Progress) -->
  @include('admin.partials.projects')

  <!-- 4. Manajemen Siswa / Worker -->
  @include('admin.partials.workers')

  <!-- 5. Tabel Pesanan & Aksi Operasional -->
  @include('admin.partials.table')
@endsection
