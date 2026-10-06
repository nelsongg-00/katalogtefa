{{--
    Kartu profil bersama untuk semua role.
    Dipanggil oleh: profile/edit.blade.php (pelanggan), profile/superadmin.blade.php,
    profile/admin.blade.php, profile/worker.blade.php.

    Navbar & footer TIDAK ditulis di sini — masing-masing layout yang menyediakannya.
    Semua CSS di-scope ke .profile-page / .profile-card agar tidak mengganggu styling layout.
--}}
<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
<link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/400.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/500.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/600.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/700.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/800.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/fontsource/css/open-sauce-sans@5.3.0/900.css" rel="stylesheet">

<style>
    /* ==========================================================
       1. DESIGN TOKENS (di-scope pada .profile-page)
       ========================================================== */
    .profile-page {
        --color-primary: #0a4aa6;
        --color-primary-dark: #00357f;
        --color-action: #00a3ff;
        --color-text: #111111;
        --color-muted: #9a9a9a;
        --color-bg: #fafafa;
        --color-white: #ffffff;
        --color-yellow: #f2b630;
        --color-border: #dcdcdc;
        --color-input-bg: #f1f1f1;
        --color-danger: #dc2626;

        --font-body: "Open Sauce Sans", "Hanken Grotesk", "Helvetica Neue", Arial, sans-serif;
        --fs-xs: 0.8125rem;
        --fs-sm: 0.875rem;
        --fs-base: 0.9375rem;
        --fs-md: 1rem;
        --fs-title: 1.5rem;

        --radius-card: 29px;
        --radius-field: 8px;
        --radius-avatar: 22px;
        --shadow-card: 0 2px 10px rgba(0, 0, 0, 0.06);

        padding: 1.5rem 1rem 5rem;
        background: var(--color-bg);
        color: var(--color-text);
        font-family: var(--font-body);
        font-size: var(--fs-base);
        line-height: 1.5;
    }

    /* ==========================================================
       2. RESET RINGKAS (hanya di dalam halaman profil)
       ========================================================== */
    .profile-page *,
    .profile-page *::before,
    .profile-page *::after { box-sizing: border-box; }

    .profile-page h1,
    .profile-page h2,
    .profile-page p,
    .profile-page dl,
    .profile-page dd,
    .profile-page ul { margin: 0; padding: 0; }

    .profile-page ul { list-style: none; }
    .profile-page img { max-width: 100%; display: block; }
    .profile-page input,
    .profile-page button,
    .profile-page select { font-family: inherit; }
    .profile-page :focus-visible { outline: 3px solid var(--color-yellow); outline-offset: 2px; }

    .profile-page .visually-hidden {
        position: absolute;
        width: 1px;
        height: 1px;
        overflow: hidden;
        clip: rect(0 0 0 0);
        white-space: nowrap;
    }

    /* Pesan validasi (komponen input-error memakai class Tailwind) */
    .profile-page ul.text-sm { margin-top: 6px; font-size: var(--fs-sm); line-height: 1.45; }
    .profile-page .text-red-600 { color: var(--color-danger); }

    /* ==========================================================
       3. PROFILE CARD
       ========================================================== */
    .profile-card {
        max-width: 897px;
        margin: 0 auto;
        padding: 24px 24px 48px;
        border: 1px solid var(--color-border);
        border-radius: var(--radius-card);
        background: var(--color-white);
        box-shadow: var(--shadow-card);
    }

    .card__title { font-size: var(--fs-title); font-weight: 700; line-height: 1.2; }
    .card__subtitle { margin-top: 8px; font-size: var(--fs-base); line-height: 1.3; }
    .card__divider { height: 0; margin: 17px 0 0; border: 0; border-top: 1px solid #000000; }
    .section__title { margin: 30px 0 0; font-size: var(--fs-base); font-weight: 700; line-height: 20px; }
    .section__desc { margin-top: 6px; font-size: var(--fs-sm); color: var(--color-muted); }

    /* ==========================================================
       4. BIODATA + FOTO
       ========================================================== */
    .biodata { display: grid; grid-template-columns: 1fr; gap: var(--space-5, 1.5rem); margin-top: 20px; }

    .info__row {
        display: grid;
        grid-template-columns: 110px 1fr;
        align-items: start;
        min-height: 49px;
        font-size: var(--fs-md);
    }

    .info__label { padding-top: 10px; font-weight: 400; }
    .info__value { display: flex; flex-direction: column; gap: 6px; padding-top: 6px; min-width: 0; }
    .info__inline { display: flex; flex-wrap: wrap; align-items: center; gap: 4px 18px; }
    .info__actions { display: flex; align-items: center; gap: 14px; margin-top: 24px; }

    .info__input {
        width: 100%;
        max-width: 340px;
        height: 35px;
        padding: 0 var(--space-3, 0.75rem);
        border: 1px solid #000000;
        border-radius: var(--radius-field);
        background: var(--color-input-bg);
        font-size: var(--fs-sm);
        color: var(--color-text);
    }

    .info__input::placeholder { color: var(--color-muted); opacity: 1; }

    .info__action {
        padding: 0;
        border: 0;
        background: none;
        font-size: var(--fs-sm);
        color: var(--color-muted);
        text-decoration: underline;
        cursor: pointer;
    }

    .info__action--link { color: var(--color-action); }

    /* Dua mode biodata: lihat (default) vs ubah. Digerakkan data-editing. */
    .biodata-form[data-editing="false"] [data-edit] { display: none; }
    .biodata-form[data-editing="true"] [data-view] { display: none; }

    .info__text { line-height: 1.35; }
    .info__text--muted { color: var(--color-muted); }

    .info__badge {
        display: inline-flex;
        align-items: center;
        padding: 2px 10px;
        border: 1px solid #a7f3d0;
        border-radius: 999px;
        background: #ecfdf5;
        font-size: var(--fs-xs);
        font-weight: 700;
        color: #059669;
        white-space: nowrap;
    }

    .btn--cancel {
        border: 1px solid var(--color-border);
        background: var(--color-white);
        color: var(--color-text);
    }

    .photo { width: 212px; justify-self: start; text-align: center; }

    .photo__avatar {
        display: grid;
        place-items: center;
        width: 160px;
        height: 160px;
        margin: 0 auto;
        border-radius: var(--radius-avatar);
        background: var(--color-action);
        font-size: 4.5rem;
        font-weight: 400;
        line-height: 1;
        color: #000000;
    }

    .photo__avatar-img {
        width: 160px;
        height: 160px;
        margin: 0 auto;
        object-fit: cover;
        border-radius: var(--radius-avatar);
        border: 1px solid var(--color-border);
    }

    .photo__choose { display: inline-block; margin-top: 7px; font-size: var(--fs-sm); color: var(--color-text); text-decoration: underline; cursor: pointer; }

    /* Input file disembunyikan secara visual — pindahkan indikator fokus keyboard ke labelnya. */
    .profile-page:has(#foto_profil:focus-visible) .photo__choose { outline: 3px solid var(--color-yellow); outline-offset: 2px; border-radius: 2px; }
    .photo__hint { margin-top: 20px; text-align: left; font-size: var(--fs-sm); line-height: 1.5; }

    .btn {
        display: inline-flex;
        align-items: center;
        height: 34px;
        padding: 0 11px;
        border: 0;
        border-radius: 8px;
        background: var(--color-action);
        font-size: var(--fs-base);
        color: var(--color-white);
        cursor: pointer;
    }

    .btn--password { margin-top: 15px; }

    .save-status { font-size: var(--fs-sm); font-weight: 700; color: #16a34a; }

    /* ==========================================================
       5. UBAH KATA SANDI
       ========================================================== */
    .password { margin-top: 93px; }
    .password .card__divider { margin-top: 0; }
    .password__form { margin-top: 34px; max-width: 416px; }
    .field { margin-bottom: 30px; }
    .field__label { display: block; margin-bottom: 9px; font-size: var(--fs-md); line-height: 20px; }

    .field__input {
        width: 100%;
        height: 35px;
        padding: 0 var(--space-3, 0.75rem);
        border: 1px solid #000000;
        border-radius: var(--radius-field);
        background: var(--color-input-bg);
        font-size: var(--fs-sm);
        color: var(--color-text);
    }

    .field__input::placeholder { color: var(--color-muted); opacity: 1; }

    /* ==========================================================
       6. BREAKPOINT >= 640px (tablet)
       ========================================================== */
    @media (min-width: 640px) {
        .profile-page { padding: 1.5rem 2rem 5rem; }
        .profile-card { padding: 24px 34px 64px; }
        .info__row { grid-template-columns: 182px 1fr; }
        .biodata { grid-template-columns: 1fr 212px; gap: 2rem; }
        .photo { justify-self: end; }
    }

    /* ==========================================================
       7. BREAKPOINT >= 1024px (desktop)
       ========================================================== */
    @media (min-width: 1024px) {
        .profile-page { padding-top: 40px; }
        .profile-card { padding-bottom: 80px; }
    }
</style>

<div class="profile-page">
    <section class="profile-card" aria-labelledby="profile-title">
        <h1 class="card__title" id="profile-title">Profile Saya</h1>
        <p class="card__subtitle">Kelola informasi profil Anda untuk mengontrol, melindungi dan mengamankan akun</p>
        <hr class="card__divider">

        @include('profile.partials.update-profile-information-form')
        @include('profile.partials.update-password-form')
    </section>
</div>
