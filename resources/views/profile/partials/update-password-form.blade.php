{{-- Ubah Kata Sandi (form tetap: PUT password.update) --}}
<section class="password" aria-labelledby="password-title">
    <hr class="card__divider">
    <h2 class="section__title" id="password-title">Ubah Kata Sandi</h2>

    <form class="password__form" method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="field">
            <label class="field__label" for="update_password_current_password">Kata Sandi Saat Ini</label>
            <input class="field__input"
                   id="update_password_current_password"
                   name="current_password"
                   type="password"
                   autocomplete="current-password"
                   placeholder="*************" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" />
        </div>

        <div class="field">
            <label class="field__label" for="update_password_password">Kata Sandi Baru</label>
            <input class="field__input"
                   id="update_password_password"
                   name="password"
                   type="password"
                   autocomplete="new-password"
                   placeholder="Minimal 8 Karakter"
                   minlength="8" />
            <x-input-error :messages="$errors->updatePassword->get('password')" />
        </div>

        <div class="field">
            <label class="field__label" for="update_password_password_confirmation">Konfirmasi Kata Sandi Baru</label>
            <input class="field__input"
                   id="update_password_password_confirmation"
                   name="password_confirmation"
                   type="password"
                   autocomplete="new-password"
                   placeholder="Ulangi Kata Sandi Baru" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" />
        </div>

        <div class="info__actions">
            <button class="btn btn--password" type="submit">Perbarui Kata Sandi</button>

            @if (session('status') === 'password-updated')
                <span class="save-status">✓ Kata sandi berhasil diperbarui</span>
            @endif
        </div>
    </form>
</section>
