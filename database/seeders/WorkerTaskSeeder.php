<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\Project;
use App\Models\ProjectLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class WorkerTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $worker = User::where('role', 'worker')->first();
        if (! $worker) {
            $jurusan = Jurusan::first();
            $worker = User::create([
                'name' => 'Budi Worker',
                'email' => 'budi.worker@example.com',
                'password' => bcrypt('password'),
                'role' => 'worker',
                'jurusan_id' => $jurusan ? $jurusan->id : null,
            ]);
        }

        $jurusanId = $worker->jurusan_id ?? Jurusan::value('id');

        // Tugas 1: Desain UI/UX Landing Page Tefa (draft, 2 timeline logs with Figma link)
        $tugas1 = Project::updateOrCreate(
            ['nama_projek' => 'Pembuatan Desain UI/UX Landing Page Tefa'],
            [
                'jurusan_id' => $jurusanId,
                'deskripsi' => 'Perancangan desain antarmuka responsif untuk portal Teaching Factory SMKN 4, mencakup wireframing, high-fidelity mockup, dan interactive prototype di Figma.',
                'status' => 'in_progress',
                'progress' => 65,
                'worker_id' => $worker->id,
                'tenggat_waktu' => now()->addDays(3),
                'status_review' => 'draft',
                'catatan_worker' => null,
                'file_hasil' => null,
            ]
        );

        ProjectLog::updateOrCreate(
            [
                'project_id' => $tugas1->id,
                'catatan' => 'Selesai eksplorasi moodboard dan wireframe low-fidelity seluruh halaman (Desktop & Mobile view).',
            ],
            [
                'worker_id' => $worker->id,
                'lampiran_file' => null,
                'link_eksternal' => 'https://www.figma.com/design/sample-tefa-wireframe',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ]
        );

        ProjectLog::updateOrCreate(
            [
                'project_id' => $tugas1->id,
                'catatan' => 'Menyelesaikan High-Fidelity UI untuk section Hero, Katalog Produk, dan Alur Pemesanan. Mulai menyusun komponen desain sistem (warna, tipografi, dan button states).',
            ],
            [
                'worker_id' => $worker->id,
                'lampiran_file' => null,
                'link_eksternal' => 'https://www.figma.com/design/sample-tefa-hifi-mockup',
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ]
        );

        // Tugas 2: Produksi Aset 3D Modeling Karakter Animasi (submitted, 1 log + screenshot)
        $tugas2 = Project::updateOrCreate(
            ['nama_projek' => 'Produksi Aset 3D Modeling Karakter Animasi'],
            [
                'jurusan_id' => $jurusanId,
                'deskripsi' => 'Pemodelan karakter maskot 3D SMKN 4 untuk kebutuhan animasi promosi sekolah. Termasuk topology clean quad, UV unwrapping, dan tekstur PBR.',
                'status' => 'in_progress',
                'progress' => 85,
                'worker_id' => $worker->id,
                'tenggat_waktu' => now()->addDays(5),
                'status_review' => 'submitted',
                'catatan_worker' => 'File master model 3D (format .FBX dan .BLENDFILE) beserta texture map 4K sudah selesai dikerjakan. Mohon direview dan konfirmasi jika ada koreksi detail mesh.',
                'file_hasil' => 'submissions/karakter_maskot_3d_final.zip',
            ]
        );

        ProjectLog::updateOrCreate(
            [
                'project_id' => $tugas2->id,
                'catatan' => 'Selesai proses UV Unwrapping dan pengecekan kerapian topology (semua quad-based tanpa ngons). Texture bake sudah diuji coba pada rendering viewport.',
            ],
            [
                'worker_id' => $worker->id,
                'lampiran_file' => 'worker_logs/preview_topology_maskot.jpg',
                'link_eksternal' => 'https://drive.google.com/drive/folders/sample-3d-assets',
                'created_at' => now()->subHours(8),
                'updated_at' => now()->subHours(8),
            ]
        );
    }
}
