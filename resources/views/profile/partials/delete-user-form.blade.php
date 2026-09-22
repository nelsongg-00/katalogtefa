<section>
    <div class="profile-section-title" style="color: #dc2626;">
        <span>⚠️</span> Hapus Akun
    </div>
    <div class="profile-section-desc">
        Setelah akun Anda dihapus, semua data dan riwayat pesanan akan dihapus secara permanen.
    </div>

    <button
        type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="profile-btn-danger"
    >
        Hapus Akun Saya
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" style="padding: 24px;">
            @csrf
            @method('delete')

            <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">
                Apakah Anda yakin ingin menghapus akun ini?
            </h3>

            <p style="font-size: 13.5px; color: #64748b; line-height: 1.6; margin-bottom: 20px;">
                Tindakan ini tidak dapat dibatalkan. Masukkan kata sandi akun Anda untuk mengonfirmasi bahwa Anda benar-benar ingin menghapus akun ini secara permanen.
            </p>

            <div style="margin-bottom: 20px;">
                <label for="password" style="display: block; font-size: 12px; font-weight: 700; color: #334155; text-transform: uppercase; margin-bottom: 6px;">
                    Kata Sandi Konfirmasi
                </label>

                <input
                    id="password"
                    name="password"
                    type="password"
                    class="profile-form-input"
                    style="max-width: 100%;"
                    placeholder="Masukkan Kata Sandi"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1" />
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" 
                        x-on:click="$dispatch('close')" 
                        style="padding: 9px 18px; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer;">
                    Batal
                </button>

                <button type="submit" 
                        style="padding: 9px 18px; background: #dc2626; color: #ffffff; border: none; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer;">
                    Hapus Akun Permanen
                </button>
            </div>
        </form>
    </x-modal>
</section>
