<div class="card">
  <div class="card-head">
    <div>
      <h3>Tren Aktivitas Pesanan Jurusan</h3>
      <p style="margin: 3px 0 0; font-size: 12px; color: var(--muted);">Perbandingan pesanan masuk dan pesanan selesai sepanjang tahun berjalan.</p>
    </div>
    <span style="background: #eef2fd; color: var(--blue); padding: 4px 10px; border-radius: 12px; font-size: 11.5px; font-weight: 700;">
      Tahun {{ date('Y') }}
    </span>
  </div>
  <div class="card-body">
    <div style="position: relative; height: 260px; width: 100%;">
      <canvas id="adminOrderChart"></canvas>
    </div>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const chartData = @json($dataChart);
    const ctx = document.getElementById('adminOrderChart');

    if (ctx && chartData) {
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: chartData.labels,
          datasets: [
            {
              label: 'Pesanan Masuk',
              data: chartData.pesananMasuk,
              backgroundColor: '#2f6fdb',
              borderRadius: 6,
              barPercentage: 0.6,
              categoryPercentage: 0.7
            },
            {
              label: 'Pesanan Selesai',
              data: chartData.pesananSelesai,
              backgroundColor: '#1fa971',
              borderRadius: 6,
              barPercentage: 0.6,
              categoryPercentage: 0.7
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'top',
              align: 'end',
              labels: {
                boxWidth: 12,
                boxHeight: 12,
                usePointStyle: true,
                pointStyle: 'circle',
                font: {
                  family: "'Plus Jakarta Sans', sans-serif",
                  size: 12,
                  weight: '600'
                }
              }
            },
            tooltip: {
              backgroundColor: '#16234a',
              titleFont: { family: "'Plus Jakarta Sans', sans-serif", size: 12, weight: '700' },
              bodyFont: { family: "'Plus Jakarta Sans', sans-serif", size: 12 },
              padding: 10,
              cornerRadius: 8
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                stepSize: 1,
                font: { family: "'Plus Jakarta Sans', sans-serif", size: 11, color: '#7a839c' }
              },
              grid: {
                color: '#eef2fd'
              }
            },
            x: {
              ticks: {
                font: { family: "'Plus Jakarta Sans', sans-serif", size: 11, weight: '600', color: '#7a839c' }
              },
              grid: {
                display: false
              }
            }
          }
        }
      });
    }
  });
</script>
@endpush
