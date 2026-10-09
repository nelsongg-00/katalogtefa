@extends('layouts.superadmin')

@section('title', 'Data Jurusan')

@section('content')
<div class="stack-y">
<x-sa-page-header
    title="DATA MASTER JURUSAN"
    subtitle="Kelola data master 6 jurusan, unit produksi Teaching Factory, kepala program keahlian, dan status keaktifan.">
    <x-slot name="actions">
        <x-sa-button variant="primary" icon="plus" onclick="openAddModal()">Tambah Jurusan</x-sa-button>
    </x-slot>
</x-sa-page-header>

<x-sa-card body="none">
    <x-sa-table>
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
                        <x-sa-badge tone="blue">{{ $dept->kode }}</x-sa-badge>
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
                    <td class="num-cell">{{ $dept->admin_count }}</td>
                    <td class="num-cell">{{ $dept->worker_count }}</td>
                    <td class="num-cell">{{ $dept->order_count }}</td>
                    <td>
                        @if($dept->status_aktif)
                            <x-sa-badge tone="green">Aktif</x-sa-badge>
                        @else
                            <x-sa-badge tone="gray">Nonaktif</x-sa-badge>
                        @endif
                    </td>
                    <td>
                        <div class="row-actions">
                            <button class="icon-btn" title="Ubah Data" onclick='openEditModal(@json($dept))'>
                                <x-sa-icon name="pencil" />
                            </button>

                            <form method="POST" action="{{ route('superadmin.departments.toggle', $dept->id) }}" style="display:inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="icon-btn" title="{{ $dept->status_aktif ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <x-sa-icon name="power" />
                                </button>
                            </form>

                            <form method="POST" action="{{ route('superadmin.departments.destroy', $dept->id) }}" style="display:inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus atau menonaktifkan jurusan {{ $dept->nama_jurusan }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="icon-btn del" title="Hapus Jurusan">
                                    <x-sa-icon name="trash" />
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <x-sa-empty
                    icon="layers"
                    title="Belum ada data jurusan"
                    message='Klik tombol "Tambah Jurusan" untuk membuat data jurusan baru.'
                    :colspan="8" />
            @endforelse
        </tbody>
    </x-sa-table>
</x-sa-card>
</div>

<!-- MODAL TAMBAH JURUSAN -->
<x-sa-dialog id="addModal" title="Tambah Jurusan Baru" :action="route('superadmin.departments.store')">
    <x-slot name="close">
        <button type="button" class="icon-btn" aria-label="Tutup" onclick="closeAddModal()"><x-sa-icon name="close" /></button>
    </x-slot>

    <x-slot name="body">
        <div class="form-grid">
            <div class="field {{ $errors->has('kode') ? 'has-error' : '' }}">
                <label for="add_kode">Kode Singkatan</label>
                <x-sa-input id="add_kode" name="kode" maxlength="10" required placeholder="Contoh: RPL" />
                @error('kode')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="field">
                <label for="add_status">Status Keaktifan</label>
                <x-sa-input as="select" id="add_status" name="status_aktif">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </x-sa-input>
            </div>
            <div class="field full {{ $errors->has('nama_jurusan') ? 'has-error' : '' }}">
                <label for="add_nama">Nama Lengkap Jurusan</label>
                <x-sa-input id="add_nama" name="nama_jurusan" required placeholder="Contoh: Rekayasa Perangkat Lunak" />
                @error('nama_jurusan')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="field full {{ $errors->has('kepala_jurusan') ? 'has-error' : '' }}">
                <label for="add_kepala">Kepala Program Keahlian</label>
                <x-sa-input id="add_kepala" name="kepala_jurusan" placeholder="Contoh: Bu Ratna Sari, S.Kom" />
                @error('kepala_jurusan')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="field full {{ $errors->has('deskripsi') ? 'has-error' : '' }}">
                <label for="add_deskripsi">Deskripsi Singkat</label>
                <x-sa-input as="textarea" id="add_deskripsi" name="deskripsi" :rows="3" placeholder="Fokus kompetensi dan unit produksi..."></x-sa-input>
                @error('deskripsi')<div class="field-error">{{ $message }}</div>@enderror
            </div>
        </div>
    </x-slot>

    <x-slot name="footer">
        <button type="button" class="btn ghost" onclick="closeAddModal()">Batal</button>
        <button type="submit" class="btn primary">Simpan Jurusan</button>
    </x-slot>
</x-sa-dialog>

<!-- MODAL EDIT JURUSAN -->
<x-sa-dialog id="editModal" formId="editForm" title="Ubah Data Jurusan" titleId="editModalTitle" method="PUT">
    <x-slot name="close">
        <button type="button" class="icon-btn" aria-label="Tutup" onclick="closeEditModal()"><x-sa-icon name="close" /></button>
    </x-slot>

    <x-slot name="body">
        <div class="form-grid">
            <div class="field {{ $errors->has('kode') ? 'has-error' : '' }}">
                <label for="edit_kode">Kode Singkatan</label>
                <x-sa-input id="edit_kode" name="kode" maxlength="10" required />
                @error('kode')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="field">
                <label for="edit_status">Status Keaktifan</label>
                <x-sa-input as="select" id="edit_status" name="status_aktif">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </x-sa-input>
            </div>
            <div class="field full {{ $errors->has('nama_jurusan') ? 'has-error' : '' }}">
                <label for="edit_nama">Nama Lengkap Jurusan</label>
                <x-sa-input id="edit_nama" name="nama_jurusan" required />
                @error('nama_jurusan')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="field full {{ $errors->has('kepala_jurusan') ? 'has-error' : '' }}">
                <label for="edit_kepala">Kepala Program Keahlian</label>
                <x-sa-input id="edit_kepala" name="kepala_jurusan" />
                @error('kepala_jurusan')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="field full {{ $errors->has('deskripsi') ? 'has-error' : '' }}">
                <label for="edit_deskripsi">Deskripsi Singkat</label>
                <x-sa-input as="textarea" id="edit_deskripsi" name="deskripsi" :rows="3"></x-sa-input>
                @error('deskripsi')<div class="field-error">{{ $message }}</div>@enderror
            </div>
        </div>
    </x-slot>

    <x-slot name="footer">
        <button type="button" class="btn ghost" onclick="closeEditModal()">Batal</button>
        <button type="submit" class="btn primary">Simpan Perubahan</button>
    </x-slot>
</x-sa-dialog>
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
