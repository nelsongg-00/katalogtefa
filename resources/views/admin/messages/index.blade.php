@extends('layouts.admin')

@section('title', 'PESAN MASUK — Admin ' . ($jurusan->nama_jurusan ?? 'Jurusan'))

@section('content')
  <div class="page-head">
    <div>
      <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
        <h1 class="page-title">PESAN MASUK & NOTIFIKASI</h1>
        @if($unreadCount > 0)
          <span class="badge-unread">{{ $unreadCount }} Pesan Baru</span>
        @endif
      </div>
      <p class="page-sub">Riwayat konsultasi, pesan kontak dari calon klien, dan notifikasi pesanan.</p>
    </div>
  </div>

  <div class="filter-bar">
    <a href="{{ route('admin.messages.index') }}" class="filter-btn {{ $filter === 'all' ? 'active' : '' }}">
      Semua Pesan ({{ $pesanMasuks->total() }})
    </a>
    <a href="{{ route('admin.messages.index', ['filter' => 'unread']) }}" class="filter-btn {{ $filter === 'unread' ? 'active' : '' }}">
      Belum Dibaca ({{ $unreadCount }})
    </a>
  </div>

  <div class="msg-list-wrap">
    @forelse($pesanMasuks as $msg)
      <div class="msg-card {{ !$msg->is_read ? 'unread' : '' }}">
        <div class="msg-header">
          <div class="msg-sender">
            <div class="msg-av">{{ strtoupper(substr($msg->nama_pengirim ?? 'U', 0, 2)) }}</div>
            <div>
              <div style="display: flex; align-items: center; gap: 8px;">
                <strong style="font-size: 14px; color: var(--text);">{{ $msg->nama_pengirim }}</strong>
                @if(!$msg->is_read)
                  <span class="badge-unread">Baru</span>
                @else
                  <span class="badge-read">Sudah Dibaca</span>
                @endif
              </div>
              <div style="font-size: 12px; color: var(--muted); margin-top: 2px;">
                <span>{{ $msg->email ?? '-' }}</span>
                @if($msg->nomor_telepon)
                  <span style="margin-left: 10px;">{{ $msg->nomor_telepon }}</span>
                @endif
              </div>
            </div>
          </div>

          <div style="display: flex; align-items: center; gap: 12px;">
            <span style="font-size: 11.5px; color: var(--muted);">
              {{ $msg->created_at ? $msg->created_at->format('d M Y, H:i') . ' (' . $msg->created_at->diffForHumans() . ')' : '-' }}
            </span>
            @if(!$msg->is_read)
              <form method="POST" action="{{ route('admin.messages.read', $msg->id) }}" style="margin: 0;">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn-mark-done">Tandai Dibaca</button>
              </form>
            @endif
          </div>
        </div>

        @if($msg->subjek)
          <div style="font-weight: 700; font-size: 13.5px; color: var(--blue); margin-bottom: 6px;">
            {{ $msg->subjek }}
          </div>
        @endif

        <div style="font-size: 13px; color: var(--text); line-height: 1.6; background: #fafbfd; padding: 12px 14px; border-radius: 8px; border: 1px solid var(--border);">
          {{ $msg->pesan }}
        </div>
      </div>
    @empty
      <div class="card" style="padding: 48px 20px; text-align: center; color: var(--muted);">
        <div style="font-weight: 700; color: var(--text);">Tidak ada pesan masuk</div>
        <div style="font-size: 12px; margin-top: 4px;">Pesan masuk dari formulir kontak dan notifikasi akan ditampilkan di sini.</div>
      </div>
    @endforelse

    <div style="margin-top: 18px;">
      {{ $pesanMasuks->links() }}
    </div>
  </div>
@endsection
