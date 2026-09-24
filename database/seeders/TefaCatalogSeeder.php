<?php

namespace Database\Seeders;

use App\Models\Jurusan as Department;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TefaCatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil data jurusan DKV, TKJ, dan Animasi
        $dkv = Department::where('slug', 'desain-komunikasi-visual')
            ->orWhere('kode', 'DKV')
            ->orWhere('nama_jurusan', 'like', '%Desain Komunikasi Visual%')
            ->first();

        $tkj = Department::where('slug', 'teknik-komputer-jaringan')
            ->orWhere('kode', 'TKJ')
            ->orWhere('nama_jurusan', 'like', '%Teknik Komputer Jaringan%')
            ->first();

        $animasi = Department::where('slug', 'animasi')
            ->orWhere('kode', 'ANI')
            ->orWhere('nama_jurusan', 'like', '%Animasi%')
            ->first();

        // -------------------------------------------------------------
        // JURUSAN 1: Desain Komunikasi Visual (DKV)
        // -------------------------------------------------------------
        if ($dkv) {
            // Produk Fisik DKV
            Product::updateOrCreate(
                [
                    'jurusan_id' => $dkv->id,
                    'nama_produk' => 'Gantungan Kunci DKV',
                ],
                [
                    'deskripsi' => 'Gantungan kunci akrilik custom hasil karya siswa DKV.',
                    'harga' => 10000,
                    'stok' => 50,
                    'foto' => null,
                ]
            );

            // Layanan Jasa DKV
            $dkvServices = [
                [
                    'nama_layanan' => 'Mock dan Design',
                    'estimasi_harga' => 75000,
                    'deskripsi' => 'Layanan perancangan visual mockup dan konsep desain grafis profesional untuk display produk dan promosi.',
                ],
                [
                    'nama_layanan' => 'Design Kaos / Pakaian',
                    'estimasi_harga' => 100000,
                    'deskripsi' => 'Layanan desain apparel kustom (kaos, jersey, jaket) dengan format resolusi tinggi siap cetak dan sablon.',
                ],
                [
                    'nama_layanan' => 'Design dan Cetak Spanduk',
                    'estimasi_harga' => 50000,
                    'deskripsi' => 'Jasa perancangan desain visual dan percetakan spanduk flexi outdoor/indoor berkualitas untuk acara atau usaha.',
                ],
                [
                    'nama_layanan' => 'Design dan Cetak Baliho',
                    'estimasi_harga' => 150000,
                    'deskripsi' => 'Jasa perancangan grafis resolusi tinggi dan cetak baliho/billboard besar untuk promosi luar ruangan.',
                ],
                [
                    'nama_layanan' => 'Design dan Cetak Banner',
                    'estimasi_harga' => 65000,
                    'deskripsi' => 'Pembuatan desain dan cetak X-Banner / Roll Banner indoor dengan bahan berkualitas, tajam, dan tahan lama.',
                ],
                [
                    'nama_layanan' => 'Design dan Cetak Poster',
                    'estimasi_harga' => 40000,
                    'deskripsi' => 'Layanan pembuatan desain grafis kreatif dan cetak poster informasi atau dekorasi dinding berbagai ukuran.',
                ],
                [
                    'nama_layanan' => 'Design dan Cetak Logo',
                    'estimasi_harga' => 200000,
                    'deskripsi' => 'Pembuatan konsep identitas logo unik, profesional, dan representatif lengkap dengan master file untuk branding bisnis.',
                ],
                [
                    'nama_layanan' => 'Design dan Cetak Kemasan',
                    'estimasi_harga' => 120000,
                    'deskripsi' => 'Jasa perancangan desain packaging dan cetak kemasan produk kreatif yang menarik serta meningkatkan daya jual.',
                ],
                [
                    'nama_layanan' => 'Design dan Cetak Undangan',
                    'estimasi_harga' => 80000,
                    'deskripsi' => 'Layanan desain custom dan cetak kartu undangan eksklusif untuk acara formal, pernikahan, seminar, atau gathering.',
                ],
            ];

            foreach ($dkvServices as $service) {
                $this->seedService($dkv->id, $service);
            }
        }

        // -------------------------------------------------------------
        // JURUSAN 2: Teknik Komputer Jaringan (TKJ)
        // -------------------------------------------------------------
        if ($tkj) {
            $tkjServices = [
                [
                    'nama_layanan' => 'Instalasi Jaringan LAN / Lokal Area',
                    'estimasi_harga' => 50000,
                    'deskripsi' => 'Pemasangan kabel dan setting jaringan LAN lokal area (dihitung Rp50.000 per titik pengerjaan).',
                ],
                [
                    'nama_layanan' => 'Pemasangan Jaringan Internet',
                    'estimasi_harga' => 150000,
                    'deskripsi' => 'Setting modem, router, dan konfigurasi akses internet kantor/rumah.',
                ],
                [
                    'nama_layanan' => 'Jasa Service Software & OS',
                    'estimasi_harga' => 75000,
                    'deskripsi' => 'Instalasi ulang sistem operasi, pembersihan malware/virus, dan optimasi performa PC/Laptop.',
                ],
                [
                    'nama_layanan' => 'Instalasi & Konfigurasi Jaringan Nirkabel (Wi-Fi)',
                    'estimasi_harga' => 100000,
                    'deskripsi' => 'Pemasangan Access Point, konfigurasi hotspot, dan manajemen bandwidth.',
                ],
            ];

            foreach ($tkjServices as $service) {
                $this->seedService($tkj->id, $service);
            }
        }

        // -------------------------------------------------------------
        // JURUSAN 3: Animasi
        // -------------------------------------------------------------
        if ($animasi) {
            // Produk Fisik Animasi
            Product::updateOrCreate(
                [
                    'jurusan_id' => $animasi->id,
                    'nama_produk' => 'Sticker Pack Animasi',
                ],
                [
                    'deskripsi' => 'Paket stiker die-cut vinyl tahan air dengan ilustrasi karakter original siswa Animasi.',
                    'harga' => 5000,
                    'stok' => 100,
                    'foto' => null,
                ]
            );

            // Layanan Jasa Animasi
            $animasiServices = [
                [
                    'nama_layanan' => 'Desain Asset 3D',
                    'estimasi_harga' => 300000,
                    'deskripsi' => 'Pemodelan aset 3D digital berdetail tinggi untuk kebutuhan game, animasi, visualisasi produk, atau rendering.',
                ],
                [
                    'nama_layanan' => 'Desain Karakter 2D',
                    'estimasi_harga' => 200000,
                    'deskripsi' => 'Perancangan karakter 2D original beserta lembar konsep desain (turnaround, ekspresi, pose) untuk komik atau animasi.',
                ],
                [
                    'nama_layanan' => 'Video Edukasi Singkat 2D',
                    'estimasi_harga' => 500000,
                    'deskripsi' => 'Produksi video animasi pembelajaran atau explainer 2D interaktif berdurasi singkat yang menarik dan komunikatif.',
                ],
                [
                    'nama_layanan' => 'Ilustrasi Digital',
                    'estimasi_harga' => 150000,
                    'deskripsi' => 'Gambar digital untuk kebutuhan poster, cover buku, konten media sosial, atau promosi.',
                ],
                [
                    'nama_layanan' => 'Video Promosi Animasi',
                    'estimasi_harga' => 750000,
                    'deskripsi' => 'Pembuatan video animasi promosi kreatif dan dinamis untuk mengenalkan produk, brand, atau event bisnis Anda.',
                ],
                [
                    'nama_layanan' => 'Animasi Maskot',
                    'estimasi_harga' => 350000,
                    'deskripsi' => 'Pengembangan dan animasi pergerakan karakter maskot brand/instansi untuk keperluan materi promosi digital.',
                ],
                [
                    'nama_layanan' => 'Video Profil Sekolah / Perusahaan',
                    'estimasi_harga' => 1000000,
                    'deskripsi' => 'Produksi video profil perusahaan (company profile) atau sekolah dengan paduan motion graphic, sinematografi, dan voice over profesional.',
                ],
            ];

            foreach ($animasiServices as $service) {
                $this->seedService($animasi->id, $service);
            }
        }
    }

    /**
     * Helper untuk insert/update data layanan jasa agar aman dari duplikasi slug.
     *
     * @param  array{nama_layanan: string, estimasi_harga: int, deskripsi: string}  $service
     */
    private function seedService(int $departmentId, array $service): void
    {
        $existing = Service::where('department_id', $departmentId)
            ->where('nama_layanan', $service['nama_layanan'])
            ->first();

        if ($existing) {
            $slug = $existing->slug;
        } else {
            $baseSlug = Str::slug($service['nama_layanan']);
            $slug = $baseSlug;
            $counter = 1;
            while (Service::where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.$counter++;
            }
        }

        Service::updateOrCreate(
            [
                'department_id' => $departmentId,
                'nama_layanan' => $service['nama_layanan'],
            ],
            [
                'slug' => $slug,
                'deskripsi' => $service['deskripsi'],
                'estimasi_harga' => $service['estimasi_harga'],
                'foto' => null,
                'is_active' => true,
            ]
        );
    }
}
