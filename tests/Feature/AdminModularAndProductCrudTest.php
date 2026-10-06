<?php

namespace Tests\Feature;

use App\Models\Jurusan;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminModularAndProductCrudTest extends TestCase
{
    protected User $admin;

    protected Jurusan $jurusan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->jurusan = Jurusan::firstOrCreate(
            ['nama_jurusan' => 'Software Engineering'],
            ['deskripsi_profil' => 'Focusing on web and app development.']
        );

        $this->admin = User::firstOrCreate(
            ['email' => 'admin_test@example.com'],
            [
                'name' => 'Admin Test',
                'password' => bcrypt('password'),
                'role' => 'admin_jurusan',
                'jurusan_id' => $this->jurusan->id,
            ]
        );
    }

    public function test_public_produk_page_displays_products(): void
    {
        Product::firstOrCreate(
            ['nama_produk' => 'Buku Jurnal Desain Tefa'],
            [
                'jurusan_id' => $this->jurusan->id,
                'deskripsi' => 'Deskripsi buku jurnal sketsa.',
                'harga' => 75000,
                'stok' => 20,
            ]
        );

        $response = $this->get(route('produk'));

        $response->assertStatus(200);
        $response->assertSee('Buku Jurnal Desain Tefa');
        $response->assertSee('75.000');
    }

    public function test_admin_can_access_modular_routes(): void
    {
        $this->actingAs($this->admin);

        $responseDashboard = $this->get(route('admin.dashboard'));
        $responseDashboard->assertStatus(200);
        $responseDashboard->assertSee('RINGKASAN DASHBOARD');

        $responseProducts = $this->get(route('admin.products.index'));
        $responseProducts->assertStatus(200);
        $responseProducts->assertSee('PRODUK FISIK TEFA');

        $responseProjects = $this->get(route('admin.projects.index'));
        $responseProjects->assertStatus(200);
        $responseProjects->assertSee('MANAJEMEN PROJEK');

        $responseWorkers = $this->get(route('admin.workers.index'));
        $responseWorkers->assertStatus(200);
        $responseWorkers->assertSee('MANAJEMEN WORKER');

        $responseMessages = $this->get(route('admin.messages.index'));
        $responseMessages->assertStatus(200);
        $responseMessages->assertSee('PESAN MASUK');
    }

    public function test_admin_can_crud_physical_product(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin);

        // 1. Create page
        $createPage = $this->get(route('admin.products.create'));
        $createPage->assertStatus(200);

        // 2. Store
        $file = UploadedFile::fake()->image('test_product.jpg');
        $storeResponse = $this->post(route('admin.products.store'), [
            'nama_produk' => 'Kaus Merchandise RPL SMKN 4',
            'deskripsi' => 'Kaus katun combed dengan sablon berkualitas tinggi.',
            'harga' => 85000,
            'stok' => 30,
            'foto' => $file,
        ]);

        $storeResponse->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'nama_produk' => 'Kaus Merchandise RPL SMKN 4',
            'harga' => 85000,
            'stok' => 30,
        ]);

        $createdProduct = Product::where('nama_produk', 'Kaus Merchandise RPL SMKN 4')->first();
        $this->assertNotNull($createdProduct->foto);
        Storage::disk('public')->assertExists($createdProduct->foto);

        // 3. Edit page
        $editPage = $this->get(route('admin.products.edit', $createdProduct->id));
        $editPage->assertStatus(200);
        $editPage->assertSee('Kaus Merchandise RPL SMKN 4');

        // 4. Update with new photo
        $newFile = UploadedFile::fake()->image('updated_product.png');
        $updateResponse = $this->put(route('admin.products.update', $createdProduct->id), [
            'nama_produk' => 'Kaus Merchandise RPL SMKN 4 - Edisi Khusus',
            'deskripsi' => 'Kaus katun combed edisi khusus TEFA.',
            'harga' => 95000,
            'stok' => 25,
            'foto' => $newFile,
        ]);

        $updateResponse->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $createdProduct->id,
            'nama_produk' => 'Kaus Merchandise RPL SMKN 4 - Edisi Khusus',
            'harga' => 95000,
            'stok' => 25,
        ]);

        $updatedProduct = Product::find($createdProduct->id);
        $this->assertNotNull($updatedProduct->foto);
        Storage::disk('public')->assertExists($updatedProduct->foto);

        // 5. Delete
        $deleteResponse = $this->delete(route('admin.products.destroy', $createdProduct->id));
        $deleteResponse->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseMissing('products', [
            'id' => $createdProduct->id,
        ]);
    }

    public function test_admin_can_create_worker_scoped_to_own_jurusan(): void
    {
        // File ini tidak memakai RefreshDatabase: bersihkan dulu supaya idempoten.
        User::where('email', 'worker.baru@example.com')->delete();

        $this->actingAs($this->admin);

        $response = $this->post(route('admin.workers.store'), [
            'name' => 'Siswa Worker Baru',
            'email' => 'worker.baru@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('admin.workers.index'));
        $this->assertNotNull($this->admin->jurusan_id);
        $this->assertDatabaseHas('users', [
            'email' => 'worker.baru@example.com',
            'role' => 'worker',
            'jurusan_id' => $this->admin->jurusan_id,
        ]);
    }

    public function test_admin_worker_store_forces_role_and_rejects_weak_password(): void
    {
        User::whereIn('email', [
            'siswa.super@example.com',
            'siswa.pendek@example.com',
            'siswa.konfirmasi@example.com',
        ])->delete();

        $this->actingAs($this->admin);

        // Role & jurusan dari request diabaikan: selalu worker + jurusan pembuat.
        $this->post(route('admin.workers.store'), [
            'name' => 'Siswa Super',
            'email' => 'siswa.super@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'super_admin',
            'jurusan_id' => 999999,
        ])->assertRedirect(route('admin.workers.index'));

        $this->assertDatabaseHas('users', [
            'email' => 'siswa.super@example.com',
            'role' => 'worker',
            'jurusan_id' => $this->admin->jurusan_id,
        ]);

        // Password minimal 8 karakter.
        $this->post(route('admin.workers.store'), [
            'name' => 'Siswa Pendek',
            'email' => 'siswa.pendek@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ])->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', ['email' => 'siswa.pendek@example.com']);

        // Password wajib dikonfirmasi.
        $this->post(route('admin.workers.store'), [
            'name' => 'Siswa Tanpa Konfirmasi',
            'email' => 'siswa.konfirmasi@example.com',
            'password' => 'password123',
        ])->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', ['email' => 'siswa.konfirmasi@example.com']);
    }

    public function test_admin_can_update_product_when_real_path_is_false_on_windows(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin);

        $product = Product::create([
            'jurusan_id' => $this->jurusan->id,
            'nama_produk' => 'Produk Uji Windows Path',
            'harga' => 10000,
            'stok' => 5,
        ]);

        $realImage = UploadedFile::fake()->image('photo.jpg');
        $tempFile = $realImage->getPathname();

        // Create mock UploadedFile where getRealPath() returns false (Windows temp quirk)
        $mockFile = new class($tempFile, 'photo.jpg', 'image/jpeg', null, true) extends UploadedFile
        {
            public function getRealPath(): string|false
            {
                return false;
            }
        };

        $response = $this->put(route('admin.products.update', $product->id), [
            'nama_produk' => 'Produk Uji Windows Path Updated',
            'harga' => 12000,
            'stok' => 4,
            'foto' => $mockFile,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $product->refresh();
        $this->assertNotNull($product->foto);
        Storage::disk('public')->assertExists($product->foto);

        if (file_exists($tempFile)) {
            @unlink($tempFile);
        }
    }
}
