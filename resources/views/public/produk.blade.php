@extends('layouts.public')

@section('title', 'Produk - Katalog TEFA SMKN 4 Tanjungpinang')

@section('content')
    <div class="page-header">
        <h1>Katalog Produk</h1>
        <p>Temukan berbagai produk unggulan karya siswa SMKN 4 Tanjungpinang</p>
    </div>

    <div class="container">
        <div class="produk-grid">
            @forelse($products as $product)
                <div class="produk-card">
                    <img src="{{ $product->foto ? asset('storage/' . $product->foto) : asset('images/placeholder-product.png') }}" 
                         alt="{{ $product->nama_produk }}" 
                         class="produk-img" 
                         style="object-fit: cover; width: 100%; height: 200px; border-top-left-radius: 12px; border-top-right-radius: 12px; display: block;">
                    
                    <div class="produk-info">
                        <h3>{{ $product->nama_produk }}</h3>
                        
                        @if($product->jurusan)
                            <span style="font-size: 12px; color: #2563eb; background: #eff6ff; padding: 3px 10px; border-radius: 10px; display: inline-block; margin-bottom: 10px; width: fit-content; font-weight: 600;">
                                {{ $product->jurusan->nama_jurusan }}
                            </span>
                        @else
                            <span style="font-size: 12px; color: #2563eb; background: #eff6ff; padding: 3px 10px; border-radius: 10px; display: inline-block; margin-bottom: 10px; width: fit-content; font-weight: 600;">
                                Produk Fisik TEFA
                            </span>
                        @endif

                        <div style="font-size: 12.5px; color: #64748b; margin-bottom: 6px;">
                            Stok: <strong style="color: {{ $product->stok > 0 ? '#10b981' : '#ef4444' }};">{{ $product->stok }}</strong>
                        </div>

                        <p>{{ Str::limit($product->deskripsi, 100) }}</p>

                        <div class="produk-price">Rp {{ number_format($product->harga, 0, ',', '.') }}</div>

                        @auth
                            @if(auth()->user()->role == 'pelanggan')
                                <a href="{{ route('checkout.show', $product->id) }}" class="btn-blue text-center" style="width: 100%; border-radius: 10px; display: block; text-decoration: none;">Pesan Sekarang</a>
                            @else
                                <button disabled class="btn-blue" style="width: 100%; border-radius: 10px; background: #94a3b8; cursor: not-allowed; border: none;">Pesan Sekarang</button>
                            @endif
                        @else
                            <a href="{{ route('checkout.show', $product->id) }}" class="btn-blue text-center" style="width: 100%; border-radius: 10px; display: block; text-decoration: none;">Pesan Sekarang</a>
                        @endauth
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: #fff; border-radius: 16px; border: 1px solid #e2e8f0;">
                    <div style="font-size: 48px; margin-bottom: 12px;">📦</div>
                    <h3 style="font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">Belum Ada Produk Tersedia</h3>
                    <p style="color: #64748b; font-size: 14px; max-width: 480px; margin: 0 auto;">Saat ini katalog produk fisik belum memiliki data produk. Silakan kembali lagi nanti atau hubungi pihak sekolah.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
