@php
  $logs = $logs ?? collect();
@endphp

<style>
  .timeline-container {
    position: relative;
    padding-left: 28px;
  }
  .timeline-container::before {
    content: "";
    position: absolute;
    top: 14px;
    bottom: 14px;
    left: 11px;
    width: 2px;
    background: #e2e8f0;
  }
  .timeline-item {
    position: relative;
    margin-bottom: 24px;
  }
  .timeline-item:last-child {
    margin-bottom: 0;
  }
  .timeline-marker {
    position: absolute;
    left: -28px;
    top: 4px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #fff;
    border: 3px solid #2f6fda;
    box-shadow: 0 0 0 3px rgba(47, 111, 218, 0.12);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    z-index: 2;
  }
  .timeline-card {
    background: #fff;
    border: 1px solid #e7ebf3;
    border-radius: 12px;
    padding: 16px 18px;
    box-shadow: 0 2px 8px rgba(18, 33, 63, 0.04);
    transition: border-color .15s ease, box-shadow .15s ease;
  }
  .timeline-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 6px 16px rgba(18, 33, 63, 0.06);
  }
  .timeline-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 10px;
  }
  .timeline-author {
    display: flex;
    align-items: center;
    gap: 9px;
  }
  .timeline-author-av {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #e8f0fe;
    color: #2f6fda;
    font-weight: 800;
    font-size: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .timeline-time {
    font-size: 11px;
    color: #74809b;
    font-weight: 600;
  }
  .timeline-content {
    font-size: 13px;
    line-height: 1.6;
    color: #1e293b;
    white-space: pre-line;
  }
  .timeline-attachment-wrap {
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
  }
  .timeline-link-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #2563eb;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    transition: all .15s ease;
  }
  .timeline-link-btn:hover {
    background: #eff6ff;
    border-color: #93c5fd;
    color: #1d4ed8;
  }
  .timeline-img-preview {
    max-height: 140px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    object-fit: cover;
  }
</style>

<div class="timeline-container">
  @forelse($logs as $log)
    <div class="timeline-item">
      <div class="timeline-marker"></div>
      <div class="timeline-card">
        <div class="timeline-header">
          <div class="timeline-author">
            <div class="timeline-author-av">
              {{ strtoupper(substr($log->worker->name ?? 'WK', 0, 2)) }}
            </div>
            <div>
              <strong style="font-size: 12.5px; color: #1e293b;">{{ $log->worker->name ?? 'Worker' }}</strong>
              <span style="font-size: 11px; color: #64748b; margin-left: 4px;">• Siswa Pelaksana</span>
            </div>
          </div>
          <div class="timeline-time" title="{{ $log->created_at ? $log->created_at->format('d M Y - H:i') : '' }}">
            🕒 {{ $log->created_at ? $log->created_at->format('d M Y, H:i') : '-' }}
            <span style="font-size: 10px; color: #94a3b8;">({{ $log->created_at ? $log->created_at->diffForHumans() : '' }})</span>
          </div>
        </div>

        <div class="timeline-content">{{ $log->catatan }}</div>

        @if($log->lampiran_file || $log->link_eksternal)
          <div class="timeline-attachment-wrap">
            @if($log->lampiran_file)
              @php
                $isImg = in_array(strtolower(pathinfo($log->lampiran_file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp', 'gif']);
              @endphp

              @if($isImg)
                <div style="width: 100%; margin-bottom: 6px;">
                  <a href="{{ asset('storage/' . $log->lampiran_file) }}" target="_blank" title="Lihat ukuran penuh">
                    <img src="{{ asset('storage/' . $log->lampiran_file) }}" alt="Lampiran" class="timeline-img-preview">
                  </a>
                </div>
              @endif

              <a href="{{ asset('storage/' . $log->lampiran_file) }}" target="_blank" class="timeline-link-btn" download>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                <span>Unduh Berkas Lampiran</span>
              </a>
            @endif

            @if($log->link_eksternal)
              <a href="{{ $log->link_eksternal }}" target="_blank" class="timeline-link-btn" style="color: #7c3aed; background: #faf5ff; border-color: #e9d5ff;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                <span>Buka Tautan (Figma / GitHub / Drive)</span>
              </a>
            @endif
          </div>
        @endif
      </div>
    </div>
  @empty
    <div style="background: #fff; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 32px 20px; text-align: center; color: #64748b;">
      <div style="font-size: 32px; margin-bottom: 8px;">⏳</div>
      <div style="font-weight: 700; font-size: 13.5px; color: #1e293b;">Belum ada riwayat progres timeline</div>
      <div style="font-size: 12px; margin-top: 4px;">Tambahkan catatan pengerjaan pertama Anda pada formulir di atas.</div>
    </div>
  @endforelse
</div>
