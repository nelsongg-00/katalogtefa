@extends('layouts.public')

@section('title', 'Katalog Produk - TEFA SMKN 4 Tanjungpinang')

@section('content')
<style>
    .page-hero-produk {
        background: linear-gradient(135deg, #0a215e 0%, #1e3a8a 100%);
        color: #ffffff;
        padding: 50px 20px;
        text-align: center;
    }
    .page-hero-produk h1 {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 8px;
    }
    .page-hero-produk p {
        color: #cbd5e1;
        font-size: 15px;
        max-width: 600px;
        margin: 0 auto;
    }
    .produk-grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 24px;
        margin: 40px auto 70px;
        max-width: 1200px;
        padding: 0 20px;
    }
    .produk-card-item {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        display: flex;
        flex-direction: column;
        cursor: pointer;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }
    .produk-card-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.08);
        border-color: #cbd5e1;
    }
    .produk-card-thumb {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: #f1f5f9;
        display: block;
    }
    .produk-card-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    /* --- STYLES MODAL DETAIL INTERAKTIF --- */
    .tefa-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 18px;
        opacity: 0;
        transition: opacity 0.25s ease;
    }
    .tefa-modal-backdrop.is-open {
        opacity: 1;
    }
    .tefa-modal-container {
        background: #ffffff;
        border-radius: 22px;
        width: 100%;
        max-width: 620px;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.28);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        transform: scale(0.94) translateY(12px);
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .tefa-modal-backdrop.is-open .tefa-modal-container {
        transform: scale(1) translateY(0);
    }
    .tefa-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 24px;
        border-bottom: 1px solid #f1f5f9;
        background: #ffffff;
    }
    .tefa-modal-header h4 {
        font-size: 15px;
        font-weight: 700;
        color: #1e3a8a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .tefa-modal-close-btn {
        background: #f1f5f9;
        border: none;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        color: #64748b;
        font-size: 18px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
        line-height: 1;
    }
    .tefa-modal-close-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .tefa-modal-content {
        padding: 24px;
        overflow-y: auto;
        flex: 1;
    }
    .tefa-modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #f1f5f9;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .tefa-modal-footer-actions {
        display: flex;
        gap: 12px;
        align-items: center;
    }
    .tefa-btn-secondary {
        background: #e2e8f0;
        color: #334155;
        border: none;
        padding: 12px 20px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: background 0.15s ease;
    }
    .tefa-btn-secondary:hover {
        background: #cbd5e1;
    }
    .tefa-btn-primary {
        background: #2563eb;
        color: #ffffff;
        border: none;
        padding: 12px 22px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        flex: 1;
        cursor: pointer;
        transition: background 0.15s ease, transform 0.15s ease;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }
    .tefa-btn-primary:hover {
        background: #1d4ed8;
    }
    .tefa-btn-disabled {
        background: #94a3b8 !important;
        cursor: not-allowed !important;
        box-shadow: none !important;
        pointer-events: none;
    }
    .tefa-desc-scrollable {
        max-height: 160px;
        overflow-y: auto;
        background: #f8fafc;
        border-radius: 12px;
        padding: 14px 16px;
        border: 1px solid #e2e8f0;
        font-size: 13.5px;
        color: #475569;
        line-height: 1.6;
        white-space: pre-line;
    }
</style>

<div class="page-hero-produk">
    <h1>Katalog Produk Unggulan</h1>
    <p>Temukan berbagai produk fisik karya inovasi siswa dan unit Teaching Factory SMKN 4 Tanjungpinang.</p>
</div>

<div class="container">
    <div class="produk-grid-container">
        @forelse($products as $product)
            @php
                $productData = [
                    'id' => $product->id,
                    'nama' => $product->nama_produk,
                    'jurusan' => $product->jurusan->nama_jurusan ?? 'Unit Teaching Factory',
                    'harga' => 'Rp ' . number_format($product->harga, 0, ',', '.'),
                    'stok' => (int) $product->stok,
                    'deskripsi' => $product->deskripsi,
                    'foto' => $product->foto ? asset('storage/' . $product->foto) : asset('images/placeholder-product.png'),
                    'checkout_url' => route('checkout.show', $product->id),
                    'login_url' => route('login', ['redirect' => route('checkout.show', $product->id)]),
                ];
            @endphp

            <div class="produk-card-item" onclick='openProductModal(@json($productData))'>
                <img src="{{ $productData['foto'] }}" 
                     alt="{{ $product->nama_produk }}" 
                     class="produk-card-thumb"
                     loading="lazy">
                
                <div class="produk-card-body">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; gap: 8px;">
                        <span style="font-size: 11.5px; color: #2563eb; background: #eff6ff; padding: 4px 10px; border-radius: 8px; font-weight: 700; border: 1px solid #dbeafe;">
                            {{ $productData['jurusan'] }}
                        </span>
                        <span style="font-size: 12px; font-weight: 700; color: {{ $product->stok > 0 ? '#059669' : '#dc2626' }};">
                            {{ $product->stok > 0 ? 'Stok: ' . $product->stok : 'Habis' }}
                        </span>
                    </div>

                    <h3 style="font-size: 17px; font-weight: 800; color: #0f172a; margin-bottom: 8px; line-height: 1.35;">
                        {{ $product->nama_produk }}
                    </h3>

                    <p style="font-size: 13px; color: #64748b; line-height: 1.5; margin-bottom: 16px; flex: 1;">
                        {{ Str::limit($product->deskripsi, 90) }}
                    </p>

                    <div style="font-size: 18px; font-weight: 800; color: #2563eb; margin-bottom: 14px;">
                        {{ $productData['harga'] }}
                    </div>

                    <button type="button" class="tefa-btn-primary" style="width: 100%; border-radius: 10px; padding: 10px;">
                        <span>🛒</span> Lihat Detail & Pesan
                    </button>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 70px 20px; background: #fff; border-radius: 20px; border: 1px solid #e2e8f0;">
                <div style="font-size: 52px; margin-bottom: 14px;">📦</div>
                <h3 style="font-size: 19px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">Belum Ada Produk Tersedia</h3>
                <p style="color: #64748b; font-size: 14.5px; max-width: 480px; margin: 0 auto; line-height: 1.6;">
                    Saat ini katalog produk fisik sedang dalam pembaruan inventaris. Silakan kembali lagi nanti atau hubungi unit produksi sekolah.
                </p>
            </div>
        @endforelse
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL POP-UP DETAIL PRODUK FISIK DINAMIS  -->
<!-- ========================================== -->
<div id="product-detail-modal" class="tefa-modal-backdrop" onclick="handleProductBackdropClick(event)">
    <div class="tefa-modal-container" role="dialog" aria-modal="true" aria-labelledby="modal-prod-name">
        <!-- Header Modal -->
        <div class="tefa-modal-header">
            <h4><span>📦</span> Detail Produk Fisik</h4>
            <button type="button" class="tefa-modal-close-btn" onclick="closeProductModal()" aria-label="Tutup Modal">
                &times;
            </button>
        </div>

        <!-- Konten Detail Produk -->
        <div class="tefa-modal-content">
            <img id="modal-prod-img" 
                 src="" 
                 alt="Foto Produk" 
                 style="width: 100%; height: 240px; object-fit: cover; border-radius: 16px; margin-bottom: 16px; background: #f1f5f9; border: 1px solid #e2e8f0;">

            <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap; margin-bottom: 10px;">
                <span id="modal-prod-jurusan" style="font-size: 12px; font-weight: 700; color: #2563eb; background: #eff6ff; padding: 4px 12px; border-radius: 999px; border: 1px solid #bfdbfe;">
                    Jurusan
                </span>
                <span id="modal-prod-stok-badge" style="font-size: 12px; font-weight: 700; padding: 4px 12px; border-radius: 999px;">
                    Stok
                </span>
            </div>

            <h2 id="modal-prod-name" style="font-size: 21px; font-weight: 800; color: #0f172a; margin-bottom: 6px; line-height: 1.3;">
                Nama Produk
            </h2>

            <div id="modal-prod-price" style="font-size: 22px; font-weight: 800; color: #2563eb; margin-bottom: 18px;">
                Rp 0
            </div>

            <div style="margin-bottom: 16px;">
                <label style="font-size: 12.5px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">
                    Deskripsi Lengkap
                </label>
                <div id="modal-prod-desc" class="tefa-desc-scrollable">
                    Deskripsi produk...
                </div>
            </div>

            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 12px 16px; font-size: 12.5px; color: #166534; display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 18px;">📍</span>
                <span><strong>Pengambilan & COD:</strong> Pesanan produk fisik diambil langsung di Lab/Unit Teaching Factory sekolah dengan pembayaran tunai di tempat.</span>
            </div>
        </div>

        <!-- Footer & Tombol Aksi -->
        <div class="tefa-modal-footer">
            <div class="tefa-modal-footer-actions">
                <button type="button" class="tefa-btn-secondary" onclick="closeProductModal()">
                    Tutup
                </button>
                <a id="modal-prod-action-btn" href="#" class="tefa-btn-primary">
                    <span>🛒</span> Pesan Sekarang (Checkout)
                </a>
            </div>

            <div id="modal-prod-guest-notice" style="display: none; text-align: center; font-size: 12px; color: #b91c1c; font-weight: 600; background: #fef2f2; padding: 6px 12px; border-radius: 8px; border: 1px solid #fecaca;">
                🔒 Silakan login terlebih dahulu untuk melakukan pemesanan
            </div>
        </div>
    </div>
</div>

<script>
    const isUserLoggedIn = {{ auth()->check() ? 'true' : 'false' }};

    function openProductModal(product) {
        const modal = document.getElementById('product-detail-modal');
        if (!modal) return;

        // Pasang data ke elemen modal
        document.getElementById('modal-prod-img').src = product.foto;
        document.getElementById('modal-prod-img').alt = product.nama;
        document.getElementById('modal-prod-jurusan').textContent = product.jurusan;
        document.getElementById('modal-prod-name').textContent = product.nama;
        document.getElementById('modal-prod-price').textContent = product.harga;
        document.getElementById('modal-prod-desc').textContent = product.deskripsi || 'Tidak ada keterangan detail untuk produk ini.';

        // Status Stok
        const stokBadge = document.getElementById('modal-prod-stok-badge');
        const actionBtn = document.getElementById('modal-prod-action-btn');
        const guestNotice = document.getElementById('modal-prod-guest-notice');

        if (product.stok > 0) {
            stokBadge.textContent = 'Stok: ' + product.stok + ' unit';
            stokBadge.style.background = '#ecfdf5';
            stokBadge.style.color = '#059669';
            stokBadge.style.border = '1px solid #a7f3d0';
        } else {
            stokBadge.textContent = 'Stok Habis';
            stokBadge.style.background = '#fef2f2';
            stokBadge.style.color = '#dc2626';
            stokBadge.style.border = '1px solid #fecaca';
        }

        // Ketentuan Login & Aksi Checkout
        if (isUserLoggedIn) {
            guestNotice.style.display = 'none';
            if (product.stok > 0) {
                actionBtn.href = product.checkout_url;
                actionBtn.className = 'tefa-btn-primary';
                actionBtn.innerHTML = '<span>🛒</span> Pesan Sekarang (Checkout)';
            } else {
                actionBtn.href = 'javascript:void(0)';
                actionBtn.className = 'tefa-btn-primary tefa-btn-disabled';
                actionBtn.innerHTML = '<span>✕</span> Stok Habis';
            }
        } else {
            guestNotice.style.display = 'block';
            actionBtn.href = product.login_url;
            actionBtn.className = 'tefa-btn-primary';
            actionBtn.innerHTML = '<span>🔑</span> Pesan Sekarang (Login)';
        }

        // Tampilkan modal dengan transisi halus
        modal.style.display = 'flex';
        requestAnimationFrame(() => {
            modal.classList.add('is-open');
        });
        document.body.style.overflow = 'hidden';
    }

    function closeProductModal() {
        const modal = document.getElementById('product-detail-modal');
        if (!modal) return;
        modal.classList.remove('is-open');
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }, 250);
    }

    function handleProductBackdropClick(e) {
        if (e.target.id === 'product-detail-modal') {
            closeProductModal();
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeProductModal();
        }
    });
</script>
@endsection
