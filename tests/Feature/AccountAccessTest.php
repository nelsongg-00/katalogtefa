<?php

namespace Tests\Feature;

use App\Models\Jurusan;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Regression guard for the "every account is locked out" defect: the User model
 * inherits MustVerifyEmail, so an unverified account cannot reach any area behind
 * the authenticated route group. Accounts created by seeders or by staff must be
 * usable immediately.
 */
class AccountAccessTest extends TestCase
{
    use DatabaseTransactions;

    protected Jurusan $jurusan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->jurusan = Jurusan::firstOrCreate(
            ['kode' => 'RPL'],
            ['nama_jurusan' => 'Rekayasa Perangkat Lunak', 'status_aktif' => true]
        );

        User::whereIn('email', [
            'unverified_client@example.com',
            'access_admin@example.com',
            'access_worker@example.com',
            'access_superadmin@example.com',
            'access_new_admin@example.com',
        ])->delete();
    }

    public function test_unverified_account_still_reaches_its_dashboard(): void
    {
        $user = User::create([
            'name' => 'Pelanggan Belum Verifikasi',
            'email' => 'unverified_client@example.com',
            'password' => bcrypt('password'),
            'role' => 'pelanggan',
            'is_active' => true,
            'email_verified_at' => null,
        ]);

        $this->actingAs($user)
            ->get(route('client.orders'))
            ->assertOk();
    }

    public function test_staff_roles_reach_their_areas_without_email_verification(): void
    {
        $admin = $this->makeUser('access_admin@example.com', 'admin_jurusan', $this->jurusan);
        $worker = $this->makeUser('access_worker@example.com', 'worker', $this->jurusan);
        $superAdmin = $this->makeUser('access_superadmin@example.com', 'super_admin');

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
        $this->actingAs($worker)->get(route('worker.dashboard'))->assertOk();
        $this->actingAs($superAdmin)->get(route('superadmin.dashboard'))->assertOk();
    }

    public function test_superadmin_created_admin_account_is_verified(): void
    {
        $superAdmin = $this->makeUser('access_superadmin@example.com', 'super_admin');

        $this->actingAs($superAdmin)
            ->post(route('superadmin.users.store'), [
                'name' => 'Admin Baru',
                'email' => 'access_new_admin@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'jurusan_id' => $this->jurusan->id,
            ])
            ->assertRedirect(route('superadmin.users.index'));

        $created = User::where('email', 'access_new_admin@example.com')->firstOrFail();

        $this->assertNotNull($created->email_verified_at);

        $this->actingAs($created)
            ->get(route('admin.dashboard'))
            ->assertOk();
    }

    protected function makeUser(string $email, string $role, ?Jurusan $jurusan = null): User
    {
        return User::create([
            'name' => $role.' '.$email,
            'email' => $email,
            'password' => bcrypt('password'),
            'role' => $role,
            'jurusan_id' => $jurusan?->id,
            'is_active' => true,
            'email_verified_at' => null,
        ]);
    }
}
