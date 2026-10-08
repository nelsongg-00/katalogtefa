@extends('layouts.public')

@section('title', 'Profil Teaching Factory (TEFA) — SMKN 4 Tanjungpinang')

@section('content')
<style>
    /* Halaman Profil TEFA — biru #0b60cf, aksen kuning #f2b630. */
    .pf {
        --blue: #0b60cf;
        --blue-soft: #6c9ddd;
        --blue-deep: #08306b;
        --yellow: #f2b630;
        --ink: #0f1b33;
        --muted: #5b6678;
        --surface: #fafafa;
        --line: #e4e9f2;
        color: var(--ink);
        overflow-x: clip;
    }

    .pf-shell { width: 100%; max-width: 1120px; margin: 0 auto; padding: 0 20px; }

    /* ================= SCROLL PROGRESS ================= */
    .scroll-progress {
        position: fixed; inset: 0 0 auto 0; height: 3px; z-index: 100;
        background: linear-gradient(90deg, #f2b630, #ffd873, #0b60cf);
        transform-origin: 0 50%; transform: scaleX(0); pointer-events: none;
    }

    /* ================= REVEAL ================= */
    .js [data-reveal] {
        opacity: 0; transform: translateY(44px);
        transition: opacity .8s ease, transform .95s cubic-bezier(.16,1,.3,1), filter .8s ease, clip-path 1.1s cubic-bezier(.77,0,.175,1);
        transition-delay: var(--d, 0ms);
    }
    .js [data-reveal="left"]  { transform: translateX(-70px); }
    .js [data-reveal="right"] { transform: translateX(70px); }
    .js [data-reveal="zoom"]  { transform: scale(.88); }
    .js [data-reveal="blur"]  { transform: translateY(24px) scale(.98); filter: blur(12px); }
    .js [data-reveal="mask"]  { opacity: 1; transform: none; clip-path: inset(0 0 100% 0); }
    .js .is-visible [data-reveal] { opacity: 1; transform: none; filter: none; }
    .js .is-visible [data-reveal="mask"] { clip-path: inset(0 0 0 0); }

    [data-parallax-img] { transform: translate3d(0,0,0) scale(1.18); will-change: transform; }

    .js .split-word { display: inline-block; overflow: hidden; vertical-align: top; padding-bottom: .12em; margin-bottom: -.12em; }
    .js .split-word > span {
        display: inline-block; opacity: 0; transform: translateY(110%) rotate(5deg); filter: blur(6px); transform-origin: 0 100%;
        transition: transform .9s cubic-bezier(.16,1,.3,1), opacity .7s ease, filter .7s ease;
        transition-delay: calc(var(--w, 0) * 70ms + var(--d, 0ms));
    }
    .js .is-visible .split-word > span { opacity: 1; transform: none; filter: none; }

    .shine-text {
        background: linear-gradient(100deg, currentColor 30%, #7fb2ff 50%, currentColor 70%);
        background-size: 220% 100%; -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; animation: shine 4.5s ease-in-out infinite;
    }
    @keyframes shine { 0% { background-position: 120% 0; } 60%, 100% { background-position: -120% 0; } }

    .js [data-stagger-item] {
        opacity: 0; transform: translateY(48px) scale(.86); filter: blur(8px);
        transition: opacity .7s ease, transform .9s cubic-bezier(.16,1,.3,1), filter .7s ease;
        transition-delay: calc(var(--stagger-index, 0) * 120ms);
    }
    .js [data-stagger-item].is-visible { opacity: 1; transform: none; filter: none; }

    /* titik berlian kecil (pengganti simbol/emoji) */
    .pf-dot { display: inline-block; width: 8px; height: 8px; background: var(--yellow); transform: rotate(45deg); flex: none; }

    /* ================= HERO (banner persegi panjang) ================= */
    .pf-hero {
        position: relative; min-height: 540px; display: flex; align-items: center; overflow: hidden; isolation: isolate;
        background:
            linear-gradient(120deg, rgba(8,48,107,.9) 0%, rgba(11,96,207,.74) 55%, rgba(60,121,213,.5) 100%),
            var(--hero-img) center / cover no-repeat;
        background-color: #08306b;
    }

    .pf-hero__grid {
        position: absolute; inset: 0; z-index: 0; pointer-events: none; opacity: .35;
        background-image: linear-gradient(rgba(255,255,255,.14) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.14) 1px, transparent 1px);
        background-size: 48px 48px;
        -webkit-mask-image: radial-gradient(ellipse at 70% 40%, #000 0%, transparent 72%);
        mask-image: radial-gradient(ellipse at 70% 40%, #000 0%, transparent 72%);
        animation: gridMove 20s linear infinite;
    }
    @keyframes gridMove { to { background-position: 48px 48px, 48px 48px; } }

    .pf-hero__sheen {
        position: absolute; inset: -20% auto -20% -40%; width: 40%; z-index: 0; pointer-events: none;
        background: linear-gradient(100deg, transparent, rgba(255,255,255,.12), transparent); transform: skewX(-18deg);
        animation: sheen 7s ease-in-out infinite;
    }
    @keyframes sheen { 0% { left: -45%; } 60%, 100% { left: 125%; } }

    .pf-orb { position: absolute; z-index: 0; border-radius: 50%; filter: blur(40px); pointer-events: none; will-change: transform; }
    .pf-orb > i { display: block; width: 100%; height: 100%; border-radius: 50%; animation: float 9s ease-in-out infinite alternate; }
    .pf-orb--1 { width: 300px; height: 300px; top: 4%; right: 6%; background: radial-gradient(circle, rgba(255,255,255,.45), transparent 70%); }
    .pf-orb--2 { width: 240px; height: 240px; bottom: 6%; right: 30%; background: radial-gradient(circle, rgba(242,182,48,.45), transparent 70%); }
    .pf-orb--3 { width: 360px; height: 360px; top: -14%; left: -8%; background: radial-gradient(circle, rgba(140,190,255,.5), transparent 70%); }
    .pf-orb--2 > i { animation-duration: 11s; animation-delay: -3s; }
    .pf-orb--3 > i { animation-duration: 13s; animation-delay: -6s; }
    @keyframes float { from { transform: translate3d(-18px,14px,0) scale(.95); } to { transform: translate3d(22px,-20px,0) scale(1.08); } }

    .pf-hero__inner { position: relative; z-index: 2; width: 100%; display: grid; grid-template-columns: 1.15fr .85fr; gap: 40px; align-items: center; padding-top: 70px; padding-bottom: 70px; }
    .pf-hero__content { will-change: transform, opacity; }

    .pf-pill {
        display: inline-flex; align-items: center; gap: 8px; padding: 7px 16px; margin-bottom: 20px;
        font-size: 12px; font-weight: 700; letter-spacing: 1.4px; text-transform: uppercase; color: #fff;
        background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.4); border-radius: 999px;
        backdrop-filter: blur(8px); opacity: 0; transform: translateY(14px); animation: fadeUp .8s cubic-bezier(.16,1,.3,1) .1s forwards;
    }
    .pf-pill::before { content: ''; width: 8px; height: 8px; border-radius: 50%; background: var(--yellow); box-shadow: 0 0 0 0 rgba(242,182,48,.7); animation: ping 2s infinite; }
    @keyframes ping { 70% { box-shadow: 0 0 0 9px rgba(242,182,48,0); } 100% { box-shadow: 0 0 0 0 rgba(242,182,48,0); } }

    .pf-hero h1 {
        margin: 0; color: #fff; font-family: 'Open Sauce One', 'Plus Jakarta Sans', sans-serif;
        font-size: clamp(30px, 5vw, 52px); line-height: 1; font-weight: 800; letter-spacing: -1.1px; text-transform: uppercase;
    }
    .pf-hero h1 em { font-style: normal; color: var(--yellow); }
    .pf-hero__line { display: block; overflow: hidden; padding-bottom: .1em; margin-bottom: -.1em; }
    .js .pf-hero__line > span { display: block; transform: translateY(115%); animation: lineUp 1s cubic-bezier(.16,1,.3,1) forwards; animation-delay: calc(var(--i,0) * 130ms + 250ms); }
    @keyframes lineUp { to { transform: none; } }

    .pf-hero__lead {
        max-width: 520px; margin: 18px 0 0; color: rgba(255,255,255,.9); font-size: 15px; line-height: 1.7;
        opacity: 0; transform: translateY(18px); animation: fadeUp .9s cubic-bezier(.16,1,.3,1) .75s forwards;
    }
    .pf-hero__actions { display: flex; align-items: center; gap: 12px; margin-top: 26px; flex-wrap: wrap; opacity: 0; transform: translateY(18px); animation: fadeUp .9s cubic-bezier(.16,1,.3,1) .95s forwards; }
    @keyframes fadeUp { to { opacity: 1; transform: none; } }

    /* kartu kaca + chip melayang di sisi kanan */
    .pf-hero__visual { position: relative; height: 340px; opacity: 0; animation: fadeUp 1s cubic-bezier(.16,1,.3,1) .8s forwards; }
    .pf-glass {
        position: absolute; inset: 30px 10px; display: grid; place-items: center; text-align: center; padding: 24px;
        background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.35); border-radius: 28px;
        backdrop-filter: blur(14px); box-shadow: 0 30px 60px -24px rgba(0,0,0,.45);
    }
    .pf-glass::before { content: ''; position: absolute; inset: 10px; border-radius: 20px; border: 1px dashed rgba(255,255,255,.3); }
    .pf-glass__ico { width: 58px; height: 58px; margin: 0 auto; color: var(--yellow); animation: bob 4s ease-in-out infinite alternate; }
    .pf-glass__ico svg { width: 100%; height: 100%; display: block; }
    .pf-glass b { display: block; margin-top: 12px; color: #fff; font-size: 1.15rem; font-weight: 800; letter-spacing: .3px; }
    .pf-glass span { display: block; margin-top: 4px; color: rgba(255,255,255,.8); font-size: 12.5px; font-weight: 600; }
    .pf-chip {
        position: absolute; z-index: 2; display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; font-size: 12px; font-weight: 800; letter-spacing: 1px; color: #fff;
        background: rgba(8,48,107,.72); border: 1px solid rgba(255,255,255,.35); border-radius: 12px; backdrop-filter: blur(8px);
        box-shadow: 0 14px 24px -12px rgba(0,0,0,.5); animation: bob 5s ease-in-out infinite alternate; will-change: transform;
    }
    .pf-chip--a { top: 0; left: 6%; }
    .pf-chip--b { top: 14%; right: -2%; animation-delay: -1.5s; }
    .pf-chip--c { bottom: 16%; left: -4%; animation-delay: -3s; }
    .pf-chip--d { bottom: 0; right: 10%; animation-delay: -2.2s; }
    @keyframes bob { from { transform: translateY(-7px) rotate(-1.5deg); } to { transform: translateY(9px) rotate(1.5deg); } }

    /* garis aksen bawah banner */
    .pf-hero__bar { position: absolute; inset: auto 0 0 0; height: 5px; z-index: 3; background: linear-gradient(90deg, transparent, var(--yellow), #ffd873, var(--yellow), transparent); background-size: 200% 100%; animation: barRun 4s linear infinite; }
    @keyframes barRun { to { background-position: -200% 0; } }

    .pf-btn {
        position: relative; display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        min-height: 38px; padding: 0 20px; border-radius: 999px; font-size: 13px; font-weight: 600; line-height: 1;
        text-decoration: none; white-space: nowrap; overflow: hidden; cursor: pointer; border: 1px solid transparent;
        transition: transform .25s ease, box-shadow .25s ease, background .25s ease, color .25s ease;
    }
    .pf-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 22px -8px rgba(0,0,0,.45); }
    .pf-btn::after {
        content: ''; position: absolute; top: 0; left: -80%; width: 50%; height: 100%;
        background: linear-gradient(100deg, transparent, rgba(255,255,255,.65), transparent); transform: skewX(-20deg); transition: left .6s ease;
    }
    .pf-btn:hover::after { left: 130%; }
    .pf-btn--primary { background: #fff; color: #111; }
    .pf-btn--outline { color: #fff; border-color: #fff; background: transparent; }
    .pf-btn--outline:hover { background: #fff; color: var(--blue); }
    .pf-btn--blue { background: var(--blue); color: #fff; }
    .pf-btn--ghost { background: #eef3fb; color: #2a3a56; }

    /* ================= SECTION HELPERS ================= */
    .pf-section { padding: 90px 0 30px; }
    .pf-tag { display: inline-flex; align-items: center; gap: 10px; font-size: 12.5px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; color: #c98f00; margin-bottom: 10px; }
    .pf-tag::before { content: ''; width: 28px; height: 3px; border-radius: 3px; background: linear-gradient(90deg, var(--yellow), #ffd873); }
    .pf-title { margin: 0 0 14px; font-size: clamp(1.6rem, 3.2vw, 2.2rem); line-height: 1.15; font-weight: 800; letter-spacing: -.5px; }
    .pf-title .accent { color: var(--blue); }
    .pf-lead { margin: 0; color: var(--muted); font-size: 1.02rem; line-height: 1.75; }

    /* ================= ABOUT ================= */
    .pf-about { display: grid; grid-template-columns: 1.05fr 1fr; gap: 64px; align-items: center; }
    .pf-about__media { position: relative; }
    .pf-about__media::before {
        content: ''; position: absolute; inset: 22px -18px -18px 22px; border-radius: 28px; z-index: 0;
        background: linear-gradient(135deg, var(--yellow), #ffd873); opacity: .9;
    }
    .pf-about__frame { position: relative; z-index: 1; height: 400px; overflow: hidden; border-radius: 28px; background: #ddd; box-shadow: 0 30px 50px -24px rgba(8,48,107,.55); }
    .pf-about__frame img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .pf-about__badge {
        position: absolute; z-index: 2; left: 18px; bottom: 18px; display: flex; align-items: center; gap: 10px; padding: 10px 16px;
        font-size: 13px; font-weight: 700; color: #fff; background: rgba(8,48,107,.78); backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,.25); border-radius: 14px;
    }
    .pf-about__float {
        position: absolute; z-index: 2; top: -18px; right: -14px; padding: 14px 18px; text-align: center; border-radius: 18px;
        background: #fff; box-shadow: 0 18px 34px -14px rgba(11,96,207,.5); animation: bob 5s ease-in-out infinite alternate;
    }
    .pf-about__float b { display: block; font-size: 1.6rem; line-height: 1; color: var(--blue); }
    .pf-about__float span { font-size: 11px; font-weight: 700; color: var(--muted); letter-spacing: .5px; }
    .pf-about p.body { margin: 0 0 24px; color: #46536a; font-size: 15px; line-height: 1.8; }
    .pf-about__actions { display: flex; gap: 12px; flex-wrap: wrap; }

    /* ================= MARQUEE ================= */
    .pf-marquee { margin-top: 80px; padding: 18px 0; overflow: hidden; background: linear-gradient(90deg, #0b60cf, #1c6bd2 50%, #0b60cf); transform: rotate(-1.2deg); box-shadow: 0 18px 34px -20px rgba(11,96,207,.7); }
    .pf-marquee__track { display: flex; width: max-content; animation: marquee 28s linear infinite; }
    .pf-marquee:hover .pf-marquee__track { animation-play-state: paused; }
    .pf-marquee__item { display: inline-flex; align-items: center; gap: 22px; padding-right: 22px; font-size: 15px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; color: #fff; white-space: nowrap; }
    @keyframes marquee { to { transform: translateX(-50%); } }

    /* ================= JEMBATAN TEKNOLOGI ================= */
    .pf-bridge-wrap { padding: 100px 0 20px; }
    .pf-bridge {
        position: relative; overflow: hidden; isolation: isolate; padding: 72px 40px 60px; color: #fff; text-align: center; border-radius: 36px;
        background: linear-gradient(135deg, #08306b 0%, #0b60cf 60%, #1c6bd2 100%); box-shadow: 0 40px 70px -34px rgba(8,48,107,.9);
    }
    .pf-bridge .pf-orb { z-index: -1; }
    .pf-bridge__grid { position: absolute; inset: 0; z-index: -1; opacity: .22; background-image: linear-gradient(rgba(255,255,255,.2) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.2) 1px, transparent 1px); background-size: 42px 42px; -webkit-mask-image: radial-gradient(ellipse at center, #000, transparent 75%); mask-image: radial-gradient(ellipse at center, #000, transparent 75%); }
    .pf-bridge .pf-tag { color: var(--yellow); justify-content: center; }
    .pf-bridge .pf-title { color: #fff; max-width: 720px; margin: 0 auto 16px; }
    .pf-bridge .pf-title .accent { color: var(--yellow); }
    .pf-bridge__text { max-width: 800px; margin: 0 auto; color: rgba(255,255,255,.9); font-size: 1.02rem; line-height: 1.85; }

    /* alur: Karya & layanan TEFA -> Platform katalog -> Pasar lebih luas */
    .pf-flow { display: grid; grid-template-columns: 1fr 110px 1.15fr 110px 1fr; align-items: center; max-width: 960px; margin: 52px auto 0; }
    .pf-fnode {
        position: relative; padding: 22px 14px; border-radius: 22px; background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.35);
        backdrop-filter: blur(10px); box-shadow: 0 20px 34px -18px rgba(0,0,0,.5);
    }
    .pf-fnode__ico { display: block; width: 40px; height: 40px; margin: 0 auto 10px; color: var(--yellow); animation: bob 4s ease-in-out infinite alternate; }
    .pf-fnode__ico svg { width: 100%; height: 100%; display: block; }
    .pf-fnode b { display: block; font-size: 14px; font-weight: 800; letter-spacing: .4px; }
    .pf-fnode > span:last-child { display: block; margin-top: 3px; font-size: 11.5px; color: rgba(255,255,255,.78); font-weight: 600; }
    .pf-fnode--core { padding: 28px 16px; background: #fff; color: var(--blue-deep); border-color: #fff; box-shadow: 0 26px 44px -18px rgba(0,0,0,.55); }
    .pf-fnode--core .pf-fnode__ico { color: var(--blue); }
    .pf-fnode--core > span:last-child { color: var(--muted); }
    .pf-fnode--core::after { content: ''; position: absolute; inset: -7px; border-radius: 28px; border: 2px solid rgba(242,182,48,.7); animation: pulseRing 2.6s ease-out infinite; }
    @keyframes pulseRing { 0% { transform: scale(.94); opacity: .9; } 100% { transform: scale(1.14); opacity: 0; } }

    /* jalur penghubung dengan paket yang bergerak */
    .pf-link { position: relative; height: 4px; margin: 0 6px; border-radius: 4px; background: rgba(255,255,255,.22); }
    .pf-link::after {
        content: ''; position: absolute; right: -2px; top: 50%; width: 10px; height: 10px; border-top: 3px solid var(--yellow); border-right: 3px solid var(--yellow);
        transform: translateY(-50%) rotate(45deg);
    }
    .pf-link i {
        position: absolute; top: 50%; left: 0; width: 12px; height: 12px; margin-top: -6px; border-radius: 3px; background: var(--yellow);
        box-shadow: 0 0 12px rgba(242,182,48,.9); opacity: 0; animation: packet 2.4s ease-in-out infinite;
    }
    .pf-link i:nth-child(2) { animation-delay: .8s; background: #fff; box-shadow: 0 0 12px rgba(255,255,255,.9); }
    .pf-link i:nth-child(3) { animation-delay: 1.6s; }
    .pf-flow .pf-link:last-of-type i { animation-delay: .4s; }
    @keyframes packet { 0% { left: 0; opacity: 0; transform: scale(.6); } 15% { opacity: 1; transform: scale(1); } 85% { opacity: 1; } 100% { left: calc(100% - 12px); opacity: 0; transform: scale(.6); } }
    @keyframes packetY { 0% { top: 0; opacity: 0; transform: scale(.6); } 15% { opacity: 1; transform: scale(1); } 85% { opacity: 1; } 100% { top: calc(100% - 12px); opacity: 0; transform: scale(.6); } }

    /* jalur umpan balik: pengalaman proyek riil mengasah kompetensi siswa */
    .pf-loop { position: relative; max-width: 780px; margin: 30px auto 0; padding: 14px 0 0; }
    .pf-loop__line { position: relative; height: 22px; border: 2px dashed rgba(255,255,255,.4); border-top: 0; border-radius: 0 0 22px 22px; }
    .pf-loop__line::before {
        content: ''; position: absolute; left: -2px; top: -2px; width: 3px; height: 14px; background: var(--yellow);
        border-radius: 3px; box-shadow: 0 0 10px rgba(242,182,48,.9);
    }
    .pf-loop__dot {
        position: absolute; bottom: -7px; right: 0; width: 12px; height: 12px; border-radius: 50%; background: var(--yellow); box-shadow: 0 0 12px rgba(242,182,48,.9);
        animation: loopRun 4.5s ease-in-out infinite;
    }
    @keyframes loopRun { 0% { right: 0; opacity: 0; } 10% { opacity: 1; } 90% { opacity: 1; } 100% { right: calc(100% - 12px); opacity: 0; } }
    .pf-loop__label {
        display: inline-flex; align-items: center; gap: 10px; margin-top: 14px; padding: 9px 20px; font-size: 13px; font-weight: 700; letter-spacing: .3px;
        background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.35); border-radius: 999px; backdrop-filter: blur(8px);
    }

    .pf-bridge__chips { display: flex; justify-content: center; flex-wrap: wrap; gap: 14px; list-style: none; margin: 34px 0 0; padding: 0; }
    .pf-bridge__chips li {
        display: inline-flex; align-items: center; gap: 10px; padding: 12px 22px; font-size: 14px; font-weight: 800; letter-spacing: .5px;
        background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.35); border-radius: 999px; backdrop-filter: blur(8px);
        transition: transform .3s ease, background .3s ease, color .3s ease;
    }
    .pf-bridge__chips li:hover { transform: translateY(-4px); background: #fff; color: var(--blue); }

    /* ================= CTA ================= */
    .pf-cta-wrap { padding: 90px 0 100px; }
    .pf-cta {
        position: relative; overflow: hidden; isolation: isolate; padding: 64px 32px; text-align: center; color: #fff; border-radius: 32px;
        background: linear-gradient(135deg, #6c9ddd 0%, #1c6bd2 45%, #0b60cf 100%); box-shadow: 0 34px 60px -28px rgba(11,96,207,.8);
    }
    .pf-cta .pf-orb { z-index: -1; }
    .pf-cta__grid { position: absolute; inset: 0; z-index: -1; opacity: .25; background-image: linear-gradient(rgba(255,255,255,.2) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.2) 1px, transparent 1px); background-size: 40px 40px; -webkit-mask-image: radial-gradient(ellipse at center, #000, transparent 75%); mask-image: radial-gradient(ellipse at center, #000, transparent 75%); }
    .pf-cta h2 { margin: 10px 0 12px; font-size: clamp(1.6rem, 3.4vw, 2.3rem); font-weight: 800; letter-spacing: -.5px; line-height: 1.15; }
    .pf-cta p { max-width: 560px; margin: 0 auto 28px; color: rgba(255,255,255,.88); font-size: 15px; line-height: 1.7; }
    .pf-cta .pf-pill { opacity: 1; transform: none; animation: none; }
    .pf-cta__actions { display: flex; justify-content: center; flex-wrap: wrap; gap: 12px; }

    :focus-visible { outline: 3px solid #f2b630; outline-offset: 2px; }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 992px) {
        .pf-hero__inner { grid-template-columns: 1fr; }
        .pf-hero__visual { display: none; }
        .pf-about { grid-template-columns: 1fr; gap: 48px; }
    }
    @media (max-width: 760px) {
        .pf-bridge { padding: 52px 22px 44px; border-radius: 28px; }
        .pf-flow { grid-template-columns: 1fr; margin-top: 38px; }
        .pf-link { width: 4px; height: 56px; margin: 8px auto; }
        .pf-link::after { right: 50%; top: auto; bottom: -2px; transform: translateX(50%) rotate(135deg); }
        .pf-link i { left: 50%; top: 0; margin: 0 0 0 -6px; animation-name: packetY; }
        .pf-loop { display: none; }
    }
    @media (max-width: 640px) {
        .pf-hero { min-height: 500px; }
        .pf-hero__inner { padding-top: 60px; padding-bottom: 60px; }
        .pf-about__frame { height: 300px; }
        .pf-about__float { right: 6px; }
        .pf-cta { padding: 48px 22px; }
        .js [data-reveal="left"]  { transform: translateX(-36px); }
        .js [data-reveal="right"] { transform: translateX(36px); }
    }
</style>

<div class="scroll-progress" id="scrollProgress" aria-hidden="true"></div>

<main class="pf bg-surface font-body">

    {{-- ================= HERO ================= --}}
    <section class="pf-hero" id="hero" aria-label="Profil Teaching Factory"
             style="--hero-img: url('{{ asset('asset/img/waka.webp') }}');">
        <div class="pf-hero__grid" aria-hidden="true"></div>
        <div class="pf-hero__sheen" aria-hidden="true"></div>
        <div class="pf-orb pf-orb--3" data-parallax-y="0.18" data-mouse="14" aria-hidden="true"><i></i></div>
        <div class="pf-orb pf-orb--1" data-parallax-y="0.32" data-mouse="-22" aria-hidden="true"><i></i></div>
        <div class="pf-orb pf-orb--2" data-parallax-y="0.12" data-mouse="18" aria-hidden="true"><i></i></div>

        <div class="pf-shell">
            <div class="pf-hero__inner">
                <div class="pf-hero__content" id="heroContent">
                    <div class="pf-pill">Profil Teaching Factory</div>
                    <h1 aria-label="Teaching Factory SMKN 4 Tanjungpinang">
                        <span class="pf-hero__line" style="--i:0" aria-hidden="true"><span>TEACHING <em>FACTORY</em></span></span>
                        <span class="pf-hero__line" style="--i:1" aria-hidden="true"><span>SMKN 4</span></span>
                        <span class="pf-hero__line" style="--i:2" aria-hidden="true"><span>TANJUNGPINANG</span></span>
                    </h1>
                    <p class="pf-hero__lead">
                        Model pembelajaran berbasis produksi dan jasa nyata yang memadukan kurikulum kejuruan dengan standar operasional industri modern di 6 unit keahlian unggulan.
                    </p>
                    <div class="pf-hero__actions">
                        <a href="#jembatan" class="pf-btn pf-btn--primary">Kenali TEFA Lebih Dekat</a>
                        <a href="{{ route('produk') }}" class="pf-btn pf-btn--outline">Katalog Produk TEFA</a>
                    </div>
                </div>

                <div class="pf-hero__visual" aria-hidden="true">
                    <div class="pf-glass" data-mouse="-10">
                        <div>
                            <div class="pf-glass__ico">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" stroke-linecap="round"><path d="M3 21V10l6 4v-4l6 4V5h3v16z"/><path d="M7 21v-3m4 3v-3m4 3v-3"/></svg>
                            </div>
                            <b>Belajar dari Produksi Nyata</b>
                            <span>Standar industri · Project riil</span>
                        </div>
                    </div>
                    <div class="pf-chip pf-chip--a" data-mouse="26"><i class="pf-dot"></i>RPL</div>
                    <div class="pf-chip pf-chip--b" data-mouse="-30"><i class="pf-dot"></i>TKJ</div>
                    <div class="pf-chip pf-chip--c" data-mouse="34"><i class="pf-dot"></i>DKV</div>
                    <div class="pf-chip pf-chip--d" data-mouse="-24"><i class="pf-dot"></i>ANIMASI</div>
                </div>
            </div>
        </div>

        <div class="pf-hero__bar" aria-hidden="true"></div>
    </section>

    {{-- ================= TENTANG ================= --}}
    <section class="pf-section" data-scroll-reveal>
        <div class="pf-shell">
            <div class="pf-about">
                <div class="pf-about__media" data-reveal="mask" style="--d:100ms">
                    <div class="pf-about__frame">
                        <img data-parallax-img="0.08"
                             src="{{ asset('asset/img/foto-sekolahmu.jpg') }}"
                             alt="Teaching Factory SMKN 4 Tanjungpinang"
                             onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=800&auto=format&fit=crop';">
                    </div>
                    <div class="pf-about__badge"><i class="pf-dot"></i>Unit TEFA — SMKN 4 Tanjungpinang</div>
                    <div class="pf-about__float"><b>6</b><span>UNIT KEAHLIAN</span></div>
                </div>

                <div>
                    <span class="pf-tag" data-reveal="left" style="--d:0ms">Tentang Teaching Factory</span>
                    <h2 class="pf-title" data-split>Pusat <span class="accent">Inovasi</span> &amp; Produksi Vokasi</h2>
                    <p class="pf-lead" data-reveal="left" style="margin-bottom:14px;--d:200ms">
                        <strong>Teaching Factory (TEFA) SMKN 4 Tanjungpinang</strong> adalah sarana pembelajaran berbasis produksi barang dan penyediaan jasa nyata yang dirancang sesuai alur kerja industri profesional.
                    </p>
                    <p class="body" data-reveal="left" style="--d:300ms">
                        Melalui ekosistem TEFA, siswa tidak hanya belajar teori di kelas, melainkan langsung menangani project riil dari masyarakat, UMKM, instansi pemerintah, dan pelaku usaha. Setiap project dikerjakan dengan bimbingan instruktur ahli untuk memastikan kualitas terbaik berstandar industri.
                    </p>
                    <div class="pf-about__actions" data-reveal style="--d:400ms">
                        <a href="{{ route('jasa') }}" class="pf-btn pf-btn--blue">Lihat Layanan Jasa</a>
                        <a href="{{ route('produk') }}" class="pf-btn pf-btn--ghost">Katalog Produk TEFA</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= MARQUEE ================= --}}
    <div class="pf-marquee" aria-hidden="true">
        <div class="pf-marquee__track">
            @for ($i = 0; $i < 2; $i++)
                <div class="pf-marquee__item">
                    <span>RPL</span><i class="pf-dot"></i><span>TKJ</span><i class="pf-dot"></i><span>DKV</span><i class="pf-dot"></i><span>PSPT</span><i class="pf-dot"></i><span>Animasi</span><i class="pf-dot"></i><span>Gim</span><i class="pf-dot"></i>
                    <span>Kreatif</span><i class="pf-dot"></i><span>Kompeten</span><i class="pf-dot"></i><span>Kolaboratif</span><i class="pf-dot"></i><span>Siap Industri</span><i class="pf-dot"></i>
                </div>
            @endfor
        </div>
    </div>

    {{-- ================= JEMBATAN MELALUI TEKNOLOGI ================= --}}
    <section class="pf-bridge-wrap" id="jembatan" style="scroll-margin-top:90px" data-scroll-reveal>
        <div class="pf-shell">
            <div class="pf-bridge" data-reveal="zoom" style="--d:0ms">
                <div class="pf-bridge__grid" aria-hidden="true"></div>
                <div class="pf-orb pf-orb--1" aria-hidden="true"><i></i></div>
                <div class="pf-orb pf-orb--3" aria-hidden="true" style="top:auto;bottom:-35%;left:-6%"><i></i></div>

                <span class="pf-tag" data-reveal style="--d:100ms">Tentang Platform Ini</span>
                <h2 class="pf-title" data-split>Menjadi <span class="accent">Jembatan</span> Melalui Teknologi</h2>
                <p class="pf-bridge__text" data-reveal="blur" style="--d:350ms">
                    Platform katalog digital ini hadir sebagai jembatan teknologi yang menghubungkan karya dan layanan Teaching Factory (TEFA) setiap jurusan di SMK dengan pasar yang lebih luas. Dengan menyalurkan keahlian teknis siswa melalui pengerjaan proyek riil, platform ini tidak hanya menjadi wadah promosi dan penjualan hasil karya TEFA, tetapi juga sarana untuk mengasah kompetensi siswa agar siap bersaing dan berkontribusi secara nyata
                </p>

                <div class="pf-flow" aria-hidden="true" data-reveal="zoom" style="--d:500ms">
                    <div class="pf-fnode">
                        <span class="pf-fnode__ico">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" stroke-linecap="round"><path d="M3 21V10l6 4v-4l6 4V5h3v16z"/><path d="M7 21v-3m4 3v-3m4 3v-3"/></svg>
                        </span>
                        <b>Karya &amp; Layanan TEFA</b>
                        <span>Setiap jurusan SMK</span>
                    </div>

                    <div class="pf-link"><i></i><i></i><i></i></div>

                    <div class="pf-fnode pf-fnode--core">
                        <span class="pf-fnode__ico">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" stroke-linecap="round"><rect x="3" y="3" width="7.5" height="7.5" rx="1.5"/><rect x="13.5" y="3" width="7.5" height="7.5" rx="1.5"/><rect x="3" y="13.5" width="7.5" height="7.5" rx="1.5"/><rect x="13.5" y="13.5" width="7.5" height="7.5" rx="1.5"/></svg>
                        </span>
                        <b>Platform Katalog Digital</b>
                        <span>Promosi &amp; penjualan hasil karya</span>
                    </div>

                    <div class="pf-link"><i></i><i></i><i></i></div>

                    <div class="pf-fnode">
                        <span class="pf-fnode__ico">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" stroke-linecap="round"><circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3c2.8 3 2.8 15 0 18M12 3c-2.8 3-2.8 15 0 18"/></svg>
                        </span>
                        <b>Pasar yang Lebih Luas</b>
                        <span>Masyarakat, UMKM &amp; industri</span>
                    </div>
                </div>

                <div class="pf-loop" aria-hidden="true">
                    <div class="pf-loop__line"><span class="pf-loop__dot"></span></div>
                    <span class="pf-loop__label"><i class="pf-dot"></i>Proyek riil mengasah kompetensi siswa</span>
                </div>

                <ul class="pf-bridge__chips" data-stagger-group>
                    <li><i class="pf-dot"></i>Wadah Promosi</li>
                    <li><i class="pf-dot"></i>Penjualan Hasil Karya</li>
                    <li><i class="pf-dot"></i>Asah Kompetensi Siswa</li>
                </ul>
            </div>
        </div>
    </section>

    {{-- ================= CTA ================= --}}
    <section class="pf-cta-wrap" data-scroll-reveal>
        <div class="pf-shell">
            <div class="pf-cta" data-reveal="zoom" style="--d:0ms">
                <div class="pf-cta__grid" aria-hidden="true"></div>
                <div class="pf-orb pf-orb--1" aria-hidden="true"><i></i></div>
                <div class="pf-orb pf-orb--3" aria-hidden="true" style="top:auto;bottom:-30%;left:-4%"><i></i></div>
                <span class="pf-pill">Kemitraan &amp; Konsultasi</span>
                <h2>Siap Bekerjasama dengan Unit TEFA Kami?</h2>
                <p>Dapatkan hasil pengerjaan berkualitas standar industri dengan harga yang kompetitif untuk kebutuhan bisnis, instansi, atau UMKM Anda.</p>
                <div class="pf-cta__actions">
                    <a href="{{ route('jasa') }}" class="pf-btn pf-btn--primary">Lihat Layanan Jasa</a>
                    <a href="{{ route('produk') }}" class="pf-btn pf-btn--outline">Jelajahi Produk</a>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    (function () {
        document.documentElement.classList.add('js');

        var canHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

        function splitWords(root) {
            var label = root.textContent.replace(/\s+/g, ' ').trim();
            var counter = 0;

            function walk(node) {
                Array.prototype.slice.call(node.childNodes).forEach(function (child) {
                    if (child.nodeType === 3) {
                        var frag = document.createDocumentFragment();
                        child.textContent.split(/(\s+)/).forEach(function (part) {
                            if (!part) { return; }
                            if (/^\s+$/.test(part)) { frag.appendChild(document.createTextNode(' ')); return; }
                            var outer = document.createElement('span');
                            var inner = document.createElement('span');
                            outer.className = 'split-word';
                            outer.style.setProperty('--w', counter++);
                            inner.textContent = part;
                            outer.appendChild(inner);
                            frag.appendChild(outer);
                        });
                        node.replaceChild(frag, child);
                    } else if (child.nodeType === 1) {
                        walk(child);
                    }
                });
            }

            walk(root);
            root.setAttribute('aria-label', label);
            Array.prototype.forEach.call(root.children, function (c) { c.setAttribute('aria-hidden', 'true'); });
        }

        function markStagger(section) {
            Array.prototype.forEach.call(section.querySelectorAll('[data-stagger-group]'), function (group) {
                Array.prototype.forEach.call(group.children, function (child, i) {
                    child.setAttribute('data-stagger-item', '');
                    child.style.setProperty('--stagger-index', Math.min(i + 4, 8));
                });
            });
        }

        function reveal(section) {
            section.classList.add('is-visible');
            Array.prototype.forEach.call(section.querySelectorAll('[data-stagger-item]'), function (item) {
                item.classList.add('is-visible');
            });
        }

        /* Parallax saat scroll + progress bar */
        function setupScrollEffects() {
            var bar = document.getElementById('scrollProgress');
            var hero = document.getElementById('hero');
            var heroContent = document.getElementById('heroContent');
            var layers = document.querySelectorAll('[data-parallax-y]');
            var imgs = document.querySelectorAll('[data-parallax-img]');
            var ticking = false;

            function update() {
                ticking = false;
                var y = window.pageYOffset;
                var vh = window.innerHeight;
                var max = document.documentElement.scrollHeight - vh;

                if (bar) { bar.style.transform = 'scaleX(' + (max > 0 ? Math.min(y / max, 1) : 0) + ')'; }

                if (hero && y < hero.offsetHeight + 100) {
                    Array.prototype.forEach.call(layers, function (el) {
                        el.style.translate = '0 ' + (y * parseFloat(el.getAttribute('data-parallax-y'))).toFixed(1) + 'px';
                    });
                    if (heroContent) {
                        heroContent.style.transform = 'translate3d(0,' + (y * 0.22).toFixed(1) + 'px,0)';
                        heroContent.style.opacity = Math.max(0, 1 - y / (hero.offsetHeight * 0.75));
                    }
                }

                Array.prototype.forEach.call(imgs, function (img) {
                    var wrap = img.parentNode.getBoundingClientRect();
                    if (wrap.bottom < 0 || wrap.top > vh) { return; }
                    var offset = (wrap.top + wrap.height / 2 - vh / 2) * parseFloat(img.getAttribute('data-parallax-img'));
                    img.style.transform = 'translate3d(0,' + offset.toFixed(1) + 'px,0) scale(1.18)';
                });
            }

            function onScroll() {
                if (!ticking) { ticking = true; window.requestAnimationFrame(update); }
            }

            window.addEventListener('scroll', onScroll, { passive: true });
            window.addEventListener('resize', onScroll);
            update();
        }

        /* Parallax mengikuti mouse di banner (orb, kartu, chip) */
        function setupMouseParallax() {
            var hero = document.getElementById('hero');
            if (!hero || !canHover) { return; }
            var items = hero.querySelectorAll('[data-mouse]');
            var raf = null, nx = 0, ny = 0;

            function apply() {
                raf = null;
                Array.prototype.forEach.call(items, function (el) {
                    var k = parseFloat(el.getAttribute('data-mouse'));
                    el.style.marginLeft = (nx * k).toFixed(1) + 'px';
                    el.style.marginTop = (ny * k).toFixed(1) + 'px';
                });
            }

            hero.addEventListener('pointermove', function (e) {
                var r = hero.getBoundingClientRect();
                nx = (e.clientX - r.left) / r.width - 0.5;
                ny = (e.clientY - r.top) / r.height - 0.5;
                if (!raf) { raf = window.requestAnimationFrame(apply); }
            });
            hero.addEventListener('pointerleave', function () {
                nx = 0; ny = 0;
                if (!raf) { raf = window.requestAnimationFrame(apply); }
            });
            Array.prototype.forEach.call(items, function (el) { el.style.transition = 'margin .6s cubic-bezier(.16,1,.3,1)'; });
        }

        function init() {
            var sections = document.querySelectorAll('[data-scroll-reveal]');

            Array.prototype.forEach.call(document.querySelectorAll('[data-split]'), splitWords);
            Array.prototype.forEach.call(sections, markStagger);

            var accents = document.querySelectorAll('[data-split] .accent');
            Array.prototype.forEach.call(accents, function (a) { setTimeout(function () { a.classList.add('shine-text'); }, 1600); });

            setupScrollEffects();
            setupMouseParallax();

            if (!('IntersectionObserver' in window)) {
                Array.prototype.forEach.call(sections, reveal);
                return;
            }

            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) { reveal(entry.target); io.unobserve(entry.target); }
                });
            }, { threshold: 0.2, rootMargin: '0px 0px -8% 0px' });

            Array.prototype.forEach.call(sections, function (s) { io.observe(s); });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    })();
</script>
@endsection