<?php

namespace Database\Seeders;

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
        // 1. Seeder Utama: Master Jurusan, Users, Produk, dan Transaksi Global
        $this->call(SuperAdminSeeder::class);

        // 2. Seeder Tambahan Fitur Admin & Worker & Services
        $this->call([
            ProductSeeder::class,
            ProjectSeeder::class,
            PesanMasukSeeder::class,
            WorkerTaskSeeder::class,
            ServiceSeeder::class,
            TefaCatalogSeeder::class,
        ]);
    }
}
