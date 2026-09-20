@extends('layouts.superadmin')

@section('title', 'Data Jurusan')

@section('content')
<div class="page-head">
  <div>
    <h1>DATA MASTER JURUSAN</h1>
    <p>Kelola data master 6 jurusan, unit produksi Teaching Factory, kepala program keahlian, dan status keaktifan.</p>
  </div>
  <div class="actions">
    <button class="btn primary" onclick="openAddModal()">
      <svg class="i" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
      Tambah Jurusan
    </button>
  </div>
</div>

<section class="card">
  <div class="tbl-wrap">
    <table>
      <thead>
        <tr>
          <th>Kode</th>
          <th>Nama Jurusan</th>
          <th>Kepala Program</th>
          <th>Admin</th>
          <th>Worker</th>
          <th>Transaksi</th>
          <th>Status</th>
          <th class="r">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($departments as $dept)
          <tr>
            <td>
              <span class="badge b-blue">{{ $dept->kode }}</span>
            </td>
            <td>
              <strong>{{ $dept->nama_jurusan }}</strong>
              @if($dept->deskripsi)
                <br><small class="t-muted">{{ Str::limit($dept->deskripsi, 60) }}</small>
              @endif
            </td>
            <td>
              <strong>{{ $dept->kepala_jurusan ?: '—' }}</strong>
            </td>
            <td>{{ $dept->admin_count }}</td>
            <td>{{ $dept->worker_count }}</td>
            <td>{{ $dept->order_count }}</td>
            <td>
              @if($dept->status_aktif)
                <span class="badge b-green">Aktif</span>
              @else
                <span class="badge b-gray">Nonaktif</span>
              @endif
            </td>
            <td>
              <div class="row-actions">
                <button class="icon-btn" title="Ubah Data" onclick='openEditModal(@json($dept))'>
                  <svg class="i" viewBox="0 0 24 24"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4z"/></svg>
                </button>

                <form method="POST" action="{{ route('superadmin.departments.toggle', $dept->id) }}" style="display:inline">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="icon-btn" title="{{ $dept->status_aktif ? 'Nonaktifkan' : 'Aktifkan' }}">
                    <svg class="i" viewBox="0 0 24 24"><path d="M18.36 6.64a9 9 0 11-12.73 0M12 2v10"/></svg>
                  </button>
                </form>

                <form method="POST" action="{{ route('superadmin.departments.destroy', $dept->id) }}" style="display:inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus atau menonaktifkan jurusan {{ $dept->nama_jurusan }}?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="icon-btn del" title="Hapus Jurusan">
                    <svg class="i" viewBox="0 0 24 24"><path d="M3 6h18M8 6V4a1 1 0 011-1h6a1 1 0 011 1v2M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6M10 11v6M14 11v6"/></svg>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8">
              <div class="empty">
                <b>Belum ada data jurusan</b>
                Klik tombol "Tambah Jurusan" untuk membuat data jurusan baru.
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</section>

<!-- MODAL TAMBAH JURUSAN -->
<div class="modal" id="addModal" role="dialog" aria-modal="true">
  <form class="dialog" method="POST" action="{{ route('superadmin.departments.store') }}">
    @csrf
    <header>
      <h3>Tambah Jurusan Baru</h3>
      <button type="button" class="icon-btn" onclick="closeAddModal()"><svg class="i" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg></button>
    </header>
    <div class="body">
      <div class="form-grid">
        <div class="field">
          <label for="add_kode">Kode Singkatan</label>
          <input class="inp" id="add_kode" name="kode" maxlength="10" required placeholder="Contoh: RPL">
        </div>
        <div class="field">
          <label for="add_status">Status Keaktifan</label>
          <select class="sel" id="add_status" name="status_aktif">
            <option value="1">Aktif</option>
            <option value="0">Nonaktif</option>
          </select>
        </div>
        <div class="field full">
          <label for="add_nama">Nama Lengkap Jurusan</label>
          <input class="inp" id="add_nama" name="nama_jurusan" required placeholder="Contoh: Rekayasa Perangkat Lunak">
        </div>
        <div class="field full">
          <label for="add_kepala">Kepala Program Keahlian</label>
          <input class="inp" id="add_kepala" name="kepala_jurusan" placeholder="Contoh: Bu Ratna Sari, S.Kom">
        </div>
        <div class="field full">
          <label for="add_deskripsi">Deskripsi Singkat</label>
          <textarea class="inp" id="add_deskripsi" name="deskripsi" rows="3" placeholder="Fokus kompetensi dan unit produksi..."></textarea>
        </div>
      </div>
    </div>
    <footer>
      <button type="button" class="btn ghost" onclick="closeAddModal()">Batal</button>
      <button type="submit" class="btn primary">Simpan Jurusan</button>
    </footer>
  </form>
</div>

<!-- MODAL EDIT JURUSAN -->
<div class="modal" id="editModal" role="dialog" aria-modal="true">
  <form class="dialog" id="editForm" method="POST" action="">
    @csrf
    @method('PUT')
    <header>
      <h3 id="editModalTitle">Ubah Data Jurusan</h3>
      <button type="button" class="icon-btn" onclick="closeEditModal()"><svg class="i" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg></button>
    </header>
    <div class="body">
      <div class="form-grid">
        <div class="field">
          <label for="edit_kode">Kode Singkatan</label>
          <input class="inp" id="edit_kode" name="kode" maxlength="10" required>
        </div>
        <div class="field">
          <label for="edit_status">Status Keaktifan</label>
          <select class="sel" id="edit_status" name="status_aktif">
            <option value="1">Aktif</option>
            <option value="0">Nonaktif</option>
          </select>
        </div>
        <div class="field full">
          <label for="edit_nama">Nama Lengkap Jurusan</label>
          <input class="inp" id="edit_nama" name="nama_jurusan" required>
        </div>
        <div class="field full">
          <label for="edit_kepala">Kepala Program Keahlian</label>
          <input class="inp" id="edit_kepala" name="kepala_jurusan">
        </div>
        <div class="field full">
          <label for="edit_deskripsi">Deskripsi Singkat</label>
          <textarea class="inp" id="edit_deskripsi" name="deskripsi" rows="3"></textarea>
        </div>
      </div>
    </div>
    <footer>
      <button type="button" class="btn ghost" onclick="closeEditModal()">Batal</button>
      <button type="submit" class="btn primary">Simpan Perubahan</button>
    </footer>
  </form>
</div>
@endsection

@push('scripts')
<script>
function openAddModal() {
  $('#addModal').classList.add('open');
}
function closeAddModal() {
  $('#addModal').classList.remove('open');
}

function openEditModal(dept) {
  $('#editModalTitle').textContent = 'Ubah Jurusan ' + dept.nama_jurusan;
  $('#edit_kode').value = dept.kode || '';
  $('#edit_nama').value = dept.nama_jurusan || '';
  $('#edit_kepala').value = dept.kepala_jurusan || '';
  $('#edit_deskripsi').value = dept.deskripsi || '';
  $('#edit_status').value = dept.status_aktif ? '1' : '0';

  const form = $('#editForm');
  form.action = `/superadmin/departments/${dept.id}`;

  $('#editModal').classList.add('open');
}
function closeEditModal() {
  $('#editModal').classList.remove('open');
}
</script>
@endpush
