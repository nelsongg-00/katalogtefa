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
        return $this->render($this->produkItems(), 'Katalog Produk', 'produk');
    }

    /**
     * Unduh katalog layanan jasa dalam format PDF.
     */
    public function jasa(): Response
    {
        return $this->render($this->jasaItems(), 'Katalog Layanan Jasa', 'jasa');
    }

    /**
     * Pratinjau katalog di browser (HTML) sebelum diunduh sebagai PDF.
     */
    public function preview(string $type): Response
    {
        $isJasa = $type === 'jasa';

        abort_unless($type === 'produk' || $isJasa, 404);

        $items = $isJasa ? $this->jasaItems() : $this->produkItems();

        $tipe = $isJasa ? 'JASA' : 'PRODUK';
        $cetak = now()->locale('id')->isoFormat('D MMMM YYYY');

        return response()->view('superadmin.katalog.katalog', [
            'items' => $items,
            'judul' => $isJasa ? 'Katalog Layanan Jasa' : 'Katalog Produk',
            'tipe' => $tipe,
            'tanggal' => $cetak,
            'preview' => [
                'tipe' => $type,
                'unduhUrl' => $isJasa ? route('superadmin.katalog.jasa') : route('superadmin.katalog.produk'),
                'unduhLabel' => $isJasa ? 'Unduh Katalog Layanan Jasa' : 'Unduh Katalog Produk',
            ],
        ]);
    }

    /**
     * Susun seluruh entri katalog produk (dipakai PDF & pratinjau).
     *
     * @return array<int, array<string, mixed>>
     */
    private function produkItems(): array
    {
        return Product::with('jurusan')
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
    }

    /**
     * Susun seluruh entri katalog layanan jasa aktif (dipakai PDF & pratinjau).
     *
     * @return array<int, array<string, mixed>>
     */
    private function jasaItems(): array
    {
        return Service::with('jurusan')
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
        $fotoRelatif = $this->resolveFoto($foto);

        return [
            'nama' => $nama,
            'deskripsi' => Str::limit((string) $deskripsi, 80),
            'harga' => $prefix.'Rp'.number_format((int) $harga, 0, ',', '.'),
            'foto' => $fotoRelatif,
            'foto_fit' => $this->fitFoto($fotoRelatif),
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
     * Hitung ukuran gambar (mm) agar muat penuh (contain) di kotak foto
     * seragam 55,5 x 70 mm (rasio 4:5) tanpa distorsi. dompdf tidak
     * mendukung object-fit, jadi skalanya dihitung per gambar di sini
     * lalu dipasang sebagai style inline di view.
     *
     * @return array{w: float, h: float, dy: float}|null
     */
    private function fitFoto(?string $relatif): ?array
    {
        if ($relatif === null) {
            return null;
        }

        $ukuran = @getimagesize(public_path($relatif));

        if ($ukuran === false || $ukuran[0] < 1 || $ukuran[1] < 1) {
            return null;
        }

        $boxW = 55.5;
        $boxH = 70.0;
        $skala = min($boxW / $ukuran[0], $boxH / $ukuran[1]);

        $w = round($ukuran[0] * $skala, 1);
        $h = round($ukuran[1] * $skala, 1);

        return [
            'w' => $w,
            'h' => $h,
            'dy' => round(($boxH - $h) / 2, 1), // huruf vertikal di tengah kotak
        ];
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
