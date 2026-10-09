@props(['id', 'title' => null, 'titleId' => null, 'formId' => null, 'action' => '', 'method' => 'POST'])

{{-- Markup dialog saja: pemanggilan buka/tutup tetap memakai fungsi JS di halaman masing-masing. --}}
<div {{ $attributes->merge(['class' => 'modal', 'id' => $id, 'role' => 'dialog', 'aria-modal' => 'true']) }}>
    <form class="dialog"
          @if($formId) id="{{ $formId }}" @endif
          method="POST"
          action="{{ $action }}">
        @csrf
        @if(strtoupper($method) !== 'POST')
            @method($method)
        @endif

        <header>
            <h3 @if($titleId) id="{{ $titleId }}" @endif>{{ $title }}</h3>
            @isset($close){{ $close }}@endisset
        </header>

        <div class="body">
            @isset($body){{ $body }}@endisset
        </div>

        <footer>
            @isset($footer){{ $footer }}@endisset
        </footer>
    </form>
</div>
