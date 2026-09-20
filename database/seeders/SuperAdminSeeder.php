<?php

namespace Database\Seeders;

use App\Models\DetailPesanan;
use App\Models\Jurusan;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Enam Jurusan Resmi TeFa
        $jurusansData = [
            [
                'nama_jurusan' => 'Rekayasa Perangkat Lunak',
                'slug' => 'rekayasa-perangkat-lunak',
                'kode' => 'RPL',
                'deskripsi' => 'Pengembangan perangkat lunak, aplikasi web, mobile, dan sistem informasi berbasis industri.',
                'deskripsi_profil' => 'Fokus pada pemrograman web, aplikasi mobile, dan rekayasa perangkat lunak modern.',
                'kepala_jurusan' => 'Bu Ratna Sari, S.Kom',
                'status_aktif' => true,
                'lokasi_pengambilan' => 'Lab Komputer & Rekayasa Perangkat Lunak',
            ],
            [
                'nama_jurusan' => 'Teknik Komputer Jaringan',
                'slug' => 'teknik-komputer-jaringan',
                'kode' => 'TKJ',
                'deskripsi' => 'Infrastruktur jaringan komputer, instalasi server, cloud & network security, serta instalasi fiber optic.',
                'deskripsi_profil' => 'Fokus pada administrasi server, instalasi jaringan komputer, dan cyber security.',
                'kepala_jurusan' => 'Pak Hendra Wijaya, S.T',
                'status_aktif' => true,
                'lokasi_pengambilan' => 'Lab Jaringan & Server TKJ',
            ],
            [
                'nama_jurusan' => 'Desain Komunikasi Visual',
                'slug' => 'desain-komunikasi-visual',
                'kode' => 'DKV',
                'deskripsi' => 'Desain grafis, branding identitas visual, percetakan digital kreatif, fotografi, dan media promosi.',
                'deskripsi_profil' => 'Fokus pada branding visual, packaging produk, dan desain grafis profesional.',
                'kepala_jurusan' => 'Bu Maya Anggraini, S.Sn',
                'status_aktif' => true,
                'lokasi_pengambilan' => 'Lab Desain Grafis DKV',
            ],
            [
                'nama_jurusan' => 'Produksi Program Siaran Televisi',
                'slug' => 'produksi-program-siaran-televisi',
                'kode' => 'PSPT',
                'deskripsi' => 'Produksi siaran program TV, liputan berita, editing video dokumenter, audio broadcasting, dan streaming.',
                'deskripsi_profil' => 'Fokus pada tata kamera, penulisan skenario, editing video, dan produksi penyiaran TV.',
                'kepala_jurusan' => 'Pak Dedi Kurniawan, S.Sn',
                'status_aktif' => true,
                'lokasi_pengambilan' => 'Studio Siaran & Editing PSTV',
            ],
            [
                'nama_jurusan' => 'Animasi',
                'slug' => 'animasi',
                'kode' => 'ANI',
                'deskripsi' => 'Produksi animasi 2D/3D, karakter rigging, visual effects, storyboard, dan aset gerak digital.',
                'deskripsi_profil' => 'Fokus pada animasi 2 dimensi dan 3 dimensi serta visual effect sinematik.',
                'kepala_jurusan' => 'Bu Lina Marlina, M.Ds',
                'status_aktif' => true,
                'lokasi_pengambilan' => 'Lab Animasi 2D/3D',
            ],
            [
                'nama_jurusan' => 'Pengembangan Gim',
                'slug' => 'pengembangan-gim',
                'kode' => 'GIM',
                'deskripsi' => 'Perancangan game play, pemrograman game engine, pemodelan aset game interaktif, dan VR/AR simulation.',
                'deskripsi_profil' => 'Fokus pada pemrograman engine gim, perancangan level desain, dan media interaktif.',
                'kepala_jurusan' => 'Pak Arif Budiman, M.Kom',
                'status_aktif' => true,
                'lokasi_pengambilan' => 'Game Dev & VR Lab',
            ],
        ];

        $jurusanModels = [];
        foreach ($jurusansData as $data) {
            $jurusanModels[$data['kode']] = Jurusan::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        // 2. Akun Super Admin
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'jurusan_id' => null,
                'is_active' => true,
            ]
        );

        // 3. Enam Akun Admin Jurusan (1 per jurusan)
        $adminJurusanData = [
            ['email' => 'adminse@example.com', 'name' => 'Admin RPL', 'kode' => 'RPL'],
            ['email' => 'admintkj@example.com', 'name' => 'Admin TKJ', 'kode' => 'TKJ'],
            ['email' => 'admindkv@example.com', 'name' => 'Admin DKV', 'kode' => 'DKV'],
            ['email' => 'adminpspt@example.com', 'name' => 'Admin PSPT', 'kode' => 'PSPT'],
            ['email' => 'adminanimasi@example.com', 'name' => 'Admin Animasi', 'kode' => 'ANI'],
            ['email' => 'admingame@example.com', 'name' => 'Admin Game Dev', 'kode' => 'GIM'],
        ];

        foreach ($adminJurusanData as $adm) {
            User::updateOrCreate(
                ['email' => $adm['email']],
                [
                    'name' => $adm['name'],
                    'password' => Hash::make('password'),
                    'role' => 'admin_jurusan',
                    'jurusan_id' => $jurusanModels[$adm['kode']]->id,
                    'is_active' => true,
                ]
            );
        }

        // 4. Akun Worker Dummy Terdistribusi
        $workersData = [
            ['email' => 'worker.rpl1@example.com', 'name' => 'Rizky Pratama (RPL)', 'kode' => 'RPL'],
            ['email' => 'worker.rpl2@example.com', 'name' => 'Salsa Nabila (RPL)', 'kode' => 'RPL'],
            ['email' => 'worker.tkj1@example.com', 'name' => 'Dimas Aditya (TKJ)', 'kode' => 'TKJ'],
            ['email' => 'worker.dkv1@example.com', 'name' => 'Putri Amelia (DKV)', 'kode' => 'DKV'],
            ['email' => 'worker.pspt1@example.com', 'name' => 'Bayu Ramadhan (PSPT)', 'kode' => 'PSPT'],
            ['email' => 'worker.ani1@example.com', 'name' => 'Nadia Zahrani (Animasi)', 'kode' => 'ANI'],
            ['email' => 'worker.gim1@example.com', 'name' => 'Fajar Nugroho (Gim)', 'kode' => 'GIM'],
        ];

        foreach ($workersData as $wk) {
            User::updateOrCreate(
                ['email' => $wk['email']],
                [
                    'name' => $wk['name'],
                    'password' => Hash::make('password'),
                    'role' => 'worker',
                    'jurusan_id' => $jurusanModels[$wk['kode']]->id,
                    'is_active' => true,
                ]
            );
        }

        // 5. Akun Pelanggan Dummy
        $clientsData = [
            ['email' => 'budi.santoso@example.com', 'name' => 'Budi Santoso'],
            ['email' => 'citra.lestari@example.com', 'name' => 'Citra Lestari'],
            ['email' => 'maju.bersama@example.com', 'name' => 'CV Maju Bersama'],
            ['email' => 'sinar.baru@example.com', 'name' => 'Toko Sinar Baru'],
        ];

        $clientModels = [];
        foreach ($clientsData as $c) {
            $clientModels[] = User::updateOrCreate(
                ['email' => $c['email']],
                [
                    'name' => $c['name'],
                    'password' => Hash::make('password'),
                    'role' => 'pelanggan',
                    'jurusan_id' => null,
                    'is_active' => true,
                ]
            );
        }

        // 6. Produk Sampel Tiap Jurusan (Fisik & Jasa)
        $sampleProducts = [
            ['kode' => 'RPL', 'nama' => 'Website Company Profile', 'tipe' => 'Layanan Jasa', 'harga' => 2500000],
            ['kode' => 'RPL', 'nama' => 'Aplikasi Kasir POS', 'tipe' => 'Produk Fisik', 'harga' => 3500000],
            ['kode' => 'TKJ', 'nama' => 'Instalasi Jaringan LAN Kantor', 'tipe' => 'Layanan Jasa', 'harga' => 4200000],
            ['kode' => 'TKJ', 'nama' => 'Setup Mikrotik Router & Wi-Fi', 'tipe' => 'Layanan Jasa', 'harga' => 1200000],
            ['kode' => 'DKV', 'nama' => 'Desain Logo & Brand Identity', 'tipe' => 'Layanan Jasa', 'harga' => 1500000],
            ['kode' => 'DKV', 'nama' => 'Merchandise Kaos Sablon (12 pcs)', 'tipe' => 'Produk Fisik', 'harga' => 960000],
            ['kode' => 'PSPT', 'nama' => 'Video Profil Perusahaan 4K', 'tipe' => 'Layanan Jasa', 'harga' => 3800000],
            ['kode' => 'PSPT', 'nama' => 'Live Streaming Multi-Camera Event', 'tipe' => 'Layanan Jasa', 'harga' => 5000000],
            ['kode' => 'ANI', 'nama' => 'Video Animasi Explainer 60 Detik', 'tipe' => 'Layanan Jasa', 'harga' => 4500000],
            ['kode' => 'ANI', 'nama' => 'Aset Karakter 3D Game Ready', 'tipe' => 'Produk Fisik', 'harga' => 850000],
            ['kode' => 'GIM', 'nama' => 'Game Edukasi Interaktif Android', 'tipe' => 'Layanan Jasa', 'harga' => 6500000],
            ['kode' => 'GIM', 'nama' => 'Modul Game Interaktif Pembelajaran', 'tipe' => 'Produk Fisik', 'harga' => 1200000],
        ];

        $productModels = [];
        foreach ($sampleProducts as $sp) {
            $jur = $jurusanModels[$sp['kode']];
            $productModels[] = Produk::firstOrCreate(
                [
                    'jurusan_id' => $jur->id,
                    'nama_produk' => $sp['nama'],
                ],
                [
                    'tipe' => $sp['tipe'],
                    'harga' => $sp['harga'],
                    'deskripsi' => 'Layanan dan produk unggulan unit Teaching Factory jurusan '.$jur->nama_jurusan,
                    'stok' => $sp['tipe'] === 'Produk Fisik' ? 25 : 0,
                    'nomor_wa' => '6281234567890',
                ]
            );
        }

        // 7. Dummy Data Transaksi Lintas Jurusan Bervariasi
        $dummyTransactions = [
            ['prod_idx' => 0, 'user_idx' => 0, 'status' => 'Completed', 'date' => '2026-01-12', 'qty' => 1],
            ['prod_idx' => 5, 'user_idx' => 1, 'status' => 'Completed', 'date' => '2026-01-28', 'qty' => 1],
            ['prod_idx' => 2, 'user_idx' => 2, 'status' => 'Completed', 'date' => '2026-02-15', 'qty' => 1],
            ['prod_idx' => 4, 'user_idx' => 3, 'status' => 'Completed', 'date' => '2026-02-22', 'qty' => 1],
            ['prod_idx' => 6, 'user_idx' => 2, 'status' => 'Completed', 'date' => '2026-03-05', 'qty' => 1],
            ['prod_idx' => 8, 'user_idx' => 1, 'status' => 'Completed', 'date' => '2026-03-19', 'qty' => 1],
            ['prod_idx' => 10, 'user_idx' => 0, 'status' => 'Completed', 'date' => '2026-04-10', 'qty' => 1],
            ['prod_idx' => 3, 'user_idx' => 3, 'status' => 'Cancelled', 'date' => '2026-04-25', 'qty' => 1],
            ['prod_idx' => 1, 'user_idx' => 2, 'status' => 'Completed', 'date' => '2026-05-08', 'qty' => 1],
            ['prod_idx' => 7, 'user_idx' => 1, 'status' => 'Completed', 'date' => '2026-06-14', 'qty' => 1],
            ['prod_idx' => 9, 'user_idx' => 0, 'status' => 'Completed', 'date' => '2026-07-02', 'qty' => 2],
            ['prod_idx' => 11, 'user_idx' => 3, 'status' => 'Completed', 'date' => '2026-07-20', 'qty' => 1],
            ['prod_idx' => 0, 'user_idx' => 1, 'status' => 'Completed', 'date' => '2026-08-11', 'qty' => 1],
            ['prod_idx' => 4, 'user_idx' => 2, 'status' => 'In Progress', 'date' => '2026-08-28', 'qty' => 1],
            ['prod_idx' => 2, 'user_idx' => 3, 'status' => 'In Progress', 'date' => '2026-09-03', 'qty' => 1],
            ['prod_idx' => 8, 'user_idx' => 0, 'status' => 'In Progress', 'date' => '2026-09-10', 'qty' => 1],
            ['prod_idx' => 5, 'user_idx' => 1, 'status' => 'Pending', 'date' => '2026-09-14', 'qty' => 2],
            ['prod_idx' => 10, 'user_idx' => 2, 'status' => 'Pending', 'date' => '2026-09-18', 'qty' => 1],
            ['prod_idx' => 3, 'user_idx' => 3, 'status' => 'Pending', 'date' => '2026-09-19', 'qty' => 1],
        ];

        foreach ($dummyTransactions as $trx) {
            $product = $productModels[$trx['prod_idx']];
            $client = $clientModels[$trx['user_idx']];
            $total = $product->harga * $trx['qty'];
            $date = Carbon::parse($trx['date']);

            $pesanan = Pesanan::firstOrCreate(
                [
                    'user_id' => $client->id,
                    'tanggal_pesan' => $date->format('Y-m-d'),
                    'total_harga' => $total,
                ],
                [
                    'status_pesanan' => $trx['status'],
                    'is_service_via_wa' => str_contains(strtolower($product->tipe), 'jasa'),
                    'created_at' => $date,
                    'updated_at' => $date,
                ]
            );

            DetailPesanan::firstOrCreate(
                [
                    'pesanan_id' => $pesanan->id,
                    'produk_id' => $product->id,
                ],
                [
                    'jumlah' => $trx['qty'],
                    'subtotal' => $total,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]
            );
        }
    }
}
