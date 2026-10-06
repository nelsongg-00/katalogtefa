<?php

namespace Tests\Feature;

use App\Models\Jurusan;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class SuperAdminKatalogPdfTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;

    private User $pelanggan;

    private Jurusan $jurusan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->jurusan = Jurusan::firstOrCreate(
            ['slug' => 'rekayasa-perangkat-lunak'],
            [
                'nama_jurusan' => 'Rekayasa Perangkat Lunak',
                'kode' => 'RPL',
                'kepala_jurusan' => 'Bu Ratna Sari, S.Kom',
                'deskripsi' => 'Pengembangan perangkat lunak.',
                'status_aktif' => true,
            ]
        );

        $this->superAdmin = User::firstOrCreate(
            ['email' => 'superadmin.katalog@example.com'],
            [
                'name' => 'Super Admin Katalog',
                'password' => bcrypt('password'),
                'role' => 'super_admin',
                'is_active' => true,
            ]
        );

        $this->pelanggan = User::firstOrCreate(
            ['email' => 'pelanggan.katalog@example.com'],
            [
                'name' => 'Pelanggan Katalog',
                'password' => bcrypt('password'),
                'role' => 'pelanggan',
                'is_active' => true,
            ]
        );
    }

    public function test_super_admin_can_download_product_catalog_as_attachment(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('superadmin.katalog.produk'));

        $response->assertOk();
        $this->assertStringContainsString('application/pdf', (string) $response->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment', (string) $response->headers->get('Content-Disposition'));
        $this->assertMatchesRegularExpression(
            '/filename="?katalog-tefa-produk-\d{4}-\d{2}-\d{2}\.pdf"?/',
            (string) $response->headers->get('Content-Disposition')
        );
        $this->assertStringStartsWith('%PDF', $response->getContent());
    }

    public function test_super_admin_can_download_service_catalog_as_attachment(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('superadmin.katalog.jasa'));

        $response->assertOk();
        $this->assertStringContainsString('application/pdf', (string) $response->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment', (string) $response->headers->get('Content-Disposition'));
        $this->assertMatchesRegularExpression(
            '/filename="?katalog-tefa-jasa-\d{4}-\d{2}-\d{2}\.pdf"?/',
            (string) $response->headers->get('Content-Disposition')
        );
        $this->assertStringStartsWith('%PDF', $response->getContent());
    }

    public function test_product_catalog_contains_product_name_and_formatted_price(): void
    {
        Product::firstOrCreate(
            ['nama_produk' => 'Cetak Mug Satu'],
            [
                'jurusan_id' => $this->jurusan->id,
                'deskripsi' => 'Mug keramik dengan cetak sablon warna.',
                'harga' => 150000,
                'stok' => 12,
            ]
        );

        $text = $this->extractPdfText(
            $this->actingAs($this->superAdmin)->get(route('superadmin.katalog.produk'))
        );

        $this->assertStringContainsString('Cetak Mug Satu', $text);
        $this->assertStringContainsString('Rp150.000', $text);
    }

    public function test_service_catalog_only_contains_active_services(): void
    {
        Service::firstOrCreate(
            ['nama_layanan' => 'Jasa Sablon Satu'],
            [
                'department_id' => $this->jurusan->id,
                'slug' => 'jasa-sablon-satu',
                'deskripsi' => 'Sablon kaos satuan warna.',
                'estimasi_harga' => 500000,
                'is_active' => true,
            ]
        );

        Service::firstOrCreate(
            ['nama_layanan' => 'Jasa Rahasia'],
            [
                'department_id' => $this->jurusan->id,
                'slug' => 'jasa-rahasia',
                'deskripsi' => 'Layanan yang belum ditampilkan.',
                'estimasi_harga' => 999999,
                'is_active' => false,
            ]
        );

        $text = $this->extractPdfText(
            $this->actingAs($this->superAdmin)->get(route('superadmin.katalog.jasa'))
        );

        $this->assertStringContainsString('Jasa Sablon Satu', $text);
        $this->assertStringContainsString('Mulai Rp500.000', $text);
        $this->assertStringNotContainsString('Jasa Rahasia', $text);
    }

    public function test_empty_catalog_still_renders_a_downloadable_pdf(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('superadmin.katalog.produk'));

        $response->assertOk();
        $this->assertStringStartsWith('%PDF', $response->getContent());
        $this->assertStringContainsString('Belum ada data', $this->extractPdfText($response));
    }

    public function test_pelanggan_cannot_download_catalog(): void
    {
        $this->actingAs($this->pelanggan)
            ->get(route('superadmin.katalog.produk'))
            ->assertRedirect('/');
    }

    public function test_guest_cannot_download_catalog(): void
    {
        $this->get(route('superadmin.katalog.jasa'))->assertRedirect(route('login'));
    }

    public function test_dashboard_shows_catalog_download_buttons(): void
    {
        $this->actingAs($this->superAdmin)
            ->get(route('superadmin.dashboard'))
            ->assertOk()
            ->assertSee(route('superadmin.katalog.produk'))
            ->assertSee(route('superadmin.katalog.jasa'));
    }

    public function test_super_admin_can_preview_product_catalog_in_browser(): void
    {
        Product::firstOrCreate(
            ['nama_produk' => 'Mug Pratinjau'],
            [
                'jurusan_id' => $this->jurusan->id,
                'deskripsi' => 'Mug untuk pengujian pratinjau.',
                'harga' => 75000,
                'stok' => 5,
            ]
        );

        $response = $this->actingAs($this->superAdmin)->get(route('superadmin.katalog.preview', 'produk'));

        $response->assertOk();
        $this->assertStringContainsString('text/html', (string) $response->headers->get('Content-Type'));
        $response->assertSee('PRATINJAU — Katalog Produk');
        $response->assertSee('Unduh PDF');
        $response->assertSee('Cetak Langsung');
        $response->assertSee('Kembali ke Dashboard');
        $response->assertSee(route('superadmin.katalog.produk'), false);
        $response->assertSee('Mug Pratinjau');
        $response->assertSee('Rp75.000');
    }

    public function test_super_admin_can_preview_service_catalog_in_browser(): void
    {
        Service::firstOrCreate(
            ['nama_layanan' => 'Jasa Sablon Pratinjau'],
            [
                'department_id' => $this->jurusan->id,
                'slug' => 'jasa-sablon-pratinjau',
                'deskripsi' => 'Layanan untuk pengujian pratinjau.',
                'estimasi_harga' => 300000,
                'is_active' => true,
            ]
        );

        $response = $this->actingAs($this->superAdmin)->get(route('superadmin.katalog.preview', 'jasa'));

        $response->assertOk();
        $this->assertStringContainsString('text/html', (string) $response->headers->get('Content-Type'));
        $response->assertSee('PRATINJAU — Katalog Layanan Jasa');
        $response->assertSee(route('superadmin.katalog.jasa'), false);
        $response->assertSee('Jasa Sablon Pratinjau');
        $response->assertSee('Mulai Rp300.000');
    }

    public function test_preview_page_shows_inactive_services_same_as_pdf(): void
    {
        Service::firstOrCreate(
            ['nama_layanan' => 'Jasa Nonaktif Pratinjau'],
            [
                'department_id' => $this->jurusan->id,
                'slug' => 'jasa-nonaktif-pratinjau',
                'deskripsi' => 'Layanan nonaktif.',
                'estimasi_harga' => 111111,
                'is_active' => false,
            ]
        );

        $response = $this->actingAs($this->superAdmin)->get(route('superadmin.katalog.preview', 'jasa'));

        $response->assertOk();
        $response->assertDontSee('Jasa Nonaktif Pratinjau');
    }

    public function test_preview_rejects_unknown_catalog_type(): void
    {
        $this->actingAs($this->superAdmin)
            ->get(route('superadmin.katalog.preview', 'buku'))
            ->assertNotFound();
    }

    public function test_pelanggan_cannot_access_catalog_preview(): void
    {
        $this->actingAs($this->pelanggan)
            ->get(route('superadmin.katalog.preview', 'produk'))
            ->assertRedirect('/');
    }

    public function test_guest_cannot_access_catalog_preview(): void
    {
        $this->get(route('superadmin.katalog.preview', 'jasa'))
            ->assertRedirect(route('login'));
    }

    public function test_dashboard_shows_catalog_preview_buttons(): void
    {
        $this->actingAs($this->superAdmin)
            ->get(route('superadmin.dashboard'))
            ->assertOk()
            ->assertSee(route('superadmin.katalog.preview', 'produk'))
            ->assertSee(route('superadmin.katalog.preview', 'jasa'));
    }

    /**
     * Ambil teks yang benar-benar tertulis di dalam PDF (stream sudah didekompresi).
     */
    private function extractPdfText(TestResponse $response): string
    {
        $raw = $response->getContent();
        $out = '';
        $offset = 0;

        while (($pos = strpos($raw, 'stream', $offset)) !== false) {
            $start = $pos + 6;
            if (substr($raw, $start, 2) === "\r\n") {
                $start += 2;
            } elseif ($raw[$start] === "\n") {
                $start += 1;
            }

            $end = strpos($raw, 'endstream', $start);
            if ($end === false) {
                break;
            }

            $chunk = substr($raw, $start, $end - $start);
            $offset = $end + 9;

            $decoded = @gzuncompress($chunk);
            if ($decoded === false) {
                $decoded = @gzinflate($chunk);
            }
            $out .= $decoded === false ? $chunk : $decoded;
        }

        // dompdf menulis teks sebagai UTF-16BE tanpa BOM: "A", "\0", "B", "\0", ...
        // Kembalikan versi yang dinormalisasi supaya bisa dicari sebagai string biasa.
        $plain = str_replace("\0", '', $out);

        return $plain.$out;
    }
}
