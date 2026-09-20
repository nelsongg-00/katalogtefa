@extends('layouts.admin')

@section('title', 'PESAN MASUK — Admin ' . ($jurusan->nama_jurusan ?? 'Jurusan'))

@section('content')
  <style>
    .msg-card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      padding: 18px 22px;
      margin-bottom: 12px;
      transition: box-shadow .15s, border-color .15s;
    }
    .msg-card.unread {
      border-left: 4px solid var(--blue);
      background: #fafcff;
    }
    .msg-card:hover {
      box-shadow: var(--shadow-sm);
    }
    .msg-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 8px;
      flex-wrap: wrap;
      gap: 10px;
    }
    .msg-sender {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .msg-av {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: #e8f0fe;
      color: var(--blue);
      font-weight: 800;
      font-size: 13px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .filter-bar {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 18px;
    }
    .filter-btn {
      padding: 6px 14px;
      border-radius: 20px;
      font-size: 12.5px;
      font-weight: 700;
      color: var(--muted);
      background: #fff;
      border: 1px solid var(--border);
      text-decoration: none;
      transition: all .15s;
    }
    .filter-btn:hover {
      border-color: var(--blue);
      color: var(--blue);
    }
    .filter-btn.active {
      background: var(--blue);
      color: #fff;
      border-color: var(--blue);
    }
    .badge-unread {
      background: #fde3e4;
      color: var(--red);
      font-size: 11px;
      font-weight: 800;
      padding: 3px 8px;
      border-radius: 12px;
    }
    .badge-read {
      background: #eef1f7;
      color: var(--muted);
      font-size: 11px;
      font-weight: 700;
      padding: 3px 8px;
      border-radius: 12px;
    }
    .btn-mark-done {
      background: #eef2fd;
      color: var(--blue);
      border: 1px solid #d3defa;
      padding: 6px 12px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 700;
      cursor: pointer;
      transition: all .15s;
    }
    .btn-mark-done:hover {
      background: var(--blue);
      color: #fff;
    }
  </style>

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
                <span>📧 {{ $msg->email ?? '-' }}</span>
                @if($msg->nomor_telepon)
                  <span style="margin-left: 10px;">📱 {{ $msg->nomor_telepon }}</span>
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
                <button type="submit" class="btn-mark-done">✓ Tandai Dibaca</button>
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
        <div style="font-size: 36px; margin-bottom: 8px;">📬</div>
        <div style="font-weight: 700; color: var(--text);">Tidak ada pesan masuk</div>
        <div style="font-size: 12px; margin-top: 4px;">Pesan masuk dari formulir kontak dan notifikasi akan ditampilkan di sini.</div>
      </div>
    @endforelse

    <div style="margin-top: 18px;">
      {{ $pesanMasuks->links() }}
    </div>
  </div>
@endsection
