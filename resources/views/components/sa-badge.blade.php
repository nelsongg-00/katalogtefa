@props(['tone' => 'gray'])

{{-- tone: primary | blue | green | yellow | red | purple | gray --}}
<span {{ $attributes->merge(['class' => 'badge b-'.$tone]) }}>{{ $slot }}</span>
