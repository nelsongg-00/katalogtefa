<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\Product;
use App\Traits\HandlesUploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    use HandlesUploads;

    /**
     * Display a listing of the physical products.
     */
    public function index(): View
    {
        $user = auth()->user();
        $jurusanId = $user->jurusan_id ?? $user->department_id;
        $jurusan = $user->jurusan;

        $jurusanIds = $jurusanId ? [$jurusanId] : [];
        if ($jurusan && $jurusan->kode) {
            $jurusanIds = Jurusan::where('kode', $jurusan->kode)->pluck('id')->toArray();
        }

        $productsQuery = Product::with('jurusan')->latest();
        if (! empty($jurusanIds)) {
            $productsQuery->where(function ($q) use ($jurusanIds) {
                $q->whereIn('jurusan_id', $jurusanIds)->orWhereNull('jurusan_id');
            });
        }

        $products = $productsQuery->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new physical product.
     */
    public function create(): View
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created physical product in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $this->storeUploadedFile($request->file('foto'), 'products');
        }

        Product::create([
            'jurusan_id' => auth()->user()->jurusan_id,
            'nama_produk' => $validated['nama_produk'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'harga' => $validated['harga'],
            'stok' => $validated['stok'],
            'foto' => $fotoPath,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk fisik berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified physical product.
     */
    public function edit(Product $product): View
    {
        $this->authorizeProduct($product);

        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified physical product in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->authorizeProduct($product);

        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $fotoPath = $product->foto;
        if ($request->hasFile('foto')) {
            if ($product->foto && Storage::disk('public')->exists($product->foto)) {
                Storage::disk('public')->delete($product->foto);
            }
            $fotoPath = $this->storeUploadedFile($request->file('foto'), 'products');
        }

        $product->update([
            'nama_produk' => $validated['nama_produk'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'harga' => $validated['harga'],
            'stok' => $validated['stok'],
            'foto' => $fotoPath,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Data produk fisik berhasil diperbarui.');
    }

    /**
     * Remove the specified physical product from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $this->authorizeProduct($product);

        if ($product->foto && Storage::disk('public')->exists($product->foto)) {
            Storage::disk('public')->delete($product->foto);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk fisik berhasil dihapus.');
    }

    /**
     * Pastikan Admin Jurusan hanya mengelola produk fisik miliknya.
     */
    private function authorizeProduct(Product $product): void
    {
        $user = auth()->user();
        if (! $user) {
            abort(403, 'Silakan login terlebih dahulu.');
        }

        if ($user->role === 'super_admin' || $product->jurusan_id === null) {
            return;
        }

        $userJurusanId = $user->jurusan_id ?? $user->department_id;
        $userJurusan = $user->jurusan;

        $allowedIds = [];
        if ($userJurusanId) {
            $allowedIds[] = (int) $userJurusanId;
        }

        if ($userJurusan && $userJurusan->kode) {
            $matchingIds = Jurusan::where('kode', $userJurusan->kode)->pluck('id')->map(fn ($id) => (int) $id)->toArray();
            $allowedIds = array_merge($allowedIds, $matchingIds);
        }

        if ($product->jurusan && $userJurusan) {
            if (strtoupper($product->jurusan->kode ?? '') === strtoupper($userJurusan->kode ?? '')) {
                return;
            }
            if (! empty($product->jurusan->slug) && $product->jurusan->slug === $userJurusan->slug) {
                return;
            }
        }

        $allowedIds = array_unique(array_filter($allowedIds));

        if (! in_array((int) $product->jurusan_id, $allowedIds, true)) {
            abort(403, 'Anda tidak memiliki akses untuk mengelola produk fisik jurusan lain.');
        }
    }
}
