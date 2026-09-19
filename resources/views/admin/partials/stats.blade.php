<style>
  .stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 20px;
  }

  .stat-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 18px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: var(--shadow-sm);
    transition: box-shadow .18s, transform .18s;
  }
  .stat-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-1px);
  }

  .stat-card .val {
    font-size: 24px;
    font-weight: 800;
    letter-spacing: -0.4px;
  }
  .stat-card .lbl {
    font-size: 12px;
    color: var(--muted);
    font-weight: 600;
    margin-top: 3px;
  }
  .stat-card .delta {
    font-size: 11.5px;
    font-weight: 700;
    margin-top: 6px;
  }

  .stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  @media (max-width: 980px) {
    .stat-grid { grid-template-columns: repeat(2, 1fr); }
  }
  @media (max-width: 580px) {
    .stat-grid { grid-template-columns: 1fr; }
  }
</style>

<div class="stat-grid">
  <!-- 1. Total Pesanan -->
  <div class="stat-card">
    <div>
      <div class="val">{{ $totalPesanan }}</div>
      <div class="lbl">Total Pesanan Jurusan</div>
      <div class="delta" style="color: {{ $pesananPending > 0 ? 'var(--amber)' : 'var(--green)' }}">
        {{ $pesananPending }} Menunggu • {{ $pesananProses }} Proses
      </div>
    </div>
    <div class="stat-icon" style="background:#e8f0ff;color:var(--blue)">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 9h10M7 13h6"/></svg>
    </div>
  </div>

  <!-- 2. Pendapatan Jurusan -->
  <div class="stat-card">
    <div>
      <div class="val">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
      <div class="lbl">Pendapatan Selesai</div>
      <div class="delta" style="color:var(--green)">
        {{ $pesananSelesai }} Pesanan Selesai
      </div>
    </div>
    <div class="stat-icon" style="background:#fdf1d9;color:var(--gold-dark)">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
    </div>
  </div>

  <!-- 3. Worker / Siswa -->
  <div class="stat-card">
    <div>
      <div class="val">{{ $workerAktif }}</div>
      <div class="lbl">Worker / Siswa Aktif</div>
      <div class="delta" style="color:var(--muted)">
        Jurusan {{ $jurusan->nama_jurusan ?? 'Terkait' }}
      </div>
    </div>
    <div class="stat-icon" style="background:#eee7fd;color:#7237c9">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="7" r="4"/><path d="M5.5 21a6.5 6.5 0 0113 0"/></svg>
    </div>
  </div>

  <!-- 4. Total Produk & Jasa -->
  <div class="stat-card">
    <div>
      <div class="val">{{ $totalProduk }}</div>
      <div class="lbl">Total Produk & Jasa</div>
      <div class="delta" style="color:var(--blue)">
        {{ $totalFisik }} Fisik • {{ $totalJasa }} Jasa
      </div>
    </div>
    <div class="stat-icon" style="background:#e4f7ee;color:var(--green)">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 8l-9-5-9 5v8l9 5 9-5V8z"/><path d="M3 8l9 5 9-5M12 13v8"/></svg>
    </div>
  </div>
</div>
