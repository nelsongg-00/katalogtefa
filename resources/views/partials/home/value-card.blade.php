{{-- Kartu nilai TEFA (Kreatif / Kompeten / Kolaboratif / Siap Industri). --}}
{{-- Props: $icon (nama file di public/asset/img), $title, $tag, $tagClass, $text --}}
<li>
    <img class="mb-2 h-12 w-12 object-contain"
         src="{{ asset('asset/img/' . $icon) }}"
         alt=""
         width="48"
         height="48">
    <!-- TODO: add image -->
    <h3 class="text-base font-extrabold leading-[1.2] text-brand-navy">{{ $title }}</h3>
    <p class="mb-1 text-xs font-semibold {{ $tagClass }}">{{ $tag }}</p>
    <p class="text-xs text-ink-muted">{{ $text }}</p>
</li>
