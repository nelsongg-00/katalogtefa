{{-- Navbar publik (navbar utama). Dipakai bersama oleh layouts/public.blade.php
     dan halaman login. CSS + JS ikut di dalam partial agar tampil & berperilaku identik
     walau halaman pemanggil tidak memuat @vite (mis. login yang berdiri sendiri).
     Font dipaksa Segoe UI di wrapper .site-header supaya tidak mewarisi font halaman. --}}
<div class="site-header">
<style>
/* Font khusus navbar agar identik dengan layout publik (halaman login memakai font lain). */
.site-header,
.site-header * {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

        /* --- NAVBAR --- */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 50px;
            background-color: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        /* Bagian Kiri (Logo) */
        .navbar-brand { 
            flex: 1; /* Memastikan logo mengambil sepertiga ruang kiri */
            display: flex; 
            align-items: center; 
            gap: 12px; 
            text-decoration: none;
        }
        
        .navbar-brand img { 
            height: 50px; 
            width: auto;
            object-fit: contain;
        }
        
        .brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .brand-text-main {
            color: #0a215e;
            font-weight: 700;
            font-size: 17px;
            letter-spacing: 0.5px;
        }

        .brand-text-sub {
            color: #2563eb;
            font-weight: 600;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        /* Bagian Tengah (Menu Beranda dll) */
        .navbar-center {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .navbar-links {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .navbar-links a { 
            text-decoration: none; 
            color: #1e3a8a;
            font-weight: 600; 
            font-size: 15px;
            transition: 0.2s;
        }
        
        .navbar-links a:hover {
            color: #2563eb;
        }

        .navbar-links a.active { 
            color: #ffffff; 
            background-color: #2563eb; 
            padding: 10px 24px; 
            border-radius: 50px; 
        }

        /* Bagian Kanan (Auth/Login) */
        .navbar-auth { 
            flex: 1; /* Memastikan auth mengambil sepertiga ruang kanan (menyeimbangkan logo) */
            display: flex; 
            justify-content: flex-end; /* Memaksa tombol ke ujung kanan */
            align-items: center; 
            gap: 15px; 
        }
        
        .btn-login {
            text-decoration: none; 
            font-weight: 600; 
            color: #1e3a8a; 
            font-size: 15px;
        }

        .btn-register {
            text-decoration: none;
            font-weight: 600;
            color: #2563eb;
            font-size: 14px;
            padding: 8px 18px;
            border: 1.5px solid #2563eb;
            border-radius: 50px;
            transition: 0.3s;
        }
        
        .btn-register:hover {
            background-color: #2563eb;
            color: #fff;
        }

        /* --- AVATAR DROPDOWN COMPACT STYLES --- */
        .nav-cart-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            color: #475569;
            background: #f1f5f9;
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid #e2e8f0;
        }
        .nav-cart-btn:hover {
            color: #2563eb;
            background: #eff6ff;
            border-color: #bfdbfe;
        }
        .nav-avatar-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 999px;
            padding: 4px 10px 4px 4px;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .nav-avatar-btn:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
        }
        .nav-avatar-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff;
            font-weight: 700;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-transform: uppercase;
        }
        .nav-role-badge {
            font-size: 11px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 999px;
            white-space: nowrap;
        }
        .role-pelanggan { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
        .role-admin { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
        .role-worker { background: #f5f3ff; color: #7c3aed; border: 1px solid #ddd6fe; }
        .role-super { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }

        .nav-popover-card {
            display: none;
            position: absolute;
            right: 0;
            top: calc(100% + 10px);
            width: 250px;
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 15px 35px -5px rgba(15,23,42,0.15), 0 8px 16px -6px rgba(15,23,42,0.08);
            border: 1px solid #e2e8f0;
            z-index: 1000;
            padding: 6px 0;
            text-align: left;
        }
        .nav-popover-caret {
            position: absolute;
            top: -6px;
            right: 20px;
            width: 12px;
            height: 12px;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            border-left: 1px solid #e2e8f0;
            transform: rotate(45deg);
        }
        .nav-popover-header {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .nav-popover-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 16px;
            font-size: 13.5px;
            font-weight: 600;
            color: #334155;
            text-decoration: none;
            transition: 0.15s;
        }
        .nav-popover-item:hover {
            background: #f8fafc;
            color: #0f172a;
        }
        .nav-popover-divider {
            height: 1px;
            background: #f1f5f9;
            margin: 6px 0;
        }
        .nav-popover-logout {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 16px;
            font-size: 13.5px;
            font-weight: 600;
            color: #dc2626;
            background: none;
            border: none;
            cursor: pointer;
            text-align: left;
            transition: 0.15s;
        }
        .nav-popover-logout:hover {
            background: #fef2f2;
            color: #b91c1c;
        }
</style>

    <!-- NAVBAR UTAMA -->
    <nav class="navbar">
        <!-- BAGIAN KIRI: Logo & Tulisan -->
        <a href="{{ route('home') }}" class="navbar-brand">
            <img src="{{ asset('asset/img/logo-smkn4.png') }}" alt="Logo SMKN 4">
            <div class="brand-text">
                <span class="brand-text-main">SMKN 4 TANJUNGPINANG</span>
                <span class="brand-text-sub">KATALOG TEFA</span>
            </div>
        </a>

        <!-- BAGIAN TENGAH: Menu Utama -->
        <div class="navbar-center">
            <div class="navbar-links">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('profil') }}" class="{{ request()->routeIs('profil') ? 'active' : '' }}">Profil Tefa</a>
                <a href="{{ route('produk') }}" class="{{ request()->routeIs('produk') ? 'active' : '' }}">Produk</a>
                <a href="{{ route('jasa') }}" class="{{ request()->routeIs('jasa') ? 'active' : '' }}">Layanan Jasa</a>
                <a href="{{ route('portofolio') }}" class="{{ request()->routeIs('portofolio') ? 'active' : '' }}">Portofolio</a>
            </div>
        </div>

        <!-- BAGIAN KANAN: Auth & Register / Avatar Dropdown -->
        <div class="navbar-auth" style="position: relative;">
            @guest
                <!-- Tampil Jika Belum Login -->
                <a href="{{ route('login') }}" class="btn-login">Masuk</a>
                <a href="{{ route('register') }}" class="btn-register">Daftar</a>
            @endguest
            @auth
                @php
                    $u = auth()->user();
                    $r = $u->role ?? 'pelanggan';
                    $roleLabel = match($r) {
                        'super_admin' => 'Super Admin',
                        'admin_jurusan' => 'Admin Jurusan',
                        'worker' => 'Worker',
                        default => 'Pelanggan',
                    };
                    $badgeClass = match($r) {
                        'super_admin' => 'role-super',
                        'admin_jurusan' => 'role-admin',
                        'worker' => 'role-worker',
                        default => 'role-pelanggan',
                    };
                    $dashRoute = match($r) {
                        'super_admin' => route('superadmin.dashboard'),
                        'admin_jurusan' => route('admin.dashboard'),
                        'worker' => route('worker.dashboard'),
                        default => route('dashboard'),
                    };
                    $initial = strtoupper(substr($u->name, 0, 1));
                @endphp

                <!-- Khusus Pelanggan: Ikon Keranjang Belanja -->
                @if($r === 'pelanggan')
                    <a href="{{ route('client.orders') }}" class="nav-cart-btn" title="Pesanan Saya / Keranjang">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </a>
                @endif

                <!-- Trigger Avatar Dropdown -->
                <button type="button" class="nav-avatar-btn" onclick="togglePublicDropdown(event)" id="public-avatar-trigger">
                    <div class="nav-avatar-circle">
                        @include('partials.avatar', ['user' => $u, 'initial' => $initial])
                    </div>
                    @if($r !== 'pelanggan')
                        <span class="nav-role-badge {{ $badgeClass }}">
                            {{ $roleLabel }}
                        </span>
                    @endif
                    <svg id="public-dropdown-chevron" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="transition: transform 0.2s; color: #64748b;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <!-- Floating Popover Card dengan Caret -->
                <div class="nav-popover-card" id="public-user-popover">
                    <div class="nav-popover-caret"></div>

                    <!-- Header User Ringkas -->
                    <div class="nav-popover-header">
                        <div class="nav-avatar-circle" style="width: 36px; height: 36px; font-size: 14px;">
                            @include('partials.avatar', ['user' => $u, 'initial' => $initial])
                        </div>
                        <div style="overflow: hidden;">
                            <div style="font-weight: 700; font-size: 14px; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $u->name }}</div>
                            <div style="font-size: 11.5px; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 2px;">{{ $u->email }}</div>
                            <span class="nav-role-badge {{ $badgeClass }}">
                                {{ $roleLabel }}
                            </span>
                        </div>
                    </div>

                    <!-- Menu List -->
                    <div style="padding: 4px 0;">
                        @if($r !== 'pelanggan')
                            <a href="{{ $dashRoute }}" class="nav-popover-item">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: #2563eb;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                <span>Dashboard</span>
                            </a>
                        @endif

                        <a href="{{ route('profile.edit') }}" class="nav-popover-item">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: #64748b;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Edit Profil</span>
                        </a>

                        @if($r === 'pelanggan')
                            <a href="{{ route('client.orders') }}" class="nav-popover-item">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: #2563eb;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                <span>Pesanan Saya</span>
                            </a>
                        @endif

                        <a href="{{ route('order.tracking.index') }}" class="nav-popover-item">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: #f59e0b;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            <span>Status Pesanan</span>
                        </a>
                    </div>

                    <div class="nav-popover-divider"></div>

                    <!-- Logout Form -->
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="nav-popover-logout">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </nav>

<script>
    function togglePublicDropdown(event) {
        event.stopPropagation();
        const popover = document.getElementById('public-user-popover');
        const chevron = document.getElementById('public-dropdown-chevron');
        if (popover) {
            const isVisible = popover.style.display === 'block';
            popover.style.display = isVisible ? 'none' : 'block';
            if (chevron) {
                chevron.style.transform = isVisible ? 'rotate(0deg)' : 'rotate(180deg)';
            }
        }
    }

    document.addEventListener('click', function(e) {
        const popover = document.getElementById('public-user-popover');
        const trigger = document.getElementById('public-avatar-trigger');
        if (popover && popover.style.display === 'block') {
            if (!popover.contains(e.target) && (!trigger || !trigger.contains(e.target))) {
                popover.style.display = 'none';
                const chevron = document.getElementById('public-dropdown-chevron');
                if (chevron) chevron.style.transform = 'rotate(0deg)';
            }
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const popover = document.getElementById('public-user-popover');
            if (popover) {
                popover.style.display = 'none';
                const chevron = document.getElementById('public-dropdown-chevron');
                if (chevron) chevron.style.transform = 'rotate(0deg)';
            }
        }
    });
</script>
</div>