<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Product;
use App\Models\Service;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function profil()
    {
        return view('public.profil');
    }

    public function produk()
    {
        $products = Product::latest()->get();

        return view('public.produk', compact('products'));
    }

    public function jasa()
    {
        $services = Service::with('department')->where('is_active', true)->latest()->get();

        return view('public.jasa', compact('services'));
    }

    public function portofolio()
    {
        $jurusans = Jurusan::where('status_aktif', true)->get();

        $portfolios = [
            [
                'id' => 1,
                'jurusan_code' => 'RPL',
                'jurusan_name' => 'Rekayasa Perangkat Lunak',
                'badge_color' => '#2563eb',
                'badge_bg' => '#eff6ff',
                'badge_border' => '#bfdbfe',
                'title' => 'Aplikasi Presensi RFID & Face Recognition',
                'short_desc' => 'Sistem pencatatan absensi digital terintegrasi perangkat keras RFID dan deteksi wajah berbasis AI.',
                'team' => 'Tim Siswa RPL SMKN 4',
                'mentor' => 'Bu Ratna Sari, S.Kom & Instruktur IoT TEFA',
                'year' => '2025/2026',
                'image_bg' => 'linear-gradient(135deg, #1e3a8a, #0284c7)',
                'icon' => '📱',
                'full_desc' => 'Aplikasi Presensi RFID & Face Recognition merupakan inovasi sistem manajemen kehadiran otomatis yang dirancang oleh siswa RPL untuk lingkungan sekolah dan instansi mitra. Sistem menggabungkan kartu RFID fisik dengan kamera verifikasi wajah berbasis kecerdasan buatan, terhubung langsung ke dashboard web admin secara real-time untuk mencegah kecurangan absensi.',
                'features' => [
                    'Pindai kartu RFID instan < 0.5 detik terhubung ke mikrokontroler ESP32',
                    'Verifikasi wajah ganda untuk validasi identitas pengguna secara otomatis',
                    'Dashboard pemantauan kehadiran real-time berbasis Web Laravel',
                    'Export laporan otomatis format PDF & Excel untuk rekap bulanan',
                    'Notifikasi kehadiran otomatis via bot WhatsApp gateway',
                ],
                'tech_stack' => ['Laravel 11', 'PHP 8.3', 'MySQL', 'ESP32 & RFID RC522', 'Python OpenCV', 'Tailwind CSS'],
            ],
            [
                'id' => 2,
                'jurusan_code' => 'RPL',
                'jurusan_name' => 'Rekayasa Perangkat Lunak',
                'badge_color' => '#2563eb',
                'badge_bg' => '#eff6ff',
                'badge_border' => '#bfdbfe',
                'title' => 'Aplikasi POS Kasir & Inventori UMKM Kuliner',
                'short_desc' => 'Aplikasi kasir terintegrasi cetak struk bluetooth thermal dan analitik omzet penjualan harian.',
                'team' => 'Tim Siswa RPL SMKN 4',
                'mentor' => 'Pak Hendra Wijaya, S.T',
                'year' => '2025/2026',
                'image_bg' => 'linear-gradient(135deg, #0f766e, #0e7490)',
                'icon' => '💻',
                'full_desc' => 'Sistem Point of Sales (POS) berbasis web responsif yang dirancang untuk mendukung digitalisasi UMKM kuliner di Tanjungpinang. Memudahkan kasir mencatat transaksi pesanan meja/take-away, cetak struk instan lewat printer thermal Bluetooth, dan memberikan visualisasi laba-rugi harian untuk pemilik usaha.',
                'features' => [
                    'Antarmuka kasir cepat (fast checkout) dengan dukungan layar sentuh',
                    'Dukungan printer Bluetooth Thermal 58mm & 80mm',
                    'Manajemen stok bahan baku dengan notifikasi saat stok menipis',
                    'Laporan grafik penjualan, produk terlaris, dan rekap shift kasir',
                    'Mode offline-first dengan sinkronisasi cloud berkala',
                ],
                'tech_stack' => ['Vue.js', 'Laravel API', 'PostgreSQL', 'Web Bluetooth API', 'Tailwind CSS'],
            ],
            [
                'id' => 3,
                'jurusan_code' => 'Animasi',
                'jurusan_name' => 'Animasi',
                'badge_color' => '#ea580c',
                'badge_bg' => '#fff7ed',
                'badge_border' => '#fed7aa',
                'title' => 'Film Pendek Animasi 2D "Petualangan Si Kancil Modern"',
                'short_desc' => 'Karya film pendek animasi 2D frame-by-frame dengan karakter dan alur cerita edukasi kearifan lokal.',
                'team' => 'Studio Animasi TEFA SMKN 4',
                'mentor' => 'Bu Lina Marlina, M.Ds',
                'year' => '2025/2026',
                'image_bg' => 'linear-gradient(135deg, #c2410c, #b45309)',
                'icon' => '🎬',
                'full_desc' => 'Film pendek animasi 2 dimensi berdurasi 7 menit yang diproduksi secara komprehensif oleh siswa jurusan Animasi. Mulai dari penulisan naskah, concept art, character sheet, storyboard, background painting, frame-by-frame animating, hingga audio scoring dan compositing.',
                'features' => [
                    'Animasi 2D digital frame-by-frame 24 FPS dengan pergerakan luwes',
                    'Desain karakter orisinal mengadaptasi folklore nusantara berbalut nilai modern',
                    'Visual background lukis digital (digital matte painting) resolusi 4K UHD',
                    'Tata suara orisinal dan dubbing vokal oleh siswa SMKN 4 Tanjungpinang',
                    'Telah tayang pada pameran karya vokasi tingkat provinsi',
                ],
                'tech_stack' => ['Toon Boom Harmony', 'Adobe Animate', 'Clip Studio Paint', 'Adobe After Effects', 'Premiere Pro'],
            ],
            [
                'id' => 4,
                'jurusan_code' => 'Animasi',
                'jurusan_name' => 'Animasi',
                'badge_color' => '#ea580c',
                'badge_bg' => '#fff7ed',
                'badge_border' => '#fed7aa',
                'title' => 'Animasi 3D Iklan Layanan Masyarakat "Jaga Laut Kepri"',
                'short_desc' => 'Animasi 3D sinematik tentang pelestarian terumbu karang dan kebersihan biota laut Kepulauan Riau.',
                'team' => 'Tim 3D Animasi SMKN 4',
                'mentor' => 'Tim Instruktur TEFA Animasi',
                'year' => '2025',
                'image_bg' => 'linear-gradient(135deg, #0369a1, #0284c7)',
                'icon' => '🌊',
                'full_desc' => 'Proyek kampanye lingkungan animasi 3 dimensi yang mengedukasi masyarakat tentang bahaya sampah plastik terhadap ekosistem pesisir laut Tanjungpinang. Menampilkan pemodelan karakter bawah laut yang ekspresif dengan pencahayaan sinematik yang memukau.',
                'features' => [
                    'High-poly 3D character modeling dan rigging ekspresi wajah kompleks',
                    'Simulasi partikel air laut dan underwater lighting effects yang realistis',
                    'Rendering engine Cycles & Eevee dengan optimasi render farm internal',
                    'Desain motion yang dinamis untuk menarik minat generasi muda di media sosial',
                ],
                'tech_stack' => ['Blender 4.0', 'Autodesk Maya', 'Substance Painter', 'DaVinci Resolve', 'Audacity'],
            ],
            [
                'id' => 5,
                'jurusan_code' => 'DKV',
                'jurusan_name' => 'Desain Komunikasi Visual',
                'badge_color' => '#9333ea',
                'badge_bg' => '#faf5ff',
                'badge_border' => '#e9d5ff',
                'title' => 'Identitas Visual & Kemasan Produk UMKM "Kopi Kepri"',
                'short_desc' => 'Rebranding total identitas visual produk kopi lokal meliputi logo, pouch packaging, dan feed media sosial.',
                'team' => 'Tim Studio Kreatif DKV SMKN 4',
                'mentor' => 'Bu Maya Anggraini, S.Sn',
                'year' => '2025/2026',
                'image_bg' => 'linear-gradient(135deg, #6b21a8, #7c3aed)',
                'icon' => '🎨',
                'full_desc' => 'Proyek pendampingan nyata TEFA DKV terhadap pelaku UMKM daerah. Siswa merancang brand guidelines lengkap: pemilihan tipografi khas Melayu modern, palet warna otentik, desain kemasan standing pouch berbahan ramah lingkungan dengan teknik cetak foil emas, serta aset materi promosi Instagram.',
                'features' => [
                    'Buku Brand Guidelines: filosofi logo, palet warna, tipografi, dan do/don\'t',
                    'Desain kemasan standing pouch 250gr & 500gr siap cetak offset (CMYK 300 DPI)',
                    'Desain label botol cold brew dan stiker merchandise premium',
                    'Template feed & story Instagram interaktif untuk promosi digital UMKM',
                ],
                'tech_stack' => ['Adobe Illustrator', 'Adobe Photoshop', 'Figma', 'Adobe InDesign', 'Mockup 3D'],
            ],
            [
                'id' => 6,
                'jurusan_code' => 'DKV',
                'jurusan_name' => 'Desain Komunikasi Visual',
                'badge_color' => '#9333ea',
                'badge_bg' => '#faf5ff',
                'badge_border' => '#e9d5ff',
                'title' => 'Buku Profil Pariwisata & Ilustrasi "Pesona Gurindam"',
                'short_desc' => 'Desain layout editorial majalah dan buku ilustrasi digital mengangkat situs bersejarah Pulau Penyengat.',
                'team' => 'Divisi Editorial & Ilustrasi DKV',
                'mentor' => 'Instruktur Desain Grafis TEFA',
                'year' => '2025',
                'image_bg' => 'linear-gradient(135deg, #4c1d95, #6d28d9)',
                'icon' => '📖',
                'full_desc' => 'Karya publikasi editorial yang memadukan keahlian tata letak (grid system), kurasi fotografi, dan ilustrasi digital tangan bertema arsitektur Istana Kantor dan Masjid Raya Sultan Riau Penyengat. Dikerjakan dengan standar cetak buku profesional jilid hard cover.',
                'features' => [
                    'Master layout 64 halaman dengan tata letak editorial modern & proporsional',
                    '20+ ilustrasi digital orisinal karya siswa pemenang lomba poster daerah',
                    'Koreksi warna foto peninggalan sejarah berstandar gamut cetak art paper',
                ],
                'tech_stack' => ['Adobe InDesign', 'Adobe Illustrator', 'Procreate', 'Adobe Lightroom'],
            ],
            [
                'id' => 7,
                'jurusan_code' => 'TKJ',
                'jurusan_name' => 'Teknik Komputer Jaringan',
                'badge_color' => '#0891b2',
                'badge_bg' => '#ecfeff',
                'badge_border' => '#a5f3fc',
                'title' => 'Infrastruktur Jaringan Hotspot Sekolah & Failover Mikrotik',
                'short_desc' => 'Perancangan jaringan internet berkecepatan tinggi dengan multi-ISP failover dan manajemen bandwidth cerdas.',
                'team' => 'Tim Network Engineer TKJ SMKN 4',
                'mentor' => 'Pak Hendra Wijaya, S.T & Teknisi TEFA TKJ',
                'year' => '2025/2026',
                'image_bg' => 'linear-gradient(135deg, #0e7490, #0891b2)',
                'icon' => '🌐',
                'full_desc' => 'Proyek implementasi nyata infrastruktur jaringan kampus SMKN 4 Tanjungpinang. Siswa mendesain topologi jaringan terpusat menggunakan router Mikrotik CCR Series, sistem proteksi firewall terhadap serangan DDoS, captive portal voucher hotspot terintegrasi database radius, dan switch manage Cisco.',
                'features' => [
                    'Multi-WAN Load Balancing & Automated Failover 2 ISP tanpa putus koneksi',
                    'Sistem Voucher Hotspot dengan limitasi kecepatan per user (Queue Tree)',
                    'Isolasi VLAN terpisah antara ruang kelas, kantor guru, dan lab TEFA',
                    'Pemasangan kabel Fiber Optic antargedung dengan Fusion Splicer terkalibrasi',
                    'Sistem monitoring lalu lintas jaringan real-time menggunakan Zabbix & Dude',
                ],
                'tech_stack' => ['MikroTik RouterOS', 'Cisco Switch IOS', 'Fiber Optic Fusion Splicer', 'Linux Ubuntu Server', 'Zabbix'],
            ],
            [
                'id' => 8,
                'jurusan_code' => 'TKJ',
                'jurusan_name' => 'Teknik Komputer Jaringan',
                'badge_color' => '#0891b2',
                'badge_bg' => '#ecfeff',
                'badge_border' => '#a5f3fc',
                'title' => 'Server Cloud Privat & Network Attached Storage (NAS) TEFA',
                'short_desc' => 'Solusi server cloud mandiri untuk backup data guru dan siswa berkapasitas 8 TB dengan RAID 5 redundancy.',
                'team' => 'Tim Sysadmin TKJ SMKN 4',
                'mentor' => 'Instruktur Server & Cloud TEFA',
                'year' => '2025',
                'image_bg' => 'linear-gradient(135deg, #155e75, #0e7490)',
                'icon' => '🖥️',
                'full_desc' => 'Pembangunan sistem penyimpanan data terpusat menggunakan server open-source berbasis TrueNAS dan Nextcloud. Mengamankan aset digital sekolah dari serangan ransomware dengan enkripsi otomatis, backup berkala, dan hak akses bertingkat.',
                'features' => [
                    'Penyimpanan redundan RAID 5 toleran terhadap kerusakan harddisk',
                    'Akses file aman melalui VPN Wireguard dari luar jaringan sekolah',
                    'Otomatisasi snapshot data per jam untuk mencegah data hilang',
                ],
                'tech_stack' => ['TrueNAS Scale', 'Nextcloud', 'Docker Container', 'Wireguard VPN', 'RAID Controller'],
            ],
            [
                'id' => 9,
                'jurusan_code' => 'PSPT',
                'jurusan_name' => 'Produksi Program Siaran Televisi',
                'badge_color' => '#e11d48',
                'badge_bg' => '#fff1f2',
                'badge_border' => '#fecdd3',
                'title' => 'Program Siaran Talkshow Multicam "Milenial Bicara Vokasi"',
                'short_desc' => 'Produksi siaran talkshow studio lengkap dengan tata kamera 3 sudut, audio mixer, dan live switching.',
                'team' => 'Kru Produksi Studio Siaran PSPT SMKN 4',
                'mentor' => 'Pak Dedi Kurniawan, S.Sn',
                'year' => '2025/2026',
                'image_bg' => 'linear-gradient(135deg, #be123c, #e11d48)',
                'icon' => '🎥',
                'full_desc' => 'Program siaran televisi multi-kamera yang diproduksi secara berkala di Studio TV SMKN 4 Tanjungpinang. Siswa mengelola seluruh peran industri penyiaran: Produser, Sutradara (Program Director), Switcher Operator, Kameramen, Audio Engineer, dan Penata Cahaya studio.',
                'features' => [
                    'Sistem switching siaran langsung 3 kamera (Blackmagic Design ATEM)',
                    'Tata artistik studio berkonsep talkshow industri profesional',
                    'Manajemen tata audio wireless lavalier multi-channel bebas interferensi',
                    'Grafis siaran on-air (lower third, bumper opening, closing credit) dinamis',
                    'Live streaming simultan ke YouTube & media sosial sekolah berkualitas 1080p60',
                ],
                'tech_stack' => ['Blackmagic ATEM Mini Pro', 'Sony Cinema Line Cameras', 'vMix Broadcasting', 'Adobe Audition', 'DaVinci Resolve'],
            ],
            [
                'id' => 10,
                'jurusan_code' => 'PSPT',
                'jurusan_name' => 'Produksi Program Siaran Televisi',
                'badge_color' => '#e11d48',
                'badge_bg' => '#fff1f2',
                'badge_border' => '#fecdd3',
                'title' => 'Dokumenter Sinematik "Jejak Kota Rebah & Warisan Maritim"',
                'short_desc' => 'Film dokumenter sejarah maritim Tanjungpinang dengan sinematografi aerial drone dan color grading sinematik.',
                'team' => 'Tim Dokumenter Eksternal PSPT SMKN 4',
                'mentor' => 'Guru Penyiaran & Sinematografi TEFA',
                'year' => '2025',
                'image_bg' => 'linear-gradient(135deg, #9f1239, #be123c)',
                'icon' => '🎞️',
                'full_desc' => 'Eksplorasi audio-visual mendalam tentang peradaban maritim Kesultanan Riau-Lingga di muara Sungai Carang. Dikerjakan dengan riset historis, wawancara budayawan ternama, pengambilan gambar udara menggunakan drone 4K, dan narasi puitis yang menyentuh hati penonton.',
                'features' => [
                    'Pengambilan gambar sinematik 4K HDR dengan profil warna S-Log3',
                    'Footage udara lanskap pesisir menggunakan drone DJI Cinema',
                    'Color grading profesional terstandarisasi industri penyiaran',
                ],
                'tech_stack' => ['Sony FX3 / FX30', 'DJI Mavic Pro', 'DaVinci Resolve Studio', 'Sennheiser Shotgun Mic'],
            ],
            [
                'id' => 11,
                'jurusan_code' => 'Gim',
                'jurusan_name' => 'Pengembangan Gim',
                'badge_color' => '#16a34a',
                'badge_bg' => '#f0fdf4',
                'badge_border' => '#bbf7d0',
                'title' => 'Game 3D Action-Adventure "Pendekar Gurindam"',
                'short_desc' => 'Game petualangan aksi bertema pendekar silat Kepri dengan pertarungan real-time dan grafis 3D Unreal Engine.',
                'team' => 'Game Studio TEFA SMKN 4 (Jurusan Gim)',
                'mentor' => 'Pak Arif Budiman, M.Kom',
                'year' => '2025/2026',
                'image_bg' => 'linear-gradient(135deg, #15803d, #16a34a)',
                'icon' => '🎮',
                'full_desc' => 'Sebuah video game aksi petualangan orang ketiga (third-person action) yang dikembangkan oleh siswa jurusan Pengembangan Gim. Pemain berperan sebagai pendekar muda yang menjelajahi pulau-pulau legendaris untuk memecahkan misteri prasasti kuno sambil mempraktikkan seni bela diri silat Melayu.',
                'features' => [
                    'Gameplay hack-and-slash dinamis dengan motion capture animasi silat',
                    'Dunia semi-open world yang mereplikasi arsitektur rumah panggung tradisional Melayu',
                    'Sistem quest interaktif, dialog berakar budaya lokal, dan teka-teki logika',
                    'Pengoptimalan rendering Unreal Engine 5 dengan fitur Lumen & Nanite',
                    'Dukungan kontroler gamepad (Xbox/PlayStation) dan keyboard-mouse',
                ],
                'tech_stack' => ['Unreal Engine 5.3', 'Blueprints & C++', 'Blender 3D', 'Substance Painter', 'FMOD Studio Audio'],
            ],
            [
                'id' => 12,
                'jurusan_code' => 'Gim',
                'jurusan_name' => 'Pengembangan Gim',
                'badge_color' => '#16a34a',
                'badge_bg' => '#f0fdf4',
                'badge_border' => '#bbf7d0',
                'title' => 'Game Edukasi Mobile 2D "Petualangan Bahari Si Udang"',
                'short_desc' => 'Game mobile casual edukasi pelestarian terumbu karang dan keanekaragaman biota laut untuk anak-anak.',
                'team' => 'Tim Mobile Game Dev SMKN 4',
                'mentor' => 'Instruktur Unity & Game Design TEFA',
                'year' => '2025',
                'image_bg' => 'linear-gradient(135deg, #047857, #059669)',
                'icon' => '🕹️',
                'full_desc' => 'Game kasual layar sentuh 2D untuk platform Android yang dirancang dengan mekanisme permainan seru dan mendidik. Pemain memandu karakter udang kecil membersihkan sampah di laut sambil menjawab kuis pengetahuan biologi laut berhadiah bintang penghargaan.',
                'features' => [
                    'Mekanisme kontrol sentuh satu jari yang sangat responsif',
                    'Visual 2D ramah anak dengan animasi kartun ceria dan sound effects menyenangkan',
                    '20 level dengan tingkat tantangan yang meningkat secara bertahap',
                    'Papan skor pencapaian lokal dan edukasi ensiklopedia mini ikan Kepri',
                ],
                'tech_stack' => ['Unity 2D Engine', 'C# Scripting', 'Adobe Illustrator', 'Spine 2D Animation', 'Android SDK'],
            ],
        ];

        return view('public.portofolio', compact('jurusans', 'portfolios'));
    }
}
