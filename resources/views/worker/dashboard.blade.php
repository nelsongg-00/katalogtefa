@extends('layouts.worker')

@section('title', 'Dashboard Worker — Workspace Produksi TEFA')

@section('content')
<div class="stack-y">

<section class="hero">
    <div>
        <span class="pill">Worker &middot; {{ auth()->user()?->jurusan?->nama_jurusan ?? 'TEFA' }}</span>
        <h1>Workspace Produksi TEFA</h1>
        <p>Semua penugasan pengerjaan projek dari Admin Jurusan terpusat di sini. Perbarui catatan timeline kerja dan serahkan berkas akhir untuk review langsung.</p>
    </div>
    <div class="acts">
        <a href="#active-tasks" class="btn-w">Lihat Penugasan Aktif</a>
    </div>
</section>

<div class="stat-grid">
  <div class="stat-card">
    <div class="stat-icon" style="background:var(--blue-l);color:var(--blue)">
      <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    </div>
    <div class="val" data-n="{{ $tugasBerjalan }}">0</div>
    <div class="lbl">Tugas Berjalan</div>
    <div class="delta" style="color:var(--blue)">Sedang dalam tahap pengerjaan</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon" style="background:var(--yellow-soft);color:var(--yellow-ink)">
      <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
    </div>
    <div class="val" data-n="{{ $menungguReview }}" style="color:var(--yellow-ink)">0</div>
    <div class="lbl">Menunggu Review</div>
    <div class="delta" style="color:var(--yellow-ink)">Berkas diserahkan ke Admin</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon" style="background:var(--green-soft);color:var(--green-ink)">
      <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    </div>
    <div class="val" data-n="{{ $tugasSelesai }}" style="color:var(--green-ink)">0</div>
    <div class="lbl">Tugas Selesai</div>
    <div class="delta" style="color:var(--green-ink)">Telah di-ACC &amp; selesai</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon" style="background:#f1e8fd;color:#7c3aed">
      <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
    </div>
    <div class="val" data-n="{{ $totalTugas }}">0</div>
    <div class="lbl">Total Penugasan</div>
    <div class="delta" style="color:var(--muted)">Akumulasi seluruh projek</div>
  </div>
</div>

<div class="card" id="active-tasks">
  <div class="card-head">
    <div>
      <h3>Daftar Penugasan Projek Aktif</h3>
      <p style="margin:3px 0 0;font-size:12px;color:var(--muted)">Projek yang dialokasikan oleh Admin Jurusan kepada Anda</p>
    </div>
  </div>
  <div class="table-responsive">
    <table class="order-table">
      <thead>
        <tr>
          <th>Nama Projek</th>
          <th>Jurusan</th>
          <th>Tenggat Waktu</th>
          <th>Status Review</th>
          <th>Progress</th>
          <th style="text-align:right">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($projects as $p)
          <tr>
            <td>
              <strong style="font-size:13.5px;color:var(--ink)">{{ $p->nama_projek }}</strong>
              @if($p->deskripsi)
                <div style="font-size:11.5px;color:var(--muted);margin-top:3px;max-width:380px;line-height:1.4">
                  {{ Str::limit($p->deskripsi, 85) }}
                </div>
              @endif
            </td>
            <td>
              <span class="badge badge-review">{{ $p->jurusan->nama_jurusan ?? 'Jurusan' }}</span>
            </td>
            <td>
              <span style="font-size:12px;font-weight:700;color:var(--ink-2)">
                {{ $p->tenggat_waktu ? $p->tenggat_waktu->format('d M Y') : '-' }}
              </span>
            </td>
            <td>
              @if($p->status_review === 'submitted')
                <span class="badge badge-submitted">Menunggu ACC Admin</span>
              @elseif($p->status_review === 'revision')
                <span class="badge badge-revision">Perlu Revisi</span>
              @elseif($p->status_review === 'approved')
                <span class="badge badge-approved">Disetujui</span>
              @else
                <span class="badge badge-draft">Draft Pengerjaan</span>
              @endif
            </td>
            <td>
              <div class="project-progress-wrap">
                <div class="project-progress-bar">
                  <div class="project-progress-fill" data-w="{{ $p->progress }}" style="background:linear-gradient(90deg,var(--blue),var(--blue-d))"></div>
                </div>
                <span class="project-progress-pct">{{ $p->progress }}%</span>
              </div>
            </td>
            <td style="text-align:right">
              <a href="{{ route('worker.projects.show', $p->id) }}" class="btn-sm-edit">Buka Lembar Kerja</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="empty">
              <div class="empty-ic">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 3h9l3 3v15H6z"/><path d="M15 3v4h4"/><path d="M9 12h6M9 16h5"/></svg>
              </div>
              <b>Belum ada penugasan projek aktif</b>
              Penugasan projek dari Admin Jurusan akan otomatis tampil di sini.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

</div>
@endsection
