<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the physical products.
     */
    public function index(): View
    {
        $user = auth()->user();
        $jurusanId = $user->jurusan_id;

        $productsQuery = Product::with('jurusan')->latest();
        if ($jurusanId) {
            $productsQuery->where(function ($q) use ($jurusanId) {
                $q->where('jurusan_id', $jurusanId)->orWhereNull('jurusan_id');
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
            $fotoPath = $request->file('foto')->store('products', 'public');
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
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified physical product in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
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
            $fotoPath = $request->file('foto')->store('products', 'public');
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
        if ($product->foto && Storage::disk('public')->exists($product->foto)) {
            Storage::disk('public')->delete($product->foto);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk fisik berhasil dihapus.');
    }
}
