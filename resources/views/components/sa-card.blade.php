@props(['title' => null, 'subtitle' => null, 'chip' => null, 'body' => 'default'])

{{-- body: default (padding penuh) | flush (padding kecil, untuk daftar padat) | none (slot langsung, untuk kartu toolbar+tabel) --}}
<section {{ $attributes->merge(['class' => 'card']) }}>
    @if($title || $subtitle || $chip || isset($actions))
        <div class="card-head">
            <div>
                @if($title)<h3>{{ $title }}</h3>@endif
                @if($subtitle)<p>{{ $subtitle }}</p>@endif
            </div>
            <div class="card-head-actions">
                @isset($actions){{ $actions }}@endisset
                @if($chip)<span class="chip">{{ $chip }}</span>@endif
            </div>
        </div>
    @endif

    @if($body === 'none')
        {{ $slot }}
    @elseif($body === 'flush')
        <div class="card-body flush">{{ $slot }}</div>
    @else
        <div class="card-body">{{ $slot }}</div>
    @endif
</section>
