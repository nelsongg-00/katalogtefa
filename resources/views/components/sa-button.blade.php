@props(['variant' => 'ghost', 'size' => null, 'href' => null, 'type' => 'button', 'icon' => null])

@php
    $classes = trim('btn '.$variant.($size ? ' '.$size : ''));
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)<x-sa-icon :name="$icon" />@endif{{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)<x-sa-icon :name="$icon" />@endif{{ $slot }}
    </button>
@endif
