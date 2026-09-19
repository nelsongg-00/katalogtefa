<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workers = User::where('role', 'worker')->get();
        $worker1 = $workers->first();
        $worker2 = $workers->skip(1)->first() ?? $worker1;
        $worker3 = $workers->skip(2)->first() ?? $worker1;

        $projects = [
            [
                'jurusan_id' => $worker1?->jurusan_id ?? 1,
                'nama_projek' => 'Sistem Informasi Presensi Berbasis QR',
                'deskripsi' => 'Pengembangan aplikasi absensi digital siswa dan guru berbasis scan QR code dan web dashboard.',
                'status' => 'in_progress',
                'progress' => 25,
                'worker_id' => $worker1?->id,
                'tenggat_waktu' => now()->addDays(20),
            ],
            [
                'jurusan_id' => $worker2?->jurusan_id ?? 1,
                'nama_projek' => 'Website Company Profile PT Bintan Kreatif',
                'deskripsi' => 'Pembuatan landing page interaktif dengan integrasi formulir kontak dan katalog layanan jasa.',
                'status' => 'in_progress',
                'progress' => 60,
                'worker_id' => $worker2?->id,
                'tenggat_waktu' => now()->addDays(10),
            ],
            [
                'jurusan_id' => $worker3?->jurusan_id ?? 1,
                'nama_projek' => 'Aplikasi POS Kasir UMKM Kuliner',
                'deskripsi' => 'Aplikasi kasir ringan pencatat transaksi harian dan cetak struk bluetooth thermal.',
                'status' => 'in_progress',
                'progress' => 85,
                'worker_id' => $worker3?->id,
                'tenggat_waktu' => now()->addDays(5),
            ],
            [
                'jurusan_id' => $worker1?->jurusan_id ?? 1,
                'nama_projek' => 'Redesain UI/UX Platform E-Learning',
                'deskripsi' => 'Perancangan prototipe Figma dan implementasi antarmuka portal pembelajaran TEFA.',
                'status' => 'pending',
                'progress' => 0,
                'worker_id' => null,
                'tenggat_waktu' => now()->addDays(30),
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
