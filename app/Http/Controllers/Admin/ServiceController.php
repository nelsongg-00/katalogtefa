<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    /**
     * Tampilkan daftar layanan jasa milik jurusan yang sedang login.
     */
    public function index(): View
    {
        $jurusanId = auth()->user()->jurusan_id;

        $services = Service::where('department_id', $jurusanId)
            ->latest()
            ->paginate(10);

        return view('admin.services.index', compact('services'));
    }

    /**
     * Form tambah layanan jasa baru.
     */
    public function create(): View
    {
        return view('admin.services.create');
    }

    /**
     * Simpan layanan jasa baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $jurusanId = auth()->user()->jurusan_id;

        $validated = $request->validate([
            'nama_layanan' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'estimasi_harga' => ['required', 'numeric', 'min:0'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $slug = Str::slug($validated['nama_layanan']);
        $originalSlug = $slug;
        $counter = 1;
        while (Service::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter++;
        }

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('services', 'public');
        }

        Service::create([
            'department_id' => $jurusanId,
            'nama_layanan' => $validated['nama_layanan'],
            'slug' => $slug,
            'deskripsi' => $validated['deskripsi'],
            'estimasi_harga' => (int) $validated['estimasi_harga'],
            'foto' => $fotoPath,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.services.index')
            ->with('success', 'Layanan jasa berhasil ditambahkan.');
    }

    /**
     * Form edit layanan jasa.
     */
    public function edit(Service $service): View
    {
        $this->authorizeService($service);

        return view('admin.services.edit', compact('service'));
    }

    /**
     * Perbarui layanan jasa.
     */
    public function update(Request $request, Service $service): RedirectResponse
    {
        $this->authorizeService($service);

        $validated = $request->validate([
            'nama_layanan' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'estimasi_harga' => ['required', 'numeric', 'min:0'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $updateData = [
            'nama_layanan' => $validated['nama_layanan'],
            'deskripsi' => $validated['deskripsi'],
            'estimasi_harga' => (int) $validated['estimasi_harga'],
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->hasFile('foto')) {
            if ($service->foto && Storage::disk('public')->exists($service->foto)) {
                Storage::disk('public')->delete($service->foto);
            }
            $updateData['foto'] = $request->file('foto')->store('services', 'public');
        }

        $service->update($updateData);

        return redirect()->route('admin.services.index')
            ->with('success', 'Layanan jasa berhasil diperbarui.');
    }

    /**
     * Hapus layanan jasa.
     */
    public function destroy(Service $service): RedirectResponse
    {
        $this->authorizeService($service);

        if ($service->foto && Storage::disk('public')->exists($service->foto)) {
            Storage::disk('public')->delete($service->foto);
        }

        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Layanan jasa berhasil dihapus.');
    }

    /**
     * Pastikan Admin Jurusan hanya mengelola layanan jurusannya sendiri.
     */
    private function authorizeService(Service $service): void
    {
        if ($service->department_id !== auth()->user()->jurusan_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengelola layanan jurusan lain.');
        }
    }
}
