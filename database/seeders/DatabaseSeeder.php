<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jurusan
        $jurusans = [
            ['nama_jurusan' => 'Software Engineering', 'deskripsi_profil' => 'Focusing on web, app development, and algorithms.'],
            ['nama_jurusan' => 'Computer and Network Engineering', 'deskripsi_profil' => 'Focusing on hardware, server infrastructure, and network security.'],
            ['nama_jurusan' => 'Visual Communication Design', 'deskripsi_profil' => 'Focusing on graphic design, typography, and illustration.'],
            ['nama_jurusan' => 'TV Broadcasting Production', 'deskripsi_profil' => 'Focusing on studio management, camera work, and post-production.'],
            ['nama_jurusan' => 'Animation', 'deskripsi_profil' => 'Focusing on 2D/3D character design and visual effects.'],
            ['nama_jurusan' => 'Game Development', 'deskripsi_profil' => 'Focusing on game mechanics, engine programming, and interactive media.']
        ];
        
        foreach ($jurusans as $j) {
            \App\Models\Jurusan::create($j);
        }

        // Users
        \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
        ]);
        \App\Models\User::create([
            'name' => 'Admin SE',
            'email' => 'adminse@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin_jurusan',
            'jurusan_id' => 1,
        ]);
        \App\Models\User::create([
            'name' => 'Worker SE',
            'email' => 'workerse@example.com',
            'password' => bcrypt('password'),
            'role' => 'worker',
            'jurusan_id' => 1,
        ]);
        \App\Models\User::create([
            'name' => 'Client',
            'email' => 'client@example.com',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
        ]);

        // Products
        $produks = [
            ['jurusan_id' => 1, 'tipe' => 'Produk Fisik', 'nama_produk' => 'Source Code E-Commerce', 'harga' => 500000, 'deskripsi' => 'Full source code aplikasi E-commerce.', 'stok' => 10],
            ['jurusan_id' => 1, 'tipe' => 'Layanan Jasa', 'nama_produk' => 'Jasa Pembuatan Web', 'harga' => 1500000, 'deskripsi' => 'Pembuatan website custom perusahaan.', 'nomor_wa' => '6281234567890', 'stok' => 0],
            ['jurusan_id' => 3, 'tipe' => 'Produk Fisik', 'nama_produk' => 'Poster Digital Cetak', 'harga' => 100000, 'deskripsi' => 'Poster A3 cetak berkualitas.', 'stok' => 50],
            ['jurusan_id' => 3, 'tipe' => 'Layanan Jasa', 'nama_produk' => 'Jasa Desain Logo', 'harga' => 300000, 'deskripsi' => 'Pembuatan logo profesional.', 'nomor_wa' => '6281234567891', 'stok' => 0],
        ];

        foreach ($produks as $p) {
            \Illuminate\Support\Facades\DB::table('produks')->insert(array_merge($p, ['created_at' => now(), 'updated_at' => now()]));
        }
    }
}
