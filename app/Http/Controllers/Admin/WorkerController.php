<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class WorkerController extends Controller
{
    /**
     * Display a listing of department workers.
     */
    public function index(): View
    {
        $jurusanId = auth()->user()->jurusan_id;
        $jurusan = auth()->user()->jurusan;

        $workersQuery = User::where('role', 'worker')->with('jurusan');
        if ($jurusanId) {
            $workersQuery->where('jurusan_id', $jurusanId);
        }
        $workers = $workersQuery->latest()->get();
        $workerAktif = $workers->count();

        return view('admin.workers.index', compact('workers', 'jurusan', 'workerAktif'));
    }

    /**
     * Store a new worker for the department.
     */
    public function store(Request $request): RedirectResponse
    {
        // Lapisan otorisasi tambahan: hanya Admin Jurusan yang boleh membuat akun Worker.
        // (Lapisan utama sudah ditangani middleware `role:admin_jurusan` pada route.)
        abort_if(auth()->user()?->role !== 'admin_jurusan', 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'role' => 'worker',
            // Jurusan diwarisi dari pembuat, bukan dari request — Admin Jurusan
            // tidak pernah bisa membuat akun di luar jurusannya sendiri.
            'jurusan_id' => auth()->user()->jurusan_id,
        ]);

        return redirect()->route('admin.workers.index')->with('success', 'Worker/siswa baru berhasil ditambahkan.');
    }

    /**
     * Delete or remove a worker from department.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->role === 'worker' && $user->jurusan_id == auth()->user()->jurusan_id) {
            $user->delete();

            return redirect()->route('admin.workers.index')->with('success', 'Worker/siswa berhasil dihapus.');
        }

        return redirect()->route('admin.workers.index')->with('error', 'Anda tidak memiliki otoritas untuk menghapus akun ini.');
    }
}
