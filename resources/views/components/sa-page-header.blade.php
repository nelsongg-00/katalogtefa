@props(['title', 'subtitle' => null, 'pill' => null])

<div {{ $attributes->merge(['class' => 'page-head']) }}>
    <div>
        <h1>{{ $title }}@if($pill) <span class="pill">{{ $pill }}</span>@endif</h1>
        @if($subtitle)<p>{{ $subtitle }}</p>@endif
    </div>
    @isset($actions)
        <div class="actions">{{ $actions }}</div>
    @endisset
</div>
