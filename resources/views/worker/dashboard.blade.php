@extends('layouts.worker')

@section('title', 'Dashboard Worker — Workspace Produksi TEFA')

@section('content')
  <style>
    .hero {
      position: relative;
      overflow: hidden;
      padding: 28px 30px;
      border-radius: 20px;
      color: white;
      background:
        radial-gradient(circle at 80% 10%, rgba(227, 183, 91, .18), transparent 28%),
        radial-gradient(circle at 10% 130%, rgba(77, 120, 255, .20), transparent 38%),
        linear-gradient(135deg, var(--navy-800), var(--navy-700));
      box-shadow: 0 16px 40px rgba(13, 27, 58, .16);
      margin-bottom: 22px;
    }
    .hero::before {
      content: "";
      position: absolute;
      inset: 0;
      background-image:
        linear-gradient(rgba(255, 255, 255, .035) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, .035) 1px, transparent 1px);
      background-size: 26px 26px;
      mask-image: linear-gradient(90deg, black, transparent 85%);
      pointer-events: none;
    }
    .hero-inner {
      position: relative;
      display: flex;
      align-items: flex-end;
      justify-content: space-between;
      gap: 20px;
    }
    .eyebrow {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      font-size: 11px;
      letter-spacing: .12em;
      text-transform: uppercase;
      color: #c7d0e9;
      font-weight: 800;
    }
    .pulse {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: #63d7a9;
      box-shadow: 0 0 0 4px rgba(99, 215, 169, .15), 0 0 12px rgba(99, 215, 169, .65);
    }
    .hero h1 {
      margin: 10px 0 8px;
      font-size: 28px;
      line-height: 1.15;
      letter-spacing: -.03em;
    }
    .hero p {
      margin: 0;
      max-width: 600px;
      color: #bac6e2;
      font-size: 13px;
      line-height: 1.6;
    }
    .hero-right {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }
    .btn-gold {
      background: var(--gold);
      color: #17203a;
      height: 38px;
      padding: 0 18px;
      border-radius: 20px;
      border: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: 12px;
      font-weight: 800;
      transition: .18s ease;
      box-shadow: 0 8px 20px rgba(227, 183, 91, .2);
    }
    .btn-gold:hover {
      filter: brightness(1.05);
      transform: translateY(-1px);
    }

    /* Metrics Grid */
    .metrics {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
      margin-bottom: 22px;
    }
    .metric {
      background: #fff;
      border: 1px solid var(--line);
      border-radius: var(--radius-card);
      padding: 18px 20px;
      box-shadow: var(--shadow-soft);
    }
    .metric-top {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .metric-label {
      font-size: 11px;
      font-weight: 800;
      letter-spacing: .04em;
      color: #7c879f;
      text-transform: uppercase;
    }
    .metric-icon {
      width: 32px;
      height: 32px;
      border-radius: 10px;
      display: grid;
      place-items: center;
      background: #f3f6fc;
      color: var(--blue);
    }
    .metric-value {
      margin-top: 10px;
      font-size: 26px;
      font-weight: 900;
      letter-spacing: -.03em;
    }
    .metric-note {
      margin-top: 6px;
      font-size: 11px;
      color: #8993a9;
    }

    /* Card & Table */
    .card {
      background: #fff;
      border: 1px solid var(--line);
      border-radius: var(--radius-card);
      box-shadow: var(--shadow-soft);
      overflow: hidden;
      margin-bottom: 22px;
    }
    .card-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 18px 22px;
      border-bottom: 1px solid var(--line);
    }
    .card-title {
      font-size: 14px;
      font-weight: 850;
      color: #1e293b;
    }
    .card-sub {
      margin-top: 3px;
      font-size: 11.5px;
      color: #8a94aa;
    }
    .table-wrap {
      overflow-x: auto;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
    }
    th {
      background: #fafbfd;
      padding: 12px 18px;
      text-align: left;
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: .08em;
      color: #8a94aa;
      font-weight: 850;
      border-bottom: 1px solid var(--line);
    }
    td {
      padding: 14px 18px;
      border-bottom: 1px solid #f0f2f6;
      vertical-align: middle;
      color: #334155;
    }
    tbody tr:hover {
      background: #f8fafc;
    }

    .badge-review {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 4px 10px;
      border-radius: 999px;
      font-size: 11px;
      font-weight: 800;
    }
    .badge-draft { background: #f1f5f9; color: #475569; }
    .badge-submitted { background: #fef3c7; color: #b45309; }
    .badge-revision { background: #fee2e2; color: #b91c1c; }
    .badge-approved { background: #dcfce7; color: #15803d; }

    .progress-wrap {
      display: flex;
      align-items: center;
      gap: 10px;
      min-width: 130px;
    }
    .progress-bar {
      flex: 1;
      height: 7px;
      background: #edf0f5;
      border-radius: 99px;
      overflow: hidden;
    }
    .progress-fill {
      height: 100%;
      border-radius: 99px;
      background: linear-gradient(90deg, #4d78ff, #2563eb);
    }
    .progress-pct {
      font-size: 11.5px;
      font-weight: 850;
      color: #475569;
      min-width: 32px;
    }

    .btn-work {
      background: #2f6fda;
      color: #fff;
      padding: 7px 14px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 750;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: background .15s ease;
    }
    .btn-work:hover {
      background: #1e56b8;
    }

    @media (max-width: 900px) {
      .metrics { grid-template-columns: repeat(2, 1fr); }
      .hero-inner { flex-direction: column; align-items: flex-start; }
    }
    @media (max-width: 500px) {
      .metrics { grid-template-columns: 1fr; }
    }
  </style>

  <!-- HERO SECTION -->
  <section class="hero">
    <div class="hero-inner">
      <div>
        <div class="eyebrow"><span class="pulse"></span> WORKER ACTIVE SHIFT • {{ now()->translatedFormat('l, d F Y') }}</div>
        <h1>Fokus ke pekerjaan yang<br>perlu selesai hari ini.</h1>
        <p>Semua penugasan pengerjaan projek dari Admin Jurusan terpusat di sini. Perbarui catatan timeline kerja dan serahkan berkas akhir untuk review langsung.</p>
      </div>
      <div class="hero-right">
        <a href="#active-tasks" class="btn-gold">
          <span>Lihat Penugasan Aktif &darr;</span>
        </a>
      </div>
    </div>
  </section>

  <!-- METRICS CARDS -->
  <div class="metrics">
    <div class="metric">
      <div class="metric-top">
        <span class="metric-label">Tugas Berjalan</span>
        <div class="metric-icon" style="background: #eff6ff; color: #2563eb;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
      </div>
      <div class="metric-value">{{ $tugasBerjalan }}</div>
      <div class="metric-note">Sedang dalam tahap pengerjaan</div>
    </div>

    <div class="metric">
      <div class="metric-top">
        <span class="metric-label">Menunggu Review</span>
        <div class="metric-icon" style="background: #fffbeb; color: #d97706;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        </div>
      </div>
      <div class="metric-value" style="color: #d97706;">{{ $menungguReview }}</div>
      <div class="metric-note">Berkas diserahkan ke Admin</div>
    </div>

    <div class="metric">
      <div class="metric-top">
        <span class="metric-label">Tugas Selesai</span>
        <div class="metric-icon" style="background: #f0fdf4; color: #16a34a;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
      </div>
      <div class="metric-value" style="color: #16a34a;">{{ $tugasSelesai }}</div>
      <div class="metric-note">Telah di-ACC &amp; selesai</div>
    </div>

    <div class="metric">
      <div class="metric-top">
        <span class="metric-label">Total Penugasan</span>
        <div class="metric-icon" style="background: #f5f3ff; color: #7c3aed;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
        </div>
      </div>
      <div class="metric-value">{{ $totalTugas }}</div>
      <div class="metric-note">Akumulasi seluruh projek</div>
    </div>
  </div>

  <!-- TABLE PENUGASAN AKTIF -->
  <div class="card" id="active-tasks">
    <div class="card-head">
      <div>
        <div class="card-title">Daftar Penugasan Projek Aktif</div>
        <div class="card-sub">Projek yang dialokasikan oleh Admin Jurusan kepada Anda</div>
      </div>
    </div>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Nama Projek</th>
            <th>Jurusan</th>
            <th>Tenggat Waktu</th>
            <th>Status Review</th>
            <th>Progress</th>
            <th style="text-align: right;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($projects as $p)
            <tr>
              <td>
                <strong style="font-size: 13.5px; color: #1e293b;">{{ $p->nama_projek }}</strong>
                @if($p->deskripsi)
                  <div style="font-size: 11.5px; color: #64748b; margin-top: 3px; max-width: 380px; line-height: 1.4;">
                    {{ Str::limit($p->deskripsi, 85) }}
                  </div>
                @endif
              </td>
              <td>
                <span style="background: #eef2ff; color: #3730a3; font-weight: 700; font-size: 11px; padding: 3px 8px; border-radius: 6px;">
                  {{ $p->jurusan->nama_jurusan ?? 'Jurusan' }}
                </span>
              </td>
              <td>
                <span style="font-size: 12px; font-weight: 700; color: #334155;">
                  {{ $p->tenggat_waktu ? $p->tenggat_waktu->format('d M Y') : '-' }}
                </span>
              </td>
              <td>
                @if($p->status_review === 'submitted')
                  <span class="badge-review badge-submitted">⏳ Menunggu ACC Admin</span>
                @elseif($p->status_review === 'revision')
                  <span class="badge-review badge-revision">⚠️ Perlu Revisi</span>
                @elseif($p->status_review === 'approved')
                  <span class="badge-review badge-approved">✓ Disetujui</span>
                @else
                  <span class="badge-review badge-draft">📝 Draft Pengerjaan</span>
                @endif
              </td>
              <td>
                <div class="progress-wrap">
                  <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ $p->progress }}%;"></div>
                  </div>
                  <span class="progress-pct">{{ $p->progress }}%</span>
                </div>
              </td>
              <td style="text-align: right;">
                <a href="{{ route('worker.projects.show', $p->id) }}" class="btn-work">
                  <span>Buka Lembar Kerja &rarr;</span>
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="text-align: center; padding: 48px 20px; color: #64748b;">
                <div style="font-size: 36px; margin-bottom: 8px;">📋</div>
                <div style="font-weight: 700; font-size: 14px; color: #1e293b;">Belum ada penugasan projek aktif</div>
                <div style="font-size: 12px; margin-top: 4px;">Penugasan projek dari Admin Jurusan akan otomatis tampil di sini.</div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
