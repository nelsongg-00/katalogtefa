<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\PesanMasuk;
use Illuminate\Database\Seeder;

class PesanMasukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurId = Jurusan::first()?->id ?? 1;

        $messages = [
            [
                'jurusan_id' => $jurId,
                'nama_pengirim' => 'Rahmat Hidayat (CV Bintan Digital)',
                'email' => 'rahmat@bintandigital.com',
                'subjek' => 'Permintaan Penawaran Website Sistem Informasi',
                'pesan' => 'Halo Admin, kami tertarik memesan sistem web company profile dan portal klien untuk kantor kami. Mohon info estimasi biaya dan waktu pengerjaan.',
                'is_read' => false,
                'created_at' => now()->subMinutes(25),
            ],
            [
                'jurusan_id' => $jurId,
                'nama_pengirim' => 'Dinas Koperasi & UMKM',
                'email' => 'layanan@umkm-tanjungpinang.go.id',
                'subjek' => 'Kolaborasi Aplikasi Kasir untuk Pelaku Usaha',
                'pesan' => 'Selamat pagi, kami mengapresiasi karya TEFA siswa dan berencana mendiskusikan pengadaan aplikasi POS kasir untuk 20 UMKM binaan kami.',
                'is_read' => false,
                'created_at' => now()->subHours(2),
            ],
            [
                'jurusan_id' => $jurId,
                'nama_pengirim' => 'Lestari Indah',
                'email' => 'lestari.indah@gmail.com',
                'subjek' => 'Tanya Ketersediaan Jasa Pembuatan Aplikasi Mobile',
                'pesan' => 'Apakah jurusan RPL juga melayani pembuatan aplikasi Android sederhana untuk tugas inventaris kantor? Terima kasih.',
                'is_read' => false,
                'created_at' => now()->subHours(5),
            ],
            [
                'jurusan_id' => $jurId,
                'nama_pengirim' => 'Hendra Setiawan',
                'email' => 'hendra@setiakawan.co.id',
                'subjek' => 'Konfirmasi Pembayaran dan Detail Lisensi',
                'pesan' => 'Pembayaran untuk source code e-commerce telah kami transfer melalui rekening BCA sekolah. Mohon validasi dan kirimkan link repositori.',
                'is_read' => true,
                'created_at' => now()->subDays(1),
            ],
            [
                'jurusan_id' => $jurId,
                'nama_pengirim' => 'SMK Negeri 1 Bintan',
                'email' => 'humas@smkn1bintan.sch.id',
                'subjek' => 'Studi Banding Program Teaching Factory',
                'pesan' => 'Kami bermaksud mengadakan kunjungan studi tiru pengelolaan teaching factory di SMKN 4 Tanjungpinang pada akhir bulan ini.',
                'is_read' => true,
                'created_at' => now()->subDays(2),
            ],
        ];

        foreach ($messages as $message) {
            PesanMasuk::create($message);
        }
    }
}
