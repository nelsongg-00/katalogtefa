<?php

namespace Database\Seeders;

use App\Models\DetailPesanan;
use App\Models\Jurusan;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Jurusan
        $jurusans = [
            ['nama_jurusan' => 'Software Engineering', 'deskripsi_profil' => 'Focusing on web, app development, and algorithms.'],
            ['nama_jurusan' => 'Computer and Network Engineering', 'deskripsi_profil' => 'Focusing on hardware, server infrastructure, and network security.'],
            ['nama_jurusan' => 'Visual Communication Design', 'deskripsi_profil' => 'Focusing on graphic design, typography, and illustration.'],
            ['nama_jurusan' => 'TV Broadcasting Production', 'deskripsi_profil' => 'Focusing on studio management, camera work, and post-production.'],
            ['nama_jurusan' => 'Animation', 'deskripsi_profil' => 'Focusing on 2D/3D character design and visual effects.'],
            ['nama_jurusan' => 'Game Development', 'deskripsi_profil' => 'Focusing on game mechanics, engine programming, and interactive media.'],
        ];

        foreach ($jurusans as $j) {
            Jurusan::create($j);
        }

        // 2. Users (Super Admin, Admin Jurusan, Workers, Client)
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
        ]);

        User::create([
            'name' => 'Admin SE',
            'email' => 'adminse@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin_jurusan',
            'jurusan_id' => 1,
        ]);

        // 3-4 Akun Worker Dummy
        $workers = [
            ['name' => 'Budi Worker', 'email' => 'budi.worker@example.com', 'role' => 'worker', 'jurusan_id' => 1],
            ['name' => 'Siti Worker', 'email' => 'siti.worker@example.com', 'role' => 'worker', 'jurusan_id' => 1],
            ['name' => 'Fajar Worker', 'email' => 'fajar.worker@example.com', 'role' => 'worker', 'jurusan_id' => 1],
            ['name' => 'Dinda Worker', 'email' => 'dinda.worker@example.com', 'role' => 'worker', 'jurusan_id' => 1],
        ];

        foreach ($workers as $w) {
            User::create(array_merge($w, ['password' => bcrypt('password')]));
        }

        $client = User::create([
            'name' => 'Client Contoh',
            'email' => 'client@example.com',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
        ]);

        // 3. Products
        $produks = [
            ['jurusan_id' => 1, 'tipe' => 'Produk Fisik', 'nama_produk' => 'Source Code E-Commerce', 'harga' => 500000, 'deskripsi' => 'Full source code aplikasi E-commerce.', 'stok' => 10],
            ['jurusan_id' => 1, 'tipe' => 'Layanan Jasa', 'nama_produk' => 'Jasa Pembuatan Web', 'harga' => 1500000, 'deskripsi' => 'Pembuatan website custom perusahaan.', 'nomor_wa' => '6281234567890', 'stok' => 0],
            ['jurusan_id' => 1, 'tipe' => 'Produk Fisik', 'nama_produk' => 'Aplikasi Kasir UMKM', 'harga' => 350000, 'deskripsi' => 'Aplikasi POS kasir toko retail.', 'stok' => 20],
            ['jurusan_id' => 3, 'tipe' => 'Produk Fisik', 'nama_produk' => 'Poster Digital Cetak', 'harga' => 100000, 'deskripsi' => 'Poster A3 cetak berkualitas.', 'stok' => 50],
            ['jurusan_id' => 3, 'tipe' => 'Layanan Jasa', 'nama_produk' => 'Jasa Desain Logo', 'harga' => 300000, 'deskripsi' => 'Pembuatan logo profesional.', 'nomor_wa' => '6281234567891', 'stok' => 0],
        ];

        foreach ($produks as $p) {
            DB::table('produks')->insert(array_merge($p, ['created_at' => now(), 'updated_at' => now()]));
        }

        // 4. Sample Orders
        $pesanan1 = Pesanan::create([
            'user_id' => $client->id,
            'status_pesanan' => 'Pending',
            'total_harga' => 350000,
            'tanggal_pesan' => now()->subDay(),
        ]);
        DetailPesanan::create([
            'pesanan_id' => $pesanan1->id,
            'produk_id' => 3,
            'jumlah' => 1,
            'subtotal' => 350000,
        ]);

        $pesanan2 = Pesanan::create([
            'user_id' => $client->id,
            'status_pesanan' => 'Completed',
            'total_harga' => 500000,
            'tanggal_pesan' => now()->subDays(3),
        ]);
        DetailPesanan::create([
            'pesanan_id' => $pesanan2->id,
            'produk_id' => 1,
            'jumlah' => 1,
            'subtotal' => 500000,
        ]);

        // 5. Projects & Messages
        $this->call([
            ProjectSeeder::class,
            PesanMasukSeeder::class,
        ]);
    }
}
