<?php

namespace Tests\Feature;

use App\Models\Jurusan;
use App\Models\Portfolio;
use App\Models\PortfolioImage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PortfolioCrudTest extends TestCase
{
    protected Jurusan $jurusan;

    protected Jurusan $otherJurusan;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // File ini tidak memakai RefreshDatabase: bersihkan dulu supaya idempoten.
        $this->jurusan = Jurusan::firstOrCreate(
            ['kode' => 'TSTRPL'],
            ['nama_jurusan' => 'Test RPL Portofolio', 'status_aktif' => true]
        );

        $this->otherJurusan = Jurusan::firstOrCreate(
            ['kode' => 'TSTDKV'],
            ['nama_jurusan' => 'Test DKV Portofolio', 'status_aktif' => true]
        );

        Portfolio::whereIn('jurusan_id', [$this->jurusan->id, $this->otherJurusan->id])->delete();

        $this->admin = User::firstOrCreate(
            ['email' => 'admin.portofolio@example.com'],
            [
                'name' => 'Admin Portofolio Test',
                'password' => bcrypt('password'),
                'role' => 'admin_jurusan',
                'jurusan_id' => $this->jurusan->id,
            ]
        );
    }

    public function test_admin_can_open_portofolio_menu(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.portofolio.index'));

        $response->assertStatus(200);
        $response->assertSee('KELOLA PORTOFOLIO');
    }

    public function test_admin_can_crud_portofolio_with_multiple_images(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin);

        $this->get(route('admin.portofolio.create'))->assertStatus(200);

        $this->post(route('admin.portofolio.store'), [
            'title' => 'Aplikasi Presensi RFID Test',
            'description' => 'Sistem absensi digital terintegrasi RFID dan deteksi wajah.',
            'team_name' => 'Tim Siswa RPL Test',
            'mentor_name' => 'Bu Ratna Sari, S.Kom',
            'images' => [
                UploadedFile::fake()->image('presensi-1.jpg'),
                UploadedFile::fake()->image('presensi-2.jpg'),
            ],
        ])->assertRedirect(route('admin.portofolio.index'));

        $portfolio = Portfolio::where('title', 'Aplikasi Presensi RFID Test')->firstOrFail();
        $this->assertSame($this->jurusan->id, $portfolio->jurusan_id);
        $this->assertSame('Bu Ratna Sari, S.Kom', $portfolio->mentor_name);
        $this->assertCount(2, $portfolio->images);

        foreach ($portfolio->images as $image) {
            Storage::disk('public')->assertExists($image->image_path);
        }

        $this->get(route('admin.portofolio.edit', $portfolio))->assertStatus(200);

        // Hapus gambar pertama, atur ulang urutan gambar kedua, tambah satu gambar baru.
        $first = $portfolio->images->first();
        $second = $portfolio->images->last();

        $this->put(route('admin.portofolio.update', $portfolio), [
            'title' => 'Aplikasi Presensi RFID Test (Revisi)',
            'description' => 'Deskripsi revisi portofolio.',
            'team_name' => 'Tim Siswa RPL Test',
            'mentor_name' => 'Pak Instruktur IoT TEFA',
            'delete_images' => [$first->id],
            'image_order' => [$second->id => 5],
            'images' => [UploadedFile::fake()->image('presensi-3.jpg')],
        ])->assertRedirect(route('admin.portofolio.index'));

        $portfolio->refresh();
        $this->assertSame('Aplikasi Presensi RFID Test (Revisi)', $portfolio->title);
        $this->assertSame('Pak Instruktur IoT TEFA', $portfolio->mentor_name);
        $this->assertDatabaseMissing('portfolio_images', ['id' => $first->id]);
        Storage::disk('public')->assertMissing($first->image_path);
        $this->assertSame(5, $second->refresh()->order);
        $this->assertCount(2, $portfolio->images);

        // Hapus portofolio: baris gambar & file ikut terhapus.
        $remaining = $portfolio->images->all();

        $this->delete(route('admin.portofolio.destroy', $portfolio))
            ->assertRedirect(route('admin.portofolio.index'));

        $this->assertDatabaseMissing('portfolios', ['id' => $portfolio->id]);
        $this->assertSame(0, PortfolioImage::where('portfolio_id', $portfolio->id)->count());

        foreach ($remaining as $image) {
            Storage::disk('public')->assertMissing($image->image_path);
        }
    }

    public function test_admin_cannot_touch_other_jurusan_portofolio(): void
    {
        $this->actingAs($this->admin);

        $foreign = Portfolio::factory()->create([
            'jurusan_id' => $this->otherJurusan->id,
            'title' => 'Portofolio Jurusan Lain',
        ]);

        $this->get(route('admin.portofolio.index'))->assertDontSee('Portofolio Jurusan Lain');
        $this->get(route('admin.portofolio.edit', $foreign))->assertForbidden();

        $this->put(route('admin.portofolio.update', $foreign), [
            'title' => 'Diretas',
            'description' => 'Tidak boleh berhasil.',
        ])->assertForbidden();

        $this->delete(route('admin.portofolio.destroy', $foreign))->assertForbidden();

        $this->assertDatabaseHas('portfolios', [
            'id' => $foreign->id,
            'title' => 'Portofolio Jurusan Lain',
        ]);
    }

    public function test_portofolio_images_are_capped_at_ten(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin);

        $eleven = [];
        for ($i = 1; $i <= 11; $i++) {
            $eleven[] = UploadedFile::fake()->image("img-{$i}.jpg");
        }

        $this->post(route('admin.portofolio.store'), [
            'title' => 'Portofolio Sebelas Gambar',
            'description' => 'Melebihi batas gambar.',
            'images' => $eleven,
        ])->assertSessionHasErrors('images');

        $this->assertDatabaseMissing('portfolios', ['title' => 'Portofolio Sebelas Gambar']);

        // Batas total juga berlaku saat menambah gambar lewat form edit.
        $portfolio = Portfolio::factory()->create(['jurusan_id' => $this->jurusan->id]);
        PortfolioImage::factory()->count(10)->create(['portfolio_id' => $portfolio->id]);

        $this->put(route('admin.portofolio.update', $portfolio), [
            'title' => $portfolio->title,
            'description' => 'Deskripsi.',
            'images' => [UploadedFile::fake()->image('img-11.jpg')],
        ])->assertSessionHasErrors('images');

        $this->assertSame(10, $portfolio->images()->count());
    }

    public function test_public_portofolio_page_lists_searches_and_filters(): void
    {
        $rpl = Jurusan::firstOrCreate(
            ['kode' => 'RPL'],
            ['nama_jurusan' => 'Rekayasa Perangkat Lunak Portofolio', 'status_aktif' => true]
        );
        $dkv = Jurusan::firstOrCreate(
            ['kode' => 'DKV'],
            ['nama_jurusan' => 'Desain Komunikasi Visual Portofolio', 'status_aktif' => true]
        );

        Portfolio::whereIn('title', ['Portofolio Publik RPL', 'Portofolio Publik DKV'])->delete();

        $rplPortfolio = Portfolio::factory()->create([
            'jurusan_id' => $rpl->id,
            'title' => 'Portofolio Publik RPL',
            'team_name' => 'Tim Publik RPL',
            'mentor_name' => 'Pembimbing Publik RPL',
        ]);
        Portfolio::factory()->create([
            'jurusan_id' => $dkv->id,
            'title' => 'Portofolio Publik DKV',
        ]);

        $this->get(route('portofolio'))
            ->assertStatus(200)
            ->assertSee('PORTOFOLIO')
            ->assertSee('Portofolio Publik RPL')
            ->assertSee('Tim Publik RPL');

        $this->get(route('portofolio', ['q' => 'DKV']))
            ->assertSee('Portofolio Publik DKV')
            ->assertDontSee('Portofolio Publik RPL');

        $this->get(route('portofolio', ['jurusan' => 'RPL']))
            ->assertSee('Portofolio Publik RPL')
            ->assertDontSee('Portofolio Publik DKV');

        // Detail: slideshow memuat gambar dan tombol kembali.
        PortfolioImage::factory()->create([
            'portfolio_id' => $rplPortfolio->id,
            'image_path' => 'portfolios/slideshow-uji.jpg',
        ]);

        $this->get(route('portofolio.show', $rplPortfolio))
            ->assertStatus(200)
            ->assertSee('Kembali ke Portofolio')
            ->assertSee('slideshow-uji.jpg')
            ->assertSee('TENTANG PROYEK')
            ->assertSee('Tim Publik RPL')
            ->assertSee('Pembimbing Publik RPL');
    }
}
