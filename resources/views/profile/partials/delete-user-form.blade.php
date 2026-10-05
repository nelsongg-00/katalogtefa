{{--
    Hapus Akun — bagian ketiga di dalam kartu profil.
    Versi inline (bukan modal Alpine): layout admin/superadmin/worker tidak memuat
    resources/js/app.js sehingga <x-modal> tidak akan terbuka di sana.
--}}
<section class="danger" aria-labelledby="delete-title">
    <hr class="card__divider">
    <h2 class="section__title section__title--danger" id="delete-title">Hapus Akun</h2>
    <p class="section__desc">
        Setelah akun Anda dihapus, semua data dan riwayat pesanan akan dihapus secara permanen.
    </p>

    <form class="password__form"
          method="post"
          action="{{ route('profile.destroy') }}"
          onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini secara permanen?');">
        @csrf
        @method('delete')

        <div class="field">
            <label class="field__label" for="delete_password">Kata Sandi Konfirmasi</label>
            <input class="field__input"
                   id="delete_password"
                   name="password"
                   type="password"
                   autocomplete="current-password"
                   placeholder="Masukkan Kata Sandi"
                   required />
            <x-input-error :messages="$errors->userDeletion->get('password')" />
        </div>

        <button class="btn btn--danger" type="submit">Hapus Akun Permanen</button>
    </form>
</section>
