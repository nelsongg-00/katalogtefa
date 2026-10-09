@extends('layouts.superadmin')

@section('title', 'Ringkasan Dashboard')

@section('content')
<div class="stack-y">

<!-- HERO -->
<section class="hero">
    <div>
        <span class="pill">Super Admin &middot; Semua Jurusan</span>
        <h1>RINGKASAN DASHBOARD</h1>
        <p>Pantauan global performa transaksi, pengguna, dan unit produksi seluruh jurusan TeFa SMKN 4.</p>
    </div>
    <div class="acts">
        <a href="{{ route('superadmin.users.index') }}" class="btn-w">Kelola User</a>
        <a href="{{ route('superadmin.reports.index') }}" class="btn-t">Laporan Global</a>
        <a href="{{ route('superadmin.katalog.preview', 'produk') }}" class="btn-t">Cetak Katalog Produk</a>
        <a href="{{ route('superadmin.katalog.preview', 'jasa') }}" class="btn-t">Cetak Katalog Jasa</a>
    </div>
</section>

<!-- 4 KARTU STATISTIK -->
<section class="stats">
    <x-sa-stat
        :value="$totalOrders"
        :count="$totalOrders"
        label="Total Transaksi Global"
        icon="receipt"
        hero="1"
        subTone="warning"
        :sub="$pendingOrders.' Menunggu · '.$inProgressOrders.' Proses'" />

    <x-sa-stat
        :value="'Rp '.number_format($totalRevenue, 0, ',', '.')"
        :count="$totalRevenue"
        rp="1"
        label="Pendapatan TeFa Selesai"
        icon="money"
        subTone="success"
        :sub="$completedOrders.' Transaksi Selesai'" />

    <x-sa-stat
        :value="$totalUsers"
        :count="$totalUsers"
        label="Total Pengguna Aktif"
        icon="users"
        subTone="info"
        :sub="$totalAdmin.' Admin · '.$totalWorker.' Worker · '.$totalClient.' Pelanggan'" />

    <x-sa-stat
        :value="$totalJurusans"
        :count="$totalJurusans"
        label="Total Jurusan TeFa"
        icon="layers"
        subTone="info"
        :sub="$activeJurusans.' Aktif · '.($totalJurusans - $activeJurusans).' Nonaktif'" />
</section>

<!-- GRAFIK TREN BULANAN DINAMIS -->
<x-sa-card
    title="Tren Aktivitas Transaksi Global"
    subtitle="Perbandingan pesanan masuk dan transaksi selesai dari 6 jurusan sepanjang tahun berjalan."
    :chip="'Tahun '.$year">
    <div class="legend">
        <span><i style="background:var(--blue)"></i>Transaksi Masuk</span>
        <span><i style="background:var(--green)"></i>Transaksi Selesai</span>
    </div>
    <div class="chart" role="img" aria-label="Grafik batang transaksi masuk dan selesai per bulan">
        <div class="grid">
            @foreach($chartTicks as $tick)
                <div style="bottom: calc(({{ $chartTop > 0 ? ($tick / $chartTop) * 100 : 0 }}% * 1)); top: auto">
                    <span>{{ $tick }}</span>
                </div>
            @endforeach
        </div>
        <div class="cols">
            @php
                $months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
            @endphp
            @foreach($months as $idx => $mName)
                @php
                    $inCount = $monthlyIncoming[$idx] ?? 0;
                    $doneCount = $monthlyCompleted[$idx] ?? 0;
                    $inHeight = $chartTop > 0 ? ($inCount / $chartTop) * 100 : 0;
                    $doneHeight = $chartTop > 0 ? ($doneCount / $chartTop) * 100 : 0;
                @endphp
                <div class="col">
                    <div class="bars">
                        <div class="bar a" data-h="{{ $inHeight }}" data-v="{{ $inCount }}"></div>
                        <div class="bar b" data-h="{{ $doneHeight }}" data-v="{{ $doneCount }}"></div>
                    </div>
                    <div class="m">{{ $mName }}</div>
                </div>
            @endforeach
        </div>
    </div>
</x-sa-card>

<!-- DUA KOLOM: REKAP JURUSAN & KOMPOSISI USER -->
<div class="two">
    <x-sa-card title="Rekap Pendapatan per Jurusan" subtitle="Akumulasi omzet transaksi berstatus selesai dari setiap jurusan.">
        <x-slot name="actions">
            <x-sa-button variant="ghost" size="sm" :href="route('superadmin.reports.index')">Detail</x-sa-button>
        </x-slot>

        @php
            $barColors = ['#0b60cf', '#16a34a', '#0c4284', '#64748b', '#d97706', '#0a4fa8'];
        @endphp
        @forelse($revenuePerJurusan as $idx => $rev)
            @php
                $pct = $maxOmzetPerJurusan > 0 ? ($rev['omzet'] / $maxOmzetPerJurusan) * 100 : 0;
                $cColor = $barColors[$idx % count($barColors)];
            @endphp
            <div class="prog">
                <div class="t">
                    <div>
                        <strong>{{ $rev['jurusan']->nama_jurusan }}</strong>
                        @if(!$rev['jurusan']->status_aktif)
                            <x-sa-badge tone="gray">Nonaktif</x-sa-badge>
                        @endif
                    </div>
                    <span>Rp {{ number_format($rev['omzet'], 0, ',', '.') }} · {{ $rev['total_orders'] }} transaksi</span>
                </div>
                <div class="track">
                    <div class="fill" data-w="{{ $pct }}" style="background: {{ $cColor }}"></div>
                </div>
            </div>
        @empty
            <x-sa-empty icon="money" title="Belum ada data pendapatan per jurusan." />
        @endforelse
    </x-sa-card>

    <div class="stack">
        <x-sa-card title="Komposisi Pengguna">
            @php
                $userTotalDivider = max(1, $totalUsers);
            @endphp
            <div class="prog">
                <div class="t">
                    <b>Admin Jurusan</b>
                    <span>{{ $totalAdmin }} akun ({{ round(($totalAdmin / $userTotalDivider) * 100) }}%)</span>
                </div>
                <div class="track">
                    <div class="fill" data-w="{{ ($totalAdmin / $userTotalDivider) * 100 }}" style="background: var(--blue)"></div>
                </div>
            </div>

            <div class="prog">
                <div class="t">
                    <b>Worker</b>
                    <span>{{ $totalWorker }} akun ({{ round(($totalWorker / $userTotalDivider) * 100) }}%)</span>
                </div>
                <div class="track">
                    <div class="fill" data-w="{{ ($totalWorker / $userTotalDivider) * 100 }}" style="background: #f59e0b"></div>
                </div>
            </div>

            <div class="prog">
                <div class="t">
                    <b>Pelanggan</b>
                    <span>{{ $totalClient }} akun ({{ round(($totalClient / $userTotalDivider) * 100) }}%)</span>
                </div>
                <div class="track">
                    <div class="fill" data-w="{{ ($totalClient / $userTotalDivider) * 100 }}" style="background: var(--green)"></div>
                </div>
            </div>
        </x-sa-card>

        <x-sa-card title="Transaksi Terbaru" body="flush">
            @forelse($recentOrders as $ro)
                @php
                    $firstProduct = $ro->detailPesanans->first()?->produk;
                    $jurName = $firstProduct?->jurusan?->nama_jurusan ?? 'Semua Jurusan';
                    $stLower = strtolower($ro->status_pesanan);
                    $stTone = 'yellow';
                    if (in_array($stLower, ['completed', 'selesai'])) $stTone = 'green';
                    elseif (in_array($stLower, ['in progress', 'diproses', 'proses'])) $stTone = 'blue';
                    elseif (in_array($stLower, ['cancelled', 'dibatalkan', 'batal'])) $stTone = 'red';
                @endphp
                <div class="feed-row">
                    <div>
                        <b>{{ $firstProduct?->nama_produk ?? ('Pesanan #' . $ro->id) }}</b>
                        <small>{{ $jurName }} · {{ $ro->created_at->format('d M Y') }}</small>
                    </div>
                    <x-sa-badge :tone="$stTone">{{ $ro->status_pesanan }}</x-sa-badge>
                </div>
            @empty
                <x-sa-empty icon="receipt" title="Belum ada transaksi." />
            @endforelse
        </x-sa-card>
    </div>
</div>
</div>
@endsection
