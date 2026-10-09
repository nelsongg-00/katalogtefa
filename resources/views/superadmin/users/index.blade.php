@extends('layouts.superadmin')

@section('title', 'Manajemen User')

@section('content')
<div class="stack-y">
<x-sa-page-header
    title="MANAJEMEN DATA MASTER USER"
    subtitle="Tambah, edit peran (role), kelola status keaktifan akun, dan tetapkan jurusan untuk Admin Jurusan dan Worker.">
    <x-slot name="actions">
        <x-sa-button variant="primary" icon="plus" onclick="openAddUserModal()">Tambah User</x-sa-button>
    </x-slot>
</x-sa-page-header>

<!-- 4 KARTU MINI STATISTIK PENGGUNA -->
<section class="mini-stats">
    <x-sa-stat variant="mini" label="Total Pengguna" :value="$totalUsers" />
    <x-sa-stat variant="mini" label="Admin Jurusan" :value="$totalAdmin" />
    <x-sa-stat variant="mini" label="Worker (Siswa/i)" :value="$totalWorker" />
    <x-sa-stat variant="mini" label="Pelanggan / Klien" :value="$totalClient" />
</section>

<!-- TABEL & FILTER TOOLBAR -->
<x-sa-card body="none">
    <form method="GET" action="{{ route('superadmin.users.index') }}" class="toolbar">
        <div class="search">
            <x-sa-icon name="search" :size="16" />
            <x-sa-input name="q" :value="$q ?? ''" placeholder="Cari nama atau email pengguna..." aria-label="Cari nama atau email pengguna" />
        </div>

        <x-sa-input as="select" name="role" aria-label="Filter role" onchange="this.form.submit()">
            <option value="">Semua Role</option>
            <option value="super_admin" {{ ($role ?? '') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
            <option value="admin_jurusan" {{ ($role ?? '') === 'admin_jurusan' ? 'selected' : '' }}>Admin Jurusan</option>
            <option value="worker" {{ ($role ?? '') === 'worker' ? 'selected' : '' }}>Worker</option>
            <option value="pelanggan" {{ ($role ?? '') === 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
        </x-sa-input>

        <x-sa-input as="select" name="jurusan" aria-label="Filter jurusan" onchange="this.form.submit()">
            <option value="">Semua Jurusan</option>
            @foreach($jurusans as $j)
                <option value="{{ $j->id }}" {{ (string)($jurusanId ?? '') === (string)$j->id ? 'selected' : '' }}>
                    {{ $j->nama_jurusan }}
                </option>
            @endforeach
        </x-sa-input>

        @if(!empty($q) || !empty($role) || !empty($jurusanId))
            <x-sa-button variant="ghost" size="sm" :href="route('superadmin.users.index')">Reset Filter</x-sa-button>
        @endif
    </form>

    <x-sa-table>
        <thead>
            <tr>
                <th>Pengguna</th>
                <th>Role / Peran</th>
                <th>Jurusan</th>
                <th>Status Akun</th>
                <th class="r">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                @php
                    $initials = collect(explode(' ', $user->name))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->join('');
                    $roleLabel = match($user->role) {
                        'super_admin' => 'Super Admin',
                        'admin_jurusan' => 'Admin Jurusan',
                        'worker' => 'Worker',
                        default => 'Pelanggan',
                    };
                    $roleClass = match($user->role) {
                        'super_admin' => 'primary',
                        'admin_jurusan' => 'blue',
                        'worker' => 'yellow',
                        default => 'gray',
                    };
                @endphp
                <tr>
                    <td>
                        <div class="person">
                            <div class="avatar">{{ $initials }}</div>
                            <div>
                                <b>{{ $user->name }}</b>
                                <small>{{ $user->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <x-sa-badge :tone="$roleClass">{{ $roleLabel }}</x-sa-badge>
                    </td>
                    <td>
                        @if($user->jurusan)
                            <x-sa-badge tone="blue">{{ $user->jurusan->nama_jurusan }}</x-sa-badge>
                        @else
                            <span class="t-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($user->is_active ?? true)
                            <x-sa-badge tone="green">Aktif</x-sa-badge>
                        @else
                            <x-sa-badge tone="gray">Nonaktif</x-sa-badge>
                        @endif
                    </td>
                    <td>
                        <div class="row-actions">
                            <button class="icon-btn" title="Ubah User" onclick='openEditUserModal(@json($user))'>
                                <x-sa-icon name="pencil" />
                            </button>

                            <button class="icon-btn" title="Reset Password" onclick='openResetPasswordModal({{ $user->id }}, "{{ $user->name }}")'>
                                <x-sa-icon name="key" />
                            </button>

                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('superadmin.users.destroy', $user->id) }}" style="display:inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $user->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="icon-btn del" title="Hapus Akun">
                                        <x-sa-icon name="trash" />
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <x-sa-empty
                    icon="users"
                    title="User tidak ditemukan"
                    message="Ubah kata kunci pencarian atau filter role/jurusan."
                    :colspan="5" />
            @endforelse
        </tbody>
    </x-sa-table>
</x-sa-card>
</div>

<!-- MODAL TAMBAH USER -->
<x-sa-dialog id="addUserModal" title="Tambah Pengguna Baru" :action="route('superadmin.users.store')">
    <x-slot name="close">
        <button type="button" class="icon-btn" aria-label="Tutup" onclick="closeAddUserModal()"><x-sa-icon name="close" /></button>
    </x-slot>

    <x-slot name="body">
        <div class="form-grid">
            <div class="field full {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="add_u_name">Nama Lengkap</label>
                <x-sa-input id="add_u_name" name="name" required placeholder="Contoh: Budi Santoso" />
                @error('name')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="field full {{ $errors->has('email') ? 'has-error' : '' }}">
                <label for="add_u_email">Email</label>
                <x-sa-input id="add_u_email" type="email" name="email" required placeholder="Contoh: user@example.com" />
                @error('email')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="field">
                <label for="add_u_role">Role / Peran</label>
                <x-sa-input as="select" id="add_u_role" name="role" required onchange="handleRoleChange('add')">
                    <option value="admin_jurusan">Admin Jurusan</option>
                </x-sa-input>
            </div>
            <div class="field {{ $errors->has('jurusan_id') ? 'has-error' : '' }}">
                <label for="add_u_jurusan">Jurusan Terkait</label>
                <x-sa-input as="select" id="add_u_jurusan" name="jurusan_id">
                    <option value="">— Pilih Jurusan —</option>
                    @foreach($jurusans as $j)
                        <option value="{{ $j->id }}">{{ $j->nama_jurusan }}</option>
                    @endforeach
                </x-sa-input>
                @error('jurusan_id')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="field full {{ $errors->has('password') ? 'has-error' : '' }}">
                <label for="add_u_password">Password Awal</label>
                <x-sa-input id="add_u_password" type="text" name="password" required minlength="8" value="password" />
                <div class="hint">Default: "password" (bisa diubah sesuai kebutuhan).</div>
                @error('password')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="field full">
                <label for="add_u_password_confirmation">Ulangi Password</label>
                <x-sa-input id="add_u_password_confirmation" type="text" name="password_confirmation" required minlength="8" value="password" />
                <div class="hint">Wajib sama dengan password di atas, minimal 8 karakter.</div>
            </div>
            <div class="field full">
                <label>Status Akun</label>
                <div class="sw">
                    <span>Aktif</span>
                    <div class="tg on" data-toggle="add_u_active" data-on="1" data-off="0" onclick="toggleSwitch(this)"></div>
                    <input type="hidden" name="is_active" id="add_u_active" value="1">
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="footer">
        <button type="button" class="btn ghost" onclick="closeAddUserModal()">Batal</button>
        <button type="submit" class="btn primary">Simpan User</button>
    </x-slot>
</x-sa-dialog>

<!-- MODAL EDIT USER -->
<x-sa-dialog id="editUserModal" formId="editUserForm" title="Ubah Data Pengguna" titleId="editUserModalTitle" method="PUT">
    <x-slot name="close">
        <button type="button" class="icon-btn" aria-label="Tutup" onclick="closeEditUserModal()"><x-sa-icon name="close" /></button>
    </x-slot>

    <x-slot name="body">
        <div class="form-grid">
            <div class="field full {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="edit_u_name">Nama Lengkap</label>
                <x-sa-input id="edit_u_name" name="name" required />
                @error('name')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="field full {{ $errors->has('email') ? 'has-error' : '' }}">
                <label for="edit_u_email">Email</label>
                <x-sa-input id="edit_u_email" type="email" name="email" required />
                @error('email')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="field">
                <label for="edit_u_role">Role / Peran</label>
                <x-sa-input as="select" id="edit_u_role" name="role" required onchange="handleRoleChange('edit')">
                    <option value="admin_jurusan">Admin Jurusan</option>
                    <option value="worker">Worker</option>
                    <option value="pelanggan">Pelanggan</option>
                    <option value="super_admin">Super Admin</option>
                </x-sa-input>
                @error('role')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="field {{ $errors->has('jurusan_id') ? 'has-error' : '' }}">
                <label for="edit_u_jurusan">Jurusan Terkait</label>
                <x-sa-input as="select" id="edit_u_jurusan" name="jurusan_id">
                    <option value="">— Pilih Jurusan —</option>
                    @foreach($jurusans as $j)
                        <option value="{{ $j->id }}">{{ $j->nama_jurusan }}</option>
                    @endforeach
                </x-sa-input>
                @error('jurusan_id')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="field full">
                <label>Status Akun</label>
                <div class="sw">
                    <span id="edit_u_active_label">Aktif</span>
                    <div class="tg on" id="edit_u_active_tg" data-toggle="edit_u_active" data-on="1" data-off="0" onclick="toggleSwitch(this)"></div>
                    <input type="hidden" name="is_active" id="edit_u_active" value="1">
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="footer">
        <button type="button" class="btn ghost" onclick="closeEditUserModal()">Batal</button>
        <button type="submit" class="btn primary">Simpan Perubahan</button>
    </x-slot>
</x-sa-dialog>

<!-- MODAL RESET PASSWORD -->
<x-sa-dialog id="resetPasswordModal" formId="resetPasswordForm" title="Reset Password Pengguna" method="PUT">
    <x-slot name="close">
        <button type="button" class="icon-btn" aria-label="Tutup" onclick="closeResetPasswordModal()"><x-sa-icon name="close" /></button>
    </x-slot>

    <x-slot name="body">
        {{-- Pass existing name, email, role to preserve them --}}
        <input type="hidden" id="rp_name" name="name">
        <input type="hidden" id="rp_email" name="email">
        <input type="hidden" id="rp_role" name="role">
        <input type="hidden" id="rp_jurusan" name="jurusan_id">
        <input type="hidden" id="rp_active" name="is_active" value="1">

        <p id="resetModalDesc"></p>

        <div class="field full {{ $errors->has('password') ? 'has-error' : '' }}">
            <label for="rp_password">Password Baru</label>
            <x-sa-input id="rp_password" type="password" name="password" required minlength="8" placeholder="Minimal 8 karakter" />
            @error('password')<div class="field-error">{{ $message }}</div>@enderror
        </div>
    </x-slot>

    <x-slot name="footer">
        <button type="button" class="btn ghost" onclick="closeResetPasswordModal()">Batal</button>
        <button type="submit" class="btn primary">Simpan Password Baru</button>
    </x-slot>
</x-sa-dialog>
@endsection

@push('scripts')
<script>
function handleRoleChange(prefix) {
  const roleSelect = $(`#${prefix}_u_role`);
  const jurSelect = $(`#${prefix}_u_jurusan`);
  if (!roleSelect || !jurSelect) return;

  const needJur = roleSelect.value === 'admin_jurusan' || roleSelect.value === 'worker';
  jurSelect.disabled = !needJur;
  if (!needJur) jurSelect.value = '';
}

function openAddUserModal() {
  $('#addUserModal').classList.add('open');
  handleRoleChange('add');
}
function closeAddUserModal() {
  $('#addUserModal').classList.remove('open');
}

function openEditUserModal(user) {
  $('#editUserModalTitle').textContent = 'Ubah Akun: ' + user.name;
  $('#edit_u_name').value = user.name || '';
  $('#edit_u_email').value = user.email || '';
  $('#edit_u_role').value = user.role || 'pelanggan';
  $('#edit_u_jurusan').value = user.jurusan_id || '';
  $('#edit_u_active').value = (user.is_active === false || user.is_active === 0) ? '0' : '1';
  const editTg = $('#edit_u_active_tg');
  if (editTg) {
      editTg.classList.toggle('on', $('#edit_u_active').value === '1');
      const lbl = $('#edit_u_active_label');
      if (lbl) lbl.textContent = $('#edit_u_active').value === '1' ? 'Aktif' : 'Nonaktif';
  }

  const form = $('#editUserForm');
  form.action = `/superadmin/users/${user.id}`;

  handleRoleChange('edit');
  $('#editUserModal').classList.add('open');
}
function closeEditUserModal() {
  $('#editUserModal').classList.remove('open');
}

function openResetPasswordModal(id, name) {
  // Fetch user object from rows if available
  fetch(`/superadmin/users/${id}`)
  $('#resetModalDesc').innerHTML = `Buat password baru untuk akun <strong>${name}</strong>.`;
  const form = $('#resetPasswordForm');
  form.action = `/superadmin/users/${id}`;

  // Pre-fill hidden inputs with fallback values
  $('#rp_password').value = '';
  // Populate from active row if possible
  const row = event.target.closest('tr');
  if (row) {
    const editBtn = row.querySelector('button[title="Ubah User"]');
    if (editBtn) {
      const match = editBtn.getAttribute('onclick').match(/openEditUserModal\((\{.*\})\)/);
      if (match) {
        try {
          const uData = JSON.parse(match[1]);
          $('#rp_name').value = uData.name;
          $('#rp_email').value = uData.email;
          $('#rp_role').value = uData.role;
          $('#rp_jurusan').value = uData.jurusan_id || '';
          $('#rp_active').value = (uData.is_active === false || uData.is_active === 0) ? '0' : '1';
        } catch(e) {}
      }
    }
  }

  $('#resetPasswordModal').classList.add('open');
}
function closeResetPasswordModal() {
  $('#resetPasswordModal').classList.remove('open');
}
</script>
@endpush
