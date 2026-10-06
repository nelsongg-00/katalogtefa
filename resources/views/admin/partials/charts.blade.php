<div class="card">
  <div class="card-head">
    <div>
      <h3>Tren Aktivitas Pesanan Jurusan</h3>
      <p style="margin: 3px 0 0; font-size: 12px; color: var(--muted);">Perbandingan pesanan masuk dan pesanan selesai sepanjang tahun berjalan.</p>
    </div>
    <span style="background: #eef4ff; color: var(--blue); padding: 4px 10px; border-radius: 12px; font-size: 11.5px; font-weight: 700;">
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
              backgroundColor: '#0a4aa6',
              borderRadius: 6,
              barPercentage: 0.6,
              categoryPercentage: 0.7
            },
            {
              label: 'Pesanan Selesai',
              data: chartData.pesananSelesai,
              backgroundColor: '#16a34a',
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
                  family: "'Open Sauce Sans', sans-serif",
                  size: 12,
                  weight: '600'
                }
              }
            },
            tooltip: {
              backgroundColor: '#0a215e',
              titleFont: { family: "'Open Sauce Sans', sans-serif", size: 12, weight: '700' },
              bodyFont: { family: "'Open Sauce Sans', sans-serif", size: 12 },
              padding: 10,
              cornerRadius: 8
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                stepSize: 1,
                font: { family: "'Open Sauce Sans', sans-serif", size: 11, color: '#64748b' }
              },
              grid: {
                color: '#eef4ff'
              }
            },
            x: {
              ticks: {
                font: { family: "'Open Sauce Sans', sans-serif", size: 11, weight: '600', color: '#64748b' }
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
