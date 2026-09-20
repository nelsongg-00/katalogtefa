@extends('layouts.superadmin')

@section('title', 'Manajemen User')

@section('content')
<div class="page-head">
  <div>
    <h1>MANAJEMEN DATA MASTER USER</h1>
    <p>Tambah, edit peran (role), kelola status keaktifan akun, dan tetapkan jurusan untuk Admin Jurusan dan Worker.</p>
  </div>
  <div class="actions">
    <button class="btn primary" onclick="openAddUserModal()">
      <svg class="i" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
      Tambah User
    </button>
  </div>
</div>

<!-- 4 KARTU MINI STATISTIK PENGGUNA -->
<section class="mini-stats">
  <div class="card mini">
    <small>Total Pengguna</small>
    <b>{{ $totalUsers }}</b>
  </div>
  <div class="card mini">
    <small>Admin Jurusan</small>
    <b>{{ $totalAdmin }}</b>
  </div>
  <div class="card mini">
    <small>Worker (Siswa/i)</small>
    <b>{{ $totalWorker }}</b>
  </div>
  <div class="card mini">
    <small>Pelanggan / Klien</small>
    <b>{{ $totalClient }}</b>
  </div>
</section>

<!-- TABEL & FILTER TOOLBAR -->
<section class="card">
  <form method="GET" action="{{ route('superadmin.users.index') }}" class="toolbar">
    <div class="search">
      <svg class="i" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
      <input class="inp" name="q" value="{{ $q ?? '' }}" placeholder="Cari nama atau email pengguna...">
    </div>

    <select class="sel" name="role" onchange="this.form.submit()">
      <option value="">Semua Role</option>
      <option value="super_admin" {{ ($role ?? '') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
      <option value="admin_jurusan" {{ ($role ?? '') === 'admin_jurusan' ? 'selected' : '' }}>Admin Jurusan</option>
      <option value="worker" {{ ($role ?? '') === 'worker' ? 'selected' : '' }}>Worker</option>
      <option value="pelanggan" {{ ($role ?? '') === 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
    </select>

    <select class="sel" name="jurusan" onchange="this.form.submit()">
      <option value="">Semua Jurusan</option>
      @foreach($jurusans as $j)
        <option value="{{ $j->id }}" {{ (string)($jurusanId ?? '') === (string)$j->id ? 'selected' : '' }}>
          {{ $j->nama_jurusan }}
        </option>
      @endforeach
    </select>

    @if(!empty($q) || !empty($role) || !empty($jurusanId))
      <a href="{{ route('superadmin.users.index') }}" class="btn ghost sm">Reset Filter</a>
    @endif
  </form>

  <div class="tbl-wrap">
    <table>
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
                'super_admin' => 'b-purple',
                'admin_jurusan' => 'b-blue',
                'worker' => 'b-yellow',
                default => 'b-gray',
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
              <span class="badge {{ $roleClass }}">{{ $roleLabel }}</span>
            </td>
            <td>
              @if($user->jurusan)
                <span class="badge b-blue">{{ $user->jurusan->nama_jurusan }}</span>
              @else
                <span class="t-muted">—</span>
              @endif
            </td>
            <td>
              @if($user->is_active ?? true)
                <span class="badge b-green">Aktif</span>
              @else
                <span class="badge b-gray">Nonaktif</span>
              @endif
            </td>
            <td>
              <div class="row-actions">
                <button class="icon-btn" title="Ubah User" onclick='openEditUserModal(@json($user))'>
                  <svg class="i" viewBox="0 0 24 24"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4z"/></svg>
                </button>

                <button class="icon-btn" title="Reset Password" onclick='openResetPasswordModal({{ $user->id }}, "{{ $user->name }}")'>
                  <svg class="i" viewBox="0 0 24 24"><circle cx="8" cy="15" r="4"/><path d="M11 12l9-9M16 7l3 3"/></svg>
                </button>

                @if($user->id !== auth()->id())
                  <form method="POST" action="{{ route('superadmin.users.destroy', $user->id) }}" style="display:inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $user->name }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="icon-btn del" title="Hapus Akun">
                      <svg class="i" viewBox="0 0 24 24"><path d="M3 6h18M8 6V4a1 1 0 011-1h6a1 1 0 011 1v2M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6M10 11v6M14 11v6"/></svg>
                    </button>
                  </form>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5">
              <div class="empty">
                <b>User tidak ditemukan</b>
                Ubah kata kunci pencarian atau filter role/jurusan.
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</section>

<!-- MODAL TAMBAH USER -->
<div class="modal" id="addUserModal" role="dialog" aria-modal="true">
  <form class="dialog" method="POST" action="{{ route('superadmin.users.store') }}">
    @csrf
    <header>
      <h3>Tambah Pengguna Baru</h3>
      <button type="button" class="icon-btn" onclick="closeAddUserModal()"><svg class="i" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg></button>
    </header>
    <div class="body">
      <div class="form-grid">
        <div class="field full">
          <label for="add_u_name">Nama Lengkap</label>
          <input class="inp" id="add_u_name" name="name" required placeholder="Contoh: Budi Santoso">
        </div>
        <div class="field full">
          <label for="add_u_email">Email</label>
          <input class="inp" id="add_u_email" type="email" name="email" required placeholder="Contoh: user@example.com">
        </div>
        <div class="field">
          <label for="add_u_role">Role / Peran</label>
          <select class="sel" id="add_u_role" name="role" required onchange="handleRoleChange('add')">
            <option value="admin_jurusan">Admin Jurusan</option>
            <option value="worker">Worker</option>
            <option value="pelanggan">Pelanggan</option>
            <option value="super_admin">Super Admin</option>
          </select>
        </div>
        <div class="field">
          <label for="add_u_jurusan">Jurusan Terkait</label>
          <select class="sel" id="add_u_jurusan" name="jurusan_id">
            <option value="">— Pilih Jurusan —</option>
            @foreach($jurusans as $j)
              <option value="{{ $j->id }}">{{ $j->nama_jurusan }}</option>
            @endforeach
          </select>
        </div>
        <div class="field full">
          <label for="add_u_password">Password Awal</label>
          <input class="inp" id="add_u_password" type="text" name="password" required minlength="6" value="password">
          <div class="hint">Default: "password" (bisa diubah sesuai kebutuhan).</div>
        </div>
        <div class="field full">
          <label for="add_u_active">Status Akun</label>
          <select class="sel" id="add_u_active" name="is_active">
            <option value="1">Aktif</option>
            <option value="0">Nonaktif</option>
          </select>
        </div>
      </div>
    </div>
    <footer>
      <button type="button" class="btn ghost" onclick="closeAddUserModal()">Batal</button>
      <button type="submit" class="btn primary">Simpan User</button>
    </footer>
  </form>
</div>

<!-- MODAL EDIT USER -->
<div class="modal" id="editUserModal" role="dialog" aria-modal="true">
  <form class="dialog" id="editUserForm" method="POST" action="">
    @csrf
    @method('PUT')
    <header>
      <h3 id="editUserModalTitle">Ubah Data Pengguna</h3>
      <button type="button" class="icon-btn" onclick="closeEditUserModal()"><svg class="i" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg></button>
    </header>
    <div class="body">
      <div class="form-grid">
        <div class="field full">
          <label for="edit_u_name">Nama Lengkap</label>
          <input class="inp" id="edit_u_name" name="name" required>
        </div>
        <div class="field full">
          <label for="edit_u_email">Email</label>
          <input class="inp" id="edit_u_email" type="email" name="email" required>
        </div>
        <div class="field">
          <label for="edit_u_role">Role / Peran</label>
          <select class="sel" id="edit_u_role" name="role" required onchange="handleRoleChange('edit')">
            <option value="admin_jurusan">Admin Jurusan</option>
            <option value="worker">Worker</option>
            <option value="pelanggan">Pelanggan</option>
            <option value="super_admin">Super Admin</option>
          </select>
        </div>
        <div class="field">
          <label for="edit_u_jurusan">Jurusan Terkait</label>
          <select class="sel" id="edit_u_jurusan" name="jurusan_id">
            <option value="">— Pilih Jurusan —</option>
            @foreach($jurusans as $j)
              <option value="{{ $j->id }}">{{ $j->nama_jurusan }}</option>
            @endforeach
          </select>
        </div>
        <div class="field full">
          <label for="edit_u_active">Status Akun</label>
          <select class="sel" id="edit_u_active" name="is_active">
            <option value="1">Aktif</option>
            <option value="0">Nonaktif</option>
          </select>
        </div>
      </div>
    </div>
    <footer>
      <button type="button" class="btn ghost" onclick="closeEditUserModal()">Batal</button>
      <button type="submit" class="btn primary">Simpan Perubahan</button>
    </footer>
  </form>
</div>

<!-- MODAL RESET PASSWORD -->
<div class="modal" id="resetPasswordModal" role="dialog" aria-modal="true">
  <form class="dialog" id="resetPasswordForm" method="POST" action="">
    @csrf
    @method('PUT')
    <!-- Pass existing name, email, role to preserve them -->
    <input type="hidden" id="rp_name" name="name">
    <input type="hidden" id="rp_email" name="email">
    <input type="hidden" id="rp_role" name="role">
    <input type="hidden" id="rp_jurusan" name="jurusan_id">
    <input type="hidden" id="rp_active" name="is_active" value="1">
    <header>
      <h3>Reset Password Pengguna</h3>
      <button type="button" class="icon-btn" onclick="closeResetPasswordModal()"><svg class="i" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg></button>
    </header>
    <div class="body">
      <p id="resetModalDesc" style="margin-bottom:14px; font-size:13.5px; color:var(--ink)"></p>
      <div class="field full">
        <label for="rp_password">Password Baru</label>
        <input class="inp" id="rp_password" type="password" name="password" required minlength="6" placeholder="Minimal 6 karakter">
      </div>
    </div>
    <footer>
      <button type="button" class="btn ghost" onclick="closeResetPasswordModal()">Batal</button>
      <button type="submit" class="btn primary">Simpan Password Baru</button>
    </footer>
  </form>
</div>
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
