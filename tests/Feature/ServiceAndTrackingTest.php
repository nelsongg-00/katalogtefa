<?php

namespace Tests\Feature;

use App\Models\Jurusan;
use App\Models\Order;
use App\Models\Project;
use App\Models\ProjectLog;
use App\Models\Service;
use App\Models\User;
use Tests\TestCase;

class ServiceAndTrackingTest extends TestCase
{
    protected Jurusan $jurusan;

    protected Jurusan $otherJurusan;

    protected User $adminJurusan;

    protected User $worker;

    protected Service $service;

    protected Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        $this->jurusan = Jurusan::firstOrCreate(
            ['nama_jurusan' => 'Rekayasa Perangkat Lunak'],
            ['deskripsi_profil' => 'Software engineering program.', 'kode' => 'RPL', 'is_active' => true]
        );

        $this->otherJurusan = Jurusan::firstOrCreate(
            ['nama_jurusan' => 'Desain Komunikasi Visual'],
            ['deskripsi_profil' => 'Visual communication design.', 'kode' => 'DKV', 'is_active' => true]
        );

        $this->adminJurusan = User::firstOrCreate(
            ['email' => 'admin_rpl_test@example.com'],
            [
                'name' => 'Admin RPL Test',
                'password' => bcrypt('password'),
                'role' => 'admin_jurusan',
                'jurusan_id' => $this->jurusan->id,
            ]
        );

        $this->worker = User::firstOrCreate(
            ['email' => 'worker_rpl_test@example.com'],
            [
                'name' => 'Worker RPL Test',
                'password' => bcrypt('password'),
                'role' => 'worker',
                'jurusan_id' => $this->jurusan->id,
            ]
        );

        $this->service = Service::firstOrCreate(
            ['slug' => 'test-layanan-website'],
            [
                'department_id' => $this->jurusan->id,
                'nama_layanan' => 'Pembuatan Website Uji Coba',
                'deskripsi' => 'Deskripsi layanan web untuk pengujian unit & feature.',
                'estimasi_harga' => 1250000,
                'is_active' => true,
            ]
        );

        $project = Project::firstOrCreate(
            ['nama_projek' => 'Projek Uji Coba Tracking TEFA-7777'],
            [
                'jurusan_id' => $this->jurusan->id,
                'deskripsi' => 'Pengujian tracking timeline.',
                'status' => 'in_progress',
                'progress' => 60,
                'worker_id' => $this->worker->id,
            ]
        );

        ProjectLog::firstOrCreate(
            ['project_id' => $project->id, 'catatan' => 'Pengerjaan modul frontend tracking selesai.'],
            ['worker_id' => $this->worker->id]
        );

        $this->order = Order::firstOrCreate(
            ['order_code' => 'TEFA-7777'],
            [
                'customer_name' => 'Bapak Test User',
                'customer_phone' => '081299998888',
                'service_id' => $this->service->id,
                'worker_id' => $this->worker->id,
                'project_id' => $project->id,
                'status' => 'in_progress',
                'total_biaya' => 1250000,
                'catatan' => 'Catatan order uji coba.',
            ]
        );
    }

    public function test_public_can_view_services_catalog_without_errors(): void
    {
        $response = $this->get(route('jasa'));

        $response->assertStatus(200);
        $response->assertSee('Layanan Jasa Kejuruan TeFa');
        $response->assertSee($this->service->nama_layanan);
        $response->assertSee('Konsultasi via WhatsApp');
    }

    public function test_public_can_track_order_with_valid_code_without_login(): void
    {
        $response = $this->get(route('order.track', 'TEFA-7777'));

        $response->assertStatus(200);
        $response->assertSee('TEFA-7777');
        $response->assertSee('Bapak Test User');
        $response->assertSee('Pembuatan Website Uji Coba');
        $response->assertSee('Pengerjaan modul frontend tracking selesai.');
    }

    public function test_public_tracking_shows_not_found_on_invalid_code(): void
    {
        $response = $this->get(route('order.track', 'TEFA-INVALID999'));

        $response->assertStatus(200);
        $response->assertSee('Pesanan Tidak Ditemukan');
        $response->assertSee('TEFA-INVALID999');
    }

    public function test_admin_jurusan_can_create_service(): void
    {
        $response = $this->actingAs($this->adminJurusan)->post(route('admin.services.store'), [
            'nama_layanan' => 'Layanan Baru Test Admin',
            'deskripsi' => 'Deskripsi layanan baru',
            'estimasi_harga' => 800000,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.services.index'));
        $this->assertDatabaseHas('services', [
            'nama_layanan' => 'Layanan Baru Test Admin',
            'department_id' => $this->jurusan->id,
        ]);
    }

    public function test_admin_jurusan_can_record_manual_order_and_creates_project(): void
    {
        $response = $this->actingAs($this->adminJurusan)->post(route('admin.orders.store'), [
            'customer_name' => 'Ibu Pelanggan Baru',
            'customer_phone' => '081234567800',
            'service_id' => $this->service->id,
            'worker_id' => $this->worker->id,
            'total_biaya' => 1500000,
            'catatan' => 'Mohon warna biru dongker dominan.',
        ]);

        $response->assertRedirect(route('admin.orders.index'));
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Ibu Pelanggan Baru',
            'service_id' => $this->service->id,
            'worker_id' => $this->worker->id,
        ]);

        $newOrder = Order::where('customer_name', 'Ibu Pelanggan Baru')->first();
        $this->assertNotNull($newOrder);
        $this->assertStringStartsWith('TEFA-', $newOrder->order_code);
        $this->assertNotNull($newOrder->project_id);

        $this->assertDatabaseHas('projects', [
            'id' => $newOrder->project_id,
            'worker_id' => $this->worker->id,
        ]);

        $this->assertDatabaseHas('project_logs', [
            'project_id' => $newOrder->project_id,
        ]);
    }

    public function test_admin_jurusan_cannot_access_other_department_service_edit(): void
    {
        $otherService = Service::firstOrCreate(
            ['slug' => 'test-layanan-dkv-isolated'],
            [
                'department_id' => $this->otherJurusan->id,
                'nama_layanan' => 'Desain DKV Khusus',
                'deskripsi' => 'Deskripsi DKV',
                'estimasi_harga' => 500000,
                'is_active' => true,
            ]
        );

        $response = $this->actingAs($this->adminJurusan)->get(route('admin.services.edit', $otherService));
        $response->assertStatus(403);
    }
}
