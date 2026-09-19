@extends('layouts.admin')

@section('title', 'Dashboard Admin ' . ($jurusan->nama_jurusan ?? 'Jurusan') . ' — TEFA Hub')

@section('content')
  <div class="page-head">
    <div>
      <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
        <h1 class="page-title">Dashboard Admin Jurusan</h1>
        <span class="badge-dept">
          ★ {{ $jurusan->nama_jurusan ?? 'Katalog TEFA' }}
        </span>
      </div>
      <p class="page-sub">Ringkasan aktivitas pesanan, performa pendapatan, dan penugasan siswa Teaching Factory hari ini.</p>
    </div>

    <div>
      <a href="{{ route('produk') }}" target="_blank" class="btn-outline" style="background:#fff; border:1px solid var(--border); padding: 8px 16px; border-radius: 20px; font-weight:700; font-size:13px; color:var(--text); display:inline-flex; align-items:center; gap:6px;">
        <span>Lihat Katalog Produk &rarr;</span>
      </a>
    </div>
  </div>

  <!-- 1. Stats Metrik -->
  @include('admin.partials.stats')

  <!-- 2. Grafik Tren Aktivitas -->
  @include('admin.partials.charts')

  <!-- 3. Tabel Pesanan & Aksi Operasional -->
  @include('admin.partials.table')
@endsection
