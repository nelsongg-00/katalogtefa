<?php

namespace Tests\Feature;

use App\Models\Jurusan;
use App\Models\Order;
use App\Models\Product;
use App\Models\Project;
use App\Models\ProjectLog;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CatalogModalAndAuthTest extends TestCase
{
    use DatabaseTransactions;

    protected Jurusan $jurusan;

    protected Product $product;

    protected Service $service;

    protected User $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->jurusan = Jurusan::firstOrCreate(
            ['kode' => 'RPL'],
            [
                'nama_jurusan' => 'Rekayasa Perangkat Lunak',
                'deskripsi_profil' => 'Unit Kejuruan Software Engineering.',
                'status_aktif' => true,
                'lokasi_pengambilan' => 'Lab RPL Lantai 2',
            ]
        );

        $this->product = Product::create([
            'nama_produk' => 'Mousepad TeFa Gaming RPL',
            'harga' => 45000,
            'stok' => 10,
            'deskripsi' => 'Mousepad presisi tinggi buatan siswa jurusan Rekayasa Perangkat Lunak SMKN 4.',
            'jurusan_id' => $this->jurusan->id,
            'is_active' => true,
        ]);

        $this->service = Service::create([
            'nama_layanan' => 'Jasa Pembuatan Web Profil Perusahaan',
            'slug' => 'jasa-pembuatan-web-profil-perusahaan',
            'estimasi_harga' => 500000,
            'deskripsi' => 'Layanan pembuatan website profil perusahaan modern, responsif, dan SEO friendly.',
            'department_id' => $this->jurusan->id,
            'is_active' => true,
        ]);

        $this->client = User::firstOrCreate(
            ['email' => 'client_modal_test@example.com'],
            [
                'name' => 'Siti Nurhaliza',
                'password' => bcrypt('password'),
                'role' => 'pelanggan',
            ]
        );
    }

    public function test_product_catalog_displays_products_and_interactive_modal(): void
    {
        $response = $this->get(route('produk'));

        $response->assertStatus(200);
        $response->assertSee('Mousepad TeFa Gaming RPL');
        $response->assertSee('product-detail-modal');
        $response->assertSee('openProductModal');
        $response->assertSee('Pesan Sekarang (Checkout)');
        $response->assertSee('Silakan login terlebih dahulu untuk melakukan pemesanan');
    }

    public function test_service_catalog_displays_services_and_interactive_modal(): void
    {
        $response = $this->get(route('jasa'));

        $response->assertStatus(200);
        $response->assertSee('Jasa Pembuatan Web Profil Perusahaan');
        $response->assertSee('service-detail-modal');
        $response->assertSee('openServiceModal');
        $response->assertSee('Pesan / Konsultasi Layanan');
        $response->assertSee('Silakan login terlebih dahulu untuk melakukan pemesanan');
    }

    public function test_guest_cannot_access_checkout_directly_and_is_redirected_to_login_with_intended_url(): void
    {
        $response = $this->get(route('checkout.show', $this->product->id));

        $response->assertRedirect(route('login'));
        $this->assertEquals(route('checkout.show', $this->product->id), session('url.intended'));
    }

    public function test_guest_cannot_order_service_directly_and_is_redirected_to_login_with_intended_url(): void
    {
        $response = $this->get(route('services.order', $this->service->id));

        $response->assertRedirect(route('login'));
        $this->assertEquals(route('services.order', $this->service->id), session('url.intended'));
    }

    public function test_login_page_preserves_redirect_query_param_into_intended_session(): void
    {
        $targetUrl = route('checkout.show', $this->product->id);

        $response = $this->get(route('login', ['redirect' => $targetUrl]));

        $response->assertStatus(200);
        $this->assertEquals($targetUrl, session('url.intended'));
    }

    public function test_authenticated_client_can_order_service_and_order_is_recorded_with_wa_redirect(): void
    {
        $response = $this->actingAs($this->client)->get(route('services.order', $this->service->id));

        // Harus dialihkan ke WhatsApp dengan nomor tujuan dan pesan terenkode
        $response->assertRedirect();
        $targetUrl = $response->headers->get('Location');
        $this->assertStringContainsString('https://wa.me/6281234567890', $targetUrl);
        $this->assertStringContainsString('Jasa%20Pembuatan%20Web%20Profil%20Perusahaan', $targetUrl);

        // Verifikasi Order tercatat di DB
        $order = Order::where('service_id', $this->service->id)
            ->where('user_id', $this->client->id)
            ->latest()
            ->first();

        $this->assertNotNull($order);
        $this->assertStringStartsWith('TEFA-JASA-', $order->order_code);
        $this->assertEquals('pending', $order->status);
        $this->assertEquals($this->service->estimasi_harga, $order->total_biaya);

        // Verifikasi Project & ProjectLog terbuat
        $this->assertNotNull($order->project_id);
        $this->assertDatabaseHas('projects', [
            'id' => $order->project_id,
            'jurusan_id' => $this->jurusan->id,
            'status' => 'pending',
        ]);
        $this->assertDatabaseHas('project_logs', [
            'project_id' => $order->project_id,
        ]);
    }

    public function test_authenticated_client_can_view_checkout_page(): void
    {
        $response = $this->actingAs($this->client)->get(route('checkout.show', $this->product->id));

        $response->assertStatus(200);
        $response->assertSee('Mousepad TeFa Gaming RPL');
        $lokasi = $this->jurusan->lokasi_pengambilan ?? 'Lab';
        $response->assertSee($lokasi);
    }
}
