<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DepartmentController extends Controller
{
    /**
     * Tampilkan data master 6 jurusan.
     */
    public function index(): View
    {
        $jurusans = Jurusan::withCount(['produks'])->get();

        // Hitung statistik per jurusan
        $allUsers = User::all();
        $allOrders = Pesanan::with('detailPesanans.produk')->get();

        $departments = $jurusans->map(function ($jur) use ($allUsers, $allOrders) {
            $adminCount = $allUsers->where('jurusan_id', $jur->id)->where('role', 'admin_jurusan')->count();
            $workerCount = $allUsers->where('jurusan_id', $jur->id)->where('role', 'worker')->count();

            $orderCount = $allOrders->filter(function ($order) use ($jur) {
                return $order->detailPesanans->contains(fn ($d) => $d->produk?->jurusan_id === $jur->id);
            })->count();

            return (object) [
                'id' => $jur->id,
                'nama_jurusan' => $jur->nama_jurusan,
                'slug' => $jur->slug,
                'kode' => $jur->kode ?: strtoupper(substr($jur->slug, 0, 3)),
                'kepala_jurusan' => $jur->kepala_jurusan,
                'deskripsi' => $jur->deskripsi,
                'status_aktif' => (bool) $jur->status_aktif,
                'admin_count' => $adminCount,
                'worker_count' => $workerCount,
                'order_count' => $orderCount,
            ];
        });

        return view('superadmin.departments.index', compact('departments'));
    }

    /**
     * Simpan jurusan baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_jurusan' => ['required', 'string', 'max:255'],
            'kode' => ['required', 'string', 'max:10'],
            'kepala_jurusan' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'status_aktif' => ['nullable', 'boolean'],
        ]);

        $slug = Str::slug($validated['nama_jurusan']);
        $originalSlug = $slug;
        $counter = 1;
        while (Jurusan::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter++;
        }

        Jurusan::create([
            'nama_jurusan' => $validated['nama_jurusan'],
            'slug' => $slug,
            'kode' => strtoupper($validated['kode']),
            'kepala_jurusan' => $validated['kepala_jurusan'] ?? null,
            'deskripsi' => $validated['deskripsi'] ?? null,
            'deskripsi_profil' => $validated['deskripsi'] ?? null,
            'status_aktif' => $request->boolean('status_aktif', true),
        ]);

        return redirect()->route('superadmin.departments.index')
            ->with('success', 'Jurusan '.$validated['nama_jurusan'].' berhasil ditambahkan.');
    }

    /**
     * Perbarui data jurusan.
     */
    public function update(Request $request, Jurusan $jurusan): RedirectResponse
    {
        $validated = $request->validate([
            'nama_jurusan' => ['required', 'string', 'max:255'],
            'kode' => ['required', 'string', 'max:10'],
            'kepala_jurusan' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'status_aktif' => ['nullable', 'boolean'],
        ]);

        $jurusan->update([
            'nama_jurusan' => $validated['nama_jurusan'],
            'kode' => strtoupper($validated['kode']),
            'kepala_jurusan' => $validated['kepala_jurusan'] ?? null,
            'deskripsi' => $validated['deskripsi'] ?? null,
            'deskripsi_profil' => $validated['deskripsi'] ?? $jurusan->deskripsi_profil,
            'status_aktif' => $request->boolean('status_aktif', true),
        ]);

        return redirect()->route('superadmin.departments.index')
            ->with('success', 'Data jurusan '.$jurusan->nama_jurusan.' berhasil diperbarui.');
    }

    /**
     * Toggle status aktif jurusan.
     */
    public function toggle(Jurusan $jurusan): RedirectResponse
    {
        $jurusan->status_aktif = ! $jurusan->status_aktif;
        $jurusan->save();

        $statusStr = $jurusan->status_aktif ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('superadmin.departments.index')
            ->with('success', 'Jurusan '.$jurusan->nama_jurusan.' berhasil '.$statusStr.'.');
    }

    /**
     * Hapus jurusan (dengan proteksi jika masih dipakai relasi data).
     */
    public function destroy(Jurusan $jurusan): RedirectResponse
    {
        $hasUsers = User::where('jurusan_id', $jurusan->id)->exists();
        $hasProducts = $jurusan->produks()->exists();

        if ($hasUsers || $hasProducts) {
            // Nonaktifkan alih-alih merusak integritas foreign key
            $jurusan->status_aktif = false;
            $jurusan->save();

            return redirect()->route('superadmin.departments.index')
                ->with('warning', 'Jurusan '.$jurusan->nama_jurusan.' memiliki relasi user/produk, sehingga statusnya diubah menjadi Nonaktif.');
        }

        $jurusanName = $jurusan->nama_jurusan;
        $jurusan->delete();

        return redirect()->route('superadmin.departments.index')
            ->with('success', 'Jurusan '.$jurusanName.' berhasil dihapus.');
    }
}
