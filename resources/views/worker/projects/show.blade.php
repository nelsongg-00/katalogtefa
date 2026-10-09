@extends('layouts.worker')

@section('title', 'Detail Projek: ' . $project->nama_projek . ' — Worker Workspace')

@section('content')
<div class="stack-y">

<a href="{{ route('worker.dashboard') }}" class="btn-outline" style="margin-bottom:20px">&larr; Kembali ke Dashboard</a>

<div class="card" style="padding:24px 28px">
  <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-bottom:12px">
    <div>
      <h1 class="page-title">{{ $project->nama_projek }}</h1>
      <div style="font-size:12.5px;color:var(--muted);margin-top:4px">
        Jurusan: <strong>{{ $project->jurusan->nama_jurusan ?? 'Teaching Factory' }}</strong>
      </div>
    </div>
    <div>
      @if($project->status_review === 'submitted')
        <span class="badge badge-submitted">Menunggu ACC Admin</span>
      @elseif($project->status_review === 'revision')
        <span class="badge badge-revision">Perlu Revisi</span>
      @elseif($project->status_review === 'approved')
        <span class="badge badge-approved">Disetujui</span>
      @else
        <span class="badge badge-draft">Draft Pengerjaan</span>
      @endif
    </div>
  </div>

  @if($project->deskripsi)
    <p style="font-size:13.5px;color:var(--ink-2);line-height:1.6;margin:12px 0 0">{{ $project->deskripsi }}</p>
  @endif

  <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:18px;padding-top:18px;border-top:1px solid var(--line)">
    <div>
      <span style="font-size:11px;font-weight:800;color:var(--muted);text-transform:uppercase;letter-spacing:.04em">Tenggat Waktu</span>
      <div style="margin-top:4px;font-size:13.5px;font-weight:700;color:var(--ink)">
        {{ $project->tenggat_waktu ? $project->tenggat_waktu->format('d M Y') : 'Tidak ditentukan' }}
      </div>
    </div>
    <div>
      <span style="font-size:11px;font-weight:800;color:var(--muted);text-transform:uppercase;letter-spacing:.04em">Status Pengerjaan</span>
      <div style="margin-top:4px;font-size:13.5px;font-weight:700;color:var(--ink)">
        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
      </div>
    </div>
    <div>
      <span style="font-size:11px;font-weight:800;color:var(--muted);text-transform:uppercase;letter-spacing:.04em">Progress ({{ $project->progress }}%)</span>
      <div class="project-progress-wrap" style="margin-top:6px">
        <div class="project-progress-bar">
          <div class="project-progress-fill" data-w="{{ $project->progress }}" style="background:linear-gradient(90deg,var(--blue),var(--blue-d))"></div>
        </div>
        <span class="project-progress-pct">{{ $project->progress }}%</span>
      </div>
    </div>
  </div>
</div>

@if($project->status_review === 'revision' && $project->catatan_revisi_admin)
  <div class="card" style="padding:16px 20px;border-left:4px solid var(--red);margin-bottom:22px;background:var(--red-soft)">
    <div style="color:var(--red-ink);font-weight:800;font-size:13.5px;margin-bottom:6px">Catatan Koreksi / Revisi dari Admin Jurusan:</div>
    <div style="color:var(--red-ink);font-size:13px;line-height:1.5">{{ $project->catatan_revisi_admin }}</div>
    <div style="font-size:11px;color:var(--red-ink);margin-top:8px;font-weight:600">
      Mohon perbaiki berkas sesuai instruksi di atas lalu unggah kembali melalui panel Penyerahan Berkas Akhir.
    </div>
  </div>
@endif

<div style="display:grid;grid-template-columns:1.25fr .75fr;gap:22px;align-items:start">
  <div>
    <div class="card">
      <div class="card-head">
        <div>
          <h3>Tambah Catatan Lini Masa (Timeline Log)</h3>
          <p style="margin:3px 0 0;font-size:12px;color:var(--muted)">Tracking Progres Harian</p>
        </div>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('worker.projects.log', $project->id) }}" enctype="multipart/form-data">
          @csrf
          <div class="form-group">
            <label class="form-label">Catatan Pengerjaan <span style="color:var(--red)">*</span></label>
            <textarea name="catatan" rows="3" class="form-control" required placeholder="Jelaskan progres yang berhasil kamu selesaikan hari ini..."></textarea>
          </div>
          <div class="form-grid">
            <div class="form-group">
              <label class="form-label">Tautan Link Eksternal (Opsional)</label>
              <input type="url" name="link_eksternal" class="form-control" placeholder="https://figma.com/... atau github.com/...">
            </div>
            <div class="form-group">
              <label class="form-label">Update Progress (%)</label>
              <input type="number" name="progress" class="form-control" min="0" max="100" value="{{ $project->progress }}" placeholder="0-100">
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Unggah Bukti / Screenshot Pendukung (Opsional)</label>
            <input type="file" name="lampiran_file" class="form-control">
            <small style="color:var(--muted);font-size:11px">Maksimal ukuran file: 5MB (PNG, JPG, PDF, ZIP).</small>
          </div>
          <div style="text-align:right">
            <button type="submit" class="btn primary">Posting Catatan Log</button>
          </div>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-head">
        <div>
          <h3>Riwayat Lini Masa Progres (Timeline Feed)</h3>
        </div>
        <span style="font-size:11.5px;font-weight:700;color:var(--blue)">{{ $logs->count() }} Aktivitas</span>
      </div>
      <div class="card-body">
        @include('components.timeline-log', ['logs' => $logs])
      </div>
    </div>
  </div>

  <div>
    <div class="card" style="border-top:4px solid var(--blue)">
      <div class="card-head"><h3>Penyerahan Berkas Akhir (Submission)</h3></div>
      <div class="card-body">
        <p style="font-size:12px;color:var(--muted);line-height:1.5;margin-top:0">
          Area penyerahan file master/hasil akhir projek untuk diperiksa dan di-ACC oleh Admin Jurusan.
        </p>

        @if($project->file_hasil)
          <div style="background:var(--subtle);border:1px solid var(--line);border-radius:var(--r-md);padding:14px;margin-bottom:16px">
            <div style="font-size:11px;font-weight:800;color:var(--muted);text-transform:uppercase">Berkas Terakhir Diunggah:</div>
            <div style="margin-top:6px">
              <a href="{{ asset('storage/' . $project->file_hasil) }}" target="_blank" class="btn-sm-edit" download>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Unduh File Penyerahan
              </a>
            </div>
            @if($project->catatan_worker)
              <div style="margin-top:10px;font-size:12px;color:var(--ink-2);background:var(--card);padding:8px 10px;border-radius:var(--r-md);border:1px solid var(--line)">
                <em>"{{ $project->catatan_worker }}"</em>
              </div>
            @endif
          </div>
        @endif

        <form method="POST" action="{{ route('worker.projects.submit', $project->id) }}" enctype="multipart/form-data">
          @csrf
          <div class="form-group">
            <label class="form-label">Unggah Berkas Akhir (.ZIP, .PDF, .MP4, dsb) <span style="color:var(--red)">*</span></label>
            <input type="file" name="file_hasil" class="form-control" required>
            <small style="color:var(--muted);font-size:11px">Maksimal ukuran file: 10MB.</small>
          </div>
          <div class="form-group">
            <label class="form-label">Catatan untuk Admin</label>
            <textarea name="catatan_worker" rows="3" class="form-control" placeholder="Tuliskan ringkasan hasil pengerjaan atau catatan lisensi berkas...">{{ old('catatan_worker', $project->catatan_worker) }}</textarea>
          </div>
          <button type="submit" class="btn-gold" style="width:100%;justify-content:center" onclick="return confirm('Kirim berkas akhir ini ke Admin Jurusan untuk direview?')">
            Kirim untuk Review Admin
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

</div>
@endsection
