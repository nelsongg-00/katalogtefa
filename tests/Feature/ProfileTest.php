<?php

namespace Tests\Feature;

use App\Models\Jurusan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }

    public function test_profile_photo_can_be_uploaded(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'foto_profil' => UploadedFile::fake()->image('avatar.jpg', 200, 200),
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertNotNull($user->foto_profil);
        $this->assertStringStartsWith('avatars/', $user->foto_profil);

        Storage::disk('public')->assertExists($user->foto_profil);
    }

    public function test_profile_photo_must_be_a_supported_image_within_1mb(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'foto_profil' => UploadedFile::fake()->create('dokumen.pdf', 10, 'application/pdf'),
            ])
            ->assertSessionHasErrors('foto_profil');

        $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'foto_profil' => UploadedFile::fake()->image('foto.jpg', 100, 100)->size(2048),
            ])
            ->assertSessionHasErrors('foto_profil');

        $this->assertNull($user->refresh()->foto_profil);
    }

    public function test_old_profile_photo_is_deleted_when_replaced(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'foto_profil' => UploadedFile::fake()->image('pertama.jpg', 200, 200),
            ])
            ->assertSessionHasNoErrors();

        $firstPath = $user->refresh()->foto_profil;

        $this->assertNotNull($firstPath);
        Storage::disk('public')->assertExists($firstPath);

        $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'foto_profil' => UploadedFile::fake()->image('kedua.jpg', 200, 200),
            ])
            ->assertSessionHasNoErrors();

        $secondPath = $user->refresh()->foto_profil;

        $this->assertNotNull($secondPath);
        $this->assertNotSame($firstPath, $secondPath);
        Storage::disk('public')->assertExists($secondPath);
        Storage::disk('public')->assertMissing($firstPath);
    }

    public function test_profile_photo_survives_an_update_without_a_new_file(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'foto_profil' => UploadedFile::fake()->image('avatar.jpg', 200, 200),
            ])
            ->assertSessionHasNoErrors();

        $existingPath = $user->refresh()->foto_profil;

        $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Nama Baru',
                'email' => $user->email,
            ])
            ->assertSessionHasNoErrors();

        $user->refresh();

        $this->assertSame('Nama Baru', $user->name);
        $this->assertSame($existingPath, $user->foto_profil);
        Storage::disk('public')->assertExists($existingPath);
    }

    public function test_phone_number_can_be_updated(): void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => '081234567890',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertSame('081234567890', $user->refresh()->phone);
    }

    public function test_profile_page_shows_the_photo_upload_form(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response
            ->assertOk()
            ->assertSee('Profile Saya')
            ->assertSee('Ubah Kata Sandi')
            ->assertSee('Pilih Foto');
    }

    public function test_profile_page_uses_the_layout_of_each_role(): void
    {
        // Pelanggan → layouts.public (navbar publik)
        $this
            ->actingAs(User::factory()->create(['role' => 'pelanggan']))
            ->get('/profile')
            ->assertOk()
            ->assertSee('Profil Tefa');

        // Super admin → layouts.superadmin (sidebar superadmin)
        $this
            ->actingAs(User::factory()->create(['role' => 'super_admin']))
            ->get('/profile')
            ->assertOk()
            ->assertSee('Manajemen User');

        // Admin jurusan → layouts.admin (sidebar admin)
        $this
            ->actingAs(User::factory()->create(['role' => 'admin_jurusan']))
            ->get('/profile')
            ->assertOk()
            ->assertSee('Pesanan WA & Lacak', false);

        // Worker → layouts.worker (topbar worker)
        $this
            ->actingAs(User::factory()->create(['role' => 'worker']))
            ->get('/profile')
            ->assertOk()
            ->assertSee('WORKER WORKSPACE');
    }

    public function test_header_avatar_shows_the_uploaded_profile_photo(): void
    {
        $user = User::factory()->create([
            'name' => 'Budi Santoso',
            'foto_profil' => 'avatars/header-avatar-photo.jpg',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('home'));

        $response->assertOk();
        $response->assertSee('storage/avatars/header-avatar-photo.jpg', false);

        // Foto harus berada DI DALAM lingkaran avatar, bukan di tempat lain.
        $this->assertMatchesRegularExpression(
            '/class="nav-avatar-circle"[^>]*>\s*<img src="[^"]*storage\/avatars\/header-avatar-photo\.jpg"/',
            $response->getContent()
        );
    }

    public function test_header_avatar_falls_back_to_the_initial_letter_without_a_photo(): void
    {
        $user = User::factory()->create([
            'name' => 'Budi Santoso',
            'foto_profil' => null,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('home'));

        $response->assertOk();
        $response->assertDontSee('storage/avatars/', false);

        $this->assertMatchesRegularExpression(
            '/class="nav-avatar-circle"[^>]*>\s*B\s*<\/div>/',
            $response->getContent()
        );
    }

    public function test_role_layout_headers_show_the_uploaded_profile_photo(): void
    {
        $path = 'avatars/role-header-avatar.jpg';

        $jurusan = Jurusan::firstOrCreate(
            ['kode' => 'RPL'],
            [
                'nama_jurusan' => 'Rekayasa Perangkat Lunak',
                'deskripsi_profil' => 'Unit Kejuruan Software Engineering.',
                'status_aktif' => true,
            ]
        );

        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'foto_profil' => $path,
        ]);

        $superAdminResponse = $this
            ->actingAs($superAdmin)
            ->get(route('superadmin.dashboard'));

        $superAdminResponse->assertOk()->assertSee('storage/'.$path, false);
        $this->assertMatchesRegularExpression(
            '/class="avatar">\s*<img src="[^"]*storage\/avatars\/role-header-avatar\.jpg"/',
            $superAdminResponse->getContent()
        );

        $admin = User::factory()->create([
            'role' => 'admin_jurusan',
            'jurusan_id' => $jurusan->id,
            'foto_profil' => $path,
        ]);

        $adminResponse = $this
            ->actingAs($admin)
            ->get(route('admin.dashboard'));

        $adminResponse->assertOk()->assertSee('storage/'.$path, false);
        $this->assertMatchesRegularExpression(
            '/class="avatar">\s*<img src="[^"]*storage\/avatars\/role-header-avatar\.jpg"/',
            $adminResponse->getContent()
        );

        $worker = User::factory()->create([
            'role' => 'worker',
            'jurusan_id' => $jurusan->id,
            'foto_profil' => $path,
        ]);

        $workerResponse = $this
            ->actingAs($worker)
            ->get(route('worker.dashboard'));

        $workerResponse->assertOk()->assertSee('storage/'.$path, false);
        $this->assertMatchesRegularExpression(
            '/class="top-avatar">\s*<img src="[^"]*storage\/avatars\/role-header-avatar\.jpg"/',
            $workerResponse->getContent()
        );

        // Fallback superadmin tetap "SA" saat foto tidak ada.
        $superAdmin->update(['foto_profil' => null]);

        $fallbackResponse = $this
            ->actingAs($superAdmin->fresh())
            ->get(route('superadmin.dashboard'));

        $fallbackResponse->assertOk()->assertDontSee('storage/avatars/', false);
        $this->assertMatchesRegularExpression(
            '/class="avatar">\s*SA\s*<\/div>/',
            $fallbackResponse->getContent()
        );
    }
}
