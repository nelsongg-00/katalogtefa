{{-- Latar partikel jaringan untuk halaman auth (login & register).
     CSS + canvas + script ikut di partial agar halaman pemanggil tidak
     memuat @vite. Animasi selalu berjalan. --}}
<style>
  .particle-bg {
    position: fixed;
    inset: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    pointer-events: none;
    display: block;
  }
</style>

<canvas class="particle-bg" id="particle-bg" aria-hidden="true"></canvas>

<script>
  (function () {
    var canvas = document.getElementById('particle-bg');
    if (!canvas || !canvas.getContext) return;
    var ctx = canvas.getContext('2d');

    var CFG = {
      density: 9500,        // makin kecil = makin banyak partikel
      maxCount: 150,
      minCount: 35,
      speed: 0.35,          // kecepatan dasar partikel
      linkDist: 125,        // jarak maks antar partikel untuk dihubungkan
      mouseDist: 170,       // radius pengaruh kursor
      repel: 0.9,           // kekuatan dorongan kursor
      dotColor: '255,255,255',
      lineColor: '255,255,255',
      dotAlpha: 0.7,
      lineAlpha: 0.28,
      mouseLineAlpha: 0.55
    };

    var w = 0, h = 0, dpr = 1, parts = [];
    var mouse = { x: null, y: null };

    function rand(a, b) { return a + Math.random() * (b - a); }

    function make() {
      var a = rand(0, Math.PI * 2), s = rand(0.35, 1) * CFG.speed;
      return { x: rand(0, w), y: rand(0, h), vx: Math.cos(a) * s, vy: Math.sin(a) * s, r: rand(1.1, 2.4) };
    }

    function resize() {
      dpr = Math.min(window.devicePixelRatio || 1, 2);
      w = window.innerWidth;
      h = window.innerHeight;
      canvas.width = Math.round(w * dpr);
      canvas.height = Math.round(h * dpr);
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

      var target = Math.max(CFG.minCount, Math.min(CFG.maxCount, Math.round(w * h / CFG.density)));
      while (parts.length < target) parts.push(make());
      if (parts.length > target) parts.length = target;
    }

    function step() {
      ctx.clearRect(0, 0, w, h);
      var i, j, p, q, dx, dy, d, f;

      for (i = 0; i < parts.length; i++) {
        p = parts[i];

        // dorongan dari kursor
        if (mouse.x !== null) {
          dx = p.x - mouse.x; dy = p.y - mouse.y;
          d = Math.sqrt(dx * dx + dy * dy);
          if (d < CFG.mouseDist && d > 0.01) {
            f = (1 - d / CFG.mouseDist) * CFG.repel;
            p.x += (dx / d) * f * 2.2;
            p.y += (dy / d) * f * 2.2;
          }
        }

        p.x += p.vx;
        p.y += p.vy;

        // pantul di tepi layar
        if (p.x < 0)      { p.x = 0; p.vx = Math.abs(p.vx); }
        else if (p.x > w) { p.x = w; p.vx = -Math.abs(p.vx); }
        if (p.y < 0)      { p.y = 0; p.vy = Math.abs(p.vy); }
        else if (p.y > h) { p.y = h; p.vy = -Math.abs(p.vy); }

        // titik
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
        ctx.fillStyle = 'rgba(' + CFG.dotColor + ',' + CFG.dotAlpha + ')';
        ctx.fill();
      }

      // garis antar partikel
      ctx.lineWidth = 1;
      for (i = 0; i < parts.length; i++) {
        p = parts[i];
        for (j = i + 1; j < parts.length; j++) {
          q = parts[j];
          dx = p.x - q.x; dy = p.y - q.y;
          d = dx * dx + dy * dy;
          if (d < CFG.linkDist * CFG.linkDist) {
            ctx.strokeStyle = 'rgba(' + CFG.lineColor + ',' + (CFG.lineAlpha * (1 - Math.sqrt(d) / CFG.linkDist)) + ')';
            ctx.beginPath();
            ctx.moveTo(p.x, p.y);
            ctx.lineTo(q.x, q.y);
            ctx.stroke();
          }
        }

        // garis ke kursor
        if (mouse.x !== null) {
          dx = p.x - mouse.x; dy = p.y - mouse.y;
          d = Math.sqrt(dx * dx + dy * dy);
          if (d < CFG.mouseDist * 1.25) {
            ctx.strokeStyle = 'rgba(' + CFG.lineColor + ',' + (CFG.mouseLineAlpha * (1 - d / (CFG.mouseDist * 1.25))) + ')';
            ctx.beginPath();
            ctx.moveTo(p.x, p.y);
            ctx.lineTo(mouse.x, mouse.y);
            ctx.stroke();
          }
        }
      }

      requestAnimationFrame(step);
    }

    function setPos(x, y) { mouse.x = x; mouse.y = y; }
    function clearPos() { mouse.x = null; mouse.y = null; }

    // Dibaca dari window karena canvas pointer-events: none
    window.addEventListener('mousemove', function (e) { setPos(e.clientX, e.clientY); }, { passive: true });
    window.addEventListener('touchstart', function (e) { var t = e.touches[0]; if (t) setPos(t.clientX, t.clientY); }, { passive: true });
    window.addEventListener('touchmove',  function (e) { var t = e.touches[0]; if (t) setPos(t.clientX, t.clientY); }, { passive: true });
    window.addEventListener('touchend', clearPos, { passive: true });
    document.addEventListener('mouseleave', clearPos);
    window.addEventListener('blur', clearPos);

    var rt;
    window.addEventListener('resize', function () { clearTimeout(rt); rt = setTimeout(resize, 120); });

    resize();
    requestAnimationFrame(step);
  })();
</script>
