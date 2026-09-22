<section>
    <div class="profile-section-title">
        <span>🔒</span> Keamanan & Kata Sandi
    </div>
    <div class="profile-section-desc">
        Pastikan akun Anda menggunakan kata sandi yang aman untuk melindungi data pesanan Anda.
    </div>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="profile-form-group">
            <label for="update_password_current_password" class="profile-form-label">Kata Sandi Saat Ini</label>
            <input id="update_password_current_password" 
                   name="current_password" 
                   type="password" 
                   class="profile-form-input" 
                   autocomplete="current-password" 
                   placeholder="••••••••" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
        </div>

        <div class="profile-form-group">
            <label for="update_password_password" class="profile-form-label">Kata Sandi Baru</label>
            <input id="update_password_password" 
                   name="password" 
                   type="password" 
                   class="profile-form-input" 
                   autocomplete="new-password" 
                   placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
        </div>

        <div class="profile-form-group">
            <label for="update_password_password_confirmation" class="profile-form-label">Konfirmasi Kata Sandi Baru</label>
            <input id="update_password_password_confirmation" 
                   name="password_confirmation" 
                   type="password" 
                   class="profile-form-input" 
                   autocomplete="new-password" 
                   placeholder="Ulangi kata sandi baru" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
        </div>

        <div style="display: flex; align-items: center; gap: 14px; margin-top: 24px;">
            <button type="submit" class="profile-btn-save">
                Perbarui Kata Sandi
            </button>

            @if (session('status') === 'password-updated')
                <span style="font-size: 13.5px; font-weight: 700; color: #16a34a; display: flex; align-items: center; gap: 4px;">
                    ✓ Kata sandi berhasil diperbarui
                </span>
            @endif
        </div>
    </form>
</section>
