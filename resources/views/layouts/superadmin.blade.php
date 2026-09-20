<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard Super Admin') — TeFa SMKN 4</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
    :root{
      --header:#16224a;
      --sidebar:#1e2b5c;
      --sidebar-active:#2a3a73;
      --bg:#f3f6fb;
      --card:#ffffff;
      --ink:#0f1b3d;
      --muted:#64748b;
      --line:#e6ebf3;
      --yellow:#fbbf24;
      --yellow-soft:#fef3c7;
      --yellow-ink:#d99a06;
      --blue:#2b6cdb;
      --blue-soft:#e6eefc;
      --green:#16a870;
      --green-soft:#dcf5ea;
      --red:#e0484f;
      --red-soft:#fde8e9;
      --purple:#7c4ddb;
      --purple-soft:#eee7fc;
      --radius:16px;
    }
    *{box-sizing:border-box;margin:0;padding:0}
    html{scroll-behavior:smooth}
    body{font-family:'Plus Jakarta Sans',system-ui,-apple-system,'Segoe UI',Roboto,sans-serif;background:var(--bg);color:var(--ink);font-size:14px;line-height:1.5;-webkit-font-smoothing:antialiased}
    button,input,select,textarea{font-family:inherit;font-size:inherit;color:inherit}
    button{cursor:pointer;border:none;background:none}
    a{text-decoration:none;color:inherit}
    :focus-visible{outline:2px solid var(--yellow);outline-offset:2px}
    svg.i{width:18px;height:18px;fill:none;stroke:currentColor;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round;flex:none}

    /* ---------- Header ---------- */
    .topbar{position:fixed;inset:0 0 auto 0;height:62px;background:var(--header);display:flex;align-items:center;justify-content:space-between;padding:0 22px;z-index:50;color:#fff}
    .topbar .left,.topbar .right{display:flex;align-items:center;gap:18px}
    .brand{display:flex;align-items:center;gap:12px;font-weight:800;font-size:17px;letter-spacing:.2px}
    .brand .logo{width:34px;height:34px;border-radius:10px;background:var(--yellow);color:var(--header);display:grid;place-items:center;font-size:13px;font-weight:800}
    .toplink{color:#c9d3f0;font-weight:600;font-size:13.5px;text-decoration:none;transition:color .15s}
    .toplink:hover{color:#fff}
    .menu-btn{display:none;color:#fff;width:36px;height:36px;border-radius:10px;place-items:center}
    .menu-btn:hover{background:rgba(255,255,255,.1)}
    .bell{position:relative;width:38px;height:38px;border-radius:50%;background:#26346a;color:#dbe3fb;display:grid;place-items:center}
    .bell:hover{background:#2f3f7b}
    .bell .dot{position:absolute;top:-3px;right:-3px;background:var(--red);color:#fff;font-size:10px;font-weight:700;min-width:17px;height:17px;border-radius:9px;display:grid;place-items:center;padding:0 4px}
    .whoami{text-align:right;line-height:1.25}
    .whoami b{display:block;font-size:13.5px}
    .whoami span{font-size:11.5px;color:var(--yellow);font-weight:700}
    .avatar{width:36px;height:36px;border-radius:50%;background:var(--yellow);color:var(--header);font-weight:800;font-size:12.5px;display:grid;place-items:center;flex:none}
    .logout{border:1px solid #6b2f47;background:#3b2441;color:#ff8d95;font-weight:700;font-size:12.5px;padding:7px 16px;border-radius:999px;transition:background .15s}
    .logout:hover{background:#4b2a4d}

    .notif{position:absolute;top:52px;right:0;width:320px;background:#fff;color:var(--ink);border-radius:14px;box-shadow:0 18px 40px rgba(15,27,61,.22);padding:8px;display:none}
    .notif.open{display:block}
    .notif h4{font-size:13px;padding:8px 10px 6px;border-bottom:1px solid var(--line)}
    .notif .row{display:flex;gap:10px;padding:9px 10px;border-radius:10px;font-size:12.5px;align-items:center}
    .notif .row:hover{background:var(--bg)}
    .notif .row small{display:block;color:var(--muted);font-size:11px}
    .notif .ic{width:30px;height:30px;border-radius:9px;display:grid;place-items:center;flex:none}
    .rel{position:relative}

    /* ---------- Sidebar ---------- */
    .sidebar{position:fixed;top:62px;left:0;bottom:0;width:252px;background:var(--sidebar);padding:22px 0;overflow-y:auto;z-index:40;transition:transform .25s}
    .nav-label{font-size:11px;font-weight:700;letter-spacing:.9px;color:#8b9ac9;padding:0 24px;margin:18px 0 8px;text-transform:uppercase}
    .nav-label:first-child{margin-top:0}
    .nav-item{display:flex;align-items:center;gap:14px;width:100%;padding:11px 24px;color:#c3cdee;font-weight:600;font-size:14px;text-align:left;border-left:3px solid transparent;transition:background .15s,color .15s}
    .nav-item:hover{background:rgba(255,255,255,.05);color:#fff}
    .nav-item.active{background:var(--sidebar-active);color:#fff;border-left-color:var(--yellow)}
    .nav-item .count{margin-left:auto;background:var(--red);color:#fff;font-size:10.5px;font-weight:700;border-radius:9px;padding:1px 7px}
    .scrim{display:none;position:fixed;inset:62px 0 0 0;background:rgba(10,16,40,.5);z-index:39}

    /* ---------- Main ---------- */
    main{margin-left:252px;padding:92px 28px 40px}
    .page-head{display:flex;justify-content:space-between;align-items:flex-start;gap:20px;flex-wrap:wrap;margin-bottom:24px}
    .page-head h1{font-size:23px;font-weight:800;letter-spacing:-.2px;display:flex;align-items:center;gap:12px;flex-wrap:wrap}
    .pill{font-size:12px;font-weight:700;padding:4px 12px;border-radius:999px}
    .pill.yellow{background:var(--yellow-soft);color:var(--yellow-ink)}
    .page-head p{color:var(--muted);margin-top:4px;max-width:640px}
    .actions{display:flex;gap:10px;flex-wrap:wrap}
    .btn{display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:999px;font-weight:700;font-size:13.5px;transition:transform .12s,box-shadow .15s,background .15s;cursor:pointer}
    .btn:active{transform:translateY(1px)}
    .btn.primary{background:var(--yellow);color:var(--header);box-shadow:0 6px 16px rgba(251,191,36,.35)}
    .btn.primary:hover{background:#f7b50f}
    .btn.ghost{background:#fff;border:1px solid var(--line);color:var(--ink)}
    .btn.ghost:hover{background:#f8fafd}
    .btn.danger{background:var(--red);color:#fff}
    .btn.danger:hover{background:#c73b42}
    .btn.sm{padding:7px 14px;font-size:12.5px}

    .card{background:var(--card);border-radius:var(--radius);box-shadow:0 2px 10px rgba(22,34,74,.05);border:1px solid rgba(230,235,243,.7)}
    .card-head{display:flex;justify-content:space-between;align-items:center;gap:12px;padding:18px 22px;border-bottom:1px solid var(--line);flex-wrap:wrap}
    .card-head h3{font-size:15px;font-weight:800}
    .card-head p{color:var(--muted);font-size:12.5px;margin-top:1px}
    .card-body{padding:20px 22px}

    .stats{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:22px}
    .stat{padding:20px;display:flex;justify-content:space-between;align-items:flex-start;gap:10px}
    .stat .num{font-size:26px;font-weight:800;line-height:1.15;letter-spacing:-.3px}
    .stat .lbl{color:var(--muted);font-size:12.5px;margin-top:3px}
    .stat .sub{font-size:12px;font-weight:700;margin-top:6px}
    .stat .ico{width:44px;height:44px;border-radius:12px;display:grid;place-items:center;flex:none}
    .c-blue{background:var(--blue-soft);color:var(--blue)}
    .c-yellow{background:var(--yellow-soft);color:#e79c07}
    .c-purple{background:var(--purple-soft);color:var(--purple)}
    .c-green{background:var(--green-soft);color:var(--green)}
    .t-blue{color:var(--blue)}.t-yellow{color:var(--yellow-ink)}.t-green{color:var(--green)}.t-red{color:var(--red)}.t-muted{color:var(--muted)}

    /* Chart */
    .chip{background:var(--blue-soft);color:var(--blue);font-size:12px;font-weight:700;padding:4px 12px;border-radius:999px}
    .legend{display:flex;justify-content:flex-end;gap:18px;font-size:12.5px;font-weight:600;color:#3b4a6b;margin-bottom:8px}
    .legend i{display:inline-block;width:14px;height:14px;border-radius:50%;margin-right:6px;vertical-align:-2px}
    .chart{position:relative;height:240px;margin-left:34px}
    .chart .grid{position:absolute;inset:0 0 26px 0}
    .chart .grid div{position:absolute;left:0;right:0;border-top:1px solid var(--line)}
    .chart .grid span{position:absolute;left:-30px;top:-9px;font-size:11.5px;color:var(--muted);width:24px;text-align:right}
    .chart .cols{position:absolute;inset:0;display:grid;grid-template-columns:repeat(12,1fr)}
    .chart .col{display:flex;flex-direction:column;justify-content:flex-end;align-items:center}
    .chart .bars{flex:1;width:100%;display:flex;align-items:flex-end;justify-content:center;gap:3px}
    .chart .bar{width:17px;border-radius:5px 5px 0 0;position:relative;min-height:0;transition:height .5s cubic-bezier(.2,.8,.2,1)}
    .chart .bar.a{background:var(--blue)}
    .chart .bar.b{background:var(--green)}
    .chart .bar:hover::after{content:attr(data-v);position:absolute;top:-24px;left:50%;transform:translateX(-50%);background:var(--ink);color:#fff;font-size:11px;font-weight:700;padding:1px 7px;border-radius:6px;white-space:nowrap;z-index:10}
    .chart .m{height:26px;font-size:11.5px;color:var(--muted);font-weight:600;display:grid;place-items:end center}

    .two{display:grid;grid-template-columns:1.35fr 1fr;gap:16px;margin-top:22px}
    .stack{display:flex;flex-direction:column;gap:16px}

    /* Tables */
    .tbl-wrap{overflow-x:auto}
    table{width:100%;border-collapse:collapse;min-width:640px}
    th{font-size:11.5px;font-weight:700;color:var(--muted);text-align:left;padding:11px 16px;background:#f8fafd;border-bottom:1px solid var(--line);white-space:nowrap}
    td{padding:12px 16px;border-bottom:1px solid var(--line);vertical-align:middle}
    tbody tr:hover{background:#fafcff}
    tbody tr:last-child td{border-bottom:none}
    td.r,th.r{text-align:right}
    .person{display:flex;align-items:center;gap:11px}
    .person .avatar{width:34px;height:34px;font-size:11.5px}
    .person b{display:block;font-weight:700}
    .person small{color:var(--muted);font-size:12px}
    .badge{display:inline-block;font-size:11.5px;font-weight:700;padding:3px 11px;border-radius:999px;white-space:nowrap}
    .b-yellow{background:var(--yellow-soft);color:var(--yellow-ink)}
    .b-blue{background:var(--blue-soft);color:var(--blue)}
    .b-green{background:var(--green-soft);color:var(--green)}
    .b-red{background:var(--red-soft);color:var(--red)}
    .b-purple{background:var(--purple-soft);color:var(--purple)}
    .b-gray{background:#edf0f6;color:#55627f}
    .row-actions{display:flex;gap:4px;justify-content:flex-end}
    .icon-btn{width:32px;height:32px;border-radius:9px;display:grid;place-items:center;color:var(--muted);transition:background .15s,color .15s}
    .icon-btn:hover{background:var(--blue-soft);color:var(--blue)}
    .icon-btn.del:hover{background:var(--red-soft);color:var(--red)}
    .empty{text-align:center;padding:38px 20px;color:var(--muted)}
    .empty b{display:block;color:var(--ink);margin-bottom:2px}

    .toolbar{display:flex;gap:10px;flex-wrap:wrap;padding:16px 22px;border-bottom:1px solid var(--line);align-items:center}
    .field{display:flex;flex-direction:column;gap:5px}
    .field label{font-size:12px;font-weight:700;color:#3b4a6b}
    .inp,.sel{border:1px solid #d8e0ee;background:#fff;border-radius:11px;padding:9px 13px;min-width:0;transition:border-color .15s,box-shadow .15s}
    .inp:focus,.sel:focus{outline:none;border-color:var(--blue);box-shadow:0 0 0 3px rgba(43,108,219,.15)}
    .search{position:relative;flex:1;min-width:200px}
    .search svg{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);width:16px;height:16px}
    .search .inp{width:100%;padding-left:36px}

    .mini-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:18px}
    .mini{padding:16px 18px}
    .mini small{display:block;color:var(--muted);font-size:12px;font-weight:600}
    .mini b{font-size:20px;font-weight:800}

    /* Progress rows */
    .prog{margin-bottom:16px}
    .prog:last-child{margin-bottom:0}
    .prog .top{display:flex;justify-content:space-between;font-weight:700;font-size:13px;margin-bottom:6px}
    .prog .top span{color:var(--muted);font-weight:600}
    .track{height:8px;background:#edf1f8;border-radius:6px;overflow:hidden}
    .fill{height:100%;border-radius:6px;transition:width .5s}

    /* Modal */
    .modal{position:fixed;inset:0;background:rgba(10,16,40,.55);display:none;align-items:center;justify-content:center;padding:20px;z-index:100}
    .modal.open{display:flex;animation:fade .15s}
    @keyframes fade{from{opacity:0}to{opacity:1}}
    .dialog{background:#fff;border-radius:20px;width:100%;max-width:520px;max-height:92vh;overflow:auto;box-shadow:0 30px 70px rgba(10,16,40,.4);animation:pop .2s cubic-bezier(.2,.8,.2,1)}
    @keyframes pop{from{transform:translateY(12px) scale(.98)}to{transform:none}}
    .dialog header{display:flex;justify-content:space-between;align-items:center;padding:18px 24px;border-bottom:1px solid var(--line)}
    .dialog header h3{font-size:16px;font-weight:800}
    .dialog .body{padding:22px 24px}
    .dialog footer{display:flex;justify-content:flex-end;gap:10px;padding:16px 24px;background:#f8fafd;border-radius:0 0 20px 20px}
    .hint{font-size:12px;color:var(--muted);margin-top:4px}
    .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
    .form-grid .full{grid-column:1/-1}
    .form-grid .inp,.form-grid .sel{width:100%}

    .toast-wrap{position:fixed;right:22px;bottom:22px;display:flex;flex-direction:column;gap:10px;z-index:200}
    .toast{background:var(--header);color:#fff;padding:12px 18px;border-radius:12px;font-weight:600;font-size:13px;box-shadow:0 12px 30px rgba(10,16,40,.3);border-left:4px solid var(--yellow);animation:pop .25s}
    .toast.err{border-left-color:var(--red)}

    /* Responsive */
    @media (max-width:1100px){.stats,.mini-stats{grid-template-columns:repeat(2,1fr)}.two{grid-template-columns:1fr}}
    @media (max-width:900px){
      .menu-btn{display:grid}
      .sidebar{transform:translateX(-100%)}
      .sidebar.open{transform:none}
      .scrim.open{display:block}
      main{margin-left:0;padding:84px 16px 32px}
      .toplink,.whoami{display:none}
    }
    @media (max-width:560px){
      .stats,.mini-stats{grid-template-columns:1fr}
      .form-grid{grid-template-columns:1fr}
      .logout{display:none}
      .chart .bar{width:9px}
    }
    </style>
    @stack('styles')
</head>
<body>

<!-- ====== TOPBAR ====== -->
<header class="topbar">
  <div class="left">
    <button class="menu-btn" id="menuBtn" aria-label="Buka menu"><svg class="i" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
    <a href="{{ route('superadmin.dashboard') }}" class="brand">
        <div class="logo">DB</div>
        <span>SUPER ADMIN</span>
    </a>
    <a class="toplink" href="{{ route('home') }}" target="_blank">Kunjungi Situs →</a>
    <a class="toplink" href="{{ route('profil') }}" target="_blank">Profil Sekolah</a>
  </div>
  <div class="right">
    <div class="rel">
      <button class="bell" id="bellBtn" aria-label="Notifikasi"><svg class="i" viewBox="0 0 24 24"><path d="M18 8a6 6 0 10-12 0c0 7-3 9-3 9h18s-3-2-3-9M13.7 21a2 2 0 01-3.4 0"/></svg><span class="dot" id="bellCount">1</span></button>
      <div class="notif" id="notif">
        <h4>Aktivitas TeFa</h4>
        <div class="row">
          <div class="ic c-blue"><svg class="i" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></div>
          <div>Sistem aktif dan siap monitoring transaksi seluruh jurusan.<small>Hari ini</small></div>
        </div>
      </div>
    </div>
    <div class="whoami">
        <b>{{ auth()->user()->name ?? 'Super Admin' }}</b>
        <span>Superuser Sistem</span>
    </div>
    <div class="avatar">SA</div>
    <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf
        <button type="submit" class="logout" onclick="return confirm('Apakah Anda yakin ingin logout dari sesi Super Admin?')">Logout</button>
    </form>
  </div>
</header>

<div class="scrim" id="scrim"></div>

<!-- ====== SIDEBAR ====== -->
<nav class="sidebar" id="sidebar" aria-label="Menu utama">
  <div class="nav-label">Menu Utama</div>
  <a href="{{ route('superadmin.dashboard') }}" class="nav-item {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
    <svg class="i" viewBox="0 0 24 24"><path d="M3 11l9-8 9 8v9a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1z"/></svg>
    <span>Ringkasan</span>
  </a>
  <a href="{{ route('superadmin.departments.index') }}" class="nav-item {{ request()->routeIs('superadmin.departments.*') ? 'active' : '' }}">
    <svg class="i" viewBox="0 0 24 24"><path d="M12 3l9 5-9 5-9-5 9-5zM3 13l9 5 9-5M3 17.5l9 5 9-5"/></svg>
    <span>Data Jurusan</span>
  </a>
  <a href="{{ route('superadmin.users.index') }}" class="nav-item {{ request()->routeIs('superadmin.users.*') ? 'active' : '' }}">
    <svg class="i" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9.5 11a4 4 0 100-8 4 4 0 000 8zM22 21v-2a4 4 0 00-3-3.9M16 3.1a4 4 0 010 7.8"/></svg>
    <span>Manajemen User</span>
  </a>
  <a href="{{ route('superadmin.reports.index') }}" class="nav-item {{ request()->routeIs('superadmin.reports.*') ? 'active' : '' }}">
    <svg class="i" viewBox="0 0 24 24"><path d="M14 3H6a2 2 0 00-2 2v14a2 2 0 002 2h12a2 2 0 002-2V9zM14 3v6h6M8 13h8M8 17h5"/></svg>
    <span>Laporan Transaksi</span>
  </a>
</nav>

<!-- ====== KONTEN UTAMA ====== -->
<main id="view" tabindex="-1">
    @yield('content')
</main>

<div class="toast-wrap" id="toasts" aria-live="polite">
    @if(session('success'))
        <div class="toast">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="toast err">{{ session('error') }}</div>
    @endif
    @if(session('warning'))
        <div class="toast" style="border-left-color:var(--yellow)">{{ session('warning') }}</div>
    @endif
</div>

<script>
const $ = (s, r=document) => r.querySelector(s);
const $$ = (s, r=document) => [...r.querySelectorAll(s)];

// Mobile sidebar toggle
const menuBtn = $('#menuBtn');
const sidebar = $('#sidebar');
const scrim = $('#scrim');

if (menuBtn) {
    menuBtn.onclick = () => {
        sidebar.classList.toggle('open');
        scrim.classList.toggle('open');
    };
}
if (scrim) {
    scrim.onclick = () => {
        sidebar.classList.remove('open');
        scrim.classList.remove('open');
    };
}

// Notification toggle
const bellBtn = $('#bellBtn');
const notif = $('#notif');
if (bellBtn && notif) {
    bellBtn.onclick = (e) => {
        e.stopPropagation();
        notif.classList.toggle('open');
        const bellCount = $('#bellCount');
        if (bellCount) bellCount.style.display = 'none';
    };
    document.addEventListener('click', (e) => {
        if (!e.target.closest('#notif')) notif.classList.remove('open');
    });
}

// Auto dismiss session toasts after 4s
setTimeout(() => {
    $$('.toast').forEach(t => t.remove());
}, 4000);
</script>
@stack('scripts')
</body>
</html>
