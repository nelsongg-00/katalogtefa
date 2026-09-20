<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Worker Dashboard — PrintHub</title>
  <style>
    :root{
      --navy-950:#0b1127;
      --navy-900:#0d1837;
      --navy-800:#16234a;
      --navy-700:#1e2e5e;
      --navy-600:#2b3d73;
      --ink:#13213f;
      --muted:#74809b;
      --line:#e7ebf3;
      --surface:#ffffff;
      --surface-2:#f7f9fc;
      --gold:#e3b75b;
      --gold-deep:#be8b2d;
      --green:#2ba77a;
      --blue:#4d78ff;
      --red:#d95f68;
      --shadow:0 12px 32px rgba(18, 33, 63, .08);
      --shadow-soft:0 7px 22px rgba(18, 33, 63, .06);
      --radius-card:16px;
    }

    *{box-sizing:border-box}
    html{scroll-behavior:smooth}
    body{
      margin:0;
      background:var(--surface-2);
      color:var(--ink);
      font-family:Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      -webkit-font-smoothing:antialiased;
      text-rendering:optimizeLegibility;
    }
    button,input,select{font:inherit}
    button{cursor:pointer}
    a{text-decoration:none;color:inherit}

    .app{
      display:grid;
      grid-template-columns:264px 1fr;
      min-height:100vh;
    }

    /* Sidebar */
    .sidebar{
      position:fixed;
      inset:0 auto 0 0;
      width:264px;
      z-index:20;
      display:flex;
      flex-direction:column;
      padding:24px 16px;
      background:
        radial-gradient(circle at 20% 0%, rgba(227,183,91,.11), transparent 22%),
        linear-gradient(180deg,var(--navy-800),var(--navy-950));
      color:#eef2ff;
      border-right:1px solid rgba(255,255,255,.07);
    }
    .brand{
      display:flex;align-items:center;gap:11px;
      padding:2px 8px 26px;
    }
    .brand-mark{
      width:38px;height:38px;border-radius:12px;
      display:grid;place-items:center;
      background:linear-gradient(135deg,#f0cb78,#c69b41);
      color:#10172f;
      font-weight:900;
      box-shadow:0 8px 24px rgba(227,183,91,.24);
      position:relative;
      overflow:hidden;
    }
    .brand-mark::after{
      content:"";position:absolute;width:40px;height:10px;
      background:rgba(255,255,255,.28);transform:rotate(-35deg);top:5px;left:-12px;
    }
    .brand-copy strong{display:block;font-size:14px;letter-spacing:.02em}
    .brand-copy span{display:block;margin-top:2px;font-size:11px;color:#9ba8cb}

    .side-section{
      padding:10px 8px 6px;
      text-transform:uppercase;
      letter-spacing:.14em;
      font-size:9px;
      font-weight:800;
      color:#69769a;
    }
    .nav{
      display:grid;gap:4px;
    }
    .nav-item{
      position:relative;
      display:flex;align-items:center;gap:11px;
      padding:11px 12px;
      border-radius:12px;
      color:#bac5e2;
      font-size:13px;font-weight:650;
      transition:.18s ease;
    }
    .nav-item svg{width:17px;height:17px;opacity:.82}
    .nav-item:hover{background:rgba(255,255,255,.06);color:white}
    .nav-item.active{
      background:linear-gradient(90deg,rgba(227,183,91,.15),rgba(255,255,255,.05));
      color:white;
      box-shadow:inset 1px 0 0 rgba(227,183,91,.85);
    }
    .nav-item.active::before{
      content:"";width:3px;height:19px;border-radius:9px;
      position:absolute;left:-1px;top:50%;transform:translateY(-50%);
      background:var(--gold);
      box-shadow:0 0 12px rgba(227,183,91,.55);
    }
    .nav-badge{
      margin-left:auto;
      min-width:21px;height:21px;padding:0 6px;
      border-radius:999px;
      display:grid;place-items:center;
      background:rgba(255,255,255,.08);
      color:#dce4ff;font-size:10px;
    }

    .side-spacer{flex:1}
    .worker-card{
      display:flex;align-items:center;gap:10px;
      padding:11px;
      background:rgba(255,255,255,.045);
      border:1px solid rgba(255,255,255,.06);
      border-radius:15px;
    }
    .avatar{
      width:34px;height:34px;border-radius:50%;
      display:grid;place-items:center;
      background:linear-gradient(145deg,#fff0bd,#d8ab4f);
      color:#15203c;
      font-size:11px;font-weight:900;
      box-shadow:0 0 0 4px rgba(227,183,91,.09),0 7px 18px rgba(227,183,91,.20);
    }
    .worker-meta{min-width:0}
    .worker-meta strong{display:block;font-size:12px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .worker-meta span{display:block;margin-top:3px;color:#8490b1;font-size:10px}

    /* Main */
    .main{grid-column:2;min-width:0}
    .topbar{
      position:sticky;top:0;z-index:10;
      height:72px;
      display:flex;align-items:center;justify-content:space-between;
      padding:0 34px;
      background:rgba(255,255,255,.92);
      backdrop-filter:blur(14px);
      border-bottom:1px solid var(--line);
      box-shadow:0 1px 0 rgba(15,31,61,.04), inset 0 -1px 0 rgba(255,255,255,.6);
    }
    .crumbs{display:flex;align-items:center;gap:9px;font-size:12px;color:var(--muted)}
    .crumbs strong{color:var(--ink)}
    .top-actions{display:flex;align-items:center;gap:12px}
    .search{
      width:245px;height:40px;border-radius:999px;
      display:flex;align-items:center;gap:8px;padding:0 14px;
      background:#f4f6fa;border:1px solid transparent;
      transition:.18s ease;
    }
    .search:focus-within{
      background:white;border-color:#93adff;
      box-shadow:0 0 0 4px rgba(77,120,255,.12);
    }
    .search svg{width:15px;color:#7f8aa3;flex:none}
    .search input{border:0;outline:0;background:transparent;width:100%;font-size:12px;color:var(--ink)}
    .search input::placeholder{color:#98a2b7}
    .icon-btn{
      width:40px;height:40px;border:0;border-radius:12px;
      display:grid;place-items:center;background:#f4f6fa;color:#62708e;
      position:relative;transition:.18s ease;
    }
    .icon-btn:hover{background:#ebeff6;color:var(--ink)}
    .notif-dot{
      position:absolute;right:9px;top:8px;width:6px;height:6px;border-radius:50%;
      background:#e76b75;box-shadow:0 0 0 4px rgba(231,107,117,.12),0 0 10px rgba(231,107,117,.55);
    }

    .content{padding:28px 34px 34px}
    .hero{
      position:relative;overflow:hidden;
      padding:28px 30px;
      border-radius:22px;
      color:white;
      background:
        radial-gradient(circle at 80% 10%,rgba(227,183,91,.18),transparent 28%),
        radial-gradient(circle at 10% 130%,rgba(77,120,255,.20),transparent 38%),
        linear-gradient(135deg,var(--navy-800),var(--navy-700));
      box-shadow:0 16px 40px rgba(13,27,58,.16);
    }
    .hero::before{
      content:"";position:absolute;inset:0;
      background-image:
        linear-gradient(rgba(255,255,255,.035) 1px,transparent 1px),
        linear-gradient(90deg,rgba(255,255,255,.035) 1px,transparent 1px);
      background-size:26px 26px;
      mask-image:linear-gradient(90deg,black,transparent 85%);
      pointer-events:none;
    }
    .hero-inner{position:relative;display:flex;align-items:flex-end;justify-content:space-between;gap:20px}
    .eyebrow{display:inline-flex;align-items:center;gap:7px;font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:#c7d0e9;font-weight:800}
    .pulse{width:7px;height:7px;border-radius:50%;background:#63d7a9;box-shadow:0 0 0 4px rgba(99,215,169,.1),0 0 12px rgba(99,215,169,.65)}
    .hero h1{margin:9px 0 7px;font-size:31px;line-height:1.05;letter-spacing:-.035em}
    .hero p{margin:0;max-width:590px;color:#bac6e2;font-size:12px;line-height:1.7}
    .hero-right{display:flex;gap:10px;flex-wrap:wrap;justify-content:flex-end}
    .btn{
      height:38px;padding:0 15px;border-radius:22px;
      border:1px solid transparent;display:inline-flex;align-items:center;justify-content:center;gap:8px;
      font-size:11px;font-weight:800;transition:.18s ease;
      white-space:nowrap;
    }
    .btn svg{width:14px;height:14px}
    .btn-gold{background:var(--gold);color:#17203a;box-shadow:0 8px 20px rgba(227,183,91,.16)}
    .btn-gold:hover{transform:translateY(-1px);filter:brightness(1.03)}
    .btn-outline{background:rgba(255,255,255,.06);border-color:rgba(255,255,255,.16);color:#f4f7ff}
    .btn-outline:hover{background:rgba(255,255,255,.1)}

    .metrics{
      display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-top:18px;
    }
    .metric{
      background:var(--surface);border:1px solid var(--line);border-radius:var(--radius-card);
      padding:17px 18px;box-shadow:var(--shadow-soft);
    }
    .metric-top{display:flex;justify-content:space-between;align-items:center}
    .metric-label{font-size:10px;font-weight:800;letter-spacing:.04em;color:#7c879f;text-transform:uppercase}
    .metric-icon{
      width:29px;height:29px;border-radius:9px;display:grid;place-items:center;
      background:#f3f6fc;color:var(--blue)
    }
    .metric-icon svg{width:14px;height:14px}
    .metric-value{margin-top:12px;font-size:25px;font-weight:850;letter-spacing:-.03em}
    .metric-note{margin-top:6px;font-size:10px;color:#8993a9}
    .metric-note b{color:#2b9c75}

    .grid-2{
      display:grid;grid-template-columns:1.18fr .82fr;gap:16px;margin-top:16px;
    }
    .card{
      background:var(--surface);border:1px solid var(--line);border-radius:var(--radius-card);
      box-shadow:var(--shadow-soft);overflow:hidden;
    }
    .card-head{
      display:flex;align-items:center;justify-content:space-between;
      padding:17px 18px 12px;
    }
    .card-title{font-size:13px;font-weight:850;letter-spacing:-.01em}
    .card-sub{margin-top:4px;font-size:10px;color:#8a94aa}
    .head-link{font-size:10px;color:#5576d8;font-weight:800}
    .head-link:hover{text-decoration:underline}

    .orders{margin-top:16px}
    .table-wrap{overflow:auto}
    table{width:100%;border-collapse:collapse;min-width:760px}
    th,td{padding:14px 18px;text-align:left;border-top:1px solid #f0f2f6}
    th{font-size:9px;letter-spacing:.11em;text-transform:uppercase;color:#8a94aa;font-weight:850;background:#fafbfd}
    td{font-size:11px;color:#43506c}
    .order-id{font-weight:900;color:#21304f;letter-spacing:.02em}
    .customer{display:flex;align-items:center;gap:9px}
    .mini-avatar{
      width:27px;height:27px;border-radius:8px;background:#edf2ff;color:#5576d8;
      display:grid;place-items:center;font-size:9px;font-weight:900;
    }
    .customer span{font-weight:750;color:#34425e}
    .service{font-weight:750}
    .status{
      display:inline-flex;align-items:center;gap:6px;padding:6px 9px;border-radius:999px;
      font-size:9px;font-weight:850;
    }
    .status::before{content:"";width:6px;height:6px;border-radius:50%}
    .status-progress{background:#edf3ff;color:#4b6dd6}.status-progress::before{background:#4d78ff}
    .status-review{background:#fff5dd;color:#ac7b1c}.status-review::before{background:#e3b75b}
    .status-done{background:#eaf8f2;color:#247c5d}.status-done::before{background:#2ba77a}
    .deadline{font-weight:800}
    .muted{color:#9aa4b7;font-weight:650}
    .row-action{
      width:31px;height:31px;border:1px solid #e6eaf1;border-radius:10px;background:#fff;
      display:grid;place-items:center;color:#697792;
    }
    .row-action:hover{background:#f6f8fb;color:var(--ink)}

    .work-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:16px}
    .work-card{padding-bottom:5px}
    .task{padding:13px 18px;border-top:1px solid #f0f2f6;display:flex;align-items:center;gap:12px}
    .task-main{flex:1;min-width:0}
    .task-main strong{display:block;font-size:11px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .task-main span{display:block;margin-top:4px;color:#929caf;font-size:10px}
    .progress{height:6px;border-radius:99px;background:#edf0f5;margin-top:9px;overflow:hidden}
    .progress span{height:100%;display:block;border-radius:99px;background:linear-gradient(90deg,#4d78ff,#6f8cf6)}
    .percent{font-size:10px;font-weight:900;color:#586580}

    .footer-note{
      padding:16px 2px 2px;text-align:center;color:#a0a8b9;font-size:9px
    }

    .toast{
      position:fixed;right:24px;bottom:24px;z-index:50;
      padding:11px 13px;border-radius:13px;color:#eef3ff;background:#172542;
      box-shadow:0 15px 35px rgba(16,32,61,.22);
      font-size:10px;font-weight:750;
      transform:translateY(20px);opacity:0;pointer-events:none;transition:.22s ease;
    }
    .toast.show{transform:none;opacity:1}
  </style>

<style id="reference-sidebar-topbar">
  :root{
    --reference-navy:#1b2e66;
    --reference-navy-dark:#162657;
    --reference-blue:#2f6fda;
    --reference-gold:#ffb72e;
  }

  body{background:#f4f6fb}
  .app{grid-template-columns:250px 1fr;min-height:100vh}

  /* Screenshot-matched top bar */
  .topbar{
    position:fixed;left:0;right:0;top:0;z-index:30;
    width:100%;
    height:65px;padding:0 22px;
    display:flex;align-items:center;justify-content:space-between;
    background:#18285a;
    color:#fff;
    border:0;
    border-bottom:1px solid rgba(255,255,255,.08);
    box-shadow:0 2px 8px rgba(10,20,50,.14), inset 0 -1px 0 rgba(255,255,255,.04);
  }
  .top-brand{display:flex;align-items:center;gap:12px;min-width:0}
  .top-brand-badge{
    width:34px;height:34px;border-radius:10px;
    display:grid;place-items:center;
    background:#ffba32;color:#17305f;
    font-weight:900;font-size:12px;
    box-shadow:0 5px 14px rgba(255,183,46,.22);
    flex:none;
  }
  .top-brand strong{
    font-size:14px;letter-spacing:.01em;white-space:nowrap;
  }
  .top-brand a{
    margin-left:14px;
    color:#b7c2e0;font-size:12px;font-weight:700;
    transition:.18s ease;white-space:nowrap;
  }
  .top-brand a:hover{color:#fff}
  .top-brand a span{color:#d3ddf5}
  .top-actions{display:flex;align-items:center;gap:12px}
  .icon-btn{
    width:39px;height:39px;border-radius:50%;
    border:1px solid rgba(255,255,255,.12);
    background:rgba(255,255,255,.05);
    color:#cbd6f2;
    position:relative;
  }
  .icon-btn:hover{background:rgba(255,255,255,.1);color:#fff}
  .notif-dot{
    position:absolute;right:-1px;top:-2px;
    min-width:15px;height:15px;padding:0 4px;
    display:grid;place-items:center;
    border-radius:999px;background:#f3535f;color:#fff;
    border:2px solid #18285a;
    box-shadow:none;
    font-size:8px;font-weight:900;
    line-height:1;
  }
  .top-user-copy{text-align:right;line-height:1.15;margin-left:2px}
  .top-user-copy strong{display:block;font-size:11px;color:#fff}
  .top-user-copy span{display:block;margin-top:3px;color:#ffbd2f;font-size:9px;font-weight:800}
  .top-avatar{
    width:36px;height:36px;
    border:0;
    background:#ffba32;
    color:#17305f;
    box-shadow:none;
    font-size:11px;
  }
  .top-logout{
    height:31px;padding:0 13px;border-radius:999px;
    border:1px solid rgba(239,91,105,.42);
    background:rgba(239,91,105,.04);
    color:#ff8691;font-size:10px;font-weight:850;
  }
  .top-logout:hover{background:rgba(239,91,105,.1)}

  /* Screenshot-matched sidebar */
  .sidebar{
    position:fixed;inset:65px auto 0 0;
    width:250px;padding:0;
    display:flex;flex-direction:column;
    background:#1f3472;
    color:#eaf0ff;
    border-right:1px solid rgba(16,30,67,.12);
    box-shadow:none;
    overflow:auto;
  }
  .sidebar-brand{display:none}
  .side-section{
    padding:27px 22px 8px;
    color:#82a0dd;
    font-size:10px;
    letter-spacing:.12em;
    font-weight:850;
  }
  .nav{display:grid;gap:0}
  .nav-item{
    min-height:39px;
    display:flex;align-items:center;gap:11px;
    position:relative;
    padding:0 22px;
    border-radius:0;
    color:#e4ebff;
    font-size:12px;font-weight:700;
    transition:.16s ease;
  }
  .nav-item svg{width:15px;height:15px;color:#9cb3e9;opacity:1;flex:none}
  .nav-item:hover{background:rgba(67,101,182,.33);color:#fff}
  .nav-item.active{
    background:#29458d;
    box-shadow:none;
    color:#fff;
  }
  .nav-item.active::before{
    content:"";
    position:absolute;left:0;top:0;bottom:0;
    width:2px;border-radius:0;
    background:#ffb92f;
    box-shadow:none;
  }
  .nav-item.active svg{color:#ffb92f}
  .nav-badge{
    margin-left:auto;
    min-width:21px;height:18px;padding:0 6px;
    border-radius:999px;
    display:grid;place-items:center;
    background:#f25560;color:#fff;
    font-size:9px;font-weight:900;
  }
  .main{
    grid-column:2;
    min-width:0;
    padding-top:65px;
  }
  .content{padding:28px 29px 34px}

  .hero{border-radius:16px}
  .metric,.card{border-radius:15px}

  @media (max-width:1100px){
    .app{grid-template-columns:82px 1fr}
    .sidebar{width:82px;inset:65px auto 0 0}
    .side-section{padding:20px 0 7px;text-align:center;font-size:8px}
    .nav-item{justify-content:center;padding:0 8px}
    .nav-item span,.nav-badge{display:none}
    .nav-item.active::before{left:0;width:2px}
    .main{grid-column:2}
    .top-brand a{display:none}
  }
  @media (max-width:760px){
    .app{display:block}
    .sidebar{inset:auto 0 0 0;width:100%;height:64px;padding:0;z-index:40;flex-direction:row;align-items:center;overflow:hidden}
    .side-section{display:none}
    .nav{display:flex;width:100%;height:100%;justify-content:space-around}
    .nav-item{height:100%;min-height:0;width:25%;padding:0;display:flex;flex-direction:column;gap:3px;justify-content:center}
    .nav-item span{display:block;font-size:8px}
    .nav-item.active::before{top:auto;left:50%;bottom:0;width:20px;height:3px;transform:translateX(-50%)}
    .nav-item svg{width:15px;height:15px}
    .topbar{height:61px;padding:0 14px}
    .top-brand strong{font-size:12px}
    .top-brand-badge{width:31px;height:31px}
    .top-user-copy{display:none}
    .top-logout{display:none}
    .main{padding-bottom:64px}
    .content{padding:18px 15px 28px}
  }
</style>

<style id="final-layout-adjustments">
  .work-grid-single{grid-template-columns:1fr}
  .work-grid-single .work-card{width:100%}

  @media (max-width:1100px){
    .main{padding-top:65px}
  }

  @media (max-width:760px){
    .main{padding-top:61px;padding-bottom:64px}
  }
</style>
</head>

<body>
  <div class="app">
    <aside class="sidebar">
      <div class="sidebar-brand">
        <div class="brand-badge">DB</div>
        <div class="brand-title">WORKER DASHBOARD</div>
      </div>

      <div class="side-section">MENU UTAMA</div>
      <nav class="nav">
        <a class="nav-item active" href="#overview">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 10.5 12 4l8 6.5V20H4z"/><path d="M9 20v-5h6v5"/></svg>
          <span>Ringkasan</span>
        </a>
        <a class="nav-item" href="#orders">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 3h9l3 3v15H6z"/><path d="M15 3v4h4"/><path d="M9 12h6M9 16h5"/></svg>
          <span>Pesanan</span>
          <span class="nav-badge">8</span>
        </a>
        <a class="nav-item" href="#active">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 4h12M6 20h12"/><path d="M8 4c0 4 8 4 8 8s-8 4-8 8"/></svg>
          <span>Pekerjaan Aktif</span>
        </a>
      </nav>

      <div class="side-section" style="margin-top:18px">AKUN</div>
      <nav class="nav">
        <a class="nav-item" href="#profileBtn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="3.2"/><path d="M5 20c.7-4 3-6 7-6s6.3 2 7 6"/></svg>
          <span>Profil Saya</span>
        </a>
        <button class="nav-item" id="logoutBtn" style="border:0;background:transparent;width:100%;text-align:left">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10 4H5v16h5"/><path d="M13 8l4 4-4 4M9 12h8"/></svg>
          <span>Keluar</span>
        </button>
      </nav>
    </aside>

    <main class="main">
      <header class="topbar">
        <div class="top-brand">
          <div class="top-brand-badge">DB</div>
          <strong>DASHBOARD</strong>
          <a href="#overview">Kunjungi Situs <span>→</span></a>
          <a href="#overview">Profil Sekolah</a>
        </div>

        <div class="top-actions">
          <button class="icon-btn" id="notifBtn" aria-label="Notifikasi">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M18 8a6 6 0 1 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/><path d="M10 21h4"/></svg>
            <span class="notif-dot">3</span>
          </button>

          <div class="top-user-copy">
            <strong>Andra Rizky</strong>
            <span>Worker Produksi</span>
          </div>

          <button class="avatar top-avatar" id="profileBtn">AR</button>
          <button class="top-logout" id="topLogoutBtn">Logout</button>
        </div>
      </header>

      <section class="content" id="overview">
        <section class="hero">
          <div class="hero-inner">
            <div>
              <div class="eyebrow"><span class="pulse"></span> shift aktif • senin, 20 september 2026</div>
              <h1>Fokus ke pekerjaan yang<br>perlu selesai hari ini.</h1>
              <p>Semua pesanan yang ditugaskan ke kamu ada di satu tempat. Kerjakan sampai selesai—tanpa akses ke area administrasi.</p>
            </div>
            <div class="hero-right">
              <button class="btn btn-outline" id="refreshBtn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 11a8 8 0 0 0-14.9-4"/><path d="M4 4v4h4"/><path d="M4 13a8 8 0 0 0 14.9 4"/><path d="M20 20v-4h-4"/></svg>
                Sinkronkan
              </button>
              <button class="btn btn-gold" id="jumpActive">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 12h12"/><path d="m13 7 5 5-5 5"/></svg>
                Lihat pekerjaan aktif
              </button>
            </div>
          </div>
        </section>

        <section class="card orders" id="orders">
          <div class="card-head">
            <div><div class="card-title">Pesanan ditugaskan</div><div class="card-sub">Hanya pesanan yang dialokasikan oleh Admin Jurusan</div></div>
            <button class="head-link" id="filterBtn" style="border:0;background:none">Filter aktif ▾</button>
          </div>
          <div class="table-wrap">
            <table id="ordersTable">
              <thead>
                <tr>
                  <th>Order</th><th>Pelanggan</th><th>Layanan</th><th>Deadline</th><th>Status</th><th></th>
                </tr>
              </thead>
              <tbody>
                <tr data-status="progress">
                  <td class="order-id">#PH-260920-081</td>
                  <td><div class="customer"><div class="mini-avatar">DN</div><span>Dina N.</span></div></td>
                  <td class="service">Banner Event 2×3 m</td>
                  <td class="deadline">20 Sep • 15:30</td>
                  <td><span class="status status-progress">Dikerjakan</span></td>
                  <td><button class="row-action" data-order="PH-260920-081" aria-label="Buka order"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m9 18 6-6-6-6"/></svg></button></td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>

        <section class="work-grid work-grid-single" id="active">
          <article class="card work-card">
            <div class="card-head">
              <div><div class="card-title">Pekerjaan aktif</div><div class="card-sub">Prioritas berdasarkan deadline</div></div>
              <span style="font-size:10px;color:#6d7c98;font-weight:800">3 aktif</span>
            </div>

            <div class="task">
              <div class="metric-icon" style="width:31px;height:31px;color:#5576d8;flex:none"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 6h16v12H4z"/><path d="M8 10h8M8 14h5"/></svg></div>
              <div class="task-main"><strong>Banner Event 2×3 m</strong><span>#PH-260920-081 • Deadline 15:30</span><div class="progress"><span style="width:82%"></span></div></div><div class="percent">82%</div>
            </div>
          </article>
        </section>

        <div class="footer-note">Worker Workspace • Akses dibatasi pada pesanan yang ditugaskan & proses produksi</div>
      </section>
    </main>
  </div>
</body>
</html>
