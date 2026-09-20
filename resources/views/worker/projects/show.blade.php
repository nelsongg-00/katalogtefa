@extends('layouts.worker')

@section('title', 'Detail Projek: ' . $project->nama_projek . ' — Worker Workspace')

@section('content')
  <style>
    .page-nav {
      margin-bottom: 20px;
    }
    .back-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 13px;
      font-weight: 700;
      color: #4b6dd6;
      background: #fff;
      padding: 8px 16px;
      border-radius: 20px;
      border: 1px solid var(--line);
      transition: all .15s ease;
    }
    .back-btn:hover {
      background: #eef3ff;
      border-color: #93adff;
    }

    .project-header-card {
      background: #fff;
      border: 1px solid var(--line);
      border-radius: var(--radius-card);
      padding: 24px 28px;
      margin-bottom: 24px;
      box-shadow: var(--shadow-soft);
    }
    .project-title-row {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 16px;
      flex-wrap: wrap;
      margin-bottom: 12px;
    }
    .project-title {
      font-size: 22px;
      font-weight: 850;
      color: #1e293b;
      margin: 0;
    }
    .badge-review-lg {
      padding: 6px 14px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 850;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .badge-draft { background: #f1f5f9; color: #475569; }
    .badge-submitted { background: #fef3c7; color: #b45309; }
    .badge-revision { background: #fee2e2; color: #b91c1c; }
    .badge-approved { background: #dcfce7; color: #15803d; }

    .project-meta-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 16px;
      margin-top: 18px;
      padding-top: 18px;
      border-top: 1px solid #f0f2f6;
    }
    .meta-label {
      font-size: 11px;
      font-weight: 800;
      color: #7c879f;
      text-transform: uppercase;
      letter-spacing: .04em;
    }
    .meta-val {
      margin-top: 4px;
      font-size: 13.5px;
      font-weight: 750;
      color: #1e293b;
    }

    /* Revision Alert Box */
    .alert-revision-box {
      background: #fff5f5;
      border: 1px solid #fecaca;
      border-left: 5px solid #ef4444;
      border-radius: 12px;
      padding: 16px 20px;
      margin-bottom: 24px;
    }
    .alert-revision-title {
      color: #b91c1c;
      font-weight: 850;
      font-size: 13.5px;
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 6px;
    }
    .alert-revision-text {
      color: #7f1d1d;
      font-size: 13px;
      line-height: 1.5;
    }

    /* Layout 2 Kolom */
    .grid-workspace {
      display: grid;
      grid-template-columns: 1.25fr .75fr;
      gap: 22px;
      align-items: start;
    }

    /* Card Panels */
    .panel-card {
      background: #fff;
      border: 1px solid var(--line);
      border-radius: var(--radius-card);
      box-shadow: var(--shadow-soft);
      overflow: hidden;
      margin-bottom: 22px;
    }
    .panel-head {
      padding: 18px 22px;
      border-bottom: 1px solid var(--line);
      background: #fafbfd;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .panel-title {
      font-size: 14px;
      font-weight: 850;
      color: #1e293b;
      margin: 0;
    }
    .panel-body {
      padding: 20px 22px;
    }

    /* Forms */
    .form-group {
      margin-bottom: 14px;
    }
    .form-label {
      display: block;
      font-size: 12px;
      font-weight: 750;
      color: #334155;
      margin-bottom: 6px;
    }
    .form-control {
      width: 100%;
      padding: 9px 13px;
      border: 1px solid #d1d5db;
      border-radius: 8px;
      font-size: 13px;
      font-family: inherit;
      background: #fff;
      outline: none;
      transition: border-color .15s ease, box-shadow .15s ease;
    }
    .form-control:focus {
      border-color: #2f6fda;
      box-shadow: 0 0 0 3px rgba(47, 111, 218, 0.12);
    }
    .btn-submit {
      background: #2f6fda;
      color: #fff;
      border: none;
      padding: 10px 18px;
      border-radius: 8px;
      font-weight: 800;
      font-size: 13px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: background .15s ease;
    }
    .btn-submit:hover {
      background: #1e56b8;
    }

    .btn-accent {
      background: #e3b75b;
      color: #17203a;
      border: none;
      padding: 10px 20px;
      border-radius: 8px;
      font-weight: 850;
      font-size: 13px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      width: 100%;
      justify-content: center;
      transition: filter .15s ease;
    }
    .btn-accent:hover {
      filter: brightness(1.05);
    }

    @media (max-width: 960px) {
      .grid-workspace { grid-template-columns: 1fr; }
      .project-meta-grid { grid-template-columns: 1fr 1fr; }
    }
  </style>

  <!-- NAVIGASI KEMBALI -->
  <div class="page-nav">
    <a href="{{ route('worker.dashboard') }}" class="back-btn">
      <span>&larr; Kembali ke Dashboard</span>
    </a>
  </div>

  <!-- INFORMASI UTAMA TUGAS PROJEK -->
  <div class="project-header-card">
    <div class="project-title-row">
      <div>
        <h1 class="project-title">{{ $project->nama_projek }}</h1>
        <div style="font-size: 12.5px; color: #64748b; margin-top: 4px;">
          Jurusan: <strong>{{ $project->jurusan->nama_jurusan ?? 'Teaching Factory' }}</strong>
        </div>
      </div>

      <div>
        @if($project->status_review === 'submitted')
          <span class="badge-review-lg badge-submitted">⏳ Menunggu ACC Admin</span>
        @elseif($project->status_review === 'revision')
          <span class="badge-review-lg badge-revision">⚠️ Perlu Revisi</span>
        @elseif($project->status_review === 'approved')
          <span class="badge-review-lg badge-approved">✓ Disetujui</span>
        @else
          <span class="badge-review-lg badge-draft">📝 Draft Pengerjaan</span>
        @endif
      </div>
    </div>

    @if($project->deskripsi)
      <p style="font-size: 13.5px; color: #334155; line-height: 1.6; margin: 12px 0 0;">
        {{ $project->deskripsi }}
      </p>
    @endif

    <div class="project-meta-grid">
      <div>
        <span class="meta-label">Tenggat Waktu (Deadline)</span>
        <div class="meta-val">
          📅 {{ $project->tenggat_waktu ? $project->tenggat_waktu->format('d M Y') : 'Tidak ditentukan' }}
        </div>
      </div>

      <div>
        <span class="meta-label">Status Pengerjaan</span>
        <div class="meta-val">
          {{ ucfirst(str_replace('_', ' ', $project->status)) }}
        </div>
      </div>

      <div>
        <span class="meta-label">Persentase Progress ({{ $project->progress }}%)</span>
        <div style="margin-top: 6px; display: flex; align-items: center; gap: 8px;">
          <div style="flex: 1; height: 8px; background: #edf0f5; border-radius: 99px; overflow: hidden;">
            <div style="height: 100%; width: {{ $project->progress }}%; background: linear-gradient(90deg, #4d78ff, #2563eb); border-radius: 99px;"></div>
          </div>
          <span style="font-size: 12px; font-weight: 800; color: #475569;">{{ $project->progress }}%</span>
        </div>
      </div>
    </div>
  </div>

  <!-- KOTAK CATATAN REVISI DARI ADMIN (JIKA ADA) -->
  @if($project->status_review === 'revision' && $project->catatan_revisi_admin)
    <div class="alert-revision-box">
      <div class="alert-revision-title">
        <span>⚠️ Catatan Koreksi / Revisi dari Admin Jurusan:</span>
      </div>
      <div class="alert-revision-text">
        {{ $project->catatan_revisi_admin }}
      </div>
      <div style="font-size: 11px; color: #991b1b; margin-top: 8px; font-weight: 600;">
        Mohon perbaiki berkas sesuai instruksi di atas lalu unggah kembali melalui panel Penyerahan Berkas Akhir.
      </div>
    </div>
  @endif

  <!-- WORKSPACE 2 KOLOM -->
  <div class="grid-workspace">
    <!-- KOLOM KIRI: FORM LOG + TIMELINE FEED -->
    <div>
      <!-- FORM TAMBAH LOG PROGRES -->
      <div class="panel-card">
        <div class="panel-head">
          <h2 class="panel-title">Tambah Catatan Lini Masa (Timeline Log)</h2>
          <span style="font-size: 11px; color: #64748b;">Tracking Progres Harian</span>
        </div>
        <div class="panel-body">
          <form method="POST" action="{{ route('worker.projects.log', $project->id) }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
              <label class="form-label">Catatan Pengerjaan <span style="color: #ef4444;">*</span></label>
              <textarea name="catatan" rows="3" class="form-control" required placeholder="Jelaskan progres yang berhasil kamu selesaikan hari ini..."></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
              <div class="form-group">
                <label class="form-label">Tautan Link Eksternal (Opsional)</label>
                <input type="url" name="link_eksternal" class="form-control" placeholder="https://figma.com/... atau github.com/...">
              </div>

              <div class="form-group">
                <label class="form-label">Update Progress Pengerjaan (%)</label>
                <input type="number" name="progress" class="form-control" min="0" max="100" value="{{ $project->progress }}" placeholder="0-100">
              </div>
            </div>

            <div class="form-group">
              <label class="form-label">Unggah Bukti / Screenshot Pendukung (Opsional)</label>
              <input type="file" name="lampiran_file" class="form-control">
              <small style="color: #64748b; font-size: 11px;">Maksimal ukuran file: 5MB (PNG, JPG, PDF, ZIP).</small>
            </div>

            <div style="margin-top: 14px; text-align: right;">
              <button type="submit" class="btn-submit">
                <span>+ Posting Catatan Log</span>
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- TIMELINE FEED KOMPONEN (REUSABLE) -->
      <div class="panel-card">
        <div class="panel-head">
          <h2 class="panel-title">Riwayat Lini Masa Progres (Timeline Feed)</h2>
          <span style="font-size: 11.5px; font-weight: 700; color: #2563eb;">{{ $logs->count() }} Aktivitas</span>
        </div>
        <div class="panel-body">
          @include('components.timeline-log', ['logs' => $logs])
        </div>
      </div>
    </div>

    <!-- KOLOM KANAN: PENYERAHAN BERKAS AKHIR (SUBMISSION BOX) -->
    <div>
      <div class="panel-card" style="border-top: 4px solid var(--gold);">
        <div class="panel-head">
          <h2 class="panel-title">Penyerahan Berkas Akhir (Submission)</h2>
        </div>
        <div class="panel-body">
          <p style="font-size: 12px; color: #64748b; line-height: 1.5; margin-top: 0;">
            Area penyerahan file master/hasil akhir projek untuk diperiksa dan di-ACC oleh Admin Jurusan.
          </p>

          <!-- STATUS SUBMISSION SEBELUMNYA JIKA SUDAH ADA -->
          @if($project->file_hasil)
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; margin-bottom: 16px;">
              <div style="font-size: 11px; font-weight: 800; color: #475569; text-transform: uppercase;">Berkas Terakhir Diunggah:</div>
              <div style="margin-top: 6px;">
                <a href="{{ asset('storage/' . $project->file_hasil) }}" target="_blank" class="timeline-link-btn" download>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                  <span>Unduh File Penyerahan</span>
                </a>
              </div>
              @if($project->catatan_worker)
                <div style="margin-top: 10px; font-size: 12px; color: #334155; background: #fff; padding: 8px 10px; border-radius: 6px; border: 1px solid #e2e8f0;">
                  <em>"{{ $project->catatan_worker }}"</em>
                </div>
              @endif
            </div>
          @endif

          <form method="POST" action="{{ route('worker.projects.submit', $project->id) }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
              <label class="form-label">Unggah Berkas Akhir (.ZIP, .PDF, .MP4, dsb) <span style="color: #ef4444;">*</span></label>
              <input type="file" name="file_hasil" class="form-control" required>
              <small style="color: #64748b; font-size: 11px;">Maksimal ukuran file: 10MB.</small>
            </div>

            <div class="form-group">
              <label class="form-label">Catatan untuk Admin</label>
              <textarea name="catatan_worker" rows="3" class="form-control" placeholder="Tuliskan ringkasan hasil pengerjaan atau catatan lisensi berkas...">{{ old('catatan_worker', $project->catatan_worker) }}</textarea>
            </div>

            <div style="margin-top: 18px;">
              <button type="submit" class="btn-accent" onclick="return confirm('Kirim berkas akhir ini ke Admin Jurusan untuk direview?');">
                <span>🚀 Kirim untuk Review Admin</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
