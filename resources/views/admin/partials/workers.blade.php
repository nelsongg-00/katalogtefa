<div class="card" id="workers-section">
  <div class="card-head">
    <div>
      <h3>Manajemen Siswa / Worker ({{ $workerAktif }} Terdaftar)</h3>
      <p style="margin: 3px 0 0; font-size: 12px; color: var(--muted);">Daftar siswa yang memegang peran worker pada jurusan {{ $jurusan->nama_jurusan ?? '' }}.</p>
    </div>
    <button type="button" class="btn-gold" onclick="openAddWorkerModal()">
      + Tambah Worker Baru
    </button>
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
            <!-- 1. Nama & Avatar -->
            <td>
              <div class="cust">
                <div class="cust-av" style="background: #e8f0ff; color: var(--blue);">
                  {{ strtoupper(substr($w->name, 0, 2)) }}
                </div>
                <div>
                  <strong style="font-size: 13px; color: var(--text);">{{ $w->name }}</strong>
                  <div style="font-size: 11px; color: var(--muted);">Role: Worker</div>
                </div>
              </div>
            </td>

            <!-- 2. Email -->
            <td>
              <span style="color: var(--muted); font-size: 12.5px;">{{ $w->email }}</span>
            </td>

            <!-- 3. Jurusan -->
            <td>
              <span style="background: #eef1f7; color: var(--navy-900); font-weight: 700; font-size: 11.5px; padding: 3px 8px; border-radius: 6px;">
                {{ $w->jurusan->nama_jurusan ?? ($jurusan->nama_jurusan ?? 'Jurusan') }}
              </span>
            </td>

            <!-- 4. Tugas Berjalan -->
            <td>
              @if($activeProjectsCount > 0)
                <span class="badge badge-progress">{{ $activeProjectsCount }} Projek Aktif</span>
              @else
                <span style="color: var(--muted); font-size: 12px;">Tidak ada projek aktif</span>
              @endif
            </td>

            <!-- 5. Bergabung -->
            <td>
              <span style="color: var(--muted); font-size: 11.5px;">
                {{ $w->created_at ? $w->created_at->format('d M Y') : '-' }}
              </span>
            </td>

            <!-- 6. Aksi -->
            <td>
              <form method="POST" action="{{ route('admin.workers.destroy', $w->id) }}" onsubmit="return confirm('Hapus worker {{ $w->name }}?');" style="margin: 0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-sm-delete" title="Copot / Hapus Worker">
                  Hapus Worker
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" style="text-align: center; padding: 36px 20px; color: var(--muted);">
              Belum ada akun worker terdaftar untuk jurusan ini.
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
          <small style="color: var(--muted); font-size: 11px;">Password awal untuk login siswa ke Worker Dashboard.</small>
        </div>
      </div>
      <div style="padding: 16px 24px; background: #fafbfd; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px;">
        <button type="button" class="btn-outline" onclick="closeAddWorkerModal()" style="border-radius: 8px;">Batal</button>
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
