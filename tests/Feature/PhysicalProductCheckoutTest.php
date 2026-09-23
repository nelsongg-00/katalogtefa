<?php

namespace Tests\Feature;

use App\Models\Jurusan;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PhysicalProductCheckoutTest extends TestCase
{
    use DatabaseTransactions;

    protected Jurusan $jurusanRPL;

    protected Jurusan $jurusanTKJ;

    protected User $client;

    protected User $adminRPL;

    protected User $adminTKJ;

    protected Product $productRPL;

    protected function setUp(): void
    {
        parent::setUp();

        $this->jurusanRPL = Jurusan::firstOrCreate(
            ['nama_jurusan' => 'Rekayasa Perangkat Lunak'],
            [
                'deskripsi_profil' => 'Software engineering program.',
                'kode' => 'RPL',
                'status_aktif' => true,
                'lokasi_pengambilan' => 'Lab Komputer & Rekayasa Perangkat Lunak',
            ]
        );

        $this->jurusanTKJ = Jurusan::firstOrCreate(
            ['nama_jurusan' => 'Teknik Komputer Jaringan'],
            [
                'deskripsi_profil' => 'Networking program.',
                'kode' => 'TKJ',
                'status_aktif' => true,
                'lokasi_pengambilan' => 'Lab Jaringan & Server TKJ',
            ]
        );

        $this->client = User::firstOrCreate(
            ['email' => 'client_checkout_test@example.com'],
            [
                'name' => 'Budi Santoso Test',
                'password' => bcrypt('password'),
                'role' => 'pelanggan',
                'jurusan_id' => null,
            ]
        );

        $this->adminRPL = User::firstOrCreate(
            ['email' => 'admin_rpl_checkout@example.com'],
            [
                'name' => 'Admin RPL Checkout',
                'password' => bcrypt('password'),
                'role' => 'admin_jurusan',
                'jurusan_id' => $this->jurusanRPL->id,
            ]
        );

        $this->adminTKJ = User::firstOrCreate(
            ['email' => 'admin_tkj_checkout@example.com'],
            [
                'name' => 'Admin TKJ Checkout',
                'password' => bcrypt('password'),
                'role' => 'admin_jurusan',
                'jurusan_id' => $this->jurusanTKJ->id,
            ]
        );

        $this->productRPL = Product::firstOrCreate(
            ['nama_produk' => 'Modul Panduan Praktikum RPL TeFa'],
            [
                'jurusan_id' => $this->jurusanRPL->id,
                'deskripsi' => 'Buku panduan praktikum pemrograman web Laravel.',
                'harga' => 75000,
                'stok' => 10,
            ]
        );
    }

    public function test_guest_redirected_to_login_when_accessing_checkout(): void
    {
        $response = $this->get(route('checkout.show', $this->productRPL->id));
        $response->assertRedirect(route('login'));
    }

    public function test_client_can_view_checkout_with_dynamic_pickup_location(): void
    {
        $response = $this->actingAs($this->client)->get(route('checkout.show', $this->productRPL->id));

        $response->assertStatus(200);
        $response->assertSee('Modul Panduan Praktikum RPL TeFa');
        $response->assertSee('Bayar di Tempat / Cash on Delivery (COD)');
        $response->assertSee('Ambil di Tempat (Self Pick-up)');
        $response->assertSee('Lab Komputer & Rekayasa Perangkat Lunak');
    }

    public function test_client_cannot_checkout_more_than_stock(): void
    {
        $initialStock = $this->productRPL->stok;

        $response = $this->actingAs($this->client)->post(route('checkout.store', $this->productRPL->id), [
            'jumlah' => $initialStock + 5,
            'customer_phone' => '081234567890',
        ]);

        $response->assertSessionHasErrors('jumlah');
        $this->assertEquals($initialStock, $this->productRPL->fresh()->stok);
    }

    public function test_client_can_checkout_physical_product_cod_and_stock_decreases(): void
    {
        $initialStock = $this->productRPL->stok;
        $orderQty = 2;

        $response = $this->actingAs($this->client)->post(route('checkout.store', $this->productRPL->id), [
            'jumlah' => $orderQty,
            'customer_phone' => '081298765432',
            'catatan_pelanggan' => 'Tolong disiapkan saat jam istirahat sekolah.',
        ]);

        $this->assertEquals($initialStock - $orderQty, $this->productRPL->fresh()->stok);

        $order = Order::where('product_id', $this->productRPL->id)
            ->where('user_id', $this->client->id)
            ->latest()
            ->first();

        $this->assertNotNull($order);
        $this->assertStringStartsWith('TEFA-FISIK-', $order->order_code);
        $this->assertEquals('menunggu_konfirmasi', $order->status);
        $this->assertEquals('cod', $order->metode_pembayaran);
        $this->assertEquals('pickup', $order->metode_pengiriman);
        $this->assertEquals('Lab Komputer & Rekayasa Perangkat Lunak', $order->lokasi_pengambilan);
        $this->assertEquals(75000 * $orderQty, $order->total_harga);

        // Pastikan order log awal tercatat
        $this->assertDatabaseHas('order_logs', [
            'order_id' => $order->id,
            'user_id' => $this->client->id,
            'aksi' => 'buat_pesanan',
        ]);

        // Pastikan notifikasi pesan masuk untuk Admin Jurusan tercatat
        $this->assertDatabaseHas('pesan_masuks', [
            'jurusan_id' => $this->jurusanRPL->id,
            'is_read' => false,
            'subjek' => 'Pesanan Produk Fisik Baru',
        ]);

        // Pastikan pesanan muncul di daftar pesanan Admin Jurusan
        $adminOrdersResponse = $this->actingAs($this->adminRPL)->get(route('admin.orders.index'));
        $adminOrdersResponse->assertStatus(200);
        $adminOrdersResponse->assertSee($order->order_code);
        $adminOrdersResponse->assertSee('Menunggu Konfirmasi');

        // Pastikan pesanan muncul di ringkasan dashboard Admin Jurusan
        $adminDashboardResponse = $this->actingAs($this->adminRPL)->get(route('admin.dashboard'));
        $adminDashboardResponse->assertStatus(200);

        $response->assertRedirect(route('client.orders.show', $order->id));
    }

    public function test_client_can_view_order_stepper_and_pickup_alert(): void
    {
        $order = Order::create([
            'order_code' => 'TEFA-FISIK-9999',
            'user_id' => $this->client->id,
            'product_id' => $this->productRPL->id,
            'department_id' => $this->jurusanRPL->id,
            'customer_name' => $this->client->name,
            'customer_phone' => '081234567890',
            'jumlah' => 1,
            'total_biaya' => 75000,
            'total_harga' => 75000,
            'metode_pembayaran' => 'cod',
            'metode_pengiriman' => 'pickup',
            'lokasi_pengambilan' => 'Lab Komputer & Rekayasa Perangkat Lunak',
            'status' => 'bisa_diambil',
        ]);

        $response = $this->actingAs($this->client)->get(route('client.orders.show', $order->id));

        $response->assertStatus(200);
        $response->assertSee('TEFA-FISIK-9999');
        $response->assertSee('Pesanan Anda siap diambil di');
        $response->assertSee('Lab Komputer & Rekayasa Perangkat Lunak');
        $response->assertSee('Rp 75.000');
    }

    public function test_admin_jurusan_can_progress_order_workflow(): void
    {
        $order = Order::create([
            'order_code' => 'TEFA-FISIK-8888',
            'user_id' => $this->client->id,
            'product_id' => $this->productRPL->id,
            'department_id' => $this->jurusanRPL->id,
            'customer_name' => $this->client->name,
            'customer_phone' => '081234567890',
            'jumlah' => 1,
            'total_biaya' => 75000,
            'total_harga' => 75000,
            'metode_pembayaran' => 'cod',
            'metode_pengiriman' => 'pickup',
            'lokasi_pengambilan' => 'Lab Komputer & Rekayasa Perangkat Lunak',
            'status' => 'menunggu_konfirmasi',
        ]);

        // 1. Terima & Proses -> sedang_dikemas
        $response1 = $this->actingAs($this->adminRPL)->patch(route('admin.orders.physicalStatus', $order->id), [
            'status' => 'sedang_dikemas',
        ]);
        $response1->assertRedirect();
        $this->assertEquals('sedang_dikemas', $order->fresh()->status);

        // 2. Siap Diambil -> bisa_diambil
        $response2 = $this->actingAs($this->adminRPL)->patch(route('admin.orders.physicalStatus', $order->id), [
            'status' => 'bisa_diambil',
        ]);
        $response2->assertRedirect();
        $this->assertEquals('bisa_diambil', $order->fresh()->status);

        // 3. Selesaikan Transaksi -> selesai
        $response3 = $this->actingAs($this->adminRPL)->patch(route('admin.orders.physicalStatus', $order->id), [
            'status' => 'selesai',
        ]);
        $response3->assertRedirect();
        $this->assertEquals('selesai', $order->fresh()->status);
    }

    public function test_admin_jurusan_cancelling_order_restores_stock(): void
    {
        $initialStock = $this->productRPL->stok;

        $order = Order::create([
            'order_code' => 'TEFA-FISIK-7777',
            'user_id' => $this->client->id,
            'product_id' => $this->productRPL->id,
            'department_id' => $this->jurusanRPL->id,
            'customer_name' => $this->client->name,
            'customer_phone' => '081234567890',
            'jumlah' => 2,
            'total_biaya' => 150000,
            'total_harga' => 150000,
            'metode_pembayaran' => 'cod',
            'metode_pengiriman' => 'pickup',
            'lokasi_pengambilan' => 'Lab Komputer & Rekayasa Perangkat Lunak',
            'status' => 'menunggu_konfirmasi',
        ]);

        // Batalkan pesanan
        $response = $this->actingAs($this->adminRPL)->patch(route('admin.orders.physicalStatus', $order->id), [
            'status' => 'dibatalkan',
        ]);

        $response->assertRedirect();
        $this->assertEquals('dibatalkan', $order->fresh()->status);
        $this->assertEquals($initialStock + 2, $this->productRPL->fresh()->stok);
    }

    public function test_admin_jurusan_cannot_manage_other_department_physical_orders(): void
    {
        $order = Order::create([
            'order_code' => 'TEFA-FISIK-6666',
            'user_id' => $this->client->id,
            'product_id' => $this->productRPL->id,
            'department_id' => $this->jurusanRPL->id,
            'customer_name' => $this->client->name,
            'customer_phone' => '081234567890',
            'jumlah' => 1,
            'total_biaya' => 75000,
            'total_harga' => 75000,
            'metode_pembayaran' => 'cod',
            'metode_pengiriman' => 'pickup',
            'lokasi_pengambilan' => 'Lab Komputer & Rekayasa Perangkat Lunak',
            'status' => 'menunggu_konfirmasi',
        ]);

        // Admin TKJ mencoba mengupdate pesanan RPL
        $response = $this->actingAs($this->adminTKJ)->patch(route('admin.orders.physicalStatus', $order->id), [
            'status' => 'sedang_dikemas',
        ]);

        $response->assertStatus(403);
    }
}
