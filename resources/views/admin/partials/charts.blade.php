<div class="card">
  <div class="card-head">
    <div>
      <h3>Tren Aktivitas Pesanan Jurusan</h3>
      <p style="margin:3px 0 0;font-size:12px;color:var(--muted)">Perbandingan pesanan masuk dan pesanan selesai sepanjang tahun berjalan.</p>
    </div>
    <span class="chip">Tahun {{ date('Y') }}</span>
  </div>
  <div class="card-body">
    <div class="legend">
      <span><i style="background:var(--blue)"></i>Pesanan Masuk</span>
      <span><i style="background:var(--green)"></i>Pesanan Selesai</span>
    </div>
    @php
        $chartData = $dataChart ?? ['labels' => [], 'pesananMasuk' => [], 'pesananSelesai' => []];
        $chartMax = 0;
        for ($i = 0; $i < count($chartData['pesananMasuk'] ?? []); $i++) {
            $chartMax = max($chartMax, $chartData['pesananMasuk'][$i] ?? 0, $chartData['pesananSelesai'][$i] ?? 0);
        }
        $chartTop = max($chartMax, 1);
        $chartTicks = [];
        $step = max(1, intval($chartTop / 4));
        for ($t = $chartTop; $t >= 0; $t -= $step) {
            $chartTicks[] = $t;
        }
        if (!in_array(0, $chartTicks)) $chartTicks[] = 0;
    @endphp
    <div class="chart" role="img" aria-label="Grafik batang pesanan masuk dan selesai per bulan">
      <div class="grid">
        @foreach($chartTicks as $tick)
          <div style="bottom: calc({{ $chartTop > 0 ? ($tick / $chartTop) * 100 : 0 }}%); top: auto">
            <span>{{ $tick }}</span>
          </div>
        @endforeach
      </div>
      <div class="cols">
        @foreach($chartData['labels'] as $idx => $mName)
          @php
            $inCount = $chartData['pesananMasuk'][$idx] ?? 0;
            $doneCount = $chartData['pesananSelesai'][$idx] ?? 0;
            $inHeight = $chartTop > 0 ? ($inCount / $chartTop) * 100 : 0;
            $doneHeight = $chartTop > 0 ? ($doneCount / $chartTop) * 100 : 0;
          @endphp
          <div class="col">
            <div class="bars">
              <div class="bar a" data-h="{{ $inHeight }}" data-v="{{ $inCount }}"></div>
              <div class="bar b" data-h="{{ $doneHeight }}" data-v="{{ $doneCount }}"></div>
            </div>
            <div class="m">{{ $mName }}</div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
