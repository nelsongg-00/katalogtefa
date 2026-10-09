<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel Super Admin') — TeFa SMKN 4</title>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/400.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/500.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/600.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/700.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/800.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/900.css" rel="stylesheet">
    <style>
    :root{
      --blue:#0b60cf;--blue-d:#0a4fa8;--blue-dd:#073b80;--blue-l:#eaf2fd;
      --bg:#f4f7fc;--card:#fff;--subtle:#f8fafc;--muted-bg:#f1f5f9;
      --line:#e4eaf3;--line-2:#cbd5e1;
      --ink:#0f1f3a;--ink-2:#334155;--muted:#64748b;--faint:#94a3b8;
      --green:#16a34a;--green-soft:#dcfce7;--green-ink:#15803d;
      --red:#dc2626;--red-soft:#fee2e2;--red-ink:#b91c1c;
      --yellow:#d97706;--yellow-soft:#fef3c7;--yellow-ink:#b45309;
      --r-sm:6px;--r-md:12px;--r-lg:18px;--r-xl:22px;--r-pill:999px;
      --sh:0 1px 2px rgba(11,96,207,.05),0 8px 24px rgba(11,96,207,.07);
      --sh-hover:0 14px 30px rgba(11,96,207,.14);
      --sh-modal:0 30px 70px rgba(0,0,0,.3);
      --topbar-h:64px;--sidebar-w:268px;
      --ease:cubic-bezier(.4,0,.2,1);
      --spring:cubic-bezier(.2,.9,.3,1.2);
    }
    *{box-sizing:border-box;margin:0;padding:0}
    html{scroll-behavior:auto}
    body{font-family:'Open Sauce Sans',system-ui,-apple-system,'Segoe UI',Roboto,sans-serif;background:var(--bg);color:var(--ink);font-size:14px;line-height:1.5;-webkit-font-smoothing:antialiased;text-rendering:optimizeLegibility}
    button,input,select,textarea{font-family:inherit;font-size:inherit;color:inherit}
    button{cursor:pointer;border:none;background:none}
    a{text-decoration:none;color:inherit}
    :focus-visible{outline:2px solid var(--blue);outline-offset:2px;border-radius:4px}
    svg.i{width:20px;height:20px;fill:none;stroke:currentColor;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round;flex:none}
    svg.i-16{width:16px;height:16px}
    svg.i-18{width:18px;height:18px}
    .num,.tabular{font-variant-numeric:tabular-nums}
    .sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}

    /* ---------- Sidebar (blue gradient) ---------- */
    .sidebar{position:fixed;top:0;left:0;bottom:0;width:var(--sidebar-w);background:linear-gradient(170deg,var(--blue) 0%,var(--blue-dd) 100%);color:#fff;display:flex;flex-direction:column;padding:20px 14px;overflow:hidden;z-index:40;transition:transform .35s var(--ease)}
    .sidebar::before{content:"";position:absolute;width:300px;height:300px;border-radius:50%;background:rgba(255,255,255,.07);right:-140px;bottom:-90px;pointer-events:none}
    .sb-brand{display:flex;align-items:center;gap:12px;padding:6px 8px 20px;border-bottom:1px solid rgba(255,255,255,.15);margin-bottom:16px}
    .sb-brand .logo{width:46px;height:46px;border-radius:13px;background:#fff;display:grid;place-items:center;overflow:hidden;flex:none;box-shadow:0 4px 14px rgba(0,0,0,.2)}
    .sb-brand .logo img{width:100%;height:100%;object-fit:contain;padding:4px}
    .sb-brand b{display:block;font-size:15px;font-weight:800;letter-spacing:.2px;line-height:1.15}
    .sb-brand small{font-size:11px;opacity:.75;font-weight:500}
    .nav-t{font-size:10.5px;letter-spacing:1.2px;opacity:.6;font-weight:700;margin:14px 10px 8px;text-transform:uppercase}
    .nav-item{position:relative;display:flex;align-items:center;gap:12px;width:100%;padding:11px 12px;border-radius:var(--r-md);color:rgba(255,255,255,.82);font-weight:600;font-size:13.5px;text-align:left;margin-bottom:3px;transition:background .25s var(--ease),transform .25s var(--ease),color .25s var(--ease)}
    .nav-item svg{color:rgba(255,255,255,.82);transition:color .25s var(--ease);width:18px;height:18px}
    .nav-item:hover{background:rgba(255,255,255,.12);color:#fff;transform:translateX(3px)}
    .nav-item:hover svg{color:#fff}
    .nav-item.active{background:#fff;color:var(--blue);box-shadow:0 6px 18px rgba(0,0,0,.18)}
    .nav-item.active svg{color:var(--blue)}
    .nav-item .count{margin-left:auto;background:var(--red);color:#fff;font-size:10.5px;font-weight:700;border-radius:var(--r-pill);padding:1px 7px;font-variant-numeric:tabular-nums}
    .side-foot{margin-top:auto;background:rgba(255,255,255,.1);border-radius:14px;padding:12px;display:flex;gap:10px;align-items:center;position:relative}
    .side-foot .av{width:38px;height:38px;border-radius:50%;background:#fff;color:var(--blue);display:grid;place-items:center;font-weight:800;font-size:14px;flex:none;overflow:hidden}
    .side-foot b{font-size:13px;display:block}
    .side-foot small{font-size:11px;opacity:.7}
    .scrim{display:none;position:fixed;inset:0;background:rgba(7,59,128,.45);backdrop-filter:blur(4px);z-index:39;animation:fade .2s var(--ease)}

    /* ---------- Topbar ---------- */
    .topbar{position:fixed;top:0;left:var(--sidebar-w);right:0;height:var(--topbar-h);background:rgba(255,255,255,.85);backdrop-filter:blur(10px);border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between;padding:0 28px;z-index:50;transition:left .35s var(--ease)}
    .topbar .left,.topbar .right{display:flex;align-items:center;gap:14px}
    .brand{display:flex;align-items:center;gap:12px}
    .brand img{width:36px;height:36px;object-fit:contain;flex:none}
    .brand-txt b{display:block;font-size:15px;font-weight:700;line-height:20px;letter-spacing:-.01em}
    .brand-txt small{display:block;font-size:11.5px;font-weight:500;color:var(--muted);line-height:14px}
    .menu-btn{display:none;width:40px;height:40px;border-radius:var(--r-md);background:var(--blue-l);color:var(--blue);place-items:center;transition:background .16s var(--ease)}
    .menu-btn:hover{background:var(--blue-l)}
    .top-div{width:1px;height:24px;background:var(--line)}
    .bell{position:relative;width:40px;height:40px;border-radius:var(--r-md);background:#fff;border:1px solid var(--line);color:var(--muted);display:grid;place-items:center;transition:.25s var(--ease)}
    .bell:hover{color:var(--blue);border-color:var(--blue);transform:translateY(-2px)}
    .bell .dot{position:absolute;top:9px;right:10px;width:8px;height:8px;border-radius:50%;background:var(--red);border:2px solid #fff;animation:pulse 2s infinite}
    .rel{position:relative}
    .notif{position:absolute;top:48px;right:0;width:340px;background:var(--card);border:1px solid var(--line);border-radius:var(--r-lg);box-shadow:var(--sh);padding:8px;display:none}
    .notif.open{display:block;animation:drop .16s var(--ease)}
    .notif h4{font-size:12.5px;font-weight:700;padding:8px 10px 6px;border-bottom:1px solid var(--line)}
    .notif .row{display:flex;gap:10px;padding:10px;border-radius:var(--r-md);font-size:12.5px;align-items:center;transition:background .12s var(--ease)}
    .notif .row:hover{background:var(--subtle)}
    .notif .row small{display:block;color:var(--muted);font-size:11px}
    .notif .ic{width:32px;height:32px;border-radius:var(--r-md);display:grid;place-items:center;flex:none}

    /* ---------- Account menu ---------- */
    .acct-btn{display:flex;align-items:center;gap:10px;padding:6px 10px 6px 6px;border-radius:var(--r-md);transition:background .16s var(--ease)}
    .acct-btn:hover{background:var(--muted-bg)}
    .acct-btn .avatar{width:40px;height:40px;border-radius:50%;background:var(--blue-l);color:var(--blue);font-weight:800;font-size:14px;display:grid;place-items:center;flex:none;overflow:hidden}
    .acct-btn .acct-name b{display:block;font-size:13px;font-weight:600;line-height:17px}
    .acct-btn .acct-name span{display:block;font-size:11px;color:var(--muted);line-height:14px}
    .acct-btn .chev{color:var(--faint);transition:transform .2s var(--ease)}
    .acct-btn.open .chev{transform:rotate(180deg)}
    .acct-menu{position:absolute;top:50px;right:0;width:280px;background:var(--card);border:1px solid var(--line);border-radius:var(--r-lg);box-shadow:var(--sh);padding:8px;display:none}
    .acct-menu.open{display:block;animation:drop .16s var(--ease)}
    .acct-head{display:flex;align-items:center;gap:12px;padding:10px 10px 14px;border-bottom:1px solid var(--line);margin-bottom:6px}
    .acct-head .avatar{width:40px;height:40px;border-radius:50%;background:var(--blue-l);color:var(--blue);font-weight:800;font-size:14px;display:grid;place-items:center;flex:none;overflow:hidden}
    .acct-head b{display:block;font-size:14px;font-weight:600;line-height:18px}
    .acct-head small{display:block;font-size:12px;color:var(--muted);line-height:16px}
    .acct-head .role-tag{display:inline-block;font-size:10.5px;font-weight:700;padding:1px 8px;border-radius:var(--r-pill);background:var(--blue);color:#fff;margin-top:3px}
    .acct-item{display:flex;align-items:center;gap:10px;padding:9px 10px;border-radius:var(--r-md);font-size:13px;font-weight:500;color:var(--ink-2);transition:background .12s var(--ease),color .12s var(--ease)}
    .acct-item:hover{background:var(--subtle);color:var(--ink)}
    .acct-item svg{color:var(--faint)}
    .acct-item:hover svg{color:var(--blue)}
    .acct-item.danger{color:var(--red-ink)}
    .acct-item.danger svg{color:var(--red-ink)}
    .acct-item.danger:hover{background:var(--red-soft)}
    .acct-div{height:1px;background:var(--line);margin:6px 4px}

    /* ---------- Main ---------- */
    main{margin-left:var(--sidebar-w);padding:calc(var(--topbar-h) + 26px) 28px 40px;min-height:100vh}
    main:focus{outline:none}
    .content{display:flex;flex-direction:column;gap:22px}
    .stack-y{display:flex;flex-direction:column;gap:22px}

    /* ---------- Hero ---------- */
    .hero{background:linear-gradient(120deg,var(--blue),var(--blue-d) 70%,var(--blue-dd));border-radius:var(--r-xl);color:#fff;padding:28px 30px;display:flex;justify-content:space-between;align-items:center;gap:20px;flex-wrap:wrap;position:relative;overflow:hidden;box-shadow:0 14px 34px rgba(11,96,207,.28)}
    .hero::before,.hero::after{content:"";position:absolute;border-radius:50%;background:rgba(255,255,255,.08);pointer-events:none}
    .hero::before{width:260px;height:260px;right:-60px;top:-110px;animation:float 9s ease-in-out infinite}
    .hero::after{width:150px;height:150px;right:150px;bottom:-90px;animation:float 11s ease-in-out infinite reverse}
    .hero .pill{display:inline-block;background:rgba(255,255,255,.18);padding:5px 12px;border-radius:var(--r-pill);font-size:11.5px;font-weight:700;margin-bottom:10px}
    .hero h1{font-size:25px;font-weight:800;letter-spacing:.3px}
    .hero p{font-size:13.5px;opacity:.85;margin-top:6px;max-width:520px;line-height:1.55}
    .hero .acts{display:flex;gap:10px;flex-wrap:wrap;position:relative;z-index:1}
    .btn-w{background:#fff;color:var(--blue);padding:11px 18px;border-radius:var(--r-md);font-size:13px;font-weight:700;transition:.25s var(--ease);display:inline-flex;align-items:center;gap:8px}
    .btn-w:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(0,0,0,.2)}
    .btn-t{background:rgba(255,255,255,.14);color:#fff;border:1px solid rgba(255,255,255,.3);padding:11px 18px;border-radius:var(--r-md);font-size:13px;font-weight:700;transition:.25s var(--ease);display:inline-flex;align-items:center;gap:8px}
    .btn-t:hover{background:rgba(255,255,255,.25)}

    /* ---------- Page header (non-dashboard pages) ---------- */
    .page-head{display:flex;justify-content:space-between;align-items:flex-start;gap:20px;flex-wrap:wrap}
    .page-head h1{font-size:22px;line-height:30px;font-weight:800;letter-spacing:-.01em;display:flex;align-items:center;gap:10px;flex-wrap:wrap}
    .page-head p{color:var(--muted);margin-top:4px;max-width:680px;font-size:13.5px}
    .pill{font-size:11.5px;font-weight:600;padding:4px 10px;border-radius:var(--r-pill);background:var(--blue-l);color:var(--blue);border:1px solid var(--blue-l)}
    .actions{display:flex;gap:8px;flex-wrap:wrap;align-items:center}

    /* ---------- Buttons ---------- */
    .btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;padding:11px 18px;border-radius:var(--r-md);font-weight:700;font-size:13px;line-height:20px;white-space:nowrap;transition:.25s var(--ease);cursor:pointer}
    .btn:active{transform:translateY(1px)}
    .btn[disabled],.btn[aria-disabled="true"]{opacity:.55;pointer-events:none}
    .btn.primary{background:var(--blue);color:#fff}
    .btn.primary:hover{background:var(--blue-d);transform:translateY(-1px)}
    .btn.ghost{background:var(--card);border:1px solid var(--line);color:var(--ink)}
    .btn.ghost:hover{background:var(--subtle);border-color:var(--faint)}
    .btn.danger{background:var(--red);border:1px solid var(--red);color:#fff}
    .btn.danger:hover{background:var(--red-ink);border-color:var(--red-ink)}
    .btn.sm{padding:7px 14px;font-size:12.5px;gap:6px}

    /* ---------- Cards ---------- */
    .card{background:var(--card);border:1px solid var(--line);border-radius:var(--r-lg);box-shadow:var(--sh);overflow:hidden;transition:box-shadow .3s var(--ease),border-color .3s var(--ease)}
    .card-head{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;padding:22px 22px 0;flex-wrap:wrap}
    .card-head h3{font-size:16px;font-weight:800}
    .card-head p{color:var(--muted);font-size:12.5px;margin-top:3px}
    .card-head .card-head-actions{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
    .card-body{padding:22px}
    .card-body.flush{padding:6px 22px}
    .chip{background:var(--blue-l);color:var(--blue);font-size:12px;font-weight:700;padding:6px 12px;border-radius:var(--r-pill)}

    /* ---------- Stats ---------- */
    .stats{display:grid;grid-template-columns:repeat(4,1fr);gap:18px}
    .stat{padding:20px;display:flex;flex-direction:column;position:relative;overflow:hidden;transition:transform .3s var(--ease),box-shadow .3s var(--ease)}
    .stat::after{content:"";position:absolute;left:0;top:0;height:3px;width:100%;background:linear-gradient(90deg,var(--blue),#5ea1f5);transform:scaleX(0);transform-origin:left;transition:transform .4s var(--ease)}
    .stat:hover{transform:translateY(-4px);box-shadow:var(--sh-hover)}
    .stat:hover::after{transform:scaleX(1)}
    .stat .ico{width:44px;height:44px;border-radius:13px;background:var(--blue-l);color:var(--blue);display:grid;place-items:center;margin-bottom:14px;flex:none}
    .stat .ico.hero{background:linear-gradient(135deg,var(--blue),#3d8ef4);color:#fff;box-shadow:0 4px 14px rgba(11,96,207,.3)}
    .stat .ico svg{width:21px;height:21px}
    .stat .v{font-size:26px;font-weight:800;letter-spacing:-.5px;font-variant-numeric:tabular-nums}
    .stat .l{font-size:12.5px;color:var(--muted);font-weight:600;margin:2px 0 10px}
    .stat .tag{font-size:11.5px;font-weight:700;padding:4px 10px;border-radius:var(--r-pill);display:inline-block}
    .t-w{background:var(--yellow-soft);color:var(--yellow-ink)}
    .t-s{background:var(--green-soft);color:var(--green-ink)}
    .t-i{background:var(--blue-l);color:var(--blue)}
    .t-muted{color:var(--muted)}
    .mini-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:18px}
    .mini{padding:18px 20px}
    .mini small{display:block;color:var(--muted);font-size:12px;font-weight:600}
    .mini b{font-size:22px;line-height:28px;font-weight:800;font-variant-numeric:tabular-nums;letter-spacing:-.01em}
    .c-blue{background:var(--blue-l);color:var(--blue)}
    .c-yellow{background:var(--yellow-soft);color:var(--yellow-ink)}
    .c-green{background:var(--green-soft);color:var(--green-ink)}
    .c-neutral{background:var(--muted-bg);color:var(--ink-2)}

    /* ---------- Tables ---------- */
    .tbl-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch}
    table{width:100%;border-collapse:collapse;min-width:640px}
    thead th{font-size:11.5px;font-weight:700;color:var(--muted);text-align:left;letter-spacing:.6px;text-transform:uppercase;padding:10px 12px;border-bottom:1px solid var(--line);white-space:nowrap}
    thead th:first-child{padding-left:22px}
    thead th:last-child{padding-right:22px}
    tbody td{padding:13px 12px;border-bottom:1px solid var(--line);vertical-align:middle;font-size:13.5px}
    tbody td:first-child{padding-left:22px}
    tbody td:last-child{padding-right:22px}
    tbody tr{transition:background .2s var(--ease)}
    tbody tr:hover{background:#f7faff}
    tbody tr:last-child td{border-bottom:none}
    td.r,th.r{text-align:right}
    td.r strong,.num-cell{font-variant-numeric:tabular-nums}
    .person{display:flex;align-items:center;gap:11px}
    .person .avatar{width:36px;height:36px;border-radius:50%;background:var(--blue-l);color:var(--blue);font-weight:800;font-size:13px;display:grid;place-items:center;flex:none;overflow:hidden}
    .person b{display:block;font-weight:600;font-size:13.5px}
    .person small{display:block;color:var(--muted);font-size:11.5px}
    .badge{display:inline-block;font-size:11.5px;font-weight:700;padding:4px 11px;border-radius:var(--r-pill);white-space:nowrap;border:1px solid transparent}
    .b-primary{background:var(--blue);color:#fff}
    .b-blue{background:var(--blue-l);color:var(--blue);border-color:var(--blue-l)}
    .b-green{background:var(--green-soft);color:var(--green-ink);border-color:#bbf7d0}
    .b-yellow{background:var(--yellow-soft);color:var(--yellow-ink);border-color:#fde68a}
    .b-red{background:var(--red-soft);color:var(--red-ink);border-color:#fecaca}
    .b-gray{background:var(--muted-bg);color:var(--ink-2);border-color:var(--line)}
    .row-actions{display:flex;gap:4px;justify-content:flex-end}
    .icon-btn{width:36px;height:36px;border-radius:var(--r-md);display:grid;place-items:center;color:var(--muted);transition:.25s var(--ease)}
    .icon-btn:hover{background:var(--blue-l);color:var(--blue)}
    .icon-btn.del:hover{background:var(--red-soft);color:var(--red-ink)}
    .empty{text-align:center;padding:40px 24px;color:var(--muted);font-size:13.5px}
    .empty .empty-ic{width:52px;height:52px;border-radius:50%;background:var(--muted-bg);color:var(--faint);display:grid;place-items:center;margin:0 auto 12px}
    .empty b{display:block;color:var(--ink);font-size:14px;font-weight:600;margin-bottom:3px}

    /* ---------- Toolbar & form ---------- */
    .toolbar{display:flex;gap:12px;flex-wrap:wrap;padding:18px 22px;align-items:flex-end;background:var(--subtle);border-bottom:1px solid var(--line)}
    .field{display:flex;flex-direction:column;gap:5px;min-width:0}
    .field.grow{flex:1;min-width:180px}
    .toolbar-actions{display:flex;gap:6px}
    .field label{font-size:12px;font-weight:700;color:var(--ink-2)}
    .inp,.sel{border:1px solid var(--line);background:var(--card);color:var(--ink);border-radius:11px;padding:11px 13px;min-width:0;font-size:13.5px;transition:.25s var(--ease)}
    .inp::placeholder{color:var(--faint)}
    .inp:hover,.sel:hover{border-color:var(--faint)}
    .inp:focus,.sel:focus{outline:none;border-color:var(--blue);box-shadow:0 0 0 4px rgba(11,96,207,.12);background:#fff}
    .inp:disabled,.sel:disabled{background:var(--muted-bg);color:var(--faint);cursor:not-allowed}
    .field.has-error .inp,.field.has-error .sel{border-color:var(--red);box-shadow:0 0 0 4px rgba(220,38,38,.12)}
    .sel{appearance:auto;padding-right:10px}
    .search{position:relative;flex:1;min-width:200px}
    .search svg{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--faint);width:16px;height:16px}
    .search .inp{width:100%;padding-left:36px}
    .hint{font-size:12px;color:var(--muted);margin-top:2px}
    .field-error{font-size:12px;font-weight:600;color:var(--red-ink);margin-top:2px}
    .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    .form-grid .full{grid-column:1/-1}
    .form-grid .inp,.form-grid .sel,.form-grid textarea{width:100%}

    /* ---------- Toggle switch ---------- */
    .sw{display:flex;justify-content:space-between;align-items:center;background:var(--bg);padding:12px 14px;border-radius:var(--r-md);font-size:13px;font-weight:600}
    .tg{width:44px;height:25px;background:#cbd5e1;border-radius:var(--r-pill);position:relative;transition:.3s var(--ease);flex:none;cursor:pointer}
    .tg::after{content:"";position:absolute;left:3px;top:3px;width:19px;height:19px;border-radius:50%;background:#fff;transition:.3s var(--ease);box-shadow:0 2px 5px rgba(0,0,0,.2)}
    .tg.on{background:var(--blue)}
    .tg.on::after{left:22px}

    /* ---------- Progress ---------- */
    .prog{margin-bottom:16px}
    .prog:last-child{margin-bottom:0}
    .prog .t{display:flex;justify-content:space-between;font-size:13px;margin-bottom:7px;gap:8px}
    .prog .t span{color:var(--muted);font-size:12px;font-weight:600}
    .track{height:9px;background:var(--bg);border-radius:var(--r-pill);overflow:hidden}
    .fill{height:100%;border-radius:var(--r-pill);width:0;transition:width 1.1s cubic-bezier(.2,.8,.2,1)}

    /* ---------- Chart ---------- */
    .legend{display:flex;gap:18px;font-size:12px;color:var(--muted);font-weight:600;margin-bottom:10px}
    .legend i{display:inline-block;width:10px;height:10px;border-radius:3px;margin-right:6px}
    .chart{position:relative;height:250px;padding-left:30px}
    .chart .grid{position:absolute;inset:0 0 26px 30px}
    .chart .grid div{position:absolute;left:0;right:0;border-top:1px dashed var(--line)}
    .chart .grid span{position:absolute;left:-30px;top:-8px;font-size:10.5px;color:var(--muted);width:24px;text-align:right;font-variant-numeric:tabular-nums}
    .chart .cols{position:absolute;inset:0 0 0 30px;display:grid;grid-template-columns:repeat(12,1fr)}
    .chart .col{display:flex;flex-direction:column;justify-content:flex-end;align-items:center}
    .chart .bars{flex:1;width:100%;display:flex;align-items:flex-end;justify-content:center;gap:4px}
    .chart .bar{width:35%;max-width:16px;border-radius:6px 6px 2px 2px;height:0;transition:height 1s cubic-bezier(.2,.8,.2,1);position:relative;min-height:0;cursor:pointer}
    .chart .bar.a{background:linear-gradient(var(--blue),#4d93ee)}
    .chart .bar.b{background:linear-gradient(#22c55e,#86efac)}
    .chart .bar:hover::after{content:attr(data-v);position:absolute;top:-26px;left:50%;transform:translateX(-50%);background:var(--ink);color:#fff;font-size:11px;padding:3px 8px;border-radius:7px;font-weight:700;white-space:nowrap;font-variant-numeric:tabular-nums}
    .chart .m{height:26px;font-size:11px;color:var(--muted);font-weight:600;display:grid;place-items:end center;padding-top:8px}

    /* ---------- Layout helpers ---------- */
    .two{display:grid;grid-template-columns:1.25fr 1fr;gap:22px}
    .stack{display:flex;flex-direction:column;gap:22px}
    .feed-row{display:flex;justify-content:space-between;align-items:center;padding:13px 0;border-bottom:1px solid var(--line);gap:10px;transition:padding .25s var(--ease)}
    .feed-row:last-child{border-bottom:none;padding-bottom:0}
    .feed-row:hover{padding-left:6px}
    .feed-row b{font-size:13.5px;display:block}
    .feed-row small{font-size:11.5px;color:var(--muted)}

    /* ---------- Modal ---------- */
    .modal{position:fixed;inset:0;background:rgba(7,59,128,.45);backdrop-filter:blur(4px);display:none;align-items:center;justify-content:center;padding:20px;z-index:100}
    .modal.open{display:flex;animation:fade .3s var(--ease)}
    .dialog{background:var(--card);border:1px solid var(--line);border-radius:var(--r-xl);width:100%;max-width:540px;max-height:92vh;overflow:auto;box-shadow:var(--sh-modal);transform:translateY(24px) scale(.96);transition:transform .35s var(--spring)}
    .modal.open .dialog{transform:none}
    .dialog header{display:flex;justify-content:space-between;align-items:center;gap:12px;padding:22px 24px;border-bottom:1px solid var(--line);background:linear-gradient(120deg,var(--blue),var(--blue-d));color:#fff}
    .dialog header h3{font-size:17px;font-weight:800}
    .dialog header .icon-btn{color:#fff;background:rgba(255,255,255,.18);border-radius:10px;width:34px;height:34px}
    .dialog header .icon-btn:hover{background:rgba(255,255,255,.3);transform:rotate(90deg)}
    .dialog .body{padding:22px 24px;display:flex;flex-direction:column;gap:15px}
    .dialog .body > p{font-size:13.5px;color:var(--ink-2);margin-bottom:0}
    .dialog footer{display:flex;justify-content:flex-end;gap:10px;padding:0 24px 22px;background:var(--card)}

    /* ---------- Toasts ---------- */
    .toast-wrap{position:fixed;right:24px;bottom:24px;display:flex;flex-direction:column;gap:10px;z-index:200;max-width:min(380px,calc(100vw - 48px))}
    .toast{background:var(--ink);color:#fff;padding:13px 18px;border-radius:13px;font-weight:600;font-size:13px;box-shadow:var(--sh);border-left:3px solid var(--blue);animation:toastSlide .4s var(--ease)}
    .toast.err{border-left-color:var(--red)}

    /* ---------- Keyframes (forced — no reduced-motion opt-out) ---------- */
    @keyframes fade{from{opacity:0}to{opacity:1}}
    @keyframes drop{from{opacity:0;transform:translateY(-6px)}to{opacity:1;transform:none}}
    @keyframes up{to{opacity:1;transform:none}}
    @keyframes float{50%{transform:translateY(14px)}}
    @keyframes pulse{50%{box-shadow:0 0 0 5px rgba(220,38,38,.2)}}
    @keyframes toastSlide{from{opacity:0;transform:translateY(90px)}to{opacity:1;transform:none}}

    /* Staggered reveal — children of stack-y and stats */
    .stack-y > *{opacity:0;transform:translateY(16px);animation:up .6s cubic-bezier(.2,.8,.2,1) forwards}
    .stack-y > *:nth-child(1){animation-delay:0ms}
    .stack-y > *:nth-child(2){animation-delay:80ms}
    .stack-y > *:nth-child(3){animation-delay:160ms}
    .stack-y > *:nth-child(4){animation-delay:240ms}
    .stack-y > *:nth-child(5){animation-delay:320ms}
    .stack-y > *:nth-child(6){animation-delay:400ms}
    .stack-y > *:nth-child(n+7){animation-delay:480ms}
    .stats > *{opacity:0;transform:translateY(16px);animation:up .6s cubic-bezier(.2,.8,.2,1) forwards}
    .stats > *:nth-child(1){animation-delay:80ms}
    .stats > *:nth-child(2){animation-delay:160ms}
    .stats > *:nth-child(3){animation-delay:240ms}
    .stats > *:nth-child(4){animation-delay:320ms}

    /* ---------- Responsive ---------- */
    @media (max-width:1100px){.stats,.mini-stats{grid-template-columns:repeat(2,1fr)}.two{grid-template-columns:1fr}}
    @media (max-width:900px){
      .menu-btn{display:grid}
      .sidebar{transform:translateX(-100%)}
      .sidebar.open{transform:none}
      .scrim.open{display:block}
      .topbar{left:0;padding:0 16px}
      main{margin-left:0;padding:calc(var(--topbar-h) + 16px) 16px 32px}
      .acct-btn .acct-name{display:none}
      .brand-txt small{display:none}
    }
    @media (max-width:520px){
      .stats,.mini-stats{grid-template-columns:1fr}
      .form-grid{grid-template-columns:1fr}
      .chart .bar{width:12px}
      main{padding:calc(var(--topbar-h) + 12px) 12px 28px}
      .hero h1{font-size:21px}
      .hero{padding:22px 22px}
    }
    </style>
    @stack('styles')
</head>
<body>

<nav class="sidebar" id="sidebar" aria-label="Menu utama">
  <div class="nav-t">MENU UTAMA</div>
  <a href="{{ route('superadmin.dashboard') }}" class="nav-item {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
    <svg class="i" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="9" rx="2"/><rect x="14" y="3" width="7" height="5" rx="2"/><rect x="14" y="12" width="7" height="9" rx="2"/><rect x="3" y="16" width="7" height="5" rx="2"/></svg>
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
  <div class="nav-t">LAPORAN</div>
  <a href="{{ route('superadmin.reports.index') }}" class="nav-item {{ request()->routeIs('superadmin.reports.*') ? 'active' : '' }}">
    <svg class="i" viewBox="0 0 24 24"><path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/></svg>
    <span>Laporan Transaksi</span>
  </a>
  <div class="side-foot">
    <div class="av">@include('partials.avatar', ['user' => auth()->user(), 'initial' => 'SA'])</div>
    <div><b>{{ auth()->user()->name ?? 'Super Admin' }}</b><small>{{ auth()->user()->email ?? '' }}</small></div>
  </div>
</nav>

<div class="scrim" id="scrim"></div>

<header class="topbar">
  <div class="left">
    <button class="menu-btn" id="menuBtn" aria-label="Buka menu"><svg class="i" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
    <a href="{{ route('superadmin.dashboard') }}" class="brand">
        <img src="{{ asset('asset/img/logo-smkn4.png') }}" alt="Logo SMK Negeri 4 Tanjungpinang">
        <div class="brand-txt">
            <b>SMKN 4 Tanjungpinang</b>
            <small>Panel Super Admin</small>
        </div>
    </a>
  </div>
  <div class="right">
    <div class="rel">
      <button class="bell" id="bellBtn" aria-label="Notifikasi"><svg class="i" viewBox="0 0 24 24"><path d="M18 8a6 6 0 10-12 0c0 7-3 8-3 8h18s-3-2-3-9M13.7 21a2 2 0 01-3.4 0"/></svg><span class="dot" id="bellDot"></span></button>
      <div class="notif" id="notif">
        <h4>Aktivitas TeFa</h4>
        <div class="row">
          <div class="ic c-blue"><svg class="i i-16" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></div>
          <div>Sistem aktif dan siap monitoring transaksi seluruh jurusan.<small>Hari ini</small></div>
        </div>
      </div>
    </div>
    <div class="top-div"></div>
    <div class="rel">
      <button class="acct-btn" id="acctBtn" aria-label="Menu akun">
          <div class="avatar">@include('partials.avatar', ['user' => auth()->user(), 'initial' => 'SA'])</div>
          <div class="acct-name">
              <b>{{ auth()->user()->name ?? 'Super Admin' }}</b>
              <span>Super Admin</span>
          </div>
          <svg class="i i-16 chev" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
      </button>
      <div class="acct-menu" id="acctMenu">
          <div class="acct-head">
              <div class="avatar">@include('partials.avatar', ['user' => auth()->user(), 'initial' => 'SA'])</div>
              <div>
                  <b>{{ auth()->user()->name ?? 'Super Admin' }}</b>
                  <small>{{ auth()->user()->email ?? '' }}</small>
                  <span class="role-tag">Super Admin</span>
              </div>
          </div>
          <a href="{{ route('profile.edit') }}" class="acct-item">
              <svg class="i i-16" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/></svg>
              Pusat Akun
          </a>
          <a href="{{ route('profile.edit') }}" class="acct-item">
              <svg class="i i-16" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 11-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 11-4 0v-.09a1.65 1.65 0 00-1-1.51 1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 11-2.83-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 110-4h.09a1.65 1.65 0 001.51-1 1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 112.83-2.83l.06.06a1.65 1.65 0 001.82.33h0a1.65 1.65 0 001-1.51V3a2 2 0 114 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 112.83 2.83l-.06.06a1.65 1.65 0 00-.33 1.82v0a1.65 1.65 0 001.51 1H21a2 2 0 110 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
              Pengaturan
          </a>
          <div class="acct-div"></div>
          <a href="{{ route('home') }}" target="_blank" class="acct-item">
              <svg class="i i-16" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
              Kunjungi Situs
          </a>
          <a href="{{ route('profil') }}" target="_blank" class="acct-item">
              <svg class="i i-16" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z M9 22V12h6v10"/></svg>
              Profil Sekolah
          </a>
          <div class="acct-div"></div>
          <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="acct-item danger" onclick="return confirm('Apakah Anda yakin ingin keluar dari sesi Super Admin?')">
                  <svg class="i i-16" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                  Keluar
              </button>
          </form>
      </div>
    </div>
  </div>
</header>

<main id="view" tabindex="-1">
    @yield('content')
</main>

<div class="toast-wrap" id="toasts" aria-live="polite">
    @if(session('success'))<div class="toast">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="toast err">{{ session('error') }}</div>@endif
    @if(session('warning'))<div class="toast">{{ session('warning') }}</div>@endif
    @if($errors->any())<div class="toast err">{{ $errors->first() }}</div>@endif
</div>

<script>
const $ = (s, r=document) => r.querySelector(s);

// Mobile sidebar
const menuBtn = $('#menuBtn'), sidebar = $('#sidebar'), scrim = $('#scrim');
if (menuBtn) menuBtn.onclick = () => { sidebar.classList.toggle('open'); scrim.classList.toggle('open'); };
if (scrim) scrim.onclick = () => { sidebar.classList.remove('open'); scrim.classList.remove('open'); };

// Notification bell
const bellBtn = $('#bellBtn'), notif = $('#notif'), bellDot = $('#bellDot');
if (bellBtn && notif) {
    bellBtn.onclick = (e) => { e.stopPropagation(); notif.classList.toggle('open'); if (bellDot) bellDot.style.display = 'none'; };
    document.addEventListener('click', (e) => { if (!e.target.closest('#notif')) notif.classList.remove('open'); });
}

// Account menu
const acctBtn = $('#acctBtn'), acctMenu = $('#acctMenu');
if (acctBtn && acctMenu) {
    acctBtn.onclick = (e) => { e.stopPropagation(); acctBtn.classList.toggle('open'); acctMenu.classList.toggle('open'); };
    document.addEventListener('click', (e) => { if (!e.target.closest('#acctMenu') && !e.target.closest('#acctBtn')) { acctMenu.classList.remove('open'); acctBtn.classList.remove('open'); } });
}

// Toggle switch — sync hidden input
window.toggleSwitch = function(el) {
    el.classList.toggle('on');
    const input = document.getElementById(el.dataset.toggle);
    if (input) input.value = el.classList.contains('on') ? (el.dataset.on || '1') : (el.dataset.off || '0');
    const label = el.previousElementSibling;
    if (label && label.tagName === 'SPAN') label.textContent = el.classList.contains('on') ? 'Aktif' : 'Nonaktif';
};

// Counter animation
document.querySelectorAll('[data-n]').forEach(el => {
    const to = +el.dataset.n, rp = el.dataset.rp, t0 = performance.now(), d = 1300;
    (function f(t) {
        const p = Math.min((t - t0) / d, 1), e = 1 - Math.pow(1 - p, 3), v = Math.round(to * e);
        el.textContent = (rp ? 'Rp ' : '') + v.toLocaleString('id-ID');
        if (p < 1) requestAnimationFrame(f);
    })(t0);
});

// Bar + fill growth animation
setTimeout(() => {
    document.querySelectorAll('.bar[data-h]').forEach(b => b.style.height = b.dataset.h + '%');
    document.querySelectorAll('.fill[data-w]').forEach(f => f.style.width = f.dataset.w + '%');
}, 350);

// Auto-dismiss toasts
setTimeout(() => { document.querySelectorAll('.toast').forEach(t => t.remove()); }, 4000);
</script>
@stack('scripts')
</body>
</html>
