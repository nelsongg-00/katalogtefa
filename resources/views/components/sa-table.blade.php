{{-- Pembungkus tabel: scroll horizontal + tabel. Baris kolom ditulis literal di halaman agar kontrak ekspor/JS tetap terbaca. --}}
<div class="tbl-wrap">
    <table {{ $attributes }}>{{ $slot }}</table>
</div>
