{{-- Ubah Biodata Diri + foto profil (form tetap: PATCH profile.update) --}}
<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('patch')

    <h2 class="section__title">Ubah Biodata Diri</h2>

    <div class="biodata">
        <div>
            <dl class="info">
                <div class="info__row">
                    <dt class="info__label">
                        <label for="name">Username</label>
                    </dt>
                    <dd class="info__value">
                        <input id="name"
                               name="name"
                               type="text"
                               class="info__input"
                               value="{{ old('name', $user->name) }}"
                               required
                               autofocus
                               autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" />
                    </dd>
                </div>

                <div class="info__row">
                    <dt class="info__label">
                        <label for="email">Email</label>
                    </dt>
                    <dd class="info__value">
                        <div class="info__inline">
                            <input id="email"
                                   name="email"
                                   type="email"
                                   class="info__input"
                                   value="{{ old('email', $user->email) }}"
                                   required
                                   autocomplete="username" />

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <button type="submit" form="send-verification" class="info__action">verifikasi</button>
                            @endif
                        </div>

                        <x-input-error :messages="$errors->get('email')" />

                        @if (session('status') === 'verification-link-sent')
                            <p class="section__desc">Tautan verifikasi baru telah dikirim ke alamat email Anda.</p>
                        @endif
                    </dd>
                </div>

                <div class="info__row">
                    <dt class="info__label">
                        <label for="phone">No. Telepon</label>
                    </dt>
                    <dd class="info__value">
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
                                <button type="button" class="info__action" onclick="document.getElementById('phone').focus()">tambah</button>
                            @endunless
                        </div>

                        <x-input-error :messages="$errors->get('phone')" />
                    </dd>
                </div>
            </dl>

            <div class="info__actions">
                <button class="btn btn--biodata" type="submit">Simpan Perubahan</button>

                @if (session('status') === 'profile-updated')
                    <span class="save-status">✓ Tersimpan</span>
                @endif
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
