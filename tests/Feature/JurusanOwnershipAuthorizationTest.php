<?php

namespace Tests\Feature;

use App\Http\Controllers\AdminJurusanController;
use App\Models\DetailPesanan;
use App\Models\Jurusan;
use App\Models\Penugasan;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class JurusanOwnershipAuthorizationTest extends TestCase
{
    use DatabaseTransactions;

    protected Jurusan $jurusanA;

    protected Jurusan $jurusanB;

    protected User $adminA;

    protected User $superAdmin;

    protected User $workerA;

    protected User $workerB;

    protected Pesanan $pesananA;

    protected Pesanan $pesananB;

    protected Penugasan $penugasanB;

    protected Project $projectA;

    protected Project $projectB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->jurusanA = Jurusan::firstOrCreate(
            ['slug' => 'uji-kepemilikan-a'],
            ['nama_jurusan' => 'Jurusan Uji Kepemilikan A', 'kode' => 'UKA']
        );

        $this->jurusanB = Jurusan::firstOrCreate(
            ['slug' => 'uji-kepemilikan-b'],
            ['nama_jurusan' => 'Jurusan Uji Kepemilikan B', 'kode' => 'UKB']
        );

        $this->adminA = $this->makeUser('admin-uji-a@example.com', 'admin_jurusan', $this->jurusanA);
        $this->superAdmin = $this->makeUser('superadmin-uji@example.com', 'super_admin', null);
        $this->workerA = $this->makeUser('worker-uji-a@example.com', 'worker', $this->jurusanA);
        $this->workerB = $this->makeUser('worker-uji-b@example.com', 'worker', $this->jurusanB);

        $this->pesananA = $this->makePesanan($this->jurusanA);
        $this->pesananB = $this->makePesanan($this->jurusanB);

        $this->penugasanB = Penugasan::create([
            'pesanan_id' => $this->pesananB->id,
            'worker_id' => $this->workerB->id,
            'status_tugas' => 'Ditugaskan',
            'tanggal_disposisi' => now(),
        ]);

        $this->projectA = $this->makeProject($this->jurusanA);
        $this->projectB = $this->makeProject($this->jurusanB);
    }

    public function test_admin_cannot_validate_or_assign_order_of_another_jurusan(): void
    {
        $this->actingAs($this->adminA)
            ->post(route('admin.validateOrder', $this->pesananB))
            ->assertForbidden();

        $this->actingAs($this->adminA)
            ->post(route('admin.assignTask', $this->pesananB), ['worker_id' => $this->workerB->id])
            ->assertForbidden();

        $this->assertSame('Pending', $this->pesananB->fresh()->status_pesanan);
        $this->assertSame('Ditugaskan', $this->penugasanB->fresh()->status_tugas);
    }

    public function test_admin_can_validate_and_assign_own_jurusan_order(): void
    {
        $this->actingAs($this->adminA)
            ->post(route('admin.validateOrder', $this->pesananA))
            ->assertRedirect();

        $this->assertSame('Validated', $this->pesananA->fresh()->status_pesanan);

        $this->actingAs($this->adminA)
            ->post(route('admin.assignTask', $this->pesananA), ['worker_id' => $this->workerA->id])
            ->assertRedirect();

        $this->assertDatabaseHas('penugasans', [
            'pesanan_id' => $this->pesananA->id,
            'worker_id' => $this->workerA->id,
        ]);
    }

    public function test_admin_cannot_update_or_delete_project_of_another_jurusan(): void
    {
        $this->actingAs($this->adminA)
            ->patch(route('admin.projects.update', $this->projectB), ['status' => 'completed', 'progress' => 100])
            ->assertForbidden();

        $this->actingAs($this->adminA)
            ->delete(route('admin.projects.destroy', $this->projectB))
            ->assertForbidden();

        $this->assertDatabaseHas('projects', ['id' => $this->projectB->id, 'status' => 'pending']);
    }

    public function test_admin_can_update_and_delete_own_jurusan_project(): void
    {
        $this->actingAs($this->adminA)
            ->patch(route('admin.projects.update', $this->projectA), ['status' => 'completed', 'progress' => 100])
            ->assertRedirect(route('admin.projects.index'));

        $this->assertSame('completed', $this->projectA->fresh()->status);

        $this->actingAs($this->adminA)
            ->delete(route('admin.projects.destroy', $this->projectA))
            ->assertRedirect(route('admin.projects.index'));

        $this->assertDatabaseMissing('projects', ['id' => $this->projectA->id]);
    }

    public function test_admin_jurusan_controller_project_methods_enforce_ownership(): void
    {
        $this->actingAs($this->adminA);
        $controller = app(AdminJurusanController::class);

        $this->expectException(AuthorizationException::class);
        $controller->deleteProject($this->projectB);
    }

    public function test_admin_jurusan_controller_updates_own_jurusan_project(): void
    {
        $this->actingAs($this->adminA);
        $controller = app(AdminJurusanController::class);
        $request = Request::create('/admin/projects/'.$this->projectA->id, 'PATCH', [
            'status' => 'completed',
            'progress' => 100,
        ]);

        $controller->updateProject($request, $this->projectA);

        $this->assertSame('completed', $this->projectA->fresh()->status);
        $this->assertSame(100, $this->projectA->fresh()->progress);
    }

    public function test_worker_cannot_update_progress_of_another_workers_assignment(): void
    {
        $this->actingAs($this->workerA)
            ->post(route('worker.updateProgress', $this->penugasanB), ['keterangan_progres' => 'Menyelesaikan tugas'])
            ->assertForbidden();

        $this->assertDatabaseMissing('progres', ['penugasan_id' => $this->penugasanB->id]);

        $this->actingAs($this->workerB)
            ->post(route('worker.updateProgress', $this->penugasanB), ['keterangan_progres' => 'Sedang dikerjakan'])
            ->assertRedirect();

        $this->assertDatabaseHas('progres', ['penugasan_id' => $this->penugasanB->id]);
    }

    public function test_super_admin_is_allowed_on_any_jurusan(): void
    {
        // Super admin tidak melewati rute admin_jurusan (middleware role meredirect), jadi policy diuji langsung.
        $gate = Gate::forUser($this->superAdmin);

        $this->assertTrue($gate->allows('validate', $this->pesananB));
        $this->assertTrue($gate->allows('assign', $this->pesananB));
        $this->assertTrue($gate->allows('update', $this->projectB));
        $this->assertTrue($gate->allows('delete', $this->projectB));
        $this->assertTrue($gate->allows('update', $this->penugasanB));
    }

    private function makeUser(string $email, string $role, ?Jurusan $jurusan): User
    {
        return User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Pengguna Uji '.$role,
                'password' => bcrypt('password'),
                'role' => $role,
                'jurusan_id' => $jurusan?->id,
                'email_verified_at' => now(),
            ]
        );
    }

    private function makePesanan(Jurusan $jurusan): Pesanan
    {
        $produk = Produk::create([
            'jurusan_id' => $jurusan->id,
            'tipe' => 'Produk Fisik',
            'nama_produk' => 'Produk Uji '.$jurusan->kode,
            'harga' => 10000,
            'stok' => 5,
        ]);

        $pesanan = Pesanan::create([
            'status_pesanan' => 'Pending',
            'total_harga' => 10000,
        ]);

        DetailPesanan::create([
            'pesanan_id' => $pesanan->id,
            'produk_id' => $produk->id,
            'jumlah' => 1,
            'subtotal' => 10000,
        ]);

        return $pesanan;
    }

    private function makeProject(Jurusan $jurusan): Project
    {
        return Project::create([
            'jurusan_id' => $jurusan->id,
            'nama_projek' => 'Projek Uji '.$jurusan->kode,
            'status' => 'pending',
            'progress' => 0,
        ]);
    }
}
