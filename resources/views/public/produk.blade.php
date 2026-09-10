@extends('layouts.public')

@section('title', 'Produk - Katalog TEFA SMKN 4 Tanjungpinang')

@section('content')
    <div class="page-header">
        <h1>Katalog Produk</h1>
        <p>Temukan berbagai produk unggulan karya siswa SMKN 4 Tanjungpinang</p>
    </div>

    <div class="container">
        <div class="produk-grid">
            @foreach($produks as $produk)
                <div class="produk-card">
                    @if($produk->foto_produk)
                        <img src="{{ Storage::url($produk->foto_produk) }}" alt="{{ $produk->nama_produk }}" class="produk-img">
                    @else
                        <div class="produk-img" style="display: flex; align-items: center; justify-content: center; background: #e2e8f0; color: #94a3b8; font-size: 40px;">
                            📦
                        </div>
                    @endif
                    <div class="produk-info">
                        <h3>{{ $produk->nama_produk }}</h3>
                        <span style="font-size: 12px; color: #2563eb; background: #eff6ff; padding: 3px 10px; border-radius: 10px; display: inline-block; margin-bottom: 10px; width: fit-content;">
                            {{ $produk->jurusan->nama_jurusan ?? 'Produk Fisik' }}
                        </span>
                        <p>{{ Str::limit($produk->deskripsi, 80) }}</p>
                        <div class="produk-price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
                        
                        @if($produk->tipe == 'Layanan Jasa')
                            <a href="https://wa.me/6281234567890?text=Halo%20saya%20ingin%20memesan%20jasa%20{{ urlencode($produk->nama_produk) }}" target="_blank" class="btn-blue text-center" style="width: 100%; border-radius: 10px; background: #10b981;">Tanya via WhatsApp</a>
                        @else
                            @auth
                                @if(auth()->user()->role == 'pelanggan')
                                    <form action="{{ route('checkout.store', $produk->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-blue" style="width: 100%; border-radius: 10px; cursor: pointer; border: none;">Pesan Sekarang</button>
                                    </form>
                                @else
                                    <button disabled class="btn-blue" style="width: 100%; border-radius: 10px; background: #94a3b8; cursor: not-allowed; border: none;">Pesan Sekarang</button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn-blue text-center" style="width: 100%; border-radius: 10px; display: block;">Login untuk Pesan</a>
                            @endauth
                        @endif
                    </div>
                </div>
            @endforeach

            <!-- DUMMY PRODUK 1 -->
            <div class="produk-card">
                <div class="produk-img" style="display: flex; align-items: center; justify-content: center; background: #e2e8f0; color: #94a3b8; font-size: 40px;">👕</div>
                <div class="produk-info">
                    <h3>Kaos Sablon Custom</h3>
                    <span style="font-size: 12px; color: #2563eb; background: #eff6ff; padding: 3px 10px; border-radius: 10px; display: inline-block; margin-bottom: 10px; width: fit-content;">Desain Komunikasi Visual</span>
                    <p>Kaos katun combed 30s dengan sablon DTF desain custom sesuai permintaan pelanggan.</p>
                    <div class="produk-price">Rp 85.000</div>
                    <a href="{{ route('login') }}" class="btn-blue text-center" style="width: 100%; border-radius: 10px; display: block;">Login untuk Pesan</a>
                </div>
            </div>

            <!-- DUMMY PRODUK 2 -->
            <div class="produk-card">
                <div class="produk-img" style="display: flex; align-items: center; justify-content: center; background: #e2e8f0; color: #94a3b8; font-size: 40px;">☕</div>
                <div class="produk-info">
                    <h3>Mug Printing</h3>
                    <span style="font-size: 12px; color: #2563eb; background: #eff6ff; padding: 3px 10px; border-radius: 10px; display: inline-block; margin-bottom: 10px; width: fit-content;">Desain Komunikasi Visual</span>
                    <p>Mug keramik putih dengan desain cetak sublimasi full color anti luntur.</p>
                    <div class="produk-price">Rp 35.000</div>
                    <a href="{{ route('login') }}" class="btn-blue text-center" style="width: 100%; border-radius: 10px; display: block;">Login untuk Pesan</a>
                </div>
            </div>

            <!-- DUMMY PRODUK 3 -->
            <div class="produk-card">
                <div class="produk-img" style="display: flex; align-items: center; justify-content: center; background: #e2e8f0; color: #94a3b8; font-size: 40px;">🔌</div>
                <div class="produk-info">
                    <h3>Kabel LAN 10 Meter</h3>
                    <span style="font-size: 12px; color: #2563eb; background: #eff6ff; padding: 3px 10px; border-radius: 10px; display: inline-block; margin-bottom: 10px; width: fit-content;">Teknik Komputer Jaringan</span>
                    <p>Kabel UTP Cat6 siap pakai dengan konektor RJ45 yang sudah di-crimping rapi.</p>
                    <div class="produk-price">Rp 50.000</div>
                    <a href="{{ route('login') }}" class="btn-blue text-center" style="width: 100%; border-radius: 10px; display: block;">Login untuk Pesan</a>
                </div>
            </div>

            <!-- DUMMY PRODUK 4 -->
            <div class="produk-card">
                <div class="produk-img" style="display: flex; align-items: center; justify-content: center; background: #e2e8f0; color: #94a3b8; font-size: 40px;">🖼️</div>
                <div class="produk-info">
                    <h3>X-Banner Stand + Cetak</h3>
                    <span style="font-size: 12px; color: #2563eb; background: #eff6ff; padding: 3px 10px; border-radius: 10px; display: inline-block; margin-bottom: 10px; width: fit-content;">Desain Komunikasi Visual</span>
                    <p>Paket lengkap X-Banner ukuran 60x160 cm termasuk tiang penyangga dan cetakan hires.</p>
                    <div class="produk-price">Rp 120.000</div>
                    <a href="{{ route('login') }}" class="btn-blue text-center" style="width: 100%; border-radius: 10px; display: block;">Login untuk Pesan</a>
                </div>
            </div>

        </div>
    </div>
@endsection
