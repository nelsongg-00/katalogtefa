<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\Service;
use App\Traits\HandlesUploads;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    use HandlesUploads;

    /**
     * Tampilkan daftar layanan jasa milik jurusan yang sedang login.
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

        $servicesQuery = Service::with('department')->latest();
        if (! empty($jurusanIds)) {
            $servicesQuery->whereIn('department_id', $jurusanIds);
        }

        $services = $servicesQuery->paginate(10);

        return view('admin.services.index', compact('services'));
    }

    /**
     * Form tambah layanan jasa baru.
     */
    public function create(): View
    {
        return view('admin.services.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $jurusanId = $user->jurusan_id ?? $user->department_id ?? $user->jurusan?->id;

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
            $fotoPath = $this->storeUploadedFile($request->file('foto'), 'services');
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
            $updateData['foto'] = $this->storeUploadedFile($request->file('foto'), 'services');
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
        $user = auth()->user();
        if (! $user) {
            abort(403, 'Silakan login terlebih dahulu.');
        }

        if ($user->role === 'super_admin') {
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

        if ($service->department && $userJurusan) {
            if (strtoupper($service->department->kode ?? '') === strtoupper($userJurusan->kode ?? '')) {
                return;
            }
            if (! empty($service->department->slug) && $service->department->slug === $userJurusan->slug) {
                return;
            }
        }

        $allowedIds = array_unique(array_filter($allowedIds));

        if (! in_array((int) $service->department_id, $allowedIds, true)) {
            abort(403, 'Anda tidak memiliki akses untuk mengelola layanan jurusan lain.');
        }
    }
}
