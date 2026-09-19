<style>
  .project-progress-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 140px;
  }
  .project-progress-bar {
    flex: 1;
    height: 8px;
    background: #e9ecf4;
    border-radius: 6px;
    overflow: hidden;
  }
  .project-progress-fill {
    height: 100%;
    border-radius: 6px;
    transition: width .3s ease;
  }
  .project-progress-pct {
    font-size: 12px;
    font-weight: 800;
    min-width: 36px;
    color: var(--text);
  }

  .btn-sm-edit {
    background: #eef2fd;
    color: var(--blue);
    border: 1px solid #d3defa;
    padding: 5px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: all .15s;
  }
  .btn-sm-edit:hover {
    background: var(--blue);
    color: #fff;
  }

  .btn-sm-delete {
    background: #fde3e4;
    color: var(--red);
    border: 1px solid #f9bec1;
    padding: 5px 10px;
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
</style>

<div class="card" id="projects-section">
  <div class="card-head">
    <div>
      <h3>Projek Sedang Berjalan (Projects in Progress)</h3>
      <p style="margin: 3px 0 0; font-size: 12px; color: var(--muted);">Daftar pengerjaan proyek aktif, progress pengerjaan, dan penugasan siswa.</p>
    </div>
    <button type="button" class="btn-gold" onclick="openAddProjectModal()">
      + Tambah Projek Baru
    </button>
  </div>

  <div class="table-responsive">
    <table class="order-table">
      <thead>
        <tr>
          <th>Nama Projek</th>
          <th>Worker Bertugas</th>
          <th>Progress Pengerjaan</th>
          <th>Tenggat Waktu</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($projects as $project)
          @php
            $pct = $project->progress ?? 0;
            $fillColor = $pct >= 80 ? 'var(--green)' : ($pct >= 40 ? 'var(--blue)' : 'var(--amber)');
          @endphp
          <tr>
            <!-- 1. Nama & Deskripsi -->
            <td>
              <strong style="color: var(--text); font-size: 13.5px;">{{ $project->nama_projek }}</strong>
              @if($project->deskripsi)
                <div style="color: var(--muted); font-size: 11.5px; margin-top: 2px; max-width: 320px; line-height: 1.3;">
                  {{ Str::limit($project->deskripsi, 80) }}
                </div>
              @endif
            </td>

            <!-- 2. Worker Bertugas -->
            <td>
              <div class="cust">
                <div class="cust-av" style="background: #fdf1d9; color: var(--gold-dark);">
                  {{ strtoupper(substr($project->worker->name ?? '?', 0, 2)) }}
                </div>
                <div>
                  <div style="font-weight: 700; font-size: 12.5px;">
                    {{ $project->worker->name ?? 'Belum Ditugaskan' }}
                  </div>
                  @if($project->worker)
                    <div style="font-size: 11px; color: var(--muted);">Worker / Siswa</div>
                  @endif
                </div>
              </div>
            </td>

            <!-- 3. Progress Bar -->
            <td>
              <div class="project-progress-wrap">
                <div class="project-progress-bar">
                  <div class="project-progress-fill" style="width: {{ $pct }}%; background: {{ $fillColor }};"></div>
                </div>
                <span class="project-progress-pct">{{ $pct }}%</span>
              </div>
            </td>

            <!-- 4. Tenggat Waktu -->
            <td>
              <span style="font-size: 12.5px; font-weight: 600; color: var(--text);">
                {{ $project->tenggat_waktu ? $project->tenggat_waktu->format('d M Y') : '-' }}
              </span>
            </td>

            <!-- 5. Status Badge -->
            <td>
              @if($project->status === 'in_progress')
                <span class="badge badge-progress">⚙ Dikerjakan</span>
              @elseif($project->status === 'completed')
                <span class="badge badge-done">✓ Selesai</span>
              @elseif($project->status === 'cancelled')
                <span class="badge badge-cancel">✕ Dibatalkan</span>
              @else
                <span class="badge badge-wait">⏳ Menunggu</span>
              @endif
            </td>

            <!-- 6. Aksi -->
            <td>
              <div style="display: flex; align-items: center; gap: 8px;">
                <button type="button" class="btn-sm-edit" onclick="openEditProjectModal({{ $project->toJson() }})">
                  Edit Progress
                </button>
                <form method="POST" action="{{ route('admin.projects.destroy', $project->id) }}" onsubmit="return confirm('Hapus projek ini?');" style="margin: 0;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-sm-delete" title="Hapus Projek">✕</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" style="text-align: center; padding: 36px 20px; color: var(--muted);">
              Belum ada data projek berjalan untuk jurusan ini.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- MODAL TAMBAH PROJEK -->
<div class="modal-overlay" id="addProjectModal">
  <div class="modal-box">
    <div class="modal-head">
      <h3>Tambah Projek Baru</h3>
      <button type="button" class="modal-close" onclick="closeAddProjectModal()">&times;</button>
    </div>
    <form method="POST" action="{{ route('admin.projects.store') }}">
      @csrf
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Nama Projek <span style="color: var(--red);">*</span></label>
          <input type="text" name="nama_projek" class="form-control" required placeholder="Contoh: Pembuatan Website Portal...">
        </div>

        <div class="form-group">
          <label class="form-label">Deskripsi Projek</label>
          <textarea name="deskripsi" class="form-control" rows="3" placeholder="Rincian lingkup tugas atau kebutuhan klien..."></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
          <div class="form-group">
            <label class="form-label">Status Awal</label>
            <select name="status" class="form-control" required>
              <option value="pending">Menunggu (Pending)</option>
              <option value="in_progress" selected>Dikerjakan (In Progress)</option>
              <option value="completed">Selesai (Completed)</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Progress Awal (0-100%)</label>
            <input type="number" name="progress" class="form-control" min="0" max="100" value="10" required>
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
          <div class="form-group">
            <label class="form-label">Pilih Worker / Siswa</label>
            <select name="worker_id" class="form-control">
              <option value="">-- Belum Ditugaskan --</option>
              @foreach($workers as $worker)
                <option value="{{ $worker->id }}">{{ $worker->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Tenggat Waktu</label>
            <input type="date" name="tenggat_waktu" class="form-control">
          </div>
        </div>
      </div>
      <div style="padding: 16px 24px; background: #fafbfd; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px;">
        <button type="button" class="btn-outline" onclick="closeAddProjectModal()" style="border-radius: 8px;">Batal</button>
        <button type="submit" class="btn-gold" style="border-radius: 8px;">Simpan Projek</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL EDIT PROJEK -->
<div class="modal-overlay" id="editProjectModal">
  <div class="modal-box">
    <div class="modal-head">
      <h3>Update Progress & Status Projek</h3>
      <button type="button" class="modal-close" onclick="closeEditProjectModal()">&times;</button>
    </div>
    <form id="editProjectForm" method="POST" action="">
      @csrf
      @method('PATCH')
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Nama Projek</label>
          <input type="text" id="editProjectName" class="form-control" readonly style="background: #edf1f8; cursor: not-allowed;">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
          <div class="form-group">
            <label class="form-label">Status Projek</label>
            <select name="status" id="editProjectStatus" class="form-control" required>
              <option value="pending">Menunggu (Pending)</option>
              <option value="in_progress">Dikerjakan (In Progress)</option>
              <option value="completed">Selesai (Completed)</option>
              <option value="cancelled">Dibatalkan (Cancelled)</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Persentase Progress (0-100%)</label>
            <input type="number" name="progress" id="editProjectProgress" class="form-control" min="0" max="100" required>
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
          <div class="form-group">
            <label class="form-label">Worker / Siswa Bertugas</label>
            <select name="worker_id" id="editProjectWorker" class="form-control">
              <option value="">-- Belum Ditugaskan --</option>
              @foreach($workers as $worker)
                <option value="{{ $worker->id }}">{{ $worker->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Tenggat Waktu</label>
            <input type="date" name="tenggat_waktu" id="editProjectDeadline" class="form-control">
          </div>
        </div>
      </div>
      <div style="padding: 16px 24px; background: #fafbfd; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px;">
        <button type="button" class="btn-outline" onclick="closeEditProjectModal()" style="border-radius: 8px;">Batal</button>
        <button type="submit" class="btn-gold" style="border-radius: 8px;">Perbarui Progress</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  function openAddProjectModal() {
    document.getElementById('addProjectModal').classList.add('active');
  }
  function closeAddProjectModal() {
    document.getElementById('addProjectModal').classList.remove('active');
  }

  function openEditProjectModal(project) {
    const form = document.getElementById('editProjectForm');
    form.action = '/admin/projects/' + project.id;
    document.getElementById('editProjectName').value = project.nama_projek;
    document.getElementById('editProjectStatus').value = project.status;
    document.getElementById('editProjectProgress').value = project.progress;
    document.getElementById('editProjectWorker').value = project.worker_id || '';
    if (project.tenggat_waktu) {
      document.getElementById('editProjectDeadline').value = project.tenggat_waktu.substring(0, 10);
    } else {
      document.getElementById('editProjectDeadline').value = '';
    }
    document.getElementById('editProjectModal').classList.add('active');
  }
  function closeEditProjectModal() {
    document.getElementById('editProjectModal').classList.remove('active');
  }
</script>
@endpush
