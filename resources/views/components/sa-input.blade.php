@props(['as' => 'input', 'type' => 'text', 'name' => null, 'id' => null, 'value' => null, 'placeholder' => null, 'rows' => null])

@php
    $fieldId = $id ?? $name;
@endphp

@if($as === 'select')
    <select {{ $attributes->merge(['class' => 'sel', 'name' => $name, 'id' => $fieldId]) }}>{{ $slot }}</select>
@elseif($as === 'textarea')
    <textarea {{ $attributes->merge(['class' => 'inp', 'name' => $name, 'id' => $fieldId, 'rows' => $rows ?? 3]) }}>{{ $slot }}</textarea>
@else
    <input {{ $attributes->merge([
        'class' => 'inp',
        'type' => $type,
        'name' => $name,
        'id' => $fieldId,
        'value' => $value,
        'placeholder' => $placeholder,
    ]) }}>
@endif
