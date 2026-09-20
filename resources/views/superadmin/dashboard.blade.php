@extends('layouts.superadmin')

@section('title', 'Ringkasan Dashboard')

@section('content')
<div class="page-head">
  <div>
    <h1>RINGKASAN DASHBOARD <span class="pill yellow">★ Super Admin · Semua Jurusan</span></h1>
    <p>Pantauan global performa transaksi, pengguna, dan unit produksi seluruh jurusan TeFa SMKN 4.</p>
  </div>
  <div class="actions">
    <a href="{{ route('superadmin.users.index') }}" class="btn primary">
      <svg class="i" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9.5 11a4 4 0 100-8 4 4 0 000 8zM22 21v-2a4 4 0 00-3-3.9M16 3.1a4 4 0 010 7.8"/></svg>
      Kelola User
    </a>
    <a href="{{ route('superadmin.reports.index') }}" class="btn ghost">Lihat Laporan Global →</a>
  </div>
</div>

<!-- 4 KARTU STATISTIK TINGKAT TINGGI -->
<section class="stats">
  <div class="card stat">
    <div>
      <div class="num">{{ $totalOrders }}</div>
      <div class="lbl">Total Transaksi Global</div>
      <div class="sub t-yellow">{{ $pendingOrders }} Menunggu • {{ $inProgressOrders }} Proses</div>
    </div>
    <div class="ico c-blue">
      <svg class="i" viewBox="0 0 24 24"><rect x="4" y="3" width="16" height="18" rx="3"/><path d="M8 9h8M8 13h8M8 17h4"/></svg>
    </div>
  </div>

  <div class="card stat">
    <div>
      <div class="num">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
      <div class="lbl">Pendapatan TeFa Selesai</div>
      <div class="sub t-green">{{ $completedOrders }} Transaksi Selesai</div>
    </div>
    <div class="ico c-yellow">
      <svg class="i" viewBox="0 0 24 24"><path d="M12 2v20M17 6.5C16 5 14.3 4.5 12 4.5c-3 0-5 1.3-5 3.3 0 4.7 10 2.2 10 6.8 0 2-2 3.4-5 3.4-2.5 0-4.3-.7-5.3-2.3"/></svg>
    </div>
  </div>

  <div class="card stat">
    <div>
      <div class="num">{{ $totalUsers }}</div>
      <div class="lbl">Total Pengguna Aktif</div>
      <div class="sub t-blue">{{ $totalAdmin }} Admin • {{ $totalWorker }} Worker • {{ $totalClient }} Pelanggan</div>
    </div>
    <div class="ico c-purple">
      <svg class="i" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9.5 11a4 4 0 100-8 4 4 0 000 8zM22 21v-2a4 4 0 00-3-3.9M16 3.1a4 4 0 010 7.8"/></svg>
    </div>
  </div>

  <div class="card stat">
    <div>
      <div class="num">{{ $totalJurusans }}</div>
      <div class="lbl">Total Jurusan TeFa</div>
      <div class="sub t-blue">{{ $activeJurusans }} Aktif • {{ $totalJurusans - $activeJurusans }} Nonaktif</div>
    </div>
    <div class="ico c-green">
      <svg class="i" viewBox="0 0 24 24"><path d="M12 3l9 5-9 5-9-5 9-5zM3 13l9 5 9-5M3 17.5l9 5 9-5"/></svg>
    </div>
  </div>
</section>

<!-- GRAFIK TREN BULANAN DINAMIS -->
<section class="card">
  <div class="card-head">
    <div>
      <h3>Tren Aktivitas Transaksi Global</h3>
      <p>Perbandingan pesanan masuk dan transaksi selesai dari 6 jurusan sepanjang tahun berjalan.</p>
    </div>
    <span class="chip">Tahun {{ $year }}</span>
  </div>
  <div class="card-body">
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
              <div class="bar a" style="height: {{ $inHeight }}%" data-v="{{ $inCount }}"></div>
              <div class="bar b" style="height: {{ $doneHeight }}%" data-v="{{ $doneCount }}"></div>
            </div>
            <div class="m">{{ $mName }}</div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

<!-- DUA KOLOM: REKAP JURUSAN & KOMPOSISI USER -->
<div class="two">
  <section class="card">
    <div class="card-head">
      <div>
        <h3>Rekap Pendapatan per Jurusan</h3>
        <p>Akumulasi omzet transaksi berstatus selesai dari setiap jurusan.</p>
      </div>
      <a href="{{ route('superadmin.reports.index') }}" class="btn ghost sm">Detail</a>
    </div>
    <div class="card-body">
      @php
        $barColors = ['#2b6cdb','#fbbf24','#16a870','#7c4ddb','#e0484f','#0284c7'];
      @endphp
      @forelse($revenuePerJurusan as $idx => $rev)
        @php
          $pct = $maxOmzetPerJurusan > 0 ? ($rev['omzet'] / $maxOmzetPerJurusan) * 100 : 0;
          $cColor = $barColors[$idx % count($barColors)];
        @endphp
        <div class="prog">
          <div class="top">
            <div>
              <strong>{{ $rev['jurusan']->nama_jurusan }}</strong>
              @if(!$rev['jurusan']->status_aktif)
                <span class="badge b-gray">Nonaktif</span>
              @endif
            </div>
            <span>Rp {{ number_format($rev['omzet'], 0, ',', '.') }} · {{ $rev['total_orders'] }} transaksi</span>
          </div>
          <div class="track">
            <div class="fill" style="width: {{ $pct }}%; background: {{ $cColor }}"></div>
          </div>
        </div>
      @empty
        <div class="empty">Belum ada data pendapatan per jurusan.</div>
      @endforelse
    </div>
  </section>

  <div class="stack">
    <section class="card">
      <div class="card-head">
        <h3>Komposisi Pengguna</h3>
      </div>
      <div class="card-body">
        @php
          $userTotalDivider = max(1, $totalUsers);
        @endphp
        <div class="prog">
          <div class="top">
            <span>Admin Jurusan</span>
            <span>{{ $totalAdmin }} akun ({{ round(($totalAdmin / $userTotalDivider) * 100) }}%)</span>
          </div>
          <div class="track">
            <div class="fill" style="width: {{ ($totalAdmin / $userTotalDivider) * 100 }}%; background: #2b6cdb"></div>
          </div>
        </div>

        <div class="prog">
          <div class="top">
            <span>Worker</span>
            <span>{{ $totalWorker }} akun ({{ round(($totalWorker / $userTotalDivider) * 100) }}%)</span>
          </div>
          <div class="track">
            <div class="fill" style="width: {{ ($totalWorker / $userTotalDivider) * 100 }}%; background: #fbbf24"></div>
          </div>
        </div>

        <div class="prog">
          <div class="top">
            <span>Pelanggan</span>
            <span>{{ $totalClient }} akun ({{ round(($totalClient / $userTotalDivider) * 100) }}%)</span>
          </div>
          <div class="track">
            <div class="fill" style="width: {{ ($totalClient / $userTotalDivider) * 100 }}%; background: #16a870"></div>
          </div>
        </div>
      </div>
    </section>

    <section class="card">
      <div class="card-head">
        <h3>Transaksi Terbaru</h3>
      </div>
      <div class="card-body" style="padding: 6px 22px">
        @forelse($recentOrders as $ro)
          @php
            $firstProduct = $ro->detailPesanans->first()?->produk;
            $jurName = $firstProduct?->jurusan?->nama_jurusan ?? 'Semua Jurusan';
            $stLower = strtolower($ro->status_pesanan);
            $stBadge = 'b-yellow';
            if (in_array($stLower, ['completed', 'selesai'])) $stBadge = 'b-green';
            elseif (in_array($stLower, ['in progress', 'diproses', 'proses'])) $stBadge = 'b-blue';
            elseif (in_array($stLower, ['cancelled', 'dibatalkan', 'batal'])) $stBadge = 'b-red';
          @endphp
          <div style="display:flex; justify-content:space-between; align-items:center; padding:11px 0; border-bottom:1px solid var(--line)">
            <div>
              <b style="font-size:13px">{{ $firstProduct?->nama_produk ?? ('Pesanan #' . $ro->id) }}</b>
              <small style="display:block; color:var(--muted)">{{ $jurName }} · {{ $ro->created_at->format('d M Y') }}</small>
            </div>
            <span class="badge {{ $stBadge }}">{{ $ro->status_pesanan }}</span>
          </div>
        @empty
          <div class="empty" style="padding: 16px 0">Belum ada transaksi.</div>
        @endforelse
      </div>
    </section>
  </div>
</div>
@endsection
