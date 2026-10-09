<div class="stat-grid">
  <div class="stat-card">
    <div class="stat-icon" style="background:var(--blue-l);color:var(--blue)">
      <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 9h10M7 13h6"/></svg>
    </div>
    <div class="val" data-n="{{ $totalPesanan }}">0</div>
    <div class="lbl">Total Pesanan Jurusan</div>
    <div class="delta" style="color: {{ $pesananPending > 0 ? 'var(--yellow-ink)' : 'var(--green-ink)' }}">
      {{ $pesananPending }} Menunggu &middot; {{ $pesananProses }} Proses
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon" style="background:var(--yellow-soft);color:var(--yellow-ink)">
      <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
    </div>
    <div class="val" data-n="{{ $totalPendapatan }}" data-rp="1">0</div>
    <div class="lbl">Pendapatan Selesai</div>
    <div class="delta" style="color:var(--green-ink)">
      {{ $pesananSelesai }} Pesanan Selesai
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon" style="background:#f1e8fd;color:#7c3aed">
      <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="4"/><path d="M5.5 21a6.5 6.5 0 0113 0"/></svg>
    </div>
    <div class="val" data-n="{{ $workerAktif }}">0</div>
    <div class="lbl">Worker / Siswa Aktif</div>
    <div class="delta" style="color:var(--muted)">
      Jurusan {{ $jurusan->nama_jurusan ?? 'Terkait' }}
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon" style="background:var(--green-soft);color:var(--green-ink)">
      <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8l-9-5-9 5v8l9 5 9-5V8z"/><path d="M3 8l9 5 9-5M12 13v8"/></svg>
    </div>
    <div class="val" data-n="{{ $totalProduk }}">0</div>
    <div class="lbl">Total Produk & Jasa</div>
    <div class="delta" style="color:var(--blue)">
      {{ $totalFisik }} Fisik &middot; {{ $totalJasa }} Jasa
    </div>
  </div>
</div>
