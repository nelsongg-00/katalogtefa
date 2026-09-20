<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurusan = Jurusan::first();

        Product::updateOrCreate(
            ['nama_produk' => 'Buku Jurnal Desain Tefa'],
            [
                'jurusan_id' => $jurusan ? $jurusan->id : null,
                'deskripsi' => 'Buku jurnal sketsa dan catatan proyek desain grafis dengan kertas bookpaper premium 90gsm dan jilid hard cover doff berkualitas tinggi karya siswa SMKN 4.',
                'harga' => 75000,
                'stok' => 20,
                'foto' => null,
            ]
        );
    }
}
