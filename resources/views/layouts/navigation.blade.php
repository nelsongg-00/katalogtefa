<nav x-data="{ mobileOpen: false, dropdownOpen: false }" class="bg-white border-b border-gray-100 sticky top-0 z-40 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            
            <!-- BAGIAN KIRI: Branding SMKN 4 Tanjungpinang -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-3 text-decoration-none group">
                    <img src="{{ asset('asset/img/logo-smkn4.png') }}" alt="Logo SMKN 4" class="h-10 w-auto object-contain">
                    <div class="flex flex-col leading-tight">
                        <span class="text-sm font-extrabold text-[#0a215e] tracking-wide">SMKN 4 TANJUNGPINANG</span>
                        <span class="text-[11px] font-bold text-blue-600 tracking-wider">KATALOG TEFA</span>
                    </div>
                </a>
            </div>

            <!-- BAGIAN TENGAH: Menu Utama -->
            <div class="hidden md:flex items-center space-x-1 lg:space-x-2">
                <a href="{{ route('home') }}" 
                   class="px-3.5 py-1.5 rounded-full text-sm font-semibold transition duration-150 {{ request()->routeIs('home') ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                    Beranda
                </a>
                <a href="{{ route('profil') }}" 
                   class="px-3.5 py-1.5 rounded-full text-sm font-semibold transition duration-150 {{ request()->routeIs('profil') ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                    Profil Tefa
                </a>
                <a href="{{ route('produk') }}" 
                   class="px-3.5 py-1.5 rounded-full text-sm font-semibold transition duration-150 {{ request()->routeIs('produk') ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                    Produk
                </a>
                <a href="{{ route('jasa') }}" 
                   class="px-3.5 py-1.5 rounded-full text-sm font-semibold transition duration-150 {{ request()->routeIs('jasa') ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                    Layanan Jasa
                </a>
                <a href="{{ route('portofolio') }}" 
                   class="px-3.5 py-1.5 rounded-full text-sm font-semibold transition duration-150 {{ request()->routeIs('portofolio') ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50' }}">
                    Portofolio
                </a>
            </div>

            <!-- BAGIAN KANAN: Auth & Avatar Dropdown Menu -->
            <div class="hidden md:flex md:items-center md:gap-3">
                @guest
                    <!-- Pengunjung Belum Login -->
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-700 hover:text-blue-600 px-3.5 py-1.5 rounded-lg transition duration-150">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-4 py-1.5 rounded-full shadow-sm transition duration-150">
                        Daftar
                    </a>
                @else
                    @php
                        $user = Auth::user();
                        $role = $user->role ?? 'pelanggan';
                        $roleBadgeLabel = match($role) {
                            'super_admin' => 'Super Admin',
                            'admin_jurusan' => 'Admin Jurusan',
                            'worker' => 'Worker',
                            default => 'Pelanggan',
                        };
                        $roleBadgeClass = match($role) {
                            'super_admin' => 'bg-amber-50 text-amber-800 border border-amber-200',
                            'admin_jurusan' => 'bg-blue-50 text-blue-800 border border-blue-200',
                            'worker' => 'bg-purple-50 text-purple-800 border border-purple-200',
                            default => 'bg-emerald-50 text-emerald-800 border border-emerald-200',
                        };
                        $dashboardRoute = match($role) {
                            'super_admin' => route('superadmin.dashboard'),
                            'admin_jurusan' => route('admin.dashboard'),
                            'worker' => route('worker.dashboard'),
                            default => route('dashboard'),
                        };
                        $userInitial = strtoupper(substr($user->name, 0, 1));
                    @endphp

                    <!-- Khusus Pelanggan: Ikon Keranjang Belanja di sebelah kiri avatar -->
                    @if($role === 'pelanggan')
                        <a href="{{ route('client.orders') }}" 
                           class="relative p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full transition duration-150 flex items-center justify-center mr-1" 
                           title="Keranjang / Pesanan Saya">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </a>
                    @endif

                    <!-- Dropdown Avatar Container -->
                    <div class="relative" @click.outside="dropdownOpen = false">
                        <!-- Trigger Button -->
                        <button @click="dropdownOpen = !dropdownOpen" 
                                type="button" 
                                class="flex items-center gap-2 p-1 pl-1.5 pr-2.5 rounded-full border border-gray-200 hover:border-gray-300 hover:bg-gray-50 focus:outline-none transition duration-150 shadow-sm bg-white cursor-pointer">
                            
                            <!-- Avatar Bulat -->
                            <div class="relative">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-bold text-xs flex items-center justify-center shadow-inner select-none">
                                    {{ $userInitial }}
                                </div>
                                @if($role !== 'pelanggan')
                                    <!-- Indikator Role Internal -->
                                    <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full ring-2 ring-white {{ $role === 'super_admin' ? 'bg-amber-500' : ($role === 'admin_jurusan' ? 'bg-blue-600' : 'bg-purple-600') }}"></span>
                                @endif
                            </div>

                            <!-- Label Role / Indikator -->
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full {{ $roleBadgeClass }}">
                                {{ $roleBadgeLabel }}
                            </span>

                            <!-- Caret Chevron Icon -->
                            <svg class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200" 
                                 :class="{ 'rotate-180': dropdownOpen }" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Floating Popover Card dengan Caret di atasnya -->
                        <div x-show="dropdownOpen"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="transform opacity-0 scale-95 -translate-y-1"
                             x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="transform opacity-0 scale-95 -translate-y-1"
                             style="display: none;"
                             class="absolute right-0 mt-2.5 w-64 rounded-xl bg-white shadow-2xl ring-1 ring-black/5 z-50 border border-gray-100 py-1.5 focus:outline-none">
                            
                            <!-- Caret Segitiga Melayang -->
                            <div class="absolute -top-1.5 right-6 w-3 h-3 bg-white border-t border-l border-gray-200 transform rotate-45"></div>

                            <!-- Header Ringkas -->
                            <div class="px-4 py-3 border-b border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-bold text-sm flex items-center justify-center shrink-0 shadow-sm select-none">
                                        {{ $userInitial }}
                                    </div>
                                    <div class="overflow-hidden">
                                        <div class="font-bold text-sm text-gray-900 truncate">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500 truncate mb-1">{{ $user->email }}</div>
                                        <span class="inline-block text-[10.5px] font-bold px-2 py-0.5 rounded-full {{ $roleBadgeClass }}">
                                            {{ $roleBadgeLabel }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Menu Items -->
                            <div class="py-1.5">
                                @if($role !== 'pelanggan')
                                    <!-- Dashboard Khusus Role Internal -->
                                    <a href="{{ $dashboardRoute }}" 
                                       class="flex items-center gap-2.5 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition duration-150">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                        <span>Dashboard</span>
                                    </a>
                                @endif

                                <!-- Edit Profil -->
                                <a href="{{ route('profile.edit') }}" 
                                   class="flex items-center gap-2.5 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition duration-150">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>Edit Profil</span>
                                </a>

                                @if($role === 'pelanggan')
                                    <!-- Pesanan Saya untuk Pelanggan -->
                                    <a href="{{ route('client.orders') }}" 
                                       class="flex items-center gap-2.5 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition duration-150">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                        <span>Pesanan Saya</span>
                                    </a>
                                @endif

                                <!-- Lacak Pesanan -->
                                <a href="{{ route('order.tracking.index') }}" 
                                   class="flex items-center gap-2.5 px-4 py-2 text-sm font-medium text-amber-600 hover:bg-amber-50 hover:text-amber-700 transition duration-150">
                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                    <span>Lacak Pesanan</span>
                                </a>
                            </div>

                            <!-- Garis Pembatas (Divider) -->
                            <div class="border-t border-gray-100 my-1"></div>

                            <!-- Logout Form dengan Proteksi @csrf -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full flex items-center gap-2.5 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 hover:text-red-700 transition duration-150 text-left cursor-pointer">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>

            <!-- Hamburger Button (Mobile) -->
            <div class="-me-2 flex items-center md:hidden">
                <button @click="mobileOpen = !mobileOpen" 
                        type="button"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': mobileOpen, 'inline-flex': !mobileOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !mobileOpen, 'inline-flex': mobileOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Mobile Drawer Menu -->
    <div :class="{'block': mobileOpen, 'hidden': !mobileOpen}" class="hidden md:hidden border-t border-gray-200 bg-white">
        <!-- Menu Navigasi Utama Mobile -->
        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="{{ route('home') }}" 
               class="block px-3 py-2 rounded-md text-base font-semibold {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                Beranda
            </a>
            <a href="{{ route('profil') }}" 
               class="block px-3 py-2 rounded-md text-base font-semibold {{ request()->routeIs('profil') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                Profil Tefa
            </a>
            <a href="{{ route('produk') }}" 
               class="block px-3 py-2 rounded-md text-base font-semibold {{ request()->routeIs('produk') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                Produk
            </a>
            <a href="{{ route('jasa') }}" 
               class="block px-3 py-2 rounded-md text-base font-semibold {{ request()->routeIs('jasa') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                Layanan Jasa
            </a>
            <a href="{{ route('portofolio') }}" 
               class="block px-3 py-2 rounded-md text-base font-semibold {{ request()->routeIs('portofolio') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                Portofolio
            </a>
        </div>

        <!-- Auth / Profil Mobile -->
        <div class="pt-4 pb-3 border-t border-gray-200 px-4">
            @guest
                <div class="space-y-2">
                    <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700">
                        Daftar
                    </a>
                </div>
            @else
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-bold text-sm flex items-center justify-center shrink-0">
                        {{ $userInitial }}
                    </div>
                    <div>
                        <div class="font-bold text-base text-gray-800">{{ $user->name }}</div>
                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                    </div>
                    <span class="ms-auto text-xs font-bold px-2.5 py-0.5 rounded-full {{ $roleBadgeClass }}">
                        {{ $roleBadgeLabel }}
                    </span>
                </div>

                <div class="space-y-1">
                    @if($role !== 'pelanggan')
                        <a href="{{ $dashboardRoute }}" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Dashboard
                        </a>
                    @endif

                    <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Edit Profil
                    </a>

                    @if($role === 'pelanggan')
                        <a href="{{ route('client.orders') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Pesanan Saya
                        </a>
                    @endif

                    <a href="{{ route('order.tracking.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-amber-600 hover:bg-amber-50">
                        Lacak Pesanan
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="pt-1">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-sm font-medium text-red-600 hover:bg-red-50 flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            @endguest
        </div>
    </div>
</nav>
