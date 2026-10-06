<?php

namespace Tests\Feature;

use App\Models\DetailPesanan;
use App\Models\Jurusan;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminModularTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;

    private User $adminJurusan;

    private User $worker;

    private User $pelanggan;

    private Jurusan $jurusan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->jurusan = Jurusan::create([
            'nama_jurusan' => 'Rekayasa Perangkat Lunak',
            'slug' => 'rekayasa-perangkat-lunak',
            'kode' => 'RPL',
            'kepala_jurusan' => 'Bu Ratna Sari, S.Kom',
            'deskripsi' => 'Pengembangan perangkat lunak.',
            'status_aktif' => true,
        ]);

        $this->superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $this->adminJurusan = User::create([
            'name' => 'Admin RPL',
            'email' => 'adminrpl@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin_jurusan',
            'jurusan_id' => $this->jurusan->id,
            'is_active' => true,
        ]);

        $this->worker = User::create([
            'name' => 'Worker Siswa',
            'email' => 'worker@example.com',
            'password' => bcrypt('password'),
            'role' => 'worker',
            'jurusan_id' => $this->jurusan->id,
            'is_active' => true,
        ]);

        $this->pelanggan = User::create([
            'name' => 'Pelanggan Toko',
            'email' => 'pelanggan@example.com',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
            'is_active' => true,
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/superadmin');
        $response->assertRedirect('/login');
    }

    public function test_non_superadmin_cannot_access_superadmin_routes(): void
    {
        $response = $this->actingAs($this->pelanggan)->get('/superadmin');
        $response->assertRedirect('/');

        $response2 = $this->actingAs($this->worker)->get('/superadmin/users');
        $response2->assertRedirect('/');

        $response3 = $this->actingAs($this->adminJurusan)->get('/superadmin/departments');
        $response3->assertRedirect('/');
    }

    public function test_superadmin_can_access_dashboard_overview(): void
    {
        $response = $this->actingAs($this->superAdmin)->get('/superadmin');
        $response->assertStatus(200);
        $response->assertSee('RINGKASAN DASHBOARD');
        $response->assertSee('Total Transaksi Global');
        $response->assertSee('Total Pengguna Aktif');
        $response->assertSee('Total Jurusan TeFa');
    }

    public function test_superadmin_can_manage_departments(): void
    {
        // View departments
        $response = $this->actingAs($this->superAdmin)->get('/superadmin/departments');
        $response->assertStatus(200);
        $response->assertSee('DATA MASTER JURUSAN');
        $response->assertSee('Rekayasa Perangkat Lunak');

        // Store new department
        $postResponse = $this->actingAs($this->superAdmin)->post('/superadmin/departments', [
            'nama_jurusan' => 'Teknik Komputer Jaringan',
            'kode' => 'TKJ',
            'kepala_jurusan' => 'Pak Hendra Wijaya, S.T',
            'deskripsi' => 'Infrastruktur Jaringan.',
            'status_aktif' => 1,
        ]);
        $postResponse->assertRedirect(route('superadmin.departments.index'));
        $this->assertDatabaseHas('jurusans', ['nama_jurusan' => 'Teknik Komputer Jaringan', 'kode' => 'TKJ']);

        $newJurusan = Jurusan::where('kode', 'TKJ')->first();

        // Toggle department active status
        $toggleResponse = $this->actingAs($this->superAdmin)->patch("/superadmin/departments/{$newJurusan->id}/toggle");
        $toggleResponse->assertRedirect(route('superadmin.departments.index'));
        $this->assertFalse($newJurusan->fresh()->status_aktif);

        // Update department
        $putResponse = $this->actingAs($this->superAdmin)->put("/superadmin/departments/{$newJurusan->id}", [
            'nama_jurusan' => 'Teknik Komputer & Jaringan Updated',
            'kode' => 'TKJ',
            'kepala_jurusan' => 'Pak Hendra Wijaya, S.T',
            'deskripsi' => 'Updated deskripsi.',
            'status_aktif' => 1,
        ]);
        $putResponse->assertRedirect(route('superadmin.departments.index'));
        $this->assertDatabaseHas('jurusans', ['nama_jurusan' => 'Teknik Komputer & Jaringan Updated']);
    }

    public function test_superadmin_can_crud_users(): void
    {
        // View users
        $response = $this->actingAs($this->superAdmin)->get('/superadmin/users');
        $response->assertStatus(200);
        $response->assertSee('MANAJEMEN DATA MASTER USER');
        $response->assertSee($this->superAdmin->name);

        // Store new Admin Jurusan (satu-satunya role yang boleh dibuat Super Admin)
        $postResponse = $this->actingAs($this->superAdmin)->post('/superadmin/users', [
            'name' => 'Admin Jurusan Baru',
            'email' => 'newadmin@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'jurusan_id' => $this->jurusan->id,
            'is_active' => 1,
        ]);
        $postResponse->assertRedirect(route('superadmin.users.index'));
        $this->assertDatabaseHas('users', ['email' => 'newadmin@example.com', 'role' => 'admin_jurusan']);

        $createdUser = User::where('email', 'newadmin@example.com')->first();

        // Update user
        $putResponse = $this->actingAs($this->superAdmin)->put("/superadmin/users/{$createdUser->id}", [
            'name' => 'Admin Jurusan Updated',
            'email' => 'newadmin@example.com',
            'role' => 'admin_jurusan',
            'jurusan_id' => $this->jurusan->id,
            'is_active' => 1,
        ]);
        $putResponse->assertRedirect(route('superadmin.users.index'));
        $this->assertDatabaseHas('users', ['name' => 'Admin Jurusan Updated']);

        // Delete user
        $deleteResponse = $this->actingAs($this->superAdmin)->delete("/superadmin/users/{$createdUser->id}");
        $deleteResponse->assertRedirect(route('superadmin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $createdUser->id]);

        // Prevent self-deletion
        $selfDeleteResponse = $this->actingAs($this->superAdmin)->delete("/superadmin/users/{$this->superAdmin->id}");
        $selfDeleteResponse->assertRedirect(route('superadmin.users.index'));
        $this->assertDatabaseHas('users', ['id' => $this->superAdmin->id]);
    }

    public function test_superadmin_store_forces_admin_jurusan_role(): void
    {
        // Role yang dikirim client selalu diabaikan dan dipaksa ke admin_jurusan.
        foreach (['super_admin', 'worker', 'pelanggan'] as $spoofedRole) {
            $email = 'spoofed-'.strtolower($spoofedRole).'@example.com';

            $this->actingAs($this->superAdmin)->post('/superadmin/users', [
                'name' => 'Spoofed '.ucfirst($spoofedRole),
                'email' => $email,
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => $spoofedRole,
                'jurusan_id' => $this->jurusan->id,
                'is_active' => 1,
            ])->assertRedirect(route('superadmin.users.index'));

            $this->assertDatabaseHas('users', ['email' => $email, 'role' => 'admin_jurusan']);
        }
    }

    public function test_superadmin_store_requires_jurusan_and_strong_password(): void
    {
        // jurusan_id wajib karena role yang dibuat selalu admin_jurusan.
        $this->actingAs($this->superAdmin)->post('/superadmin/users', [
            'name' => 'Tanpa Jurusan',
            'email' => 'tanpa.jurusan@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'is_active' => 1,
        ])->assertSessionHasErrors('jurusan_id');

        $this->assertDatabaseMissing('users', ['email' => 'tanpa.jurusan@example.com']);

        // Password minimal 8 karakter.
        $this->actingAs($this->superAdmin)->post('/superadmin/users', [
            'name' => 'Password Pendek',
            'email' => 'password.pendek@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
            'jurusan_id' => $this->jurusan->id,
            'is_active' => 1,
        ])->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', ['email' => 'password.pendek@example.com']);

        // Password wajib dikonfirmasi.
        $this->actingAs($this->superAdmin)->post('/superadmin/users', [
            'name' => 'Tanpa Konfirmasi',
            'email' => 'tanpa.konfirmasi@example.com',
            'password' => 'password123',
            'jurusan_id' => $this->jurusan->id,
            'is_active' => 1,
        ])->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', ['email' => 'tanpa.konfirmasi@example.com']);
    }

    public function test_superadmin_can_view_and_filter_reports_and_print(): void
    {
        $produk = Produk::create([
            'jurusan_id' => $this->jurusan->id,
            'tipe' => 'Layanan Jasa',
            'nama_produk' => 'Web App Development',
            'harga' => 2000000,
            'deskripsi' => 'Pengembangan web',
            'stok' => 0,
        ]);

        $pesanan = Pesanan::create([
            'user_id' => $this->pelanggan->id,
            'status_pesanan' => 'Completed',
            'total_harga' => 2000000,
            'tanggal_pesan' => now()->format('Y-m-d'),
        ]);

        DetailPesanan::create([
            'pesanan_id' => $pesanan->id,
            'produk_id' => $produk->id,
            'jumlah' => 1,
            'subtotal' => 2000000,
        ]);

        // View reports page
        $response = $this->actingAs($this->superAdmin)->get('/superadmin/reports');
        $response->assertStatus(200);
        $response->assertSee('LAPORAN TRANSAKSI GLOBAL');
        $response->assertSee('Web App Development');
        $response->assertSee('Rp 2.000.000');

        // Filter by jurusan
        $filterResponse = $this->actingAs($this->superAdmin)->get('/superadmin/reports?jurusan='.$this->jurusan->id.'&status=Completed');
        $filterResponse->assertStatus(200);
        $filterResponse->assertSee('Web App Development');

        // Access printable page
        $printResponse = $this->actingAs($this->superAdmin)->get('/superadmin/reports/print?jurusan='.$this->jurusan->id);
        $printResponse->assertStatus(200);
        $printResponse->assertSee('TEACHING FACTORY (TeFa) SMK NEGERI 4');
        $printResponse->assertSee('AKUMULASI TOTAL PENDAPATAN');
        $printResponse->assertSee('Web App Development');

        // Correct order
        $correctResponse = $this->actingAs($this->superAdmin)->patch("/superadmin/reports/{$pesanan->id}/koreksi", [
            'total_harga' => 2500000,
            'status_pesanan' => 'Completed',
        ]);
        $correctResponse->assertStatus(302);
        $this->assertEquals(2500000, $pesanan->fresh()->total_harga);
    }
}
