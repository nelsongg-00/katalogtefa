<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk; // Memanggil Model Produk

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        Produk::insert([
            [
                'nama_produk' => 'Jasa Desain Banner TeFa',
                'harga' => 50000,
                'deskripsi' => 'Desain banner TeFa untuk acara sekolah',
                'stok' => 99,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_produk' => 'Cetak Pin Jurusan',
                'harga' => 5000,
                'deskripsi' => 'Pin kustom logo jurusan',
                'stok' => 200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}