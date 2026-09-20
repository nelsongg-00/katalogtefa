<?php

namespace Tests\Feature;

use App\Models\Jurusan;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmartRedirectAuthTest extends TestCase
{
    use RefreshDatabase;

    private Jurusan $jurusan;

    private Produk $produk;

    protected function setUp(): void
    {
        parent::setUp();

        $this->jurusan = Jurusan::create([
            'nama_jurusan' => 'Rekayasa Perangkat Lunak',
            'slug' => 'rekayasa-perangkat-lunak',
            'kode' => 'RPL',
            'status_aktif' => true,
        ]);

        $this->produk = Produk::create([
            'jurusan_id' => $this->jurusan->id,
            'tipe' => 'Produk Fisik',
            'nama_produk' => 'Aplikasi Kasir Pro',
            'harga' => 500000,
            'deskripsi' => 'Aplikasi POS kasir.',
            'stok' => 10,
        ]);
    }

    public function test_guest_accessing_checkout_is_redirected_to_login_with_intended_url(): void
    {
        $targetUrl = route('checkout.show', $this->produk->id);

        $response = $this->get($targetUrl);

        $response->assertRedirect(route('login'));
        $this->assertEquals($targetUrl, session('url.intended'));
    }

    public function test_login_from_checkout_redirects_to_intended_checkout_with_welcome_toast(): void
    {
        $targetUrl = route('checkout.show', $this->produk->id);

        $pelanggan = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
            'is_active' => true,
        ]);

        // Simulasikan guest mencoba checkout lalu login
        $this->withSession(['url.intended' => $targetUrl])
            ->post('/login', [
                'email' => 'budi@example.com',
                'password' => 'password',
            ])
            ->assertRedirect($targetUrl)
            ->assertSessionHas('toast_success', 'Selamat datang kembali, Budi Santoso!');
    }

    public function test_register_from_checkout_redirects_to_intended_checkout_with_welcome_toast(): void
    {
        $targetUrl = route('checkout.show', $this->produk->id);

        // Simulasikan guest mencoba checkout lalu memilih daftar akun baru
        $this->withSession(['url.intended' => $targetUrl])
            ->post('/register', [
                'name' => 'Citra Lestari',
                'email' => 'citra@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ])
            ->assertRedirect($targetUrl)
            ->assertSessionHas('toast_success', 'Pendaftaran berhasil! Selamat datang, Citra Lestari!');

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'citra@example.com',
            'role' => 'pelanggan',
        ]);
    }

    public function test_normal_login_pelanggan_redirects_to_home_with_toast(): void
    {
        $pelanggan = User::create([
            'name' => 'Budi Biasa',
            'email' => 'budi.biasa@example.com',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'budi.biasa@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('toast_success', 'Selamat datang kembali, Budi Biasa!');
    }

    public function test_normal_login_superadmin_redirects_to_superadmin_dashboard(): void
    {
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'superadmin@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('superadmin.dashboard'));
        $response->assertSessionHas('toast_success', 'Selamat datang kembali, Super Admin!');
    }

    public function test_normal_login_admin_jurusan_redirects_to_admin_dashboard(): void
    {
        $admin = User::create([
            'name' => 'Admin RPL',
            'email' => 'admin.rpl@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin_jurusan',
            'jurusan_id' => $this->jurusan->id,
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'admin.rpl@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('toast_success', 'Selamat datang kembali, Admin RPL!');
    }

    public function test_normal_login_worker_redirects_to_worker_dashboard(): void
    {
        $worker = User::create([
            'name' => 'Siswa Worker',
            'email' => 'worker.siswa@example.com',
            'password' => bcrypt('password'),
            'role' => 'worker',
            'jurusan_id' => $this->jurusan->id,
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'worker.siswa@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('worker.dashboard'));
        $response->assertSessionHas('toast_success', 'Selamat datang kembali, Siswa Worker!');
    }
}
