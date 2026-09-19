<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Admin Dashboard — TEFA Hub SMKN 4')</title>
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
      font-size: 14px;
      letter-spacing: 0.3px;
      box-shadow: 0 2px 8px rgba(245, 180, 49, 0.35);
    }

    .brand-name {
      color: #fff;
      font-weight: 800;
      font-size: 15px;
      letter-spacing: 0.3px;
    }

    .topbar-link {
      color: #b7c1e0;
      font-size: 13.5px;
      font-weight: 600;
      transition: color .15s;
    }
    .topbar-link:hover { color: #fff; }

    .topbar-right { display: flex; align-items: center; gap: 14px; }

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

    @media (max-width: 980px) {
      .sidebar { display: none; }
      main { padding: 18px 16px 40px; }
    }
  </style>
  @stack('styles')
</head>
<body>

  <!-- TOPBAR -->
  <header class="topbar">
    <div class="topbar-left">
      <div class="brand">
        <div class="brand-mark">TH</div>
        <div class="brand-name">TEFA Hub</div>
      </div>
      <a class="topbar-link" href="{{ route('home') }}" target="_blank">Kunjungi Situs &rarr;</a>
      <a class="topbar-link" href="{{ route('profil') }}" target="_blank">Profil Sekolah</a>
    </div>
    <div class="topbar-right">
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
    <aside class="sidebar">
      <div class="side-group">Menu Utama</div>
      <a href="{{ route('admin.dashboard') }}" class="side-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 10l9-7 9 7"/><path d="M5 9v11h14V9"/></svg>
        <span>Dashboard</span>
      </a>
      <a href="#orders-section" class="side-item">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 9h10M7 13h6"/></svg>
        <span>Kelola Pesanan</span>
        @if(isset($pesananPending) && $pesananPending > 0)
          <span class="side-badge">{{ $pesananPending }}</span>
        @endif
      </a>

      <div class="side-group">Katalog & Siswa</div>
      <a href="{{ route('produk') }}" target="_blank" class="side-item">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 8l-9-5-9 5v8l9 5 9-5V8z"/><path d="M3 8l9 5 9-5M12 13v8"/></svg>
        <span>Katalog Publik</span>
      </a>
      <a href="{{ route('jasa') }}" target="_blank" class="side-item">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 00.3 1.9l.1.1a2 2 0 11-2.8 2.8"/></svg>
        <span>Layanan Jasa</span>
      </a>

      <div class="side-group">Akun</div>
      <a href="{{ route('profile.edit') }}" class="side-item">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 00.3 1.9l.1.1a2 2 0 11-2.8 2.8l-.1-.1a1.7 1.7 0 00-1.9-.3 1.7 1.7 0 00-1 1.5V21a2 2 0 01-4 0v-.1a1.7 1.7 0 00-1-1.6 1.7 1.7 0 00-1.9.3l-.1.1a2 2 0 11-2.8-2.8l.1-.1a1.7 1.7 0 00.3-1.9 1.7 1.7 0 00-1.5-1H3a2 2 0 010-4h.1a1.7 1.7 0 001.5-1 1.7 1.7 0 00-.3-1.9l-.1-.1a2 2 0 112.8-2.8l.1.1a1.7 1.7 0 001.9.3H9a1.7 1.7 0 001-1.5V3a2 2 0 014 0v.1a1.7 1.7 0 001 1.5 1.7 1.7 0 001.9-.3l.1-.1a2 2 0 112.8 2.8l-.1.1a1.7 1.7 0 00-.3 1.9V9a1.7 1.7 0 001.5 1H21a2 2 0 010 4h-.1a1.7 1.7 0 00-1.5 1z"/></svg>
        <span>Edit Profil</span>
      </a>
    </aside>

    <main>
      @if(session('success'))
        <div class="alert-success">
          <span>✓</span>
          <span>{{ session('success') }}</span>
        </div>
      @endif

      @yield('content')
    </main>
  </div>

  @stack('scripts')
</body>
</html>
