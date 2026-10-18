<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Traits\HandlesUploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    use HandlesUploads;

    /** Batas gambar per portofolio. */
    private const MAX_IMAGES = 10;

    /**
     * Daftar portofolio milik jurusan admin yang sedang login.
     */
    public function index(): View
    {
        $portfolios = Portfolio::with('jurusan')
            ->withCount('images')
            ->where('jurusan_id', auth()->user()->jurusan_id)
            ->latest()
            ->paginate(10);

        return view('admin.portofolio.index', compact('portfolios'));
    }

    /**
     * Form tambah portofolio baru.
     */
    public function create(): View
    {
        $this->ensureJurusan();

        return view('admin.portofolio.create');
    }

    /**
     * Simpan portofolio baru beserta gambarnya.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->ensureJurusan();

        $validated = $this->validatePortfolio($request);

        $portfolio = Portfolio::create([
            'jurusan_id' => auth()->user()->jurusan_id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'team_name' => $validated['team_name'] ?? null,
            'mentor_name' => $validated['mentor_name'] ?? null,
        ]);

        $this->storeImages($portfolio, $request->file('images') ?? []);

        return redirect()->route('admin.portofolio.index')->with('success', 'Portofolio berhasil ditambahkan.');
    }

    /**
     * Form edit portofolio.
     */
    public function edit(Portfolio $portfolio): View
    {
        $this->authorizePortfolio($portfolio);
        $portfolio->load('images');

        return view('admin.portofolio.edit', compact('portfolio'));
    }

    /**
     * Perbarui data, urutan gambar, dan unggahan gambar baru.
     */
    public function update(Request $request, Portfolio $portfolio): RedirectResponse
    {
        $this->authorizePortfolio($portfolio);

        $validated = $this->validatePortfolio($request, [
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer'],
            'image_order' => ['nullable', 'array'],
            'image_order.*' => ['integer', 'min:0', 'max:255'],
        ]);

        $deleteIds = $portfolio->images()->whereIn('id', $validated['delete_images'] ?? [])->pluck('id');
        $newImages = $request->file('images') ?? [];

        if ($portfolio->images()->count() - $deleteIds->count() + count($newImages) > self::MAX_IMAGES) {
            throw ValidationException::withMessages([
                'images' => 'Maksimal '.self::MAX_IMAGES.' gambar per portofolio.',
            ]);
        }

        $portfolio->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'team_name' => $validated['team_name'] ?? null,
            'mentor_name' => $validated['mentor_name'] ?? null,
        ]);

        foreach ($portfolio->images()->whereIn('id', $deleteIds)->get() as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        foreach ($validated['image_order'] ?? [] as $imageId => $order) {
            $portfolio->images()->whereKey($imageId)->update(['order' => $order]);
        }

        $this->storeImages($portfolio, $newImages);

        return redirect()->route('admin.portofolio.index')->with('success', 'Portofolio berhasil diperbarui.');
    }

    /**
     * Hapus portofolio beserta seluruh file gambarnya.
     */
    public function destroy(Portfolio $portfolio): RedirectResponse
    {
        $this->authorizePortfolio($portfolio);

        foreach ($portfolio->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $portfolio->delete();

        return redirect()->route('admin.portofolio.index')->with('success', 'Portofolio berhasil dihapus.');
    }

    /**
     * @param  array<string, array<int, string>>  $extra
     * @return array<string, mixed>
     */
    private function validatePortfolio(Request $request, array $extra = []): array
    {
        return $request->validate(array_merge([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'team_name' => ['nullable', 'string', 'max:255'],
            'mentor_name' => ['nullable', 'string', 'max:255'],
            'images' => ['nullable', 'array', 'max:'.self::MAX_IMAGES],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], $extra));
    }

    /**
     * @param  array<int, UploadedFile>  $files
     */
    private function storeImages(Portfolio $portfolio, array $files): void
    {
        $order = (int) $portfolio->images()->max('order');

        foreach ($files as $file) {
            $portfolio->images()->create([
                'image_path' => $this->storeUploadedFile($file, 'portfolios'),
                'order' => ++$order,
            ]);
        }
    }

    /**
     * Admin Jurusan hanya boleh mengelola portofolio milik jurusannya sendiri.
     */
    private function authorizePortfolio(Portfolio $portfolio): void
    {
        abort_unless(
            $portfolio->jurusan_id !== null && $portfolio->jurusan_id === auth()->user()->jurusan_id,
            403,
            'Portofolio ini bukan milik jurusan Anda.'
        );
    }

    private function ensureJurusan(): void
    {
        abort_unless(auth()->user()->jurusan_id, 403, 'Akun Anda belum terhubung ke jurusan.');
    }
}
