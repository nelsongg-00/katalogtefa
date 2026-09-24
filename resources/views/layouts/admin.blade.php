<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'DASHBOARD — Admin Jurusan SMKN 4')</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    :root {
      --navy-950: #16234a;
      --navy-900: #1e2e5e;
      --navy-800: #253a72;
      --navy-700: #2f4884;
      --gold: #f5b431;
      --gold-dark: #e0a01c;
      --gold-soft: #fef3d6;
      --bg: #f4f6fb;
      --card: #ffffff;
      --border: #e5e9f2;
      --text: #1b2340;
      --muted: #7a839c;
      --blue: #2f6fdb;
      --green: #1fa971;
      --amber: #e8a723;
      --red: #e5484d;
      --radius-sm: 8px;
      --radius-md: 12px;
      --radius-lg: 16px;
      --shadow-sm: 0 1px 2px rgba(22, 35, 74, 0.04);
      --shadow-md: 0 4px 16px rgba(22, 35, 74, 0.06);
      --shadow-lg: 0 12px 32px rgba(22, 35, 74, 0.08);
    }

    * { box-sizing: border-box; }

    body {
      margin: 0;
      font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
      background: var(--bg);
      color: var(--text);
      -webkit-font-smoothing: antialiased;
    }

    a { text-decoration: none; color: inherit; }
    button { font-family: inherit; cursor: pointer; }

    ::-webkit-scrollbar { width: 8px; height: 8px; }
    ::-webkit-scrollbar-thumb { background: #c7cede; border-radius: 8px; }
    ::-webkit-scrollbar-track { background: transparent; }

    /* ---------- TOP BAR ---------- */
    .topbar {
      height: 60px;
      background: var(--navy-950);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 22px;
      position: sticky;
      top: 0;
      z-index: 50;
      box-shadow: 0 1px 0 rgba(255, 255, 255, 0.06) inset,
                  0 2px 10px rgba(13, 24, 55, 0.18);
    }

    .topbar-left { display: flex; align-items: center; gap: 26px; }

    .brand { display: flex; align-items: center; gap: 10px; }

    .brand-mark {
      width: 34px; height: 34px;
      border-radius: 10px;
      background: var(--gold);
      color: var(--navy-950);
      font-weight: 800;
      display: flex; align-items: center; justify-content: center;
      font-size: 13px;
      letter-spacing: 0.3px;
      box-shadow: 0 2px 8px rgba(245, 180, 49, 0.35);
    }

    .brand-name {
      color: #fff;
      font-weight: 800;
      font-size: 15px;
      letter-spacing: 0.5px;
    }

    .topbar-link {
      color: #b7c1e0;
      font-size: 13.5px;
      font-weight: 600;
      transition: color .15s;
    }
    .topbar-link:hover { color: #fff; }

    .topbar-right { display: flex; align-items: center; gap: 16px; position: relative; }

    /* Notifikasi Bell */
    .notif-bell-wrap {
      position: relative;
    }
    .notif-bell {
      position: relative;
      width: 36px; height: 36px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.14);
      display: flex; align-items: center; justify-content: center;
      color: #cfd6ea;
      cursor: pointer;
      transition: background .15s;
    }
    .notif-bell:hover { background: rgba(255, 255, 255, 0.16); }

    .notif-dot {
      position: absolute;
      top: -3px; right: -3px;
      background: var(--red);
      color: #fff;
      font-size: 10px;
      font-weight: 800;
      min-width: 18px; height: 18px;
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      border: 2px solid var(--navy-950);
      padding: 0 4px;
    }

    /* Notifikasi Dropdown */
    .notif-dropdown {
      position: absolute;
      top: 48px;
      right: 0;
      width: 360px;
      background: #fff;
      border-radius: var(--radius-md);
      box-shadow: var(--shadow-lg);
      border: 1px solid var(--border);
      display: none;
      flex-direction: column;
      z-index: 100;
      overflow: hidden;
      animation: fadeIn .18s ease;
    }
    .notif-dropdown.show { display: flex; }

    .notif-head {
      padding: 14px 18px;
      background: #fafbfd;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .notif-head h4 { margin: 0; font-size: 13.5px; font-weight: 800; color: var(--text); }

    .notif-list {
      max-height: 380px;
      overflow-y: auto;
    }
    .notif-item {
      padding: 12px 18px;
      border-bottom: 1px solid var(--border);
      display: flex;
      flex-direction: column;
      gap: 4px;
      transition: background .12s;
    }
    .notif-item.unread { background: #f6f8fe; }
    .notif-item:hover { background: #eef2fd; }
    .notif-sender { font-weight: 700; font-size: 12.5px; color: var(--text); display: flex; justify-content: space-between; align-items: center; }
    .notif-subject { font-weight: 600; font-size: 12px; color: var(--blue); }
    .notif-msg { font-size: 11.5px; color: var(--muted); line-height: 1.4; }
    .notif-time { font-size: 10.5px; color: var(--muted); }

    .btn-mark-read {
      background: none;
      border: none;
      color: var(--blue);
      font-size: 11px;
      font-weight: 700;
      padding: 0;
      margin-top: 4px;
      cursor: pointer;
      text-align: left;
    }
    .btn-mark-read:hover { text-decoration: underline; }

    .who { color: #dfe4f3; font-size: 13px; font-weight: 600; display: flex; flex-direction: column; align-items: flex-end; }
    .who small { color: var(--gold); font-size: 11px; font-weight: 700; }

    .avatar {
      width: 34px; height: 34px;
      border-radius: 50%;
      background: var(--gold);
      color: var(--navy-950);
      font-weight: 800;
      font-size: 12px;
      display: flex; align-items: center; justify-content: center;
      box-shadow: 0 2px 8px rgba(245, 180, 49, 0.35);
    }

    .btn-logout {
      background: rgba(229, 72, 77, 0.15);
      color: #ff8b8e;
      border: 1px solid rgba(229, 72, 77, 0.3);
      padding: 6px 12px;
      border-radius: 16px;
      font-size: 12px;
      font-weight: 700;
      transition: all .15s;
    }
    .btn-logout:hover {
      background: var(--red);
      color: #fff;
    }

    /* ---------- LAYOUT ---------- */
    .shell { display: flex; min-height: calc(100vh - 60px); }

    .sidebar {
      width: 252px;
      flex-shrink: 0;
      background: var(--navy-900);
      padding: 16px 0 24px;
      position: sticky;
      top: 60px;
      height: calc(100vh - 60px);
      overflow-y: auto;
      border-right: 1px solid rgba(255, 255, 255, 0.04);
    }

    .side-group {
      color: #8291bd;
      font-size: 10.5px;
      font-weight: 800;
      letter-spacing: 1.2px;
      padding: 18px 24px 8px;
      text-transform: uppercase;
    }

    .side-item {
      display: flex;
      align-items: center;
      gap: 11px;
      padding: 10px 24px;
      color: #c3cbe4;
      font-size: 13.5px;
      font-weight: 600;
      border-left: 3px solid transparent;
      cursor: pointer;
      transition: background .15s, color .15s;
    }
    .side-item svg { flex-shrink: 0; opacity: .85; }
    .side-item:hover { background: rgba(255, 255, 255, 0.05); color: #fff; }
    .side-item.active {
      background: var(--navy-800);
      color: #fff;
      border-left-color: var(--gold);
    }
    .side-item.active svg { opacity: 1; color: var(--gold); }

    .side-badge {
      margin-left: auto;
      background: var(--red);
      color: #fff;
      font-size: 10.5px;
      font-weight: 800;
      padding: 1px 8px;
      border-radius: 20px;
    }

    main {
      flex: 1;
      min-width: 0;
      padding: 26px 28px 60px;
    }

    /* ---------- COMMON COMPONENTS ---------- */
    .alert-success {
      background: #e4f7ee;
      border: 1px solid #b6ebd1;
      color: #167a50;
      padding: 12px 18px;
      border-radius: var(--radius-md);
      font-size: 13.5px;
      font-weight: 600;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .alert-error {
      background: #fde3e4;
      border: 1px solid #f9bec1;
      color: #b3282d;
      padding: 12px 18px;
      border-radius: var(--radius-md);
      font-size: 13.5px;
      font-weight: 600;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .page-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
      flex-wrap: wrap;
      gap: 12px;
    }

    .page-title {
      font-size: 22px;
      font-weight: 800;
      margin: 0;
      letter-spacing: -0.3px;
    }

    .page-sub {
      color: var(--muted);
      font-size: 13px;
      margin-top: 4px;
    }

    .badge-dept {
      background: var(--gold-soft);
      color: var(--gold-dark);
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 800;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      margin-bottom: 20px;
      box-shadow: var(--shadow-sm);
      overflow: hidden;
    }

    .card-head {
      padding: 18px 22px;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px;
    }

    .card-head h3 {
      margin: 0;
      font-size: 15px;
      font-weight: 800;
      letter-spacing: -0.2px;
    }

    .card-body { padding: 20px 22px; }

    /* Modal Styling */
    .modal-overlay {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(10, 20, 45, 0.55);
      backdrop-filter: blur(3px);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 1000;
      padding: 20px;
    }
    .modal-overlay.active { display: flex; }
    .modal-box {
      background: #fff;
      border-radius: var(--radius-lg);
      width: 100%;
      max-width: 520px;
      box-shadow: var(--shadow-lg);
      overflow: hidden;
      animation: modalSlideUp .2s ease;
    }
    .modal-head {
      padding: 18px 24px;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: #fafbfd;
    }
    .modal-head h3 { margin: 0; font-size: 16px; font-weight: 800; }
    .modal-close {
      background: none;
      border: none;
      font-size: 20px;
      color: var(--muted);
      cursor: pointer;
      line-height: 1;
    }
    .modal-body { padding: 22px 24px; }
    .form-group { margin-bottom: 16px; }
    .form-label {
      display: block;
      font-size: 12.5px;
      font-weight: 700;
      color: var(--text);
      margin-bottom: 6px;
    }
    .form-control {
      width: 100%;
      padding: 9px 14px;
      border: 1px solid var(--border);
      border-radius: 8px;
      font-size: 13px;
      font-family: inherit;
      outline: none;
      transition: border-color .15s;
      background: #fafbfd;
    }
    .form-control:focus {
      border-color: var(--blue);
      background: #fff;
    }

    .btn-gold {
      background: var(--gold);
      color: var(--navy-950);
      border: none;
      padding: 9px 18px;
      border-radius: 20px;
      font-weight: 800;
      font-size: 13px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: background .15s, box-shadow .15s;
      box-shadow: 0 3px 10px rgba(245, 180, 49, 0.35);
    }
    .btn-gold:hover {
      background: var(--gold-dark);
      box-shadow: 0 4px 14px rgba(245, 180, 49, 0.45);
    }

    /* ---------- PAGINATION STYLING ---------- */
    .pagination-wrapper {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 14px;
      padding: 6px 0;
      font-size: 13px;
      color: var(--muted);
      width: 100%;
    }

    .pagination-info {
      display: flex;
      align-items: center;
      gap: 5px;
      color: #64748b;
      font-size: 12.5px;
    }

    .pagination-info strong {
      color: #0f172a;
      font-weight: 700;
    }

    .pagination-links {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      flex-wrap: wrap;
    }

    .page-numbers {
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }

    .page-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 7px 14px;
      background: #ffffff;
      border: 1px solid var(--border);
      border-radius: 8px;
      color: #334155;
      font-size: 12.5px;
      font-weight: 700;
      text-decoration: none;
      cursor: pointer;
      transition: all .15s ease;
      box-shadow: 0 1px 2px rgba(0,0,0,0.04);
      user-select: none;
    }

    .page-btn:hover:not(.disabled) {
      background: #f8fafd;
      border-color: var(--blue);
      color: var(--blue);
      transform: translateY(-1px);
    }

    .page-btn.disabled {
      background: #f8fafc;
      color: #94a3b8;
      border-color: #e2e8f0;
      cursor: not-allowed;
      opacity: 0.65;
      box-shadow: none;
    }

    .page-num {
      min-width: 34px;
      height: 34px;
      padding: 0 8px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 700;
      color: #475569;
      text-decoration: none;
      border: 1px solid var(--border);
      background: #fff;
      transition: all .15s ease;
      user-select: none;
    }

    .page-num:hover:not(.active):not(.dots) {
      border-color: var(--blue);
      color: var(--blue);
      background: #eff6ff;
    }

    .page-num.active {
      background: #2563eb;
      border-color: #2563eb;
      color: #fff;
      box-shadow: 0 2px 6px rgba(37,99,235,0.3);
    }

    .page-num.dots {
      border: none;
      background: transparent;
      color: #94a3b8;
      cursor: default;
    }

    /* Fallback and sizing for standard Laravel pagination elements */
    nav[role="navigation"] {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 12px;
      width: 100%;
    }

    nav[role="navigation"] svg {
      width: 16px !important;
      height: 16px !important;
      max-width: 16px !important;
      max-height: 16px !important;
      display: inline-block;
      vertical-align: middle;
      flex-shrink: 0;
    }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes modalSlideUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }

    @media (max-width: 980px) {
      .sidebar { display: none; }
      main { padding: 18px 16px 40px; }
      .notif-dropdown { width: 300px; right: -50px; }
    }
  </style>
  @stack('styles')
</head>
<body>

  <!-- TOPBAR -->
  <header class="topbar">
    <div class="topbar-left">
      <div class="brand">
        <div class="brand-mark">DB</div>
        <div class="brand-name">DASHBOARD</div>
      </div>
      <a class="topbar-link" href="{{ route('home') }}" target="_blank">Kunjungi Situs &rarr;</a>
      <a class="topbar-link" href="{{ route('profil') }}" target="_blank">Profil Sekolah</a>
    </div>

    <div class="topbar-right">
      <!-- Ikon Lonceng Notifikasi Pesan Masuk -->
      <div class="notif-bell-wrap">
        <button type="button" class="notif-bell" id="bellBtn" title="Notifikasi Pesan Masuk">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 8a6 6 0 10-12 0c0 7-3 9-3 9h18s-3-2-3-9"/>
            <path d="M13.73 21a2 2 0 01-3.46 0"/>
          </svg>
          @if(isset($unreadMessagesCount) && $unreadMessagesCount > 0)
            <span class="notif-dot">{{ $unreadMessagesCount }}</span>
          @endif
        </button>

        <!-- Dropdown Pesan Masuk -->
        <div class="notif-dropdown" id="notifDropdown">
          <div class="notif-head">
            <h4>Pesan Masuk</h4>
            <span style="font-size: 11px; font-weight: 700; color: var(--blue);">
              {{ $unreadMessagesCount ?? 0 }} Belum Dibaca
            </span>
          </div>
          <div class="notif-list">
            @forelse($recentMessages ?? $pesanMasuks ?? [] as $msg)
              <div class="notif-item {{ !$msg->is_read ? 'unread' : '' }}">
                <div class="notif-sender">
                  <span>{{ $msg->nama_pengirim }}</span>
                  <span class="notif-time">{{ $msg->created_at ? $msg->created_at->diffForHumans() : '' }}</span>
                </div>
                @if($msg->subjek)
                  <div class="notif-subject">{{ $msg->subjek }}</div>
                @endif
                <div class="notif-msg">{{ Str::limit($msg->pesan, 85) }}</div>

                @if(!$msg->is_read)
                  <form method="POST" action="{{ route('admin.messages.read', $msg->id) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-mark-read">✓ Tandai sudah dibaca</button>
                  </form>
                @endif
              </div>
            @empty
              <div style="padding: 24px 18px; text-align: center; color: var(--muted); font-size: 12.5px;">
                Belum ada pesan masuk.
              </div>
            @endforelse
          </div>
        </div>
      </div>

      <!-- Info User & Logout -->
      <div class="who">
        <span>{{ auth()->user()?->name ?? 'Admin' }}</span>
        <small>{{ auth()->user()?->jurusan?->nama_jurusan ?? 'Admin Jurusan' }}</small>
      </div>
      <div class="avatar">{{ strtoupper(substr(auth()->user()?->name ?? 'AD', 0, 2)) }}</div>
      <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
        @csrf
        <button type="submit" class="btn-logout" title="Keluar dari sistem">Logout</button>
      </form>
    </div>
  </header>

  <!-- MAIN LAYOUT -->
  <div class="shell">
    @include('admin.layouts.sidebar')

    <main>
      @if(session('success'))
        <div class="alert-success">
          <span>✓</span>
          <span>{{ session('success') }}</span>
        </div>
      @endif

      @if(session('error'))
        <div class="alert-error">
          <span>⚠️</span>
          <span>{{ session('error') }}</span>
        </div>
      @endif

      @yield('content')
    </main>
  </div>

  <script>
    // Toggle notifikasi dropdown
    const bellBtn = document.getElementById('bellBtn');
    const notifDropdown = document.getElementById('notifDropdown');

    if (bellBtn && notifDropdown) {
      bellBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        notifDropdown.classList.toggle('show');
      });

      document.addEventListener('click', function(e) {
        if (!notifDropdown.contains(e.target) && e.target !== bellBtn) {
          notifDropdown.classList.remove('show');
        }
      });
    }
  </script>

  @stack('scripts')
</body>
</html>
