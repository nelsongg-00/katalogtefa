<?php

namespace Tests\Feature;

use App\Models\Jurusan;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PublicCatalogPaginationTest extends TestCase
{
    use DatabaseTransactions;

    protected Jurusan $rpl;

    protected Jurusan $dkv;

    protected Jurusan $ani;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rpl = Jurusan::firstOrCreate(
            ['kode' => 'RPL'],
            [
                'nama_jurusan' => 'Rekayasa Perangkat Lunak',
                'deskripsi_profil' => 'Unit Kejuruan Software Engineering.',
                'status_aktif' => true,
            ]
        );

        $this->dkv = Jurusan::firstOrCreate(
            ['kode' => 'DKV'],
            [
                'nama_jurusan' => 'Desain Komunikasi Visual',
                'deskripsi_profil' => 'Unit Kejuruan Visual Communication.',
                'status_aktif' => true,
            ]
        );

        $this->ani = Jurusan::firstOrCreate(
            ['kode' => 'ANI'],
            [
                'nama_jurusan' => 'Animasi',
                'deskripsi_profil' => 'Unit Kejuruan Animasi.',
                'status_aktif' => true,
            ]
        );
    }

    /**
     * created_at di-set ke masa depan supaya marker selalu menjadi data
     * terbaru di tabel — page 1 pasti berisi marker ini berapa pun data
     * sisa yang menempel di database testing.
     */
    private function makeProduct(string $nama, Jurusan $jurusan, string $deskripsi = '', int $minutesFromNow = 10): Product
    {
        $product = new Product([
            'jurusan_id' => $jurusan->id,
            'nama_produk' => $nama,
            'deskripsi' => $deskripsi !== '' ? $deskripsi : 'Deskripsi untuk '.$nama,
            'harga' => 15000,
            'stok' => 5,
        ]);
        $product->created_at = now()->addMinutes($minutesFromNow);
        $product->save();

        return $product;
    }

    private function makeService(string $nama, Jurusan $dept, string $deskripsi = '', int $minutesFromNow = 10, bool $active = true): Service
    {
        $service = new Service([
            'department_id' => $dept->id,
            'nama_layanan' => $nama,
            'slug' => 'marker-'.str()->slug($nama),
            'deskripsi' => $deskripsi !== '' ? $deskripsi : 'Deskripsi untuk '.$nama,
            'estimasi_harga' => 250000,
            'is_active' => $active,
        ]);
        $service->created_at = now()->addMinutes($minutesFromNow);
        $service->save();

        return $service;
    }

    public function test_produk_page_shows_twelve_cards_and_moves_the_oldest_to_page_two(): void
    {
        foreach (range(0, 12) as $i) {
            $this->makeProduct(sprintf('Marker Paginasi Produk %02d', $i), $this->rpl, minutesFromNow: 10 + $i);
        }

        $pageOne = $this->get(route('produk'));

        $pageOne->assertStatus(200);
        $this->assertSame(12, substr_count($pageOne->getContent(), 'class="produk-card"'));
        $pageOne->assertSee('Marker Paginasi Produk 12');
        $pageOne->assertDontSee('Marker Paginasi Produk 00');
        $pageOne->assertSee('Navigasi Halaman');

        $pageTwo = $this->get(route('produk', ['page' => 2]));

        $pageTwo->assertStatus(200);
        $pageTwo->assertSee('Marker Paginasi Produk 00');
        $pageTwo->assertDontSee('Marker Paginasi Produk 12');
    }

    public function test_produk_jurusan_filter_is_applied_server_side(): void
    {
        $this->makeProduct('Marker Produk Jurusan RPL', $this->rpl, minutesFromNow: 20);
        $this->makeProduct('Marker Produk Jurusan DKV', $this->dkv, minutesFromNow: 21);

        $unfiltered = $this->get(route('produk'));
        $unfiltered->assertSee('Marker Produk Jurusan DKV');

        $filtered = $this->get(route('produk', ['jurusan' => 'RPL']));
        $filtered->assertSee('Marker Produk Jurusan RPL');
        $filtered->assertDontSee('Marker Produk Jurusan DKV');
    }

    public function test_produk_animasi_tab_matches_ani_kode(): void
    {
        $this->makeProduct('Marker Produk Jurusan Animasi', $this->ani, minutesFromNow: 22);
        $this->makeProduct('Marker Produk Jurusan RPL Lagi', $this->rpl, minutesFromNow: 23);

        $response = $this->get(route('produk', ['jurusan' => 'ANIMASI']));

        $response->assertSee('Marker Produk Jurusan Animasi');
        $response->assertDontSee('Marker Produk Jurusan RPL Lagi');
    }

    public function test_produk_search_matches_name_and_description_server_side(): void
    {
        $this->makeProduct('Marker Kue Lapis Legit', $this->rpl, 'Kue tradisional khas Tanjungpinang.', 24);
        $this->makeProduct('Marker Tas Ransel Sekolah', $this->dkv, 'Tas sekolah bahan kanvas tebal.', 25);

        $byName = $this->get(route('produk', ['q' => 'lapis']));
        $byName->assertSee('Marker Kue Lapis Legit');
        $byName->assertDontSee('Marker Tas Ransel Sekolah');

        $byDesc = $this->get(route('produk', ['q' => 'kanvas']));
        $byDesc->assertSee('Marker Tas Ransel Sekolah');
        $byDesc->assertDontSee('Marker Kue Lapis Legit');
    }

    public function test_produk_pagination_links_preserve_jurusan_and_search_query(): void
    {
        foreach (range(0, 12) as $i) {
            $this->makeProduct(sprintf('Marker Khusus Filter %02d', $i), $this->rpl, minutesFromNow: 30 + $i);
        }

        $response = $this->get(route('produk', ['jurusan' => 'RPL', 'q' => 'khusus']));

        $response->assertStatus(200);
        $response->assertSee('Marker Khusus Filter 12');
        $response->assertSee('Navigasi Halaman');
        $response->assertSee('q=khusus&amp;page=2', false);
    }

    public function test_produk_empty_filter_result_shows_reset_state_without_pagination_bar(): void
    {
        $response = $this->get(route('produk', ['jurusan' => 'RPL', 'q' => 'katakatatakjelas']));

        $response->assertStatus(200);
        $this->assertSame(0, substr_count($response->getContent(), 'class="produk-card"'));
        $response->assertSee('Produk Tidak Ditemukan');
        $response->assertSee('Reset Filter');
        $response->assertDontSee('Navigasi Halaman');
    }

    public function test_jasa_page_shows_twelve_cards_and_moves_the_oldest_to_page_two(): void
    {
        foreach (range(0, 12) as $i) {
            $this->makeService(sprintf('Marker Paginasi Layanan %02d', $i), $this->rpl, minutesFromNow: 10 + $i);
        }

        $pageOne = $this->get(route('jasa'));

        $pageOne->assertStatus(200);
        $this->assertSame(12, substr_count($pageOne->getContent(), 'class="jasa-card"'));
        $pageOne->assertSee('Marker Paginasi Layanan 12');
        $pageOne->assertDontSee('Marker Paginasi Layanan 00');
        $pageOne->assertSee('Navigasi Halaman');

        $pageTwo = $this->get(route('jasa', ['page' => 2]));

        $pageTwo->assertStatus(200);
        $pageTwo->assertSee('Marker Paginasi Layanan 00');
        $pageTwo->assertDontSee('Marker Paginasi Layanan 12');
    }

    public function test_jasa_jurusan_filter_is_applied_server_side(): void
    {
        $this->makeService('Marker Layanan RPL Web', $this->rpl, 'Jasa pembuatan website profil.', 20);
        $this->makeService('Marker Layanan DKV Desain', $this->dkv, 'Jasa desain poster dan banner.', 21);

        $unfiltered = $this->get(route('jasa'));
        $unfiltered->assertSee('Marker Layanan DKV Desain');

        $filtered = $this->get(route('jasa', ['jurusan' => 'RPL']));
        $filtered->assertSee('Marker Layanan RPL Web');
        $filtered->assertDontSee('Marker Layanan DKV Desain');
    }

    public function test_jasa_search_matches_description_server_side(): void
    {
        $this->makeService('Marker Layanan RPL Web', $this->rpl, 'Jasa pembuatan website profil.', 22);
        $this->makeService('Marker Layanan DKV Desain', $this->dkv, 'Jasa desain poster dan banner.', 23);

        $searched = $this->get(route('jasa', ['q' => 'banner']));

        $searched->assertSee('Marker Layanan DKV Desain');
        $searched->assertDontSee('Marker Layanan RPL Web');
    }

    public function test_jasa_inactive_service_is_never_listed(): void
    {
        $this->makeService('Marker Layanan Nonaktif Tersembunyi', $this->rpl, 'Layanan sudah tidak aktif.', 24, false);

        $response = $this->get(route('jasa'));

        $response->assertDontSee('Marker Layanan Nonaktif Tersembunyi');
    }

    public function test_jasa_pagination_links_preserve_jurusan_query(): void
    {
        foreach (range(0, 12) as $i) {
            $this->makeService(sprintf('Marker Khusus Layanan %02d', $i), $this->rpl, minutesFromNow: 30 + $i);
        }

        $response = $this->get(route('jasa', ['jurusan' => 'RPL']));

        $response->assertStatus(200);
        $response->assertSee('Marker Khusus Layanan 12');
        $response->assertSee('Navigasi Halaman');
        $response->assertSee('jurusan=RPL&amp;page=2', false);
    }

    public function test_jasa_empty_filter_result_shows_reset_state_without_pagination_bar(): void
    {
        $response = $this->get(route('jasa', ['jurusan' => 'RPL', 'q' => 'katakatatakjelas']));

        $response->assertStatus(200);
        $this->assertSame(0, substr_count($response->getContent(), 'class="jasa-card"'));
        $response->assertSee('Layanan Tidak Ditemukan');
        $response->assertSee('Reset Filter');
        $response->assertDontSee('Navigasi Halaman');
    }
}
