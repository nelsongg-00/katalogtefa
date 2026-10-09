@props(['title' => null, 'message' => null, 'icon' => 'doc', 'colspan' => null])

@php ob_start(); @endphp
<div class="empty">
    <div class="empty-ic"><x-sa-icon :name="$icon" :size="20" /></div>
    @if($title)<b>{{ $title }}</b>@endif
    @if($message)<span>{{ $message }}</span>@endif
</div>
@php $inner = ob_get_clean(); @endphp

@if($colspan)
    <tr>
        <td colspan="{{ $colspan }}">{!! $inner !!}</td>
    </tr>
@else
    {!! $inner !!}
@endif
