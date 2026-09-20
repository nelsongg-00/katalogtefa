@extends('layouts.admin')

@section('title', 'MANAJEMEN WORKER — Admin ' . ($jurusan->nama_jurusan ?? 'Jurusan'))

@section('content')
  <style>
    .btn-sm-delete {
      background: #fde3e4;
      color: var(--red);
      border: 1px solid #f9bec1;
      padding: 6px 12px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 700;
      cursor: pointer;
      transition: all .15s;
    }
    .btn-sm-delete:hover {
      background: var(--red);
      color: #fff;
    }
    .badge-progress {
      background: #e8f0fe;
      color: var(--blue);
      padding: 3px 10px;
      border-radius: 12px;
      font-size: 11px;
      font-weight: 700;
    }
    .cust {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .cust-av {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 800;
      font-size: 13px;
    }
    table.order-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
    }
    table.order-table th {
      background: #fafbfd;
      padding: 12px 18px;
      text-align: left;
      font-size: 11.5px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: var(--muted);
      border-bottom: 1px solid var(--border);
    }
    table.order-table td {
      padding: 14px 18px;
      border-bottom: 1px solid var(--border);
      vertical-align: middle;
    }
    table.order-table tbody tr:hover {
      background: #f8fafc;
    }
  </style>

  <div class="page-head">
    <div>
      <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
        <h1 class="page-title">MANAJEMEN WORKER</h1>
        <span class="badge-dept">
          ★ Jurusan {{ $jurusan->nama_jurusan ?? 'TEFA' }}
        </span>
      </div>
      <p class="page-sub">Kelola akun siswa/worker yang mengerjakan tugas produksi pada jurusan ini.</p>
    </div>

    <div>
      <button type="button" class="btn-gold" onclick="openAddWorkerModal()">
        <span>+ Tambah Worker Baru</span>
      </button>
    </div>
  </div>

  <div class="card">
    <div class="card-head">
      <div>
        <h3>Daftar Siswa / Worker Terdaftar ({{ $workerAktif }})</h3>
        <p style="margin: 3px 0 0; font-size: 12px; color: var(--muted);">Daftar seluruh siswa pelaksana penugasan dan projek TEFA.</p>
      </div>
    </div>

    <div class="table-responsive">
      <table class="order-table">
        <thead>
          <tr>
            <th>Worker / Siswa</th>
            <th>Email</th>
            <th>Jurusan</th>
            <th>Tugas Berjalan</th>
            <th>Bergabung</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($workers as $w)
            @php
              $activeProjectsCount = $w->projects()->where('status', 'in_progress')->count();
            @endphp
            <tr>
              <td>
                <div class="cust">
                  <div class="cust-av" style="background: #e8f0ff; color: var(--blue);">
                    {{ strtoupper(substr($w->name, 0, 2)) }}
                  </div>
                  <div>
                    <strong style="font-size: 13.5px; color: var(--text);">{{ $w->name }}</strong>
                    <div style="font-size: 11px; color: var(--muted);">Role: {{ ucfirst($w->role) }}</div>
                  </div>
                </div>
              </td>
              <td>
                <span style="color: var(--muted); font-size: 12.5px;">{{ $w->email }}</span>
              </td>
              <td>
                <span style="background: #eef1f7; color: var(--navy-900); font-weight: 700; font-size: 11.5px; padding: 4px 10px; border-radius: 6px;">
                  {{ $w->jurusan->nama_jurusan ?? ($jurusan->nama_jurusan ?? 'Semua Jurusan') }}
                </span>
              </td>
              <td>
                @if($activeProjectsCount > 0)
                  <span class="badge-progress">{{ $activeProjectsCount }} Projek Aktif</span>
                @else
                  <span style="color: var(--muted); font-size: 12px;">Tidak ada projek aktif</span>
                @endif
              </td>
              <td>
                <span style="color: var(--muted); font-size: 12px;">
                  {{ $w->created_at ? $w->created_at->format('d M Y') : '-' }}
                </span>
              </td>
              <td>
                <form method="POST" action="{{ route('admin.workers.destroy', $w->id) }}" onsubmit="return confirm('Hapus worker {{ $w->name }}?');" style="margin: 0;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-sm-delete" title="Hapus Worker">
                    ✕ Hapus
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="text-align: center; padding: 48px 20px; color: var(--muted);">
                <div style="font-size: 32px; margin-bottom: 8px;">👥</div>
                <div style="font-weight: 700; color: var(--text);">Belum ada akun worker terdaftar</div>
                <div style="font-size: 12px; margin-top: 4px;">Klik tombol "+ Tambah Worker Baru" untuk menambahkan siswa sebagai worker.</div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- MODAL TAMBAH WORKER -->
  <div class="modal-overlay" id="addWorkerModal">
    <div class="modal-box">
      <div class="modal-head">
        <h3>Tambah Worker / Siswa Baru</h3>
        <button type="button" class="modal-close" onclick="closeAddWorkerModal()">&times;</button>
      </div>
      <form method="POST" action="{{ route('admin.workers.store') }}">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label class="form-label">Nama Lengkap Siswa <span style="color: var(--red);">*</span></label>
            <input type="text" name="name" class="form-control" required placeholder="Contoh: Muhammad Budi">
          </div>

          <div class="form-group">
            <label class="form-label">Alamat Email <span style="color: var(--red);">*</span></label>
            <input type="email" name="email" class="form-control" required placeholder="budi.worker@example.com">
          </div>

          <div class="form-group">
            <label class="form-label">Password Akun <span style="color: var(--red);">*</span></label>
            <input type="password" name="password" class="form-control" required minlength="6" placeholder="Minimal 6 karakter">
            <small style="color: var(--muted); font-size: 11px;">Password untuk login siswa ke dashboard worker.</small>
          </div>
        </div>
        <div style="padding: 16px 24px; background: #fafbfd; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px;">
          <button type="button" class="btn-outline" onclick="closeAddWorkerModal()" style="border-radius: 8px; padding: 8px 16px; border: 1px solid var(--border); background: #fff; font-size: 13px; font-weight: 700;">Batal</button>
          <button type="submit" class="btn-gold" style="border-radius: 8px;">Simpan Akun Worker</button>
        </div>
      </form>
    </div>
  </div>

  @push('scripts')
  <script>
    function openAddWorkerModal() {
      document.getElementById('addWorkerModal').classList.add('active');
    }
    function closeAddWorkerModal() {
      document.getElementById('addWorkerModal').classList.remove('active');
    }
  </script>
  @endpush
@endsection
