{{--
    Isi avatar header: foto profil jika ada, huruf awal jika tidak.
    Wadah (ukuran, warna, border-radius, dropdown) tetap milik layout pemanggil —
    partial ini hanya merender isi di dalamnya, sehingga styling tiap layout tidak berubah.

    Pakai:
        @include('partials.avatar', ['user' => $u, 'initial' => $initial])

    - $user   : model User (opsional; default auth()->user())
    - $initial: teks fallback bila foto_profil kosong (opsional; default huruf awal nama)
--}}
@php
    $avatarUser = $user ?? auth()->user();
@endphp

@if ($avatarUser?->foto_profil)
    <img src="{{ $avatarUser->foto_profil_url }}"
         alt="{{ $avatarUser->name }}"
         style="width:100%;height:100%;object-fit:cover;border-radius:inherit;display:block;" />
@else
    {{ $initial ?? strtoupper(mb_substr($avatarUser?->name ?? '?', 0, 1)) }}
@endif
