@props(['value', 'label', 'sub' => null, 'subTone' => null, 'icon' => null, 'hero' => null, 'variant' => 'default', 'count' => null, 'rp' => null])

{{-- variant: default (kartu statistik besar, vertikal) | mini (kartu ringkas halaman daftar)
     subTone: warning | success | info | danger | muted
     count: raw numeric value for counter animation (optional)
     rp: set to "1" if value is Rupiah (adds "Rp " prefix to counter) --}}
@php
    $subToneMap = [
        'warning' => 't-w',
        'success' => 't-s',
        'info' => 't-i',
        'danger' => 'b-red',
        'muted' => 't-muted',
    ];
    $subToneClass = $subToneMap[$subTone] ?? '';
    $counterAttrs = $count !== null ? ' data-n="'.$count.'"'.($rp ? ' data-rp="1"' : '') : '';
@endphp

@if($variant === 'mini')
    <div {{ $attributes->merge(['class' => 'card mini']) }}>
        <small>{{ $label }}</small>
        <b{!! $counterAttrs !!}>{{ $count !== null ? '0' : $value }}</b>
    </div>
@else
    <div {{ $attributes->merge(['class' => 'card stat']) }}>
        @if($icon)
            <div class="ico {{ $hero ? 'hero' : '' }}"><x-sa-icon :name="$icon" :size="21" /></div>
        @endif
        <div class="v"{!! $counterAttrs !!}>{{ $count !== null ? '0' : $value }}</div>
        <div class="l">{{ $label }}</div>
        @if($sub !== null && $sub !== '')
            <span class="tag {{ $subToneClass }}">{{ $sub }}</span>
        @endif
    </div>
@endif
