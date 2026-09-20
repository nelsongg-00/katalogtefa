<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Worker Dashboard — Teaching Factory SMKN 4')</title>
  <style>
    :root {
      --navy-950: #0b1127;
      --navy-900: #0d1837;
      --navy-800: #16234a;
      --navy-700: #1e2e5e;
      --navy-600: #2b3d73;
      --ink: #13213f;
      --muted: #74809b;
      --line: #e7ebf3;
      --surface: #ffffff;
      --surface-2: #f7f9fc;
      --gold: #e3b75b;
      --gold-deep: #be8b2d;
      --green: #2ba77a;
      --blue: #4d78ff;
      --red: #d95f68;
      --shadow: 0 12px 32px rgba(18, 33, 63, .08);
      --shadow-soft: 0 7px 22px rgba(18, 33, 63, .06);
      --radius-card: 16px;
    }

    * { box-sizing: border-box; }
    html { scroll-behavior: smooth; }
    body {
      margin: 0;
      background: #f4f6fb;
      color: var(--ink);
      font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      -webkit-font-smoothing: antialiased;
      text-rendering: optimizeLegibility;
    }
    button, input, select, textarea { font: inherit; }
    button { cursor: pointer; }
    a { text-decoration: none; color: inherit; }

    .app {
      display: grid;
      grid-template-columns: 250px 1fr;
      min-height: 100vh;
    }

    /* Topbar */
    .topbar {
      position: fixed;
      left: 0; right: 0; top: 0;
      z-index: 30;
      width: 100%;
      height: 65px;
      padding: 0 22px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: #18285a;
      color: #fff;
      border-bottom: 1px solid rgba(255, 255, 255, .08);
      box-shadow: 0 2px 8px rgba(10, 20, 50, .14);
    }
    .top-brand {
      display: flex;
      align-items: center;
      gap: 12px;
      min-width: 0;
    }
    .top-brand-badge {
      width: 34px; height: 34px;
      border-radius: 10px;
      display: grid; place-items: center;
      background: #ffba32; color: #17305f;
      font-weight: 900; font-size: 12px;
      box-shadow: 0 5px 14px rgba(255, 183, 46, .22);
      flex: none;
    }
    .top-brand strong {
      font-size: 14px;
      letter-spacing: .01em;
      white-space: nowrap;
    }
    .top-brand a {
      margin-left: 14px;
      color: #b7c2e0;
      font-size: 12px;
      font-weight: 700;
      transition: .18s ease;
      white-space: nowrap;
    }
    .top-brand a:hover { color: #fff; }
    .top-brand a span { color: #d3ddf5; }

    .top-actions {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .top-user-copy {
      text-align: right;
      line-height: 1.15;
      margin-left: 2px;
    }
    .top-user-copy strong {
      display: block;
      font-size: 12px;
      color: #fff;
    }
    .top-user-copy span {
      display: block;
      margin-top: 3px;
      color: #ffbd2f;
      font-size: 10px;
      font-weight: 800;
    }
    .top-avatar {
      width: 36px; height: 36px;
      border-radius: 50%;
      border: 0;
      background: #ffba32;
      color: #17305f;
      font-weight: 900;
      font-size: 11px;
      display: grid;
      place-items: center;
    }
    .top-logout {
      height: 31px;
      padding: 0 13px;
      border-radius: 999px;
      border: 1px solid rgba(239, 91, 105, .42);
      background: rgba(239, 91, 105, .08);
      color: #ff8691;
      font-size: 11px;
      font-weight: 850;
      transition: all .15s;
    }
    .top-logout:hover {
      background: #f3535f;
      color: #fff;
    }

    /* Sidebar */
    .sidebar {
      position: fixed;
      inset: 65px auto 0 0;
      width: 250px;
      padding: 0;
      display: flex;
      flex-direction: column;
      background: #1f3472;
      color: #eaf0ff;
      border-right: 1px solid rgba(16, 30, 67, .12);
      overflow-y: auto;
    }
    .side-section {
      padding: 24px 22px 8px;
      color: #82a0dd;
      font-size: 10px;
      letter-spacing: .12em;
      font-weight: 850;
      text-transform: uppercase;
    }
    .nav { display: grid; gap: 0; }
    .nav-item {
      min-height: 42px;
      display: flex;
      align-items: center;
      gap: 11px;
      position: relative;
      padding: 0 22px;
      color: #e4ebff;
      font-size: 12.5px;
      font-weight: 700;
      transition: .16s ease;
    }
    .nav-item svg { width: 16px; height: 16px; color: #9cb3e9; flex: none; }
    .nav-item:hover { background: rgba(67, 101, 182, .33); color: #fff; }
    .nav-item.active {
      background: #29458d;
      color: #fff;
    }
    .nav-item.active::before {
      content: "";
      position: absolute;
      left: 0; top: 0; bottom: 0;
      width: 3px;
      background: #ffb92f;
    }
    .nav-item.active svg { color: #ffb92f; }
    .nav-badge {
      margin-left: auto;
      min-width: 21px; height: 18px;
      padding: 0 6px;
      border-radius: 999px;
      display: grid; place-items: center;
      background: #f25560; color: #fff;
      font-size: 9px; font-weight: 900;
    }

    /* Main Area */
    .main {
      grid-column: 2;
      min-width: 0;
      padding-top: 65px;
    }
    .content {
      padding: 28px 29px 34px;
    }

    /* Alerts */
    .alert-success {
      background: #eaf8f2;
      border: 1px solid #b7ebd5;
      color: #1a7a53;
      padding: 12px 18px;
      border-radius: 12px;
      font-size: 13px;
      font-weight: 700;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .alert-error {
      background: #fde8ea;
      border: 1px solid #f9bec3;
      color: #b32832;
      padding: 12px 18px;
      border-radius: 12px;
      font-size: 13px;
      font-weight: 700;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    @media (max-width: 1100px) {
      .app { grid-template-columns: 82px 1fr; }
      .sidebar { width: 82px; inset: 65px auto 0 0; }
      .side-section { padding: 20px 0 7px; text-align: center; font-size: 8px; }
      .nav-item { justify-content: center; padding: 0 8px; }
      .nav-item span, .nav-badge { display: none; }
      .nav-item.active::before { left: 0; width: 2px; }
      .main { grid-column: 2; }
      .top-brand a { display: none; }
    }
    @media (max-width: 760px) {
      .app { display: block; }
      .sidebar {
        inset: auto 0 0 0;
        width: 100%;
        height: 64px;
        padding: 0;
        z-index: 40;
        flex-direction: row;
        align-items: center;
        overflow: hidden;
      }
      .side-section { display: none; }
      .nav { display: flex; width: 100%; height: 100%; justify-content: space-around; }
      .nav-item { height: 100%; width: 25%; padding: 0; display: flex; flex-direction: column; gap: 3px; justify-content: center; }
      .nav-item span { display: block; font-size: 8px; }
      .nav-item.active::before { top: auto; left: 50%; bottom: 0; width: 20px; height: 3px; transform: translateX(-50%); }
      .nav-item svg { width: 15px; height: 15px; }
      .topbar { height: 61px; padding: 0 14px; }
      .top-brand strong { font-size: 12px; }
      .top-brand-badge { width: 31px; height: 31px; }
      .top-user-copy { display: none; }
      .top-logout { display: none; }
      .main { padding-bottom: 64px; }
      .content { padding: 18px 15px 28px; }
    }
  </style>
  @stack('styles')
</head>

<body>
  <div class="app">
    <!-- SIDEBAR -->
    <aside class="sidebar">
      <div class="side-section">MENU UTAMA</div>
      <nav class="nav">
        <a class="nav-item {{ request()->routeIs('worker.dashboard') || request()->routeIs('worker.index') ? 'active' : '' }}" href="{{ route('worker.dashboard') }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 10.5 12 4l8 6.5V20H4z"/><path d="M9 20v-5h6v5"/></svg>
          <span>Ringkasan Tugas</span>
        </a>
        <a class="nav-item {{ request()->routeIs('worker.projects.*') ? 'active' : '' }}" href="{{ route('worker.dashboard') }}#active-tasks">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 3h9l3 3v15H6z"/><path d="M15 3v4h4"/><path d="M9 12h6M9 16h5"/></svg>
          <span>Pekerjaan Aktif</span>
        </a>
      </nav>

      <div class="side-section" style="margin-top: 24px;">PORTAL TEFA</div>
      <nav class="nav">
        <a class="nav-item" href="{{ route('produk') }}" target="_blank">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 8l-9-5-9 5v8l9 5 9-5V8z"/><path d="M3 8l9 5 9-5M12 13v8"/></svg>
          <span>Katalog Produk</span>
        </a>
        <a class="nav-item" href="{{ route('jasa') }}" target="_blank">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 00.3 1.9l.1.1a2 2 0 11-2.8 2.8"/></svg>
          <span>Layanan Jasa</span>
        </a>
      </nav>

      <div class="side-section" style="margin-top: 24px;">AKUN</div>
      <nav class="nav">
        <a class="nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="3.2"/><path d="M5 20c.7-4 3-6 7-6s6.3 2 7 6"/></svg>
          <span>Profil Saya</span>
        </a>
        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
          @csrf
          <button type="submit" class="nav-item" style="border: 0; background: transparent; width: 100%; text-align: left;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10 4H5v16h5"/><path d="M13 8l4 4-4 4M9 12h8"/></svg>
            <span>Keluar</span>
          </button>
        </form>
      </nav>
    </aside>

    <!-- MAIN -->
    <main class="main">
      <header class="topbar">
        <div class="top-brand">
          <div class="top-brand-badge">WK</div>
          <strong>WORKER WORKSPACE</strong>
          <a href="{{ route('home') }}" target="_blank">Kunjungi Situs <span>&rarr;</span></a>
          <a href="{{ route('profil') }}" target="_blank">Profil Sekolah</a>
        </div>

        <div class="top-actions">
          <div class="top-user-copy">
            <strong>{{ auth()->user()->name }}</strong>
            <span>{{ auth()->user()->jurusan->nama_jurusan ?? 'Worker Produksi TEFA' }}</span>
          </div>

          <div class="top-avatar">
            {{ strtoupper(substr(auth()->user()->name ?? 'WK', 0, 2)) }}
          </div>

          <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
            @csrf
            <button type="submit" class="top-logout">Logout</button>
          </form>
        </div>
      </header>

      <section class="content">
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
      </section>
    </main>
  </div>

  @stack('scripts')
</body>
</html>
