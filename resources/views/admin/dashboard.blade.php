@extends('layouts.admin')

@section('title', 'Ringkasan Dashboard')

@section('content')
<div class="stack-y">

<section class="hero">
    <div>
        <span class="pill">Admin Jurusan &middot; {{ $jurusan->nama_jurusan ?? 'TEFA' }}</span>
        <h1>RINGKASAN DASHBOARD</h1>
        <p>Ringkasan performa pesanan, tren aktivitas bulanan, dan statistik unit produksi TEFA.</p>
    </div>
    <div class="acts">
        <a href="{{ route('admin.products.index') }}" class="btn-w">Kelola Produk Fisik</a>
        <a href="{{ route('admin.services.index') }}" class="btn-t">Kelola Layanan Jasa</a>
        <a href="{{ route('produk') }}" target="_blank" class="btn-t">Lihat Katalog Publik</a>
    </div>
</section>

@include('admin.partials.stats')
@include('admin.partials.charts')
@include('admin.partials.table')

</div>
@endsection
