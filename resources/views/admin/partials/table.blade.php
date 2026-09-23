<style>
  .tabs {
    display: flex;
    align-items: center;
    gap: 18px;
    padding: 14px 22px;
    border-bottom: 1px solid var(--border);
    flex-wrap: wrap;
    background: #fafbfd;
  }

  .tab {
    font-size: 13px;
    font-weight: 700;
    color: var(--muted);
    padding-bottom: 6px;
    border-bottom: 2px solid transparent;
    cursor: pointer;
    transition: color .15s;
  }
  .tab.active { color: var(--blue); border-bottom-color: var(--blue); }
  .tab:hover { color: var(--text); }

  .search-box {
    margin-left: auto;
    display: flex;
    align-items: center;
    gap: 8px;
    background: #ffffff;
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 7px 14px;
    font-size: 12.5px;
    color: var(--muted);
    min-width: 240px;
    transition: border-color .15s, box-shadow .15s;
  }
  .search-box:focus-within {
    border-color: var(--blue);
    box-shadow: 0 0 0 3px rgba(47, 111, 219, 0.1);
  }
  .search-box input {
    border: none;
    background: transparent;
    outline: none;
    font-size: 12.5px;
    width: 100%;
    font-family: inherit;
    color: var(--text);
  }

  .table-responsive {
    width: 100%;
    overflow-x: auto;
  }

  table.order-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
  }

  table.order-table thead th {
    text-align: left;
    color: var(--muted);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    padding: 13px 22px;
    border-bottom: 1px solid var(--border);
    background: #fafbfd;
  }

  table.order-table tbody td {
    padding: 16px 22px;
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
  }

  table.order-table tbody tr { transition: background .12s; }
  table.order-table tbody tr:hover { background: #f8f9fc; }
  table.order-table tbody tr:last-child td { border-bottom: none; }

  .cust { display: flex; align-items: center; gap: 10px; }

  .cust-av {
    width: 32px; height: 32px;
    border-radius: 50%;
    background: #eef2fd;
    color: var(--blue);
    font-weight: 800;
    font-size: 11px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }

  .order-id { color: var(--blue); font-weight: 700; }
  .order-date {
    color: var(--muted);
    font-size: 11.5px;
    display: block;
    margin-top: 2px;
  }

  .badge {
    font-size: 11.5px;
    font-weight: 700;
    padding: 4px 11px;
    border-radius: 20px;
    display: inline-block;
    white-space: nowrap;
  }
  .badge-wait { background: #fef3d6; color: #9a6b0c; }
  .badge-review { background: #e8f0ff; color: #1866c2; }
  .badge-progress { background: #f1e8fd; color: #7237c9; }
  .badge-done { background: #e4f7ee; color: #167a50; }
  .badge-cancel { background: #fde3e4; color: #b3282d; }

  .btn-action-validate {
    background: var(--green);
    color: #fff;
    border: none;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    transition: opacity .15s;
  }
  .btn-action-validate:hover { opacity: .9; }

  .btn-action-assign {
    background: var(--blue);
    color: #fff;
    border: none;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
    transition: opacity .15s;
  }
  .btn-action-assign:hover { opacity: .9; }

  .select-worker {
    padding: 6px 10px;
    font-size: 12px;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: #fff;
    color: var(--text);
    outline: none;
    max-width: 150px;
  }
</style>

<div class="card" id="orders-section">
  <div class="card-head">
    <div>
      <h3>Daftar Pesanan Masuk</h3>
      <p style="margin: 3px 0 0; font-size: 12px; color: var(--muted);">Daftar pesanan yang membutuhkan validasi atau penugasan worker.</p>
    </div>
  </div>

  <div class="tabs">
    <span class="tab active" data-filter="all">Semua ({{ $totalPesanan }})</span>
    <span class="tab" data-filter="Pending">Menunggu Validasi ({{ $pesananPending }})</span>
    <span class="tab" data-filter="proses">Dalam Proses ({{ $pesananProses }})</span>
    <span class="tab" data-filter="Completed">Selesai ({{ $pesananSelesai }})</span>

    <div class="search-box">
      <span>🔍</span>
      <input type="text" id="orderSearch" placeholder="Cari pelanggan, produk, #ID...">
    </div>
  </div>

  <div class="table-responsive">
    <table class="order-table" id="orderTable">
      <thead>
        <tr>
          <th>Pesanan</th>
          <th>Pelanggan</th>
          <th>Rincian Produk & Jasa</th>
          <th>Total Harga</th>
          <th>Status</th>
          <th>Aksi Operasional</th>
        </tr>
      </thead>
      <tbody>
        @forelse($pesanans as $pesanan)
          @php
            $statusRaw = strtolower($pesanan->status_pesanan);
            $groupFilter = 'all';
            if (in_array($statusRaw, ['pending', 'menunggu', 'menunggu konfirmasi', 'menunggu_konfirmasi'])) {
                $groupFilter = 'Pending';
            } elseif (in_array($statusRaw, ['validated', 'in progress', 'sedang_dikemas', 'bisa_diambil'])) {
                $groupFilter = 'proses';
            } elseif (in_array($statusRaw, ['completed', 'selesai'])) {
                $groupFilter = 'Completed';
            }
          @endphp
          <tr class="order-row" data-status="{{ $groupFilter }}">
            <!-- 1. Order ID & Tanggal -->
            <td>
              <span class="order-id">#{{ $pesanan->id }}</span>
              <span class="order-date">{{ $pesanan->created_at ? $pesanan->created_at->format('d M Y, H:i') : '-' }}</span>
            </td>

            <!-- 2. Pelanggan -->
            <td>
              <div class="cust">
                <div class="cust-av">
                  {{ strtoupper(substr($pesanan->user->name ?? 'G', 0, 2)) }}
                </div>
                <div>
                  <div style="font-weight: 700; color: var(--text);">{{ $pesanan->user->name ?? 'Guest User' }}</div>
                  <div style="font-size: 11px; color: var(--muted);">{{ $pesanan->user->email ?? '-' }}</div>
                </div>
              </div>
            </td>

            <!-- 3. Rincian Produk -->
            <td>
              @forelse($pesanan->detailPesanans as $detail)
                <div style="margin-bottom: 4px; line-height: 1.3;">
                  <strong>{{ $detail->produk->nama_produk ?? 'Produk' }}</strong>
                  <span style="color: var(--muted); font-size: 11.5px;">(x{{ $detail->jumlah }})</span>
                </div>
              @empty
                <span style="color: var(--muted); font-size: 12px;">Tidak ada rincian item</span>
              @endforelse
            </td>

            <!-- 4. Total Harga -->
            <td>
              <strong style="color: var(--gold-dark); font-size: 13.5px;">
                Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
              </strong>
            </td>

            <!-- 5. Status Badge -->
            <td>
              @if(in_array(strtolower($pesanan->status_pesanan), ['pending', 'menunggu', 'menunggu konfirmasi', 'menunggu_konfirmasi']))
                <span class="badge badge-wait">⏳ Menunggu</span>
              @elseif(strtolower($pesanan->status_pesanan) === 'validated')
                <span class="badge badge-review">✓ Tervalidasi</span>
              @elseif(in_array(strtolower($pesanan->status_pesanan), ['in progress', 'sedang_dikemas', 'bisa_diambil']))
                <span class="badge badge-progress">⚙ Diproses</span>
              @elseif(in_array(strtolower($pesanan->status_pesanan), ['completed', 'selesai']))
                <span class="badge badge-done">★ Selesai</span>
              @else
                <span class="badge badge-cancel">{{ $pesanan->status_pesanan }}</span>
              @endif
            </td>

            <!-- 6. Aksi Operasional Admin Jurusan -->
            <td>
              @if(in_array(strtolower($pesanan->status_pesanan), ['pending', 'menunggu', 'menunggu konfirmasi', 'menunggu_konfirmasi']))
                <form method="POST" action="{{ route('admin.validateOrder', $pesanan->id) }}" style="display:inline;">
                  @csrf
                  <button type="submit" class="btn-action-validate">Validasi Pesanan</button>
                </form>
              @elseif($pesanan->status_pesanan === 'Validated')
                <form method="POST" action="{{ route('admin.assignTask', $pesanan->id) }}" style="display:flex; align-items:center; gap: 6px;">
                  @csrf
                  <select name="worker_id" class="select-worker" required>
                    <option value="">Pilih Siswa</option>
                    @foreach($workers as $worker)
                      <option value="{{ $worker->id }}">{{ $worker->name }}</option>
                    @endforeach
                  </select>
                  <button type="submit" class="btn-action-assign">Tugaskan</button>
                </form>
              @elseif($pesanan->status_pesanan === 'In Progress')
                @php
                  $latestPenugasan = $pesanan->penugasans->last();
                @endphp
                <div style="font-size: 12px; color: var(--muted);">
                  Worker: <strong style="color: var(--text);">{{ $latestPenugasan->worker->name ?? 'Belum ditentukan' }}</strong>
                </div>
              @elseif($pesanan->status_pesanan === 'Completed')
                <span style="font-size: 12px; color: var(--green); font-weight: 700;">✓ Pesanan Selesai</span>
              @else
                <span style="font-size: 12px; color: var(--muted);">-</span>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" style="text-align: center; padding: 40px 20px; color: var(--muted);">
              Belum ada pesanan yang masuk untuk jurusan ini.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const tabs = document.querySelectorAll(".tabs .tab");
    const rows = document.querySelectorAll("#orderTable tbody tr.order-row");
    const searchInput = document.getElementById("orderSearch");

    let currentFilter = "all";

    function filterRows() {
      const query = (searchInput.value || "").toLowerCase().trim();

      rows.forEach(function(row) {
        const rowStatus = row.getAttribute("data-status");
        const rowText = row.innerText.toLowerCase();

        const matchFilter = (currentFilter === "all" || rowStatus === currentFilter);
        const matchQuery = (query === "" || rowText.includes(query));

        if (matchFilter && matchQuery) {
          row.style.display = "";
        } else {
          row.style.display = "none";
        }
      });
    }

    tabs.forEach(function(tab) {
      tab.addEventListener("click", function() {
        tabs.forEach(t => t.classList.remove("active"));
        this.classList.add("active");
        currentFilter = this.getAttribute("data-filter");
        filterRows();
      });
    });

    if (searchInput) {
      searchInput.addEventListener("input", filterRows);
    }
  });
</script>
@endpush
