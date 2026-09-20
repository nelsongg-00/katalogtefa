<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Tampilkan tabel manajemen user dengan filter role dan jurusan.
     */
    public function index(Request $request): View
    {
        $q = $request->input('q');
        $role = $request->input('role');
        $jurusanId = $request->input('jurusan');

        $query = User::with('jurusan');

        if (! empty($q)) {
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        if (! empty($role)) {
            $query->where('role', $role);
        }

        if (! empty($jurusanId)) {
            $query->where('jurusan_id', $jurusanId);
        }

        $users = $query->orderBy('id', 'asc')->get();

        // Mini statistics
        $allUsers = User::all();
        $totalUsers = $allUsers->count();
        $totalAdmin = $allUsers->where('role', 'admin_jurusan')->count();
        $totalWorker = $allUsers->where('role', 'worker')->count();
        $totalClient = $allUsers->where('role', 'pelanggan')->count();
        $totalSuper = $allUsers->where('role', 'super_admin')->count();

        $jurusans = Jurusan::orderBy('nama_jurusan', 'asc')->get();

        return view('superadmin.users.index', compact(
            'users',
            'totalUsers',
            'totalAdmin',
            'totalWorker',
            'totalClient',
            'totalSuper',
            'jurusans',
            'q',
            'role',
            'jurusanId'
        ));
    }

    /**
     * Simpan pengguna baru ke sistem.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'string', Rule::in(['super_admin', 'admin_jurusan', 'worker', 'pelanggan'])],
            'jurusan_id' => [
                Rule::requiredIf(fn () => in_array($request->input('role'), ['admin_jurusan', 'worker'])),
                'nullable',
                'exists:jurusans,id',
            ],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $jurusanId = in_array($validated['role'], ['admin_jurusan', 'worker']) ? $validated['jurusan_id'] : null;

        User::create([
            'name' => $validated['name'],
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'jurusan_id' => $jurusanId,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'User '.$validated['name'].' berhasil ditambahkan.');
    }

    /**
     * Perbarui data pengguna.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'string', Rule::in(['super_admin', 'admin_jurusan', 'worker', 'pelanggan'])],
            'jurusan_id' => [
                Rule::requiredIf(fn () => in_array($request->input('role'), ['admin_jurusan', 'worker'])),
                'nullable',
                'exists:jurusans,id',
            ],
            'is_active' => ['nullable', 'boolean'],
        ]);

        // Cegah Super Admin menonaktifkan atau mengubah role akunnya sendiri
        if (auth()->id() === $user->id) {
            $validated['role'] = 'super_admin';
            $validated['is_active'] = true;
        }

        $jurusanId = in_array($validated['role'], ['admin_jurusan', 'worker']) ? $validated['jurusan_id'] : null;

        $updateData = [
            'name' => $validated['name'],
            'email' => strtolower(trim($validated['email'])),
            'role' => $validated['role'],
            'jurusan_id' => $jurusanId,
            'is_active' => $request->boolean('is_active', true),
        ];

        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Data pengguna '.$user->name.' berhasil diperbarui.');
    }

    /**
     * Hapus akun pengguna.
     */
    public function destroy(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('superadmin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Akun '.$userName.' berhasil dihapus.');
    }
}
