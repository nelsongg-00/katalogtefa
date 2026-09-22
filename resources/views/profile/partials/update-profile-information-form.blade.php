<section>
    <div class="profile-section-title">
        <span>👤</span> Informasi Profil
    </div>
    <div class="profile-section-desc">
        Perbarui data nama lengkap dan alamat email akun Anda.
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="profile-form-group">
            <label for="name" class="profile-form-label">Nama Lengkap</label>
            <input id="name" 
                   name="name" 
                   type="text" 
                   class="profile-form-input" 
                   value="{{ old('name', $user->name) }}" 
                   required 
                   autofocus 
                   autocomplete="name" />
            <x-input-error class="mt-1" :messages="$errors->get('name')" />
        </div>

        <div class="profile-form-group">
            <label for="email" class="profile-form-label">Alamat Email</label>
            <input id="email" 
                   name="email" 
                   type="email" 
                   class="profile-form-input" 
                   value="{{ old('email', $user->email) }}" 
                   required 
                   autocomplete="username" />
            <x-input-error class="mt-1" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div style="margin-top: 10px; padding: 12px; background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px;">
                    <p style="font-size: 13px; color: #92400e; margin: 0;">
                        Alamat email Anda belum diverifikasi.
                        <button form="send-verification" style="background: none; border: none; font-weight: 700; color: #b45309; text-decoration: underline; cursor: pointer; padding: 0; margin-left: 4px;">
                            Kirim ulang link verifikasi.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p style="margin-top: 6px; font-size: 12.5px; color: #15803d; font-weight: 600;">
                            Tautan verifikasi baru telah dikirim ke alamat email Anda.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div style="display: flex; align-items: center; gap: 14px; margin-top: 24px;">
            <button type="submit" class="profile-btn-save">
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <span style="font-size: 13.5px; font-weight: 700; color: #16a34a; display: flex; align-items: center; gap: 4px;">
                    ✓ Tersimpan
                </span>
            @endif
        </div>
    </form>
</section>
