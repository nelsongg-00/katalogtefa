<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard Super Admin — TeFa</title>
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
.logout{border:1px solid #6b2f47;background:#3b2441;color:#ff8d95;font-weight:700;font-size:12.5px;padding:7px 16px;border-radius:999px}
.logout:hover{background:#4b2a4d}

.notif{position:absolute;top:52px;right:0;width:320px;background:#fff;color:var(--ink);border-radius:14px;box-shadow:0 18px 40px rgba(15,27,61,.22);padding:8px;display:none}
.notif.open{display:block}
.notif h4{font-size:13px;padding:8px 10px 6px}
.notif .row{display:flex;gap:10px;padding:9px 10px;border-radius:10px;font-size:12.5px}
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
.btn{display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:999px;font-weight:700;font-size:13.5px;transition:transform .12s,box-shadow .15s,background .15s}
.btn:active{transform:translateY(1px)}
.btn.primary{background:var(--yellow);color:var(--header);box-shadow:0 6px 16px rgba(251,191,36,.35)}
.btn.primary:hover{background:#f7b50f}
.btn.ghost{background:#fff;border:1px solid var(--line);color:var(--ink)}
.btn.ghost:hover{background:#f8fafd}
.btn.danger{background:var(--red);color:#fff}
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

/* chart */
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
.chart .bar:hover::after{content:attr(data-v);position:absolute;top:-24px;left:50%;transform:translateX(-50%);background:var(--ink);color:#fff;font-size:11px;font-weight:700;padding:1px 7px;border-radius:6px}
.chart .m{height:26px;font-size:11.5px;color:var(--muted);font-weight:600;display:grid;place-items:end center}

.two{display:grid;grid-template-columns:1.35fr 1fr;gap:16px;margin-top:22px}
.stack{display:flex;flex-direction:column;gap:16px}

/* tables */
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
.icon-btn{width:32px;height:32px;border-radius:9px;display:grid;place-items:center;color:var(--muted)}
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
.spacer{flex:1}

.mini-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:18px}
.mini{padding:16px 18px}
.mini small{display:block;color:var(--muted);font-size:12px;font-weight:600}
.mini b{font-size:20px;font-weight:800}

/* progress rows */
.prog{margin-bottom:16px}
.prog:last-child{margin-bottom:0}
.prog .top{display:flex;justify-content:space-between;font-weight:700;font-size:13px;margin-bottom:6px}
.prog .top span{color:var(--muted);font-weight:600}
.track{height:8px;background:#edf1f8;border-radius:6px;overflow:hidden}
.fill{height:100%;border-radius:6px;transition:width .5s}

/* toggles & settings */
.setting{display:flex;justify-content:space-between;align-items:center;gap:16px;padding:14px 0;border-bottom:1px solid var(--line)}
.setting:last-child{border-bottom:none}
.setting b{display:block;font-weight:700}
.setting small{color:var(--muted);font-size:12.5px}
.switch{position:relative;width:44px;height:25px;flex:none}
.switch input{position:absolute;opacity:0;inset:0;width:100%;height:100%;cursor:pointer;z-index:2;margin:0}
.switch span{position:absolute;inset:0;background:#cfd7e6;border-radius:999px;transition:background .2s}
.switch span::after{content:"";position:absolute;top:3px;left:3px;width:19px;height:19px;background:#fff;border-radius:50%;transition:transform .2s;box-shadow:0 1px 3px rgba(0,0,0,.25)}
.switch input:checked + span{background:var(--green)}
.switch input:checked + span::after{transform:translateX(19px)}
.switch input:focus-visible + span{outline:2px solid var(--yellow);outline-offset:2px}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.form-grid .full{grid-column:1/-1}
.form-grid .inp,.form-grid .sel{width:100%}
.log{display:flex;gap:12px;padding:11px 0;border-bottom:1px solid var(--line);font-size:13px}
.log:last-child{border-bottom:none}
.log .ic{width:32px;height:32px;border-radius:10px;display:grid;place-items:center;flex:none}
.log small{display:block;color:var(--muted);font-size:11.5px}

/* modal */
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

.toast-wrap{position:fixed;right:22px;bottom:22px;display:flex;flex-direction:column;gap:10px;z-index:200}
.toast{background:var(--header);color:#fff;padding:12px 18px;border-radius:12px;font-weight:600;font-size:13px;box-shadow:0 12px 30px rgba(10,16,40,.3);border-left:4px solid var(--yellow);animation:pop .25s}
.toast.err{border-left-color:var(--red)}

.print-head{display:none}

/* ---------- Responsive ---------- */
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
@media (prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important}}

/* ---------- Cetak ---------- */
@media print{
  @page{size:A4 landscape;margin:14mm}
  body{background:#fff}
  .topbar,.sidebar,.scrim,.toast-wrap,.modal,.no-print,.toolbar,.page-head .actions,.row-actions,.col-act{display:none!important}
  main{margin:0;padding:0}
  .card{box-shadow:none;border:none}
  .print-head{display:block;text-align:center;margin-bottom:16px;border-bottom:2px solid #000;padding-bottom:10px}
  .print-head h2{font-size:18px}
  .print-head p{font-size:12px;color:#333}
  .print-meta{display:block!important;font-size:12px;margin-bottom:10px}
  table{min-width:0;font-size:11px}
  th,td{padding:6px 8px;border:1px solid #999}
  th{background:#eee!important;-webkit-print-color-adjust:exact;print-color-adjust:exact}
  .badge{background:none!important;color:#000!important;padding:0}
  .mini-stats{grid-template-columns:repeat(4,1fr);gap:8px}
  .mini{border:1px solid #999}
  .sign{display:flex!important}
}
.print-meta,.sign{display:none}
.sign{justify-content:flex-end;margin-top:36px;text-align:center;font-size:12px}
.sign div{width:220px}
.sign .line{margin-top:60px;border-top:1px solid #000;padding-top:4px;font-weight:700}
</style>
</head>
<body>

<!-- ====== HEADER ====== -->
<header class="topbar">
  <div class="left">
    <button class="menu-btn" id="menuBtn" aria-label="Buka menu"><svg class="i" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
    <div class="brand"><div class="logo">DB</div>DASHBOARD</div>
    <a class="toplink" href="#" onclick="toast('Membuka situs publik…');return false">Kunjungi Situs →</a>
    <a class="toplink" href="#" onclick="toast('Membuka profil sekolah…');return false">Profil Sekolah</a>
  </div>
  <div class="right">
    <div class="rel">
      <button class="bell" id="bellBtn" aria-label="Notifikasi"><svg class="i" viewBox="0 0 24 24"><path d="M18 8a6 6 0 10-12 0c0 7-3 9-3 9h18s-3-2-3-9M13.7 21a2 2 0 01-3.4 0"/></svg><span class="dot" id="bellCount">3</span></button>
      <div class="notif" id="notif"></div>
    </div>
    <div class="whoami"><b>Super Admin</b><span>Superuser Sistem</span></div>
    <div class="avatar">SA</div>
    <button class="logout" id="logoutBtn">Logout</button>
  </div>
</header>

<div class="scrim" id="scrim"></div>

<!-- ====== SIDEBAR ====== -->
<nav class="sidebar" id="sidebar" aria-label="Menu utama">
  <div class="nav-label">Menu Utama</div>
  <button class="nav-item" data-view="ringkasan"></button>
  <button class="nav-item" data-view="jurusan"></button>
  <button class="nav-item" data-view="user"></button>
  <button class="nav-item" data-view="laporan"></button>
  <div class="nav-label">Pengawasan</div>
  <button class="nav-item" data-view="sistem"></button>
  <div class="nav-label">Akun</div>
  <button class="nav-item" data-view="profil"></button>
</nav>

<!-- ====== KONTEN ====== -->
<main id="view" tabindex="-1"></main>

<!-- ====== MODAL ====== -->
<div class="modal" id="modal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
  <form class="dialog" id="modalForm">
    <header><h3 id="modalTitle"></h3><button type="button" class="icon-btn" id="modalX" aria-label="Tutup"><svg class="i" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg></button></header>
    <div class="body" id="modalBody"></div>
    <footer><button type="button" class="btn ghost" id="modalCancel">Batal</button><button type="submit" class="btn primary" id="modalSave">Simpan</button></footer>
  </form>
</div>
<div class="toast-wrap" id="toasts" aria-live="polite"></div>

<script>
/* =========================================================
   Util
========================================================= */
const $ = (s, r=document) => r.querySelector(s);
const $$ = (s, r=document) => [...r.querySelectorAll(s)];
const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
const rp = n => 'Rp ' + Number(n||0).toLocaleString('id-ID');
const initials = n => n.split(/\s+/).filter(Boolean).slice(0,2).map(w=>w[0]).join('').toUpperCase();
const MONTHS = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
const fmtDate = d => { const [y,m,dd] = d.split('-'); return `${+dd} ${MONTHS[+m-1]} ${y}`; };
const today = () => new Date().toISOString().slice(0,10);

const ICONS = {
  home:'<path d="M3 11l9-8 9 8v9a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1z"/>',
  layers:'<path d="M12 3l9 5-9 5-9-5 9-5zM3 13l9 5 9-5M3 17.5l9 5 9-5"/>',
  users:'<path d="M17 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9.5 11a4 4 0 100-8 4 4 0 000 8zM22 21v-2a4 4 0 00-3-3.9M16 3.1a4 4 0 010 7.8"/>',
  file:'<path d="M14 3H6a2 2 0 00-2 2v14a2 2 0 002 2h12a2 2 0 002-2V9zM14 3v6h6M8 13h8M8 17h5"/>',
  shield:'<path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/><path d="M9 12l2 2 4-4"/>',
  user:'<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0116 0"/>',
  box:'<path d="M21 8l-9-5-9 5v8l9 5 9-5zM3 8l9 5 9-5M12 13v8"/>',
  dollar:'<path d="M12 2v20M17 6.5C16 5 14.3 4.5 12 4.5c-3 0-5 1.3-5 3.3 0 4.7 10 2.2 10 6.8 0 2-2 3.4-5 3.4-2.5 0-4.3-.7-5.3-2.3"/>',
  doc:'<rect x="4" y="3" width="16" height="18" rx="3"/><path d="M8 9h8M8 13h8M8 17h4"/>',
  print:'<path d="M6 9V3h12v6M6 18H4a1 1 0 01-1-1v-6a2 2 0 012-2h14a2 2 0 012 2v6a1 1 0 01-1 1h-2M7 14h10v7H7z"/>',
  plus:'<path d="M12 5v14M5 12h14"/>',
  edit:'<path d="M12 20h9M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4z"/>',
  trash:'<path d="M3 6h18M8 6V4a1 1 0 011-1h6a1 1 0 011 1v2M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6M10 11v6M14 11v6"/>',
  search:'<circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/>',
  download:'<path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/>',
  key:'<circle cx="8" cy="15" r="4"/><path d="M11 12l9-9M16 7l3 3"/>',
  activity:'<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
  x:'<path d="M18 6L6 18M6 6l12 12"/>',
  gear:'<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 00.3 1.8l.1.1a2 2 0 11-2.8 2.8l-.1-.1a1.7 1.7 0 00-1.8-.3 1.7 1.7 0 00-1 1.5V21a2 2 0 01-4 0v-.1a1.7 1.7 0 00-1.1-1.5 1.7 1.7 0 00-1.8.3l-.1.1a2 2 0 11-2.8-2.8l.1-.1a1.7 1.7 0 00.3-1.8 1.7 1.7 0 00-1.5-1H3a2 2 0 010-4h.1a1.7 1.7 0 001.5-1.1 1.7 1.7 0 00-.3-1.8l-.1-.1a2 2 0 112.8-2.8l.1.1a1.7 1.7 0 001.8.3H9a1.7 1.7 0 001-1.5V3a2 2 0 014 0v.1a1.7 1.7 0 001 1.5 1.7 1.7 0 001.8-.3l.1-.1a2 2 0 112.8 2.8l-.1.1a1.7 1.7 0 00-.3 1.8V9a1.7 1.7 0 001.5 1H21a2 2 0 010 4h-.1a1.7 1.7 0 00-1.5 1z"/>',
};
const ic = (n, cls='') => `<svg class="i ${cls}" viewBox="0 0 24 24">${ICONS[n]||''}</svg>`;

/* =========================================================
   Data contoh (ganti dengan data dari backend / API)
========================================================= */
const state = {
  year: 2026,
  jurusan: [
    {id:'rpl',  kode:'RPL',  nama:'Software Engineering',           kepala:'Bu Ratna Sari, S.Kom',  aktif:true},
    {id:'tkj',  kode:'TKJ',  nama:'Teknik Komputer & Jaringan',     kepala:'Pak Hendra Wijaya, S.T',aktif:true},
    {id:'dkv',  kode:'DKV',  nama:'Desain Komunikasi Visual',       kepala:'Bu Maya Anggraini, S.Sn',aktif:true},
    {id:'akt',  kode:'AKT',  nama:'Akuntansi & Keuangan',           kepala:'Pak Dedi Kurniawan, S.E',aktif:true},
    {id:'boga', kode:'BOGA', nama:'Tata Boga',                      kepala:'Bu Lina Marlina, S.Pd', aktif:false},
  ],
  users: [
    {id:1, nama:'Super Admin',       email:'superadmin@tefa.sch.id', role:'superadmin', jurusan:null,  aktif:true},
    {id:2, nama:'Admin SE',          email:'admin.se@tefa.sch.id',   role:'admin',      jurusan:'rpl', aktif:true},
    {id:3, nama:'Admin TKJ',         email:'admin.tkj@tefa.sch.id',  role:'admin',      jurusan:'tkj', aktif:true},
    {id:4, nama:'Admin DKV',         email:'admin.dkv@tefa.sch.id',  role:'admin',      jurusan:'dkv', aktif:true},
    {id:5, nama:'Admin Akuntansi',   email:'admin.akt@tefa.sch.id',  role:'admin',      jurusan:'akt', aktif:false},
    {id:6, nama:'Rizky Pratama',     email:'rizky@siswa.tefa.id',    role:'worker',     jurusan:'rpl', aktif:true},
    {id:7, nama:'Salsa Nabila',      email:'salsa@siswa.tefa.id',    role:'worker',     jurusan:'rpl', aktif:true},
    {id:8, nama:'Dimas Aditya',      email:'dimas@siswa.tefa.id',    role:'worker',     jurusan:'tkj', aktif:true},
    {id:9, nama:'Putri Amelia',      email:'putri@siswa.tefa.id',    role:'worker',     jurusan:'dkv', aktif:true},
    {id:10,nama:'Fajar Nugroho',     email:'fajar@siswa.tefa.id',    role:'worker',     jurusan:'dkv', aktif:false},
    {id:11,nama:'Budi Santoso',      email:'budi.santoso@mail.com',  role:'pelanggan',  jurusan:null,  aktif:true},
    {id:12,nama:'Citra Lestari',     email:'citra.l@mail.com',       role:'pelanggan',  jurusan:null,  aktif:true},
    {id:13,nama:'CV Maju Bersama',   email:'info@majubersama.co.id', role:'pelanggan',  jurusan:null,  aktif:true},
    {id:14,nama:'Toko Sinar Baru',   email:'sinarbaru@mail.com',     role:'pelanggan',  jurusan:null,  aktif:true},
  ],
  trx: [],
  log: [
    {t:'Super Admin menambahkan akun Admin DKV',       w:'Hari ini, 09:12', k:'plus'},
    {t:'Transaksi TRX-0007 dikoreksi menjadi Selesai', w:'Kemarin, 16:40', k:'edit'},
    {t:'Jurusan Tata Boga dinonaktifkan',              w:'Kemarin, 10:05', k:'layers'},
    {t:'Laporan transaksi Agustus dicetak',            w:'2 hari lalu',    k:'print'},
    {t:'Pengaturan pendaftaran pelanggan diubah',      w:'3 hari lalu',    k:'gear'},
  ],
  config: {
    platform:'Teaching Factory (TeFa)', sekolah:'SMK Negeri Contoh', email:'kontak@tefa.sch.id', maxUpload:20,
    maintenance:false, registrasi:true, notifEmail:true, verifEmail:true, backup:true
  },
  filter: {jurusan:'', status:'', dari:'', sampai:'', q:''},
  ufilter: {q:'', role:'', jurusan:''},
};
let nextUserId = 100, nextTrx = 25;

(function seedTrx(){
  // [tanggal, pelanggan, item, jurusan, jenis, total, status]
  const rows = [
    ['2026-01-12','Budi Santoso','Website Company Profile','rpl','Jasa',2500000,'Selesai'],
    ['2026-01-28','Citra Lestari','Kaos Sablon Custom (12 pcs)','dkv','Fisik',960000,'Selesai'],
    ['2026-02-09','CV Maju Bersama','Instalasi Jaringan LAN Kantor','tkj','Jasa',4200000,'Selesai'],
    ['2026-02-21','Toko Sinar Baru','Desain Logo & Branding','dkv','Jasa',750000,'Selesai'],
    ['2026-03-03','Budi Santoso','Aplikasi Kasir Sederhana','rpl','Jasa',3500000,'Selesai'],
    ['2026-03-18','Citra Lestari','Paket Snack Box (50 box)','boga','Fisik',1250000,'Selesai'],
    ['2026-03-30','CV Maju Bersama','Pembukuan UMKM Triwulan 1','akt','Jasa',1800000,'Selesai'],
    ['2026-04-07','Toko Sinar Baru','Cetak Banner 3x1 m','dkv','Fisik',300000,'Selesai'],
    ['2026-04-22','Budi Santoso','Kabel LAN Crimping (20 unit)','tkj','Fisik',400000,'Dibatalkan'],
    ['2026-05-05','CV Maju Bersama','Sistem Informasi Inventaris','rpl','Jasa',6000000,'Selesai'],
    ['2026-05-19','Citra Lestari','Kue Ulang Tahun Tart','boga','Fisik',350000,'Selesai'],
    ['2026-06-10','Toko Sinar Baru','Website Toko Online','rpl','Jasa',4500000,'Selesai'],
    ['2026-06-24','Budi Santoso','Poster Acara Sekolah','dkv','Fisik',180000,'Selesai'],
    ['2026-07-08','CV Maju Bersama','Pemasangan CCTV 4 Titik','tkj','Jasa',3200000,'Selesai'],
    ['2026-07-25','Citra Lestari','Laporan Keuangan Yayasan','akt','Jasa',2100000,'Selesai'],
    ['2026-08-04','Toko Sinar Baru','Catering Rapat (80 porsi)','boga','Jasa',2400000,'Selesai'],
    ['2026-08-16','Budi Santoso','Undangan Digital Pernikahan','rpl','Jasa',600000,'Selesai'],
    ['2026-08-29','CV Maju Bersama','Desain Katalog Produk','dkv','Jasa',1500000,'Diproses'],
    ['2026-09-02','Citra Lestari','Setup Wi-Fi Rumah Makan','tkj','Jasa',1300000,'Selesai'],
    ['2026-09-08','Toko Sinar Baru','Aplikasi Absensi Karyawan','rpl','Jasa',5200000,'Diproses'],
    ['2026-09-14','Budi Santoso','Merchandise Stiker Coding','rpl','Fisik',150000,'Menunggu'],
    ['2026-09-16','CV Maju Bersama','Pembukuan UMKM Bulanan','akt','Jasa',900000,'Menunggu'],
    ['2026-09-18','Citra Lestari','Kaos Sablon Angkatan (30 pcs)','dkv','Fisik',2100000,'Menunggu'],
    ['2026-09-19','Toko Sinar Baru','Konsultasi Jaringan Toko','tkj','Jasa',500000,'Menunggu'],
  ];
  rows.forEach((r,i)=> state.trx.push({
    id:'TRX-'+String(i+1).padStart(4,'0'), tgl:r[0], pelanggan:r[1], item:r[2], jurusan:r[3], jenis:r[4], total:r[5], status:r[6], catatan:''
  }));
})();

/* helper lookup */
const jur = id => state.jurusan.find(j=>j.id===id);
const jurName = id => jur(id)?.nama || '—';
const ROLE = {
  superadmin:{label:'Super Admin', cls:'b-purple'},
  admin:{label:'Admin Jurusan', cls:'b-blue'},
  worker:{label:'Worker', cls:'b-yellow'},
  pelanggan:{label:'Pelanggan', cls:'b-gray'},
};
const STATUS = {Menunggu:'b-yellow', Diproses:'b-blue', Selesai:'b-green', Dibatalkan:'b-red'};
const statusBadge = s => `<span class="badge ${STATUS[s]}">${s}</span>`;
const addLog = (t,k='activity') => { state.log.unshift({t, w:'Baru saja', k}); renderNotif(); };

/* =========================================================
   UI dasar: toast, modal, konfirmasi
========================================================= */
function toast(msg, err=false){
  const el = document.createElement('div');
  el.className = 'toast' + (err?' err':'');
  el.textContent = msg;
  $('#toasts').appendChild(el);
  setTimeout(()=>el.remove(), 3200);
}
let modalSave = null;
function openModal(title, html, onSave, label='Simpan', danger=false){
  $('#modalTitle').textContent = title;
  $('#modalBody').innerHTML = html;
  const btn = $('#modalSave');
  btn.textContent = label;
  btn.className = 'btn ' + (danger ? 'danger' : 'primary');
  modalSave = onSave;
  $('#modal').classList.add('open');
  setTimeout(()=> ($('#modalBody input, #modalBody select')||btn).focus(), 30);
}
function closeModal(){ $('#modal').classList.remove('open'); modalSave = null; }
$('#modalForm').addEventListener('submit', e=>{
  e.preventDefault();
  if(!modalSave) return;
  const data = Object.fromEntries(new FormData(e.target));
  if(modalSave(data) !== false) closeModal();
});
$('#modalX').onclick = $('#modalCancel').onclick = closeModal;
$('#modal').addEventListener('mousedown', e=>{ if(e.target.id==='modal') closeModal(); });
document.addEventListener('keydown', e=>{ if(e.key==='Escape'){ closeModal(); $('#notif').classList.remove('open'); }});
function confirmBox(title, msg, fn, label='Hapus'){
  openModal(title, `<p>${msg}</p>`, ()=>{ fn(); }, label, true);
}

/* =========================================================
   Navigasi
========================================================= */
const NAV = {
  ringkasan:{label:'Ringkasan', icon:'home'},
  jurusan:{label:'Data Jurusan', icon:'layers'},
  user:{label:'Manajemen User', icon:'users'},
  laporan:{label:'Laporan Transaksi', icon:'file'},
  sistem:{label:'Konfigurasi Sistem', icon:'shield'},
  profil:{label:'Edit Profil', icon:'user'},
};
let current = 'ringkasan';
$$('.nav-item').forEach(b=>{
  const n = NAV[b.dataset.view];
  b.innerHTML = ic(n.icon) + `<span>${n.label}</span>`;
  b.addEventListener('click', ()=> go(b.dataset.view));
});
function go(v){
  current = v;
  $$('.nav-item').forEach(b=>b.classList.toggle('active', b.dataset.view===v));
  closeSidebar();
  render();
  window.scrollTo({top:0});
}
function openSidebar(){ $('#sidebar').classList.add('open'); $('#scrim').classList.add('open'); }
function closeSidebar(){ $('#sidebar').classList.remove('open'); $('#scrim').classList.remove('open'); }
$('#menuBtn').onclick = openSidebar;
$('#scrim').onclick = closeSidebar;
$('#logoutBtn').onclick = ()=> confirmBox('Keluar dari sistem?','Sesi Super Admin akan diakhiri.', ()=>toast('Anda telah keluar (demo).'), 'Logout');

/* notifikasi */
function renderNotif(){
  const items = state.log.slice(0,4);
  $('#notif').innerHTML = `<h4>Aktivitas terbaru</h4>` + items.map(l=>`
    <div class="row"><div class="ic c-blue">${ic(l.k)}</div><div>${esc(l.t)}<small>${esc(l.w)}</small></div></div>`).join('');
}
$('#bellBtn').onclick = e=>{ e.stopPropagation(); $('#notif').classList.toggle('open'); $('#bellCount').style.display='none'; };
document.addEventListener('click', e=>{ if(!e.target.closest('#notif')) $('#notif').classList.remove('open'); });

/* =========================================================
   Perhitungan
========================================================= */
function stats(){
  const t = state.trx;
  const done = t.filter(x=>x.status==='Selesai');
  return {
    total:t.length,
    menunggu:t.filter(x=>x.status==='Menunggu').length,
    proses:t.filter(x=>x.status==='Diproses').length,
    selesai:done.length,
    batal:t.filter(x=>x.status==='Dibatalkan').length,
    omzet:done.reduce((a,x)=>a+x.total,0),
    users:state.users.length,
    admin:state.users.filter(u=>u.role==='admin').length,
    worker:state.users.filter(u=>u.role==='worker').length,
    pelanggan:state.users.filter(u=>u.role==='pelanggan').length,
    jurAktif:state.jurusan.filter(j=>j.aktif).length,
  };
}

/* =========================================================
   VIEW: Ringkasan
========================================================= */
function viewRingkasan(){
  const s = stats();
  const masuk = Array(12).fill(0), selesai = Array(12).fill(0);
  state.trx.forEach(x=>{
    const [y,m] = x.tgl.split('-').map(Number);
    if(y!==state.year) return;
    masuk[m-1]++; if(x.status==='Selesai') selesai[m-1]++;
  });
  const max = Math.max(2, ...masuk);
  const top = max % 2 ? max+1 : max;
  const step = top/4;
  const ticks = [0,1,2,3,4].map(i=>Math.round(i*step*10)/10);

  const perJur = state.jurusan.map(j=>{
    const t = state.trx.filter(x=>x.jurusan===j.id);
    const d = t.filter(x=>x.status==='Selesai');
    return {j, n:t.length, omzet:d.reduce((a,x)=>a+x.total,0)};
  });
  const maxOmzet = Math.max(1, ...perJur.map(p=>p.omzet));
  const colors = ['#2b6cdb','#fbbf24','#16a870','#7c4ddb','#e0484f'];
  const latest = [...state.trx].sort((a,b)=>b.tgl.localeCompare(a.tgl)).slice(0,5);

  return `
  <div class="page-head">
    <div>
      <h1>RINGKASAN DASHBOARD <span class="pill yellow">★ Super Admin · Semua Jurusan</span></h1>
      <p>Pantauan global performa transaksi, pengguna, dan unit produksi seluruh jurusan TeFa.</p>
    </div>
    <div class="actions">
      <button class="btn primary" onclick="go('user')">${ic('users')}Kelola User</button>
      <button class="btn ghost" onclick="go('laporan')">Lihat Laporan Global →</button>
    </div>
  </div>

  <section class="stats">
    <div class="card stat"><div><div class="num">${s.total}</div><div class="lbl">Total Transaksi Global</div><div class="sub t-yellow">${s.menunggu} Menunggu • ${s.proses} Proses</div></div><div class="ico c-blue">${ic('doc')}</div></div>
    <div class="card stat"><div><div class="num">${rp(s.omzet)}</div><div class="lbl">Pendapatan Selesai</div><div class="sub t-green">${s.selesai} Transaksi Selesai</div></div><div class="ico c-yellow">${ic('dollar')}</div></div>
    <div class="card stat"><div><div class="num">${s.users}</div><div class="lbl">Total Pengguna</div><div class="sub t-blue">${s.admin} Admin • ${s.worker} Worker • ${s.pelanggan} Pelanggan</div></div><div class="ico c-purple">${ic('users')}</div></div>
    <div class="card stat"><div><div class="num">${state.jurusan.length}</div><div class="lbl">Total Jurusan</div><div class="sub t-blue">${s.jurAktif} Aktif • ${state.jurusan.length-s.jurAktif} Nonaktif</div></div><div class="ico c-green">${ic('layers')}</div></div>
  </section>

  <section class="card">
    <div class="card-head">
      <div><h3>Tren Aktivitas Transaksi Global</h3><p>Perbandingan transaksi masuk dan transaksi selesai dari semua jurusan sepanjang tahun berjalan.</p></div>
      <span class="chip">Tahun ${state.year}</span>
    </div>
    <div class="card-body">
      <div class="legend"><span><i style="background:var(--blue)"></i>Transaksi Masuk</span><span><i style="background:var(--green)"></i>Transaksi Selesai</span></div>
      <div class="chart" role="img" aria-label="Grafik batang transaksi masuk dan selesai per bulan">
        <div class="grid">${ticks.map(t=>`<div style="bottom:calc(${(t/top)*100}% * (1) ); top:auto"><span>${t}</span></div>`).join('')}</div>
        <div class="cols">${MONTHS.map((m,i)=>`
          <div class="col"><div class="bars">
            <div class="bar a" style="height:${(masuk[i]/top)*100}%" data-v="${masuk[i]}"></div>
            <div class="bar b" style="height:${(selesai[i]/top)*100}%" data-v="${selesai[i]}"></div>
          </div><div class="m">${m}</div></div>`).join('')}</div>
      </div>
    </div>
  </section>

  <div class="two">
    <section class="card">
      <div class="card-head"><div><h3>Rekap Pendapatan per Jurusan</h3><p>Hanya transaksi berstatus selesai.</p></div><button class="btn ghost sm" onclick="go('laporan')">Detail</button></div>
      <div class="card-body">
        ${perJur.map((p,i)=>`
          <div class="prog">
            <div class="top"><div>${esc(p.j.nama)} ${p.j.aktif?'':'<span class="badge b-gray">Nonaktif</span>'}</div><span>${rp(p.omzet)} · ${p.n} transaksi</span></div>
            <div class="track"><div class="fill" style="width:${(p.omzet/maxOmzet)*100}%;background:${colors[i%colors.length]}"></div></div>
          </div>`).join('')}
      </div>
    </section>

    <div class="stack">
      <section class="card">
        <div class="card-head"><h3>Komposisi Pengguna</h3></div>
        <div class="card-body">
          ${[['admin','Admin Jurusan',s.admin,'#2b6cdb'],['worker','Worker',s.worker,'#fbbf24'],['pelanggan','Pelanggan',s.pelanggan,'#16a870']].map(r=>`
            <div class="prog"><div class="top">${r[1]}<span>${r[2]} akun</span></div><div class="track"><div class="fill" style="width:${(r[2]/(s.users-1||1))*100}%;background:${r[3]}"></div></div></div>`).join('')}
        </div>
      </section>
      <section class="card">
        <div class="card-head"><h3>Transaksi Terbaru</h3></div>
        <div class="card-body" style="padding:6px 22px">
          ${latest.map(x=>`<div class="setting" style="padding:11px 0"><div><b style="font-size:13px">${esc(x.item)}</b><small>${esc(jurName(x.jurusan))} · ${fmtDate(x.tgl)}</small></div>${statusBadge(x.status)}</div>`).join('')}
        </div>
      </section>
    </div>
  </div>`;
}

/* =========================================================
   VIEW: Data Jurusan
========================================================= */
function viewJurusan(){
  const rows = state.jurusan.map(j=>{
    const adm = state.users.filter(u=>u.role==='admin' && u.jurusan===j.id).length;
    const wk = state.users.filter(u=>u.role==='worker' && u.jurusan===j.id).length;
    const tr = state.trx.filter(x=>x.jurusan===j.id).length;
    return `<tr>
      <td><span class="badge b-blue">${esc(j.kode)}</span></td>
      <td><b>${esc(j.nama)}</b><br><small class="t-muted">Kepala program: ${esc(j.kepala||'—')}</small></td>
      <td>${adm}</td><td>${wk}</td><td>${tr}</td>
      <td>${j.aktif?'<span class="badge b-green">Aktif</span>':'<span class="badge b-gray">Nonaktif</span>'}</td>
      <td><div class="row-actions">
        <button class="icon-btn" title="Ubah" aria-label="Ubah ${esc(j.nama)}" onclick="formJurusan('${j.id}')">${ic('edit')}</button>
        <button class="icon-btn del" title="Hapus" aria-label="Hapus ${esc(j.nama)}" onclick="hapusJurusan('${j.id}')">${ic('trash')}</button>
      </div></td></tr>`;
  }).join('');
  return `
  <div class="page-head">
    <div><h1>DATA JURUSAN</h1><p>Kelola jurusan atau kategori unit produksi yang tampil di seluruh platform TeFa.</p></div>
    <div class="actions"><button class="btn primary" onclick="formJurusan()">${ic('plus')}Tambah Jurusan</button></div>
  </div>
  <section class="card"><div class="tbl-wrap"><table>
    <thead><tr><th>Kode</th><th>Nama Jurusan</th><th>Admin</th><th>Worker</th><th>Transaksi</th><th>Status</th><th class="r">Aksi</th></tr></thead>
    <tbody>${rows || `<tr><td colspan="7"><div class="empty"><b>Belum ada jurusan</b>Klik “Tambah Jurusan” untuk membuat yang pertama.</div></td></tr>`}</tbody>
  </table></div></section>`;
}
function formJurusan(id){
  const j = id ? jur(id) : {kode:'',nama:'',kepala:'',aktif:true};
  openModal(id?'Ubah Jurusan':'Tambah Jurusan', `
    <div class="form-grid">
      <div class="field"><label for="f_kode">Kode</label><input class="inp" id="f_kode" name="kode" maxlength="8" required value="${esc(j.kode)}" placeholder="RPL"></div>
      <div class="field"><label for="f_aktif">Status</label><select class="sel" id="f_aktif" name="aktif"><option value="1" ${j.aktif?'selected':''}>Aktif</option><option value="0" ${!j.aktif?'selected':''}>Nonaktif</option></select></div>
      <div class="field full"><label for="f_nama">Nama jurusan</label><input class="inp" id="f_nama" name="nama" required value="${esc(j.nama)}" placeholder="Software Engineering"></div>
      <div class="field full"><label for="f_kepala">Kepala program</label><input class="inp" id="f_kepala" name="kepala" value="${esc(j.kepala)}" placeholder="Nama kepala program"></div>
    </div>`, d=>{
      const obj = {kode:d.kode.trim().toUpperCase(), nama:d.nama.trim(), kepala:d.kepala.trim(), aktif:d.aktif==='1'};
      if(state.jurusan.some(x=>x.kode===obj.kode && x.id!==id)){ toast('Kode jurusan sudah dipakai.', true); return false; }
      if(id){ Object.assign(jur(id), obj); addLog(`Jurusan ${obj.nama} diperbarui`,'edit'); toast('Jurusan diperbarui.'); }
      else { state.jurusan.push({id:obj.kode.toLowerCase()+Date.now()%1000, ...obj}); addLog(`Jurusan ${obj.nama} ditambahkan`,'plus'); toast('Jurusan ditambahkan.'); }
      render();
    });
}
function hapusJurusan(id){
  const j = jur(id);
  const dipakai = state.users.some(u=>u.jurusan===id) || state.trx.some(x=>x.jurusan===id);
  if(dipakai){
    openModal('Jurusan tidak bisa dihapus', `<p><b>${esc(j.nama)}</b> masih memiliki user atau transaksi. Nonaktifkan jurusan ini agar tidak tampil di katalog publik.</p>`,
      ()=>{ j.aktif=false; addLog(`Jurusan ${j.nama} dinonaktifkan`,'layers'); toast('Jurusan dinonaktifkan.'); render(); }, 'Nonaktifkan');
    return;
  }
  confirmBox('Hapus jurusan?', `Jurusan <b>${esc(j.nama)}</b> akan dihapus permanen.`, ()=>{
    state.jurusan = state.jurusan.filter(x=>x.id!==id); addLog(`Jurusan ${j.nama} dihapus`,'trash'); toast('Jurusan dihapus.'); render();
  });
}

/* =========================================================
   VIEW: Manajemen User
========================================================= */
function filteredUsers(){
  const f = state.ufilter, q = f.q.toLowerCase();
  return state.users.filter(u=>
    (!f.role || u.role===f.role) && (!f.jurusan || u.jurusan===f.jurusan) &&
    (!q || u.nama.toLowerCase().includes(q) || u.email.toLowerCase().includes(q)));
}
function userRows(){
  const list = filteredUsers();
  if(!list.length) return `<tr><td colspan="5"><div class="empty"><b>User tidak ditemukan</b>Ubah kata kunci atau filter untuk melihat hasil lain.</div></td></tr>`;
  return list.map(u=>`<tr>
    <td><div class="person"><div class="avatar">${esc(initials(u.nama))}</div><div><b>${esc(u.nama)}</b><small>${esc(u.email)}</small></div></div></td>
    <td><span class="badge ${ROLE[u.role].cls}">${ROLE[u.role].label}</span></td>
    <td>${u.jurusan?esc(jurName(u.jurusan)):'<span class="t-muted">—</span>'}</td>
    <td>${u.aktif?'<span class="badge b-green">Aktif</span>':'<span class="badge b-gray">Nonaktif</span>'}</td>
    <td><div class="row-actions">
      <button class="icon-btn" title="Ubah" aria-label="Ubah ${esc(u.nama)}" onclick="formUser(${u.id})">${ic('edit')}</button>
      <button class="icon-btn" title="Reset password" aria-label="Reset password ${esc(u.nama)}" onclick="resetPass(${u.id})">${ic('key')}</button>
      ${u.role==='superadmin'?'':`<button class="icon-btn del" title="Hapus" aria-label="Hapus ${esc(u.nama)}" onclick="hapusUser(${u.id})">${ic('trash')}</button>`}
    </div></td></tr>`).join('');
}
function viewUser(){
  const s = stats(), f = state.ufilter;
  return `
  <div class="page-head">
    <div><h1>MANAJEMEN USER</h1><p>Tambah, ubah, dan nonaktifkan akun Admin Jurusan, Worker, dan Pelanggan.</p></div>
    <div class="actions"><button class="btn primary" onclick="formUser()">${ic('plus')}Tambah User</button></div>
  </div>
  <section class="mini-stats">
    <div class="card mini"><small>Total pengguna</small><b>${s.users}</b></div>
    <div class="card mini"><small>Admin Jurusan</small><b>${s.admin}</b></div>
    <div class="card mini"><small>Worker</small><b>${s.worker}</b></div>
    <div class="card mini"><small>Pelanggan</small><b>${s.pelanggan}</b></div>
  </section>
  <section class="card">
    <div class="toolbar">
      <div class="search">${ic('search')}<input class="inp" id="uq" placeholder="Cari nama atau email…" value="${esc(f.q)}" aria-label="Cari user"></div>
      <select class="sel" id="urole" aria-label="Filter role"><option value="">Semua role</option>${['admin','worker','pelanggan','superadmin'].map(r=>`<option value="${r}" ${f.role===r?'selected':''}>${ROLE[r].label}</option>`).join('')}</select>
      <select class="sel" id="ujur" aria-label="Filter jurusan"><option value="">Semua jurusan</option>${state.jurusan.map(j=>`<option value="${j.id}" ${f.jurusan===j.id?'selected':''}>${esc(j.nama)}</option>`).join('')}</select>
    </div>
    <div class="tbl-wrap"><table>
      <thead><tr><th>Pengguna</th><th>Role</th><th>Jurusan</th><th>Status</th><th class="r">Aksi</th></tr></thead>
      <tbody id="userBody">${userRows()}</tbody>
    </table></div>
  </section>`;
}
function bindUser(){
  $('#uq').oninput = e=>{ state.ufilter.q = e.target.value; $('#userBody').innerHTML = userRows(); };
  $('#urole').onchange = e=>{ state.ufilter.role = e.target.value; $('#userBody').innerHTML = userRows(); };
  $('#ujur').onchange = e=>{ state.ufilter.jurusan = e.target.value; $('#userBody').innerHTML = userRows(); };
}
function formUser(id){
  const u = id ? state.users.find(x=>x.id===id) : {nama:'',email:'',role:'admin',jurusan:state.jurusan[0]?.id||'',aktif:true};
  const locked = u.role==='superadmin';
  openModal(id?'Ubah User':'Tambah User', `
    <div class="form-grid">
      <div class="field full"><label for="u_nama">Nama lengkap</label><input class="inp" id="u_nama" name="nama" required value="${esc(u.nama)}"></div>
      <div class="field full"><label for="u_email">Email</label><input class="inp" id="u_email" type="email" name="email" required value="${esc(u.email)}"></div>
      <div class="field"><label for="u_role">Role</label>
        <select class="sel" id="u_role" name="role" ${locked?'disabled':''} onchange="toggleJur()">
          ${locked?`<option value="superadmin" selected>Super Admin</option>`:['admin','worker','pelanggan'].map(r=>`<option value="${r}" ${u.role===r?'selected':''}>${ROLE[r].label}</option>`).join('')}
        </select></div>
      <div class="field"><label for="u_jur">Jurusan</label>
        <select class="sel" id="u_jur" name="jurusan"><option value="">— Pilih —</option>${state.jurusan.map(j=>`<option value="${j.id}" ${u.jurusan===j.id?'selected':''}>${esc(j.nama)}</option>`).join('')}</select></div>
      ${id?'':`<div class="field full"><label for="u_pw">Password awal</label><input class="inp" id="u_pw" type="text" name="pw" required minlength="6" placeholder="Minimal 6 karakter"><div class="hint">Minta user mengganti password setelah login pertama.</div></div>`}
      <div class="field full"><label for="u_aktif">Status akun</label><select class="sel" id="u_aktif" name="aktif" ${locked?'disabled':''}><option value="1" ${u.aktif?'selected':''}>Aktif</option><option value="0" ${!u.aktif?'selected':''}>Nonaktif</option></select></div>
    </div>`, d=>{
      const role = locked ? 'superadmin' : d.role;
      const needJur = role==='admin' || role==='worker';
      if(needJur && !d.jurusan){ toast('Pilih jurusan untuk role ini.', true); return false; }
      if(state.users.some(x=>x.email.toLowerCase()===d.email.trim().toLowerCase() && x.id!==id)){ toast('Email sudah terdaftar.', true); return false; }
      const obj = {nama:d.nama.trim(), email:d.email.trim(), role, jurusan:needJur?d.jurusan:null, aktif:locked?true:d.aktif==='1'};
      if(id){ Object.assign(state.users.find(x=>x.id===id), obj); addLog(`Akun ${obj.nama} diperbarui`,'edit'); toast('User diperbarui.'); }
      else { state.users.push({id:nextUserId++, ...obj}); addLog(`Akun ${ROLE[role].label} ${obj.nama} ditambahkan`,'plus'); toast('User ditambahkan.'); }
      render();
    });
  toggleJur();
}
function toggleJur(){
  const r = $('#u_role'), j = $('#u_jur'); if(!r||!j) return;
  const need = r.value==='admin' || r.value==='worker';
  j.disabled = !need; if(!need) j.value='';
}
function resetPass(id){
  const u = state.users.find(x=>x.id===id);
  openModal('Reset password', `<p>Buat password baru untuk <b>${esc(u.nama)}</b>.</p><div class="field" style="margin-top:12px"><label for="np">Password baru</label><input class="inp" id="np" name="pw" required minlength="6" style="width:100%"></div>`,
    ()=>{ addLog(`Password ${u.nama} direset`,'key'); toast('Password berhasil direset.'); }, 'Reset password');
}
function hapusUser(id){
  const u = state.users.find(x=>x.id===id);
  confirmBox('Hapus user?', `Akun <b>${esc(u.nama)}</b> (${ROLE[u.role].label}) akan dihapus permanen. Untuk menahan akses sementara, gunakan status Nonaktif.`, ()=>{
    state.users = state.users.filter(x=>x.id!==id); addLog(`Akun ${u.nama} dihapus`,'trash'); toast('User dihapus.'); render();
  });
}

/* =========================================================
   VIEW: Laporan Transaksi Global
========================================================= */
function filteredTrx(){
  const f = state.filter, q = f.q.toLowerCase();
  return state.trx.filter(x=>
    (!f.jurusan || x.jurusan===f.jurusan) && (!f.status || x.status===f.status) &&
    (!f.dari || x.tgl>=f.dari) && (!f.sampai || x.tgl<=f.sampai) &&
    (!q || (x.item+x.pelanggan+x.id).toLowerCase().includes(q)))
    .sort((a,b)=>b.tgl.localeCompare(a.tgl));
}
function laporanBody(){
  const list = filteredTrx();
  const done = list.filter(x=>x.status==='Selesai');
  $('#lapSum').innerHTML = `
    <div class="card mini"><small>Jumlah transaksi</small><b>${list.length}</b></div>
    <div class="card mini"><small>Total nilai selesai</small><b>${rp(done.reduce((a,x)=>a+x.total,0))}</b></div>
    <div class="card mini"><small>Sedang berjalan</small><b>${list.filter(x=>x.status==='Menunggu'||x.status==='Diproses').length}</b></div>
    <div class="card mini"><small>Dibatalkan</small><b>${list.filter(x=>x.status==='Dibatalkan').length}</b></div>`;
  $('#lapBody').innerHTML = list.length ? list.map(x=>`<tr>
    <td><b>${x.id}</b></td><td>${fmtDate(x.tgl)}</td><td>${esc(x.pelanggan)}</td>
    <td>${esc(x.item)}${x.catatan?`<br><small class="t-muted">Catatan: ${esc(x.catatan)}</small>`:''}</td>
    <td>${esc(jurName(x.jurusan))}</td><td>${x.jenis}</td>
    <td class="r">${rp(x.total)}</td><td>${statusBadge(x.status)}</td>
    <td class="col-act"><div class="row-actions"><button class="icon-btn" title="Koreksi" aria-label="Koreksi ${x.id}" onclick="koreksi('${x.id}')">${ic('edit')}</button></div></td>
  </tr>`).join('') : `<tr><td colspan="9"><div class="empty"><b>Tidak ada transaksi</b>Coba ubah filter jurusan, status, atau rentang tanggal.</div></td></tr>`;
  const f = state.filter;
  $('#printMeta').innerHTML = `Jurusan: <b>${f.jurusan?esc(jurName(f.jurusan)):'Semua jurusan'}</b> &nbsp;|&nbsp; Status: <b>${f.status||'Semua'}</b> &nbsp;|&nbsp; Periode: <b>${f.dari?fmtDate(f.dari):'Awal'} – ${f.sampai?fmtDate(f.sampai):'Sekarang'}</b>`;
}
function viewLaporan(){
  const f = state.filter;
  return `
  <div class="print-head"><h2>LAPORAN REKAPITULASI TRANSAKSI GLOBAL</h2><p>${esc(state.config.platform)} — ${esc(state.config.sekolah)}</p></div>
  <div class="page-head">
    <div><h1>LAPORAN TRANSAKSI GLOBAL</h1><p>Rekapitulasi transaksi seluruh jurusan. Anda dapat mengoreksi data yang keliru dan mencetak laporannya.</p></div>
    <div class="actions">
      <button class="btn ghost" onclick="exportCsv()">${ic('download')}Ekspor CSV</button>
      <button class="btn primary" onclick="cetak()">${ic('print')}Cetak Laporan</button>
    </div>
  </div>
  <section class="mini-stats" id="lapSum"></section>
  <section class="card">
    <div class="toolbar no-print">
      <div class="field"><label for="fj">Jurusan</label><select class="sel" id="fj"><option value="">Semua jurusan</option>${state.jurusan.map(j=>`<option value="${j.id}" ${f.jurusan===j.id?'selected':''}>${esc(j.nama)}</option>`).join('')}</select></div>
      <div class="field"><label for="fs">Status</label><select class="sel" id="fs"><option value="">Semua status</option>${Object.keys(STATUS).map(s=>`<option ${f.status===s?'selected':''}>${s}</option>`).join('')}</select></div>
      <div class="field"><label for="fd">Dari tanggal</label><input class="inp" type="date" id="fd" value="${f.dari}"></div>
      <div class="field"><label for="fe">Sampai tanggal</label><input class="inp" type="date" id="fe" value="${f.sampai}"></div>
      <div class="field" style="flex:1;min-width:180px"><label for="fq">Cari</label><input class="inp" id="fq" placeholder="ID, pelanggan, atau item" value="${esc(f.q)}"></div>
      <div class="field"><label>&nbsp;</label><button class="btn ghost sm" style="padding:10px 16px" onclick="resetFilter()">Reset</button></div>
    </div>
    <div class="print-meta" id="printMeta" style="padding:0 0 8px"></div>
    <div class="tbl-wrap"><table>
      <thead><tr><th>ID</th><th>Tanggal</th><th>Pelanggan</th><th>Produk / Jasa</th><th>Jurusan</th><th>Jenis</th><th class="r">Total</th><th>Status</th><th class="r col-act">Aksi</th></tr></thead>
      <tbody id="lapBody"></tbody>
    </table></div>
  </section>
  <div class="sign"><div>Mengetahui,<br>Super Admin<div class="line">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</div></div></div>`;
}
function bindLaporan(){
  const map = {fj:'jurusan', fs:'status', fd:'dari', fe:'sampai', fq:'q'};
  Object.entries(map).forEach(([id,key])=>{
    const el = $('#'+id);
    el.addEventListener(el.tagName==='SELECT'||el.type==='date'?'change':'input', e=>{ state.filter[key]=e.target.value; laporanBody(); });
  });
  laporanBody();
}
function resetFilter(){ state.filter = {jurusan:'',status:'',dari:'',sampai:'',q:''}; render(); }
function cetak(){ addLog('Laporan transaksi global dicetak','print'); window.print(); }
function exportCsv(){
  const list = filteredTrx();
  const head = ['ID','Tanggal','Pelanggan','Produk/Jasa','Jurusan','Jenis','Total','Status','Catatan'];
  const csv = [head, ...list.map(x=>[x.id,x.tgl,x.pelanggan,x.item,jurName(x.jurusan),x.jenis,x.total,x.status,x.catatan])]
    .map(r=>r.map(c=>`"${String(c).replace(/"/g,'""')}"`).join(',')).join('\n');
  const a = document.createElement('a');
  a.href = URL.createObjectURL(new Blob(['\ufeff'+csv],{type:'text/csv;charset=utf-8'}));
  a.download = `laporan-transaksi-${today()}.csv`;
  document.body.appendChild(a); a.click(); a.remove();
  toast(`${list.length} transaksi diekspor.`);
}
function koreksi(id){
  const x = state.trx.find(t=>t.id===id);
  openModal('Koreksi transaksi '+id, `
    <p style="margin-bottom:14px"><b>${esc(x.item)}</b><br><span class="t-muted">${esc(x.pelanggan)} · ${esc(jurName(x.jurusan))}</span></p>
    <div class="form-grid">
      <div class="field"><label for="k_total">Total (Rp)</label><input class="inp" id="k_total" type="number" min="0" step="1000" name="total" required value="${x.total}"></div>
      <div class="field"><label for="k_status">Status</label><select class="sel" id="k_status" name="status">${Object.keys(STATUS).map(s=>`<option ${x.status===s?'selected':''}>${s}</option>`).join('')}</select></div>
      <div class="field full"><label for="k_cat">Alasan koreksi</label><textarea class="inp" id="k_cat" name="catatan" rows="3" required placeholder="Contoh: nominal salah input oleh admin jurusan">${esc(x.catatan)}</textarea><div class="hint">Alasan dicatat di log aktivitas untuk keperluan audit.</div></div>
    </div>`, d=>{
      x.total = Number(d.total); x.status = d.status; x.catatan = d.catatan.trim();
      addLog(`Transaksi ${id} dikoreksi (${x.status}, ${rp(x.total)})`,'edit'); toast('Transaksi dikoreksi.'); render();
    }, 'Simpan koreksi');
}

/* =========================================================
   VIEW: Konfigurasi Sistem
========================================================= */
function viewSistem(){
  const c = state.config;
  const sw = (key,title,desc)=>`<div class="setting"><div><b>${title}</b><small>${desc}</small></div><label class="switch"><input type="checkbox" data-cfg="${key}" ${c[key]?'checked':''} aria-label="${title}"><span></span></label></div>`;
  return `
  <div class="page-head">
    <div><h1>KONFIGURASI SISTEM <span class="pill yellow">★ Akses Superuser</span></h1><p>Atur identitas platform, akses pengguna, dan keberlangsungan sistem secara keseluruhan.</p></div>
  </div>
  <div class="two" style="margin-top:0">
    <div class="stack">
      <section class="card">
        <div class="card-head"><div><h3>Identitas Platform</h3><p>Tampil di header, laporan cetak, dan situs publik.</p></div></div>
        <div class="card-body">
          <div class="form-grid">
            <div class="field full"><label for="c_p">Nama platform</label><input class="inp" id="c_p" value="${esc(c.platform)}"></div>
            <div class="field full"><label for="c_s">Nama sekolah</label><input class="inp" id="c_s" value="${esc(c.sekolah)}"></div>
            <div class="field"><label for="c_e">Email kontak</label><input class="inp" id="c_e" type="email" value="${esc(c.email)}"></div>
            <div class="field"><label for="c_u">Batas unggah file (MB)</label><input class="inp" id="c_u" type="number" min="1" max="200" value="${c.maxUpload}"></div>
          </div>
          <div style="margin-top:18px"><button class="btn primary" id="saveCfg">Simpan pengaturan</button></div>
        </div>
      </section>
      <section class="card">
        <div class="card-head"><h3>Akses & Keamanan</h3></div>
        <div class="card-body" style="padding-top:6px;padding-bottom:6px">
          ${sw('maintenance','Mode pemeliharaan','Situs publik ditutup sementara. Hanya Super Admin yang bisa masuk.')}
          ${sw('registrasi','Pendaftaran pelanggan baru','Izinkan pelanggan membuat akun sendiri dari situs publik.')}
          ${sw('verifEmail','Wajib verifikasi email','Akun baru harus memverifikasi email sebelum bisa memesan.')}
          ${sw('notifEmail','Notifikasi email transaksi','Kirim email otomatis saat status pesanan berubah.')}
          ${sw('backup','Cadangan data harian','Simpan salinan database otomatis setiap malam.')}
        </div>
      </section>
    </div>
    <section class="card">
      <div class="card-head"><div><h3>Log Aktivitas</h3><p>Jejak perubahan yang dilakukan di panel ini.</p></div></div>
      <div class="card-body" style="padding-top:8px;padding-bottom:8px">
        ${state.log.slice(0,10).map(l=>`<div class="log"><div class="ic c-blue">${ic(l.k)}</div><div>${esc(l.t)}<small>${esc(l.w)}</small></div></div>`).join('')}
      </div>
    </section>
  </div>`;
}
function bindSistem(){
  $$('[data-cfg]').forEach(el=> el.addEventListener('change', ()=>{
    const k = el.dataset.cfg;
    const doIt = ()=>{ state.config[k] = el.checked; addLog(`Pengaturan “${el.getAttribute('aria-label')}” ${el.checked?'diaktifkan':'dinonaktifkan'}`,'gear'); toast('Pengaturan disimpan.'); render(); };
    if(k==='maintenance' && el.checked){
      el.checked = false;
      confirmBox('Aktifkan mode pemeliharaan?','Situs publik tidak dapat diakses pelanggan sampai mode ini dimatikan.', ()=>{ state.config.maintenance = true; addLog('Mode pemeliharaan diaktifkan','gear'); toast('Mode pemeliharaan aktif.'); render(); }, 'Aktifkan');
    } else doIt();
  }));
  $('#saveCfg').onclick = ()=>{
    Object.assign(state.config, {platform:$('#c_p').value.trim(), sekolah:$('#c_s').value.trim(), email:$('#c_e').value.trim(), maxUpload:Number($('#c_u').value)||20});
    addLog('Identitas platform diperbarui','gear'); toast('Pengaturan disimpan.'); render();
  };
}

/* =========================================================
   VIEW: Profil
========================================================= */
function viewProfil(){
  return `
  <div class="page-head"><div><h1>EDIT PROFIL</h1><p>Perbarui data akun Super Admin dan ganti password.</p></div></div>
  <div class="two" style="margin-top:0;grid-template-columns:1fr 1fr">
    <section class="card"><div class="card-head"><h3>Data Akun</h3></div><div class="card-body">
      <div class="person" style="margin-bottom:18px"><div class="avatar" style="width:56px;height:56px;font-size:18px">SA</div><div><b>${esc(state.users[0].nama)}</b><small>Super Admin · akses penuh</small></div></div>
      <div class="form-grid">
        <div class="field full"><label for="p_n">Nama</label><input class="inp" id="p_n" value="${esc(state.users[0].nama)}"></div>
        <div class="field full"><label for="p_e">Email</label><input class="inp" id="p_e" type="email" value="${esc(state.users[0].email)}"></div>
      </div>
      <div style="margin-top:18px"><button class="btn primary" id="saveProfil">Simpan profil</button></div>
    </div></section>
    <section class="card"><div class="card-head"><h3>Ganti Password</h3></div><div class="card-body">
      <div class="form-grid">
        <div class="field full"><label for="pw0">Password saat ini</label><input class="inp" id="pw0" type="password"></div>
        <div class="field full"><label for="pw1">Password baru</label><input class="inp" id="pw1" type="password" minlength="6"></div>
        <div class="field full"><label for="pw2">Ulangi password baru</label><input class="inp" id="pw2" type="password" minlength="6"></div>
      </div>
      <div style="margin-top:18px"><button class="btn ghost" id="savePw">Ganti password</button></div>
    </div></section>
  </div>`;
}
function bindProfil(){
  $('#saveProfil').onclick = ()=>{
    const n = $('#p_n').value.trim(), e = $('#p_e').value.trim();
    if(!n || !e){ toast('Nama dan email wajib diisi.', true); return; }
    Object.assign(state.users[0], {nama:n, email:e}); toast('Profil disimpan.');
  };
  $('#savePw').onclick = ()=>{
    const a=$('#pw0').value, b=$('#pw1').value, c=$('#pw2').value;
    if(!a||!b){ toast('Isi password saat ini dan password baru.', true); return; }
    if(b.length<6){ toast('Password baru minimal 6 karakter.', true); return; }
    if(b!==c){ toast('Konfirmasi password tidak sama.', true); return; }
    ['pw0','pw1','pw2'].forEach(i=>$('#'+i).value=''); addLog('Password Super Admin diganti','key'); toast('Password berhasil diganti.');
  };
}

/* =========================================================
   Render utama
========================================================= */
function render(){
  const v = $('#view');
  const views = {ringkasan:viewRingkasan, jurusan:viewJurusan, user:viewUser, laporan:viewLaporan, sistem:viewSistem, profil:viewProfil};
  v.innerHTML = views[current]();
  ({user:bindUser, laporan:bindLaporan, sistem:bindSistem, profil:bindProfil}[current] || (()=>{}))();
  document.title = NAV[current].label + ' — Super Admin TeFa';
}

renderNotif();
go('ringkasan');
</script>
</body>
</html>