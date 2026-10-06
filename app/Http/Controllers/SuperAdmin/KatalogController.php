<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Service;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class KatalogController extends Controller
{
    /**
     * Unduh katalog produk dalam format PDF.
     */
    public function produk(): Response
    {
        $items = Product::with('jurusan')
            ->orderBy('nama_produk')
            ->get()
            ->map(fn (Product $product) => $this->makeItem(
                nama: $product->nama_produk,
                deskripsi: $product->deskripsi,
                harga: $product->harga,
                foto: $product->foto,
                kode: $product->jurusan?->kode,
                tipe: 'PRODUK',
            ))
            ->values()
            ->all();

        return $this->render($items, 'Katalog Produk', 'produk');
    }

    /**
     * Unduh katalog layanan jasa dalam format PDF.
     */
    public function jasa(): Response
    {
        $items = Service::with('jurusan')
            ->where('is_active', true)
            ->orderBy('nama_layanan')
            ->get()
            ->map(fn (Service $service) => $this->makeItem(
                nama: $service->nama_layanan,
                deskripsi: $service->deskripsi,
                harga: $service->estimasi_harga,
                foto: $service->foto,
                kode: $service->jurusan?->kode,
                tipe: 'JASA',
            ))
            ->values()
            ->all();

        return $this->render($items, 'Katalog Layanan Jasa', 'jasa');
    }

    /**
     * Susun satu entri katalog menjadi bentuk yang dipakai view PDF.
     *
     * @return array<string, mixed>
     */
    private function makeItem(string $nama, ?string $deskripsi, ?int $harga, ?string $foto, ?string $kode, string $tipe): array
    {
        $isJasa = $tipe === 'JASA';
        $prefix = $isJasa ? 'Mulai ' : '';

        return [
            'nama' => $nama,
            'deskripsi' => Str::limit((string) $deskripsi, 80),
            'harga' => $prefix.'Rp'.number_format((int) $harga, 0, ',', '.'),
            'foto' => $this->resolveFoto($foto),
            'kode' => $this->normaliseKode($kode),
            'tipe' => $tipe,
            'isJasa' => $isJasa,
        ];
    }

    /**
     * Path relatif terhadap folder public agar lolos chroot dompdf.
     */
    private function resolveFoto(?string $foto): ?string
    {
        if ($foto === null || $foto === '' || str_starts_with($foto, 'http')) {
            return null;
        }

        return is_file(public_path('storage/'.$foto)) ? 'storage/'.$foto : null;
    }

    /**
     * Samakan penulisan kode jurusan dengan katalog publik.
     */
    private function normaliseKode(?string $kode): string
    {
        $raw = strtoupper((string) $kode);

        return match (true) {
            str_contains($raw, 'ANI') => 'ANIMASI',
            str_contains($raw, 'GIM') => 'GIM',
            str_contains($raw, 'RPL') => 'RPL',
            str_contains($raw, 'TKJ') => 'TKJ',
            str_contains($raw, 'DKV') => 'DKV',
            str_contains($raw, 'PSPT') || str_contains($raw, 'PSTV') => 'PSPT',
            default => 'TEFA',
        };
    }

    /**
     * Render view PDF lalu kirim sebagai berkas yang diunduh.
     *
     * @param  array<int, array<string, mixed>>  $items
     */
    private function render(array $items, string $judul, string $slug): Response
    {
        $tipe = $slug === 'jasa' ? 'JASA' : 'PRODUK';
        $unduhan = now()->format('Y-m-d');
        $cetak = now()->locale('id')->isoFormat('D MMMM YYYY');

        return Pdf::loadView('superadmin.katalog.katalog', [
            'items' => $items,
            'judul' => $judul,
            'tipe' => $tipe,
            'tanggal' => $cetak,
        ])->download("katalog-tefa-{$slug}-{$unduhan}.pdf");
    }
}
