<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\Order;
use App\Models\Project;
use App\Models\ProjectLog;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Temukan jurusan terkait
        $rpl = Jurusan::where('kode', 'RPL')->orWhere('slug', 'like', '%rekayasa%')->first() ?? Jurusan::first();
        $dkv = Jurusan::where('kode', 'DKV')->orWhere('slug', 'like', '%desain%')->first() ?? $rpl;
        $animasi = Jurusan::where('kode', 'ANI')->orWhere('kode', 'PSPT')->orWhere('slug', 'like', '%animasi%')->first() ?? $rpl;

        $servicesData = [
            [
                'department_id' => $rpl->id,
                'nama_layanan' => 'Pembuatan Website Profil & E-Commerce',
                'slug' => 'pembuatan-website-profil-dan-e-commerce',
                'deskripsi' => 'Pengembangan website responsif modern untuk profil perusahaan, portal instansi, atau toko online terintegrasi payment gateway dan WhatsApp.',
                'estimasi_harga' => 1500000,
                'foto' => null,
                'is_active' => true,
            ],
            [
                'department_id' => $dkv->id,
                'nama_layanan' => 'Jasa Desain Identitas Visual & Branding',
                'slug' => 'jasa-desain-identitas-visual-dan-branding',
                'deskripsi' => 'Perancangan identitas visual lengkap mencakup logo profesional, brand guideline, kartu nama, kop surat, brosur, dan desain packaging produk.',
                'estimasi_harga' => 750000,
                'foto' => null,
                'is_active' => true,
            ],
            [
                'department_id' => $animasi->id,
                'nama_layanan' => 'Produksi Video Iklan & Motion Graphics',
                'slug' => 'produksi-video-iklan-dan-motion-graphics',
                'deskripsi' => 'Layanan pembuatan video promosi komersial 4K, video explainer 2D/3D interaktif, bumper logo, dan editing pascaproduksi berstandar industri penyiaran.',
                'estimasi_harga' => 2000000,
                'foto' => null,
                'is_active' => true,
            ],
        ];

        $serviceModels = [];
        foreach ($servicesData as $data) {
            $serviceModels[] = Service::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        // Siapkan 1 Dummy Order & Project Timeline untuk demonstrasi pelacakan publik (#TEFA-9821)
        $sampleService = $serviceModels[0];
        $worker = User::where('role', 'worker')->where('jurusan_id', $sampleService->department_id)->first()
            ?? User::where('role', 'worker')->first();

        $project = Project::firstOrCreate(
            [
                'nama_projek' => '[TEFA-9821] Pembuatan Website Profil & E-Commerce - Bapak Rahmat Hidayat',
            ],
            [
                'jurusan_id' => $sampleService->department_id,
                'deskripsi' => 'Pengerjaan website profil dan toko online berbasis Laravel untuk klien CV Bintan Kreatif.',
                'status' => 'in_progress',
                'progress' => 65,
                'worker_id' => $worker?->id,
                'tenggat_waktu' => now()->addDays(12),
            ]
        );

        // Timeline Logs untuk Project
        $logs = [
            [
                'project_id' => $project->id,
                'worker_id' => $worker?->id,
                'catatan' => 'Pesanan diterima via WhatsApp. Requirement gathering dan pengumpulan materi teks serta logo perusahaan selesai.',
                'link_eksternal' => null,
                'created_at' => now()->subDays(4),
            ],
            [
                'project_id' => $project->id,
                'worker_id' => $worker?->id,
                'catatan' => 'Perancangan UI/UX Mockup halaman utama dan katalog produk di Figma telah disetujui klien.',
                'link_eksternal' => 'https://figma.com/@tefa-smkn4/mockup-web',
                'created_at' => now()->subDays(2),
            ],
            [
                'project_id' => $project->id,
                'worker_id' => $worker?->id,
                'catatan' => 'Slicing HTML/Tailwind dan integrasi database katalog produk sedang berlangsung.',
                'link_eksternal' => 'https://github.com/tefa-smkn4/projek-web-klien',
                'created_at' => now()->subHours(5),
            ],
        ];

        foreach ($logs as $log) {
            ProjectLog::firstOrCreate(
                [
                    'project_id' => $log['project_id'],
                    'catatan' => $log['catatan'],
                ],
                $log
            );
        }

        // Data Order
        Order::updateOrCreate(
            ['order_code' => 'TEFA-9821'],
            [
                'customer_name' => 'Bapak Rahmat Hidayat',
                'customer_phone' => '081298765432',
                'service_id' => $sampleService->id,
                'worker_id' => $worker?->id,
                'project_id' => $project->id,
                'status' => 'in_progress',
                'total_biaya' => 1500000,
                'catatan' => 'Website untuk CV Bintan Kreatif. Deadline pertengahan bulan.',
            ]
        );
    }
}
