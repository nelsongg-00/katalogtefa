{{-- Ubah Biodata Diri + foto profil (form tetap: PATCH profile.update).
     Dua mode: lihat (default) & ubah. Ditoggle oleh skrip vanilla di bawah —
     layout role (superadmin/admin/worker) tidak memuat app.js/Alpine. --}}
<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form id="profileBiodataForm" method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('patch')

    <h2 class="section__title">Ubah Biodata Diri</h2>

    @php
        // Buka mode ubah saat validasi gagal agar pesan error terlihat.
        $biodataHasErrors = $errors->has('name') || $errors->has('email')
            || $errors->has('phone') || $errors->has('foto_profil');
    @endphp

    <div class="biodata">
        <div>
            <div class="biodata-form"
                 id="biodataForm"
                 data-editing="{{ $biodataHasErrors ? 'true' : 'false' }}">
                <dl class="info">
                    {{-- Username --}}
                    <div class="info__row">
                        <dt class="info__label">
                            <label for="name">Username</label>
                        </dt>
                        <dd class="info__value">
                            <div class="info__inline" data-view>
                                <span class="info__text">{{ $user->name }}</span>
                                <button type="button"
                                        class="info__action info__action--link"
                                        data-edit-trigger
                                        data-focus="name">ubah</button>
                            </div>
                            <div data-edit>
                                <input id="name"
                                       name="name"
                                       type="text"
                                       class="info__input"
                                       value="{{ old('name', $user->name) }}"
                                       required
                                       autocomplete="name" />
                                <x-input-error :messages="$errors->get('name')" />
                            </div>
                        </dd>
                    </div>

                    {{-- Email --}}
                    <div class="info__row">
                        <dt class="info__label">
                            <label for="email">Email</label>
                        </dt>
                        <dd class="info__value">
                            <div class="info__inline" data-view>
                                <span class="info__text">{{ $user->email }}</span>

                                @if ($user->email_verified_at)
                                    <span class="info__badge">Terverifikasi</span>
                                @else
                                    <button type="submit"
                                            form="send-verification"
                                            class="info__action info__action--link">verifikasi</button>
                                @endif
                            </div>
                            <div data-edit>
                                <input id="email"
                                       name="email"
                                       type="email"
                                       class="info__input"
                                       value="{{ old('email', $user->email) }}"
                                       required
                                       autocomplete="username" />
                                <x-input-error :messages="$errors->get('email')" />
                            </div>

                            @if (session('status') === 'verification-link-sent')
                                <p class="section__desc">Tautan verifikasi baru telah dikirim ke alamat email Anda.</p>
                            @endif
                        </dd>
                    </div>

                    {{-- No. Telepon --}}
                    <div class="info__row">
                        <dt class="info__label">
                            <label for="phone">No. Telepon</label>
                        </dt>
                        <dd class="info__value">
                            <div class="info__inline" data-view>
                                @if ($user->phone)
                                    <span class="info__text">{{ $user->phone }}</span>
                                @else
                                    <span class="info__text info__text--muted">(kosong)</span>
                                    <button type="button"
                                            class="info__action info__action--link"
                                            data-edit-trigger
                                            data-focus="phone">tambah</button>
                                @endif
                            </div>
                            <div data-edit>
                                <div class="info__inline">
                                    <input id="phone"
                                           name="phone"
                                           type="tel"
                                           class="info__input"
                                           value="{{ old('phone', $user->phone) }}"
                                           placeholder="08xxxxxxxxxx"
                                           maxlength="30"
                                           autocomplete="tel" />

                                    @unless ($user->phone)
                                        <button type="button"
                                                class="info__action info__action--link"
                                                data-focus-target="phone">tambah</button>
                                    @endunless
                                </div>
                                <x-input-error :messages="$errors->get('phone')" />
                            </div>
                        </dd>
                    </div>
                </dl>

                <div class="info__actions" data-view>
                    @if (session('status') === 'profile-updated')
                        <span class="save-status">✓ Tersimpan</span>
                    @endif
                </div>

                <div class="info__actions" data-edit>
                    <button class="btn" type="submit">Simpan</button>
                    <button class="btn btn--cancel"
                            type="button"
                            data-edit-cancel>Batal</button>
                </div>
            </div>
        </div>

        <div class="photo">
            @if ($user->foto_profil)
                <img class="photo__avatar-img"
                     src="{{ $user->foto_profil_url }}"
                     alt="Foto profil {{ $user->name }}" />
            @else
                <div class="photo__avatar" aria-hidden="true">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            @endif

            <label class="photo__choose" for="foto_profil">Pilih Foto</label>
            <input class="visually-hidden"
                   id="foto_profil"
                   name="foto_profil"
                   type="file"
                   accept=".jpeg,.jpg,.png" />
            <p class="photo__hint">
                Ukuran gambar: maks. 1 MB<br>
                Format gambar: .JPEG, .PNG
            </p>

            <x-input-error :messages="$errors->get('foto_profil')" />
        </div>
    </div>
</form>

<script>
    (function () {
        const form = document.getElementById('profileBiodataForm');
        const section = document.getElementById('biodataForm');

        if (!form || !section) {
            return;
        }

        // Simpan nilai awal agar "Batal" bisa mengembalikan tanpa menyentuh server.
        const fields = section.querySelectorAll('input');
        fields.forEach(function (field) {
            if (field.type !== 'file') {
                field.dataset.original = field.value;
            }
        });

        function focusField(name) {
            const target = name ? form.querySelector('#' + name) : null;
            if (target) {
                target.focus();
            }
        }

        function setEditing(isEditing, focusName) {
            section.dataset.editing = isEditing ? 'true' : 'false';
            if (isEditing) {
                focusField(focusName);
            }
        }

        section.addEventListener('click', function (event) {
            const trigger = event.target.closest('[data-edit-trigger]');
            if (trigger) {
                setEditing(true, trigger.dataset.focus);
                return;
            }

            const focusOnly = event.target.closest('[data-focus-target]');
            if (focusOnly) {
                focusField(focusOnly.dataset.focusTarget);
                return;
            }

            if (event.target.closest('[data-edit-cancel]')) {
                fields.forEach(function (field) {
                    if (field.type === 'file') {
                        field.value = '';
                    } else if (field.dataset.original !== undefined) {
                        field.value = field.dataset.original;
                    }
                });
                setEditing(false);
            }
        });

        // Memilih foto saat mode lihat → buka mode ubah supaya tombol Simpan muncul.
        const photo = form.querySelector('#foto_profil');
        if (photo) {
            photo.addEventListener('change', function () {
                if (photo.files && photo.files.length > 0) {
                    setEditing(true);
                }
            });
        }
    })();
</script>
