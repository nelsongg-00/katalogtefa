@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="pagination-wrapper">
        <div class="pagination-info">
            <span>Menampilkan</span>
            <strong>{{ $paginator->firstItem() }}</strong>
            <span>sampai</span>
            <strong>{{ $paginator->lastItem() }}</strong>
            <span>dari</span>
            <strong>{{ $paginator->total() }}</strong>
            <span>data</span>
        </div>

        <div class="pagination-links">
            {{-- Tombol Sebelumnya (Previous) --}}
            @if ($paginator->onFirstPage())
                <span class="page-btn disabled" aria-disabled="true" aria-label="Sebelumnya">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    <span>Sebelumnya</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="page-btn" aria-label="Sebelumnya">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    <span>Sebelumnya</span>
                </a>
            @endif

            {{-- Nomor-nomor Halaman --}}
            <div class="page-numbers">
                @foreach ($elements as $element)
                    {{-- Separator Titik Tiga --}}
                    @if (is_string($element))
                        <span class="page-num dots">{{ $element }}</span>
                    @endif

                    {{-- Daftar Link Halaman --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="page-num active" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="page-num">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Tombol Selanjutnya (Next) --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="page-btn" aria-label="Selanjutnya">
                    <span>Selanjutnya</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
            @else
                <span class="page-btn disabled" aria-disabled="true" aria-label="Selanjutnya">
                    <span>Selanjutnya</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </span>
            @endif
        </div>
    </nav>
@endif
