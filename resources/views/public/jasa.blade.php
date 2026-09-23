@extends('layouts.public')

@section('title', 'Layanan Jasa - TEFA SMKN 4 Tanjungpinang')

@section('content')
<style>
    .page-hero-jasa {
        background: linear-gradient(135deg, #0a215e 0%, #1e3a8a 100%);
        color: #ffffff;
        padding: 50px 20px;
        text-align: center;
    }
    .page-hero-jasa h1 {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 8px;
    }
    .page-hero-jasa p {
        color: #cbd5e1;
        font-size: 15px;
        max-width: 620px;
        margin: 0 auto;
        line-height: 1.6;
    }
    .jasa-grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 24px;
        margin: 40px auto 70px;
        max-width: 1200px;
        padding: 0 20px;
    }
    .jasa-card-item {
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
    .jasa-card-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.08);
        border-color: #cbd5e1;
    }
    .jasa-card-thumb {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: #f1f5f9;
        display: block;
    }
    .jasa-card-icon-placeholder {
        height: 190px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        font-size: 48px;
        border-bottom: 1px solid #f1f5f9;
    }
    .jasa-card-body {
        padding: 22px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    /* --- STYLES MODAL DETAIL INTERAKTIF LAYANAN --- */
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
        background: #10b981;
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
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
    }
    .tefa-btn-primary:hover {
        background: #059669;
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

<div class="page-hero-jasa">
    <h1>Layanan Jasa Kejuruan TeFa</h1>
    <p>Solusi profesional karya siswa dan unit Teaching Factory SMKN 4 Tanjungpinang berstandar industri.</p>
</div>

<div class="container">
    <div class="jasa-grid-container">
        @forelse($services as $service)
            @php
                $kode = strtoupper($service->department->kode ?? '');
                $icon = match($kode) {
                    'RPL' => '💻',
                    'DKV' => '🎨',
                    'ANI', 'PSPT', 'PSTV' => '🎥',
                    'TKJ' => '🔧',
                    'GIM' => '🎮',
                    default => '🤝',
                };

                $serviceData = [
                    'id' => $service->id,
                    'nama' => $service->nama_layanan,
                    'jurusan' => $service->department->nama_jurusan ?? 'Unit Teaching Factory',
                    'harga' => 'Mulai Rp ' . number_format($service->estimasi_harga, 0, ',', '.'),
                    'deskripsi' => $service->deskripsi,
                    'foto' => $service->foto ? asset('storage/' . $service->foto) : null,
                    'icon' => $icon,
                    'order_url' => route('services.order', $service->id),
                    'login_url' => route('login', ['redirect' => route('services.order', $service->id)]),
                ];
            @endphp

            <div class="jasa-card-item" onclick='openServiceModal(@json($serviceData))'>
                @if($service->foto)
                    <img src="{{ asset('storage/' . $service->foto) }}" 
                         alt="{{ $service->nama_layanan }}" 
                         class="jasa-card-thumb"
                         loading="lazy">
                @else
                    <div class="jasa-card-icon-placeholder">
                        <span>{{ $icon }}</span>
                    </div>
                @endif

                <div class="jasa-card-body">
                    <span style="font-size: 12px; font-weight: 700; color: #059669; background: #ecfdf5; padding: 4px 12px; border-radius: 999px; width: fit-content; margin-bottom: 10px; border: 1px solid #a7f3d0;">
                        {{ $serviceData['jurusan'] }}
                    </span>

                    <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 8px; line-height: 1.35;">
                        {{ $service->nama_layanan }}
                    </h3>

                    <p style="font-size: 13.5px; color: #64748b; line-height: 1.5; margin-bottom: 16px; flex: 1;">
                        {{ Str::limit($service->deskripsi, 110) }}
                    </p>

                    <div style="font-size: 18px; font-weight: 800; color: #2563eb; margin-bottom: 16px;">
                        {{ $serviceData['harga'] }}
                    </div>

                    <button type="button" class="tefa-btn-primary" style="width: 100%; border-radius: 12px; padding: 11px;">
                        <span>💬</span> Lihat Detail & Konsultasi
                    </button>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 70px 20px; background: #fff; border-radius: 20px; border: 1px solid #e2e8f0;">
                <div style="font-size: 52px; margin-bottom: 14px;">🤝</div>
                <h3 style="font-size: 19px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">Belum Ada Layanan Jasa</h3>
                <p style="color: #64748b; font-size: 14.5px; max-width: 480px; margin: 0 auto; line-height: 1.6;">
                    Saat ini belum ada data layanan jasa yang aktif. Silakan kembali lagi nanti atau hubungi pihak sekolah.
                </p>
            </div>
        @endforelse
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL POP-UP DETAIL LAYANAN JASA DINAMIS   -->
<!-- ========================================== -->
<div id="service-detail-modal" class="tefa-modal-backdrop" onclick="handleServiceBackdropClick(event)">
    <div class="tefa-modal-container" role="dialog" aria-modal="true" aria-labelledby="modal-serv-name">
        <!-- Header Modal -->
        <div class="tefa-modal-header">
            <h4><span>🛠️</span> Detail Layanan Jasa Kejuruan</h4>
            <button type="button" class="tefa-modal-close-btn" onclick="closeServiceModal()" aria-label="Tutup Modal">
                &times;
            </button>
        </div>

        <!-- Konten Detail Jasa -->
        <div class="tefa-modal-content">
            <img id="modal-serv-img" 
                 src="" 
                 alt="Foto Layanan" 
                 style="width: 100%; height: 230px; object-fit: cover; border-radius: 16px; margin-bottom: 16px; background: #f1f5f9; border: 1px solid #e2e8f0; display: none;">

            <div id="modal-serv-icon" 
                 style="height: 170px; display: flex; align-items: center; justify-content: center; background: #f8fafc; border-radius: 16px; font-size: 56px; margin-bottom: 16px; border: 1px solid #e2e8f0;">
                <span>🤝</span>
            </div>

            <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap; margin-bottom: 10px;">
                <span id="modal-serv-jurusan" style="font-size: 12px; font-weight: 700; color: #059669; background: #ecfdf5; padding: 4px 12px; border-radius: 999px; border: 1px solid #a7f3d0;">
                    Unit Teaching Factory
                </span>
                <span style="font-size: 12px; font-weight: 700; color: #2563eb; background: #eff6ff; padding: 4px 12px; border-radius: 999px; border: 1px solid #bfdbfe;">
                    Dikerjakan Siswa & Teknisi Ahli
                </span>
            </div>

            <h2 id="modal-serv-name" style="font-size: 21px; font-weight: 800; color: #0f172a; margin-bottom: 6px; line-height: 1.3;">
                Nama Layanan
            </h2>

            <div id="modal-serv-price" style="font-size: 20px; font-weight: 800; color: #2563eb; margin-bottom: 18px;">
                Mulai Rp 0
            </div>

            <div style="margin-bottom: 16px;">
                <label style="font-size: 12.5px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">
                    Deskripsi Lengkap & Lingkup Layanan
                </label>
                <div id="modal-serv-desc" class="tefa-desc-scrollable">
                    Deskripsi layanan jasa...
                </div>
            </div>

            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 12px 16px; font-size: 12.5px; color: #166534; display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 18px;">💬</span>
                <span><strong>Konsultasi via WhatsApp:</strong> Pesanan jasa akan tercatat di sistem akun Anda dan langsung dihubungkan ke Admin Jurusan melalui WhatsApp resmi untuk diskusi teknis & penugasan tim.</span>
            </div>
        </div>

        <!-- Footer & Tombol Aksi -->
        <div class="tefa-modal-footer">
            <div class="tefa-modal-footer-actions">
                <button type="button" class="tefa-btn-secondary" onclick="closeServiceModal()">
                    Tutup
                </button>
                <a id="modal-serv-action-btn" href="#" class="tefa-btn-primary">
                    <span>💬</span> Pesan / Konsultasi Layanan
                </a>
            </div>

            <div id="modal-serv-guest-notice" style="display: none; text-align: center; font-size: 12px; color: #b91c1c; font-weight: 600; background: #fef2f2; padding: 6px 12px; border-radius: 8px; border: 1px solid #fecaca;">
                🔒 Silakan login terlebih dahulu untuk melakukan pemesanan
            </div>
        </div>
    </div>
</div>

<script>
    const isUserLoggedIn = {{ auth()->check() ? 'true' : 'false' }};

    function openServiceModal(service) {
        const modal = document.getElementById('service-detail-modal');
        if (!modal) return;

        const imgEl = document.getElementById('modal-serv-img');
        const iconEl = document.getElementById('modal-serv-icon');

        if (service.foto) {
            imgEl.src = service.foto;
            imgEl.alt = service.nama;
            imgEl.style.display = 'block';
            iconEl.style.display = 'none';
        } else {
            imgEl.style.display = 'none';
            iconEl.style.display = 'flex';
            iconEl.querySelector('span').textContent = service.icon || '🤝';
        }

        document.getElementById('modal-serv-jurusan').textContent = service.jurusan;
        document.getElementById('modal-serv-name').textContent = service.nama;
        document.getElementById('modal-serv-price').textContent = service.harga;
        document.getElementById('modal-serv-desc').textContent = service.deskripsi || 'Tidak ada penjelasan detail untuk layanan ini.';

        const actionBtn = document.getElementById('modal-serv-action-btn');
        const guestNotice = document.getElementById('modal-serv-guest-notice');

        if (isUserLoggedIn) {
            guestNotice.style.display = 'none';
            actionBtn.href = service.order_url;
            actionBtn.className = 'tefa-btn-primary';
            actionBtn.style.background = '#10b981';
            actionBtn.innerHTML = '<span>💬</span> Pesan / Konsultasi Layanan';
        } else {
            guestNotice.style.display = 'block';
            actionBtn.href = service.login_url;
            actionBtn.className = 'tefa-btn-primary';
            actionBtn.style.background = '#2563eb';
            actionBtn.innerHTML = '<span>🔑</span> Pesan / Konsultasi (Login)';
        }

        // Tampilkan modal dengan transisi halus
        modal.style.display = 'flex';
        requestAnimationFrame(() => {
            modal.classList.add('is-open');
        });
        document.body.style.overflow = 'hidden';
    }

    function closeServiceModal() {
        const modal = document.getElementById('service-detail-modal');
        if (!modal) return;
        modal.classList.remove('is-open');
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }, 250);
    }

    function handleServiceBackdropClick(e) {
        if (e.target.id === 'service-detail-modal') {
            closeServiceModal();
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeServiceModal();
        }
    });
</script>
@endsection
