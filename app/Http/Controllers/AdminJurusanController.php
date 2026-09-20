<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Penugasan;
use App\Models\Pesanan;
use App\Models\PesanMasuk;
use App\Models\Produk;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminJurusanController extends Controller
{
    /**
     * Display the admin dashboard for the logged-in department admin.
     */
    public function index(): View
    {
        $user = auth()->user();
        $jurusanId = $user->jurusan_id;
        $jurusan = $user->jurusan;

        // 1. Pesanan yang terkait dengan produk jurusan ini
        $pesanans = Pesanan::with(['user', 'detailPesanans.produk', 'penugasans.worker'])
            ->whereHas('detailPesanans.produk', function ($q) use ($jurusanId) {
                if ($jurusanId) {
                    $q->where('jurusan_id', $jurusanId);
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. Metrik Statistik
        $totalPesanan = $pesanans->count();
        $pesananPending = $pesanans->where('status_pesanan', 'Pending')->count();
        $pesananProses = $pesanans->whereIn('status_pesanan', ['Validated', 'In Progress'])->count();
        $pesananSelesai = $pesanans->where('status_pesanan', 'Completed')->count();

        $totalPendapatan = (int) DetailPesanan::whereHas('produk', function ($q) use ($jurusanId) {
            if ($jurusanId) {
                $q->where('jurusan_id', $jurusanId);
            }
        })
            ->whereHas('pesanan', function ($q) {
                $q->where('status_pesanan', 'Completed');
            })
            ->sum('subtotal');

        // 3. Worker & Produk Jurusan
        $workersQuery = User::where('role', 'worker');
        if ($jurusanId) {
            $workersQuery->where('jurusan_id', $jurusanId);
        }
        $workers = $workersQuery->get();
        $workerAktif = $workers->count();

        $produksQuery = Produk::query();
        if ($jurusanId) {
            $produksQuery->where('jurusan_id', $jurusanId);
        }
        $produks = $produksQuery->get();
        $totalProduk = $produks->count();
        $totalFisik = $produks->where('tipe', 'Produk Fisik')->count();
        $totalJasa = $produks->where('tipe', 'Layanan Jasa')->count();

        // 4. Data Agregat Bulanan untuk Chart.js (Jan - Des)
        $bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $pesananMasukPerBulan = array_fill(0, 12, 0);
        $pesananSelesaiPerBulan = array_fill(0, 12, 0);

        foreach ($pesanans as $pesanan) {
            if ($pesanan->created_at) {
                $bulanIndex = (int) $pesanan->created_at->format('n') - 1;
                if ($bulanIndex >= 0 && $bulanIndex < 12) {
                    $pesananMasukPerBulan[$bulanIndex]++;
                    if ($pesanan->status_pesanan === 'Completed') {
                        $pesananSelesaiPerBulan[$bulanIndex]++;
                    }
                }
            }
        }

        $dataChart = [
            'labels' => $bulanLabels,
            'pesananMasuk' => $pesananMasukPerBulan,
            'pesananSelesai' => $pesananSelesaiPerBulan,
        ];

        // 5. Projek Sedang Berjalan
        $projectsQuery = Project::with('worker');
        if ($jurusanId) {
            $projectsQuery->where('jurusan_id', $jurusanId);
        }
        $projects = $projectsQuery->orderBy('created_at', 'desc')->get();

        // 6. Notifikasi Pesan Masuk
        $pesanMasuksQuery = PesanMasuk::where(function ($q) use ($jurusanId) {
            $q->whereNull('jurusan_id');
            if ($jurusanId) {
                $q->orWhere('jurusan_id', $jurusanId);
            }
        });

        $unreadMessagesCount = (clone $pesanMasuksQuery)->where('is_read', false)->count();
        $pesanMasuks = $pesanMasuksQuery->orderBy('created_at', 'desc')->take(8)->get();

        return view('admin.dashboard', compact(
            'jurusan',
            'pesanans',
            'workers',
            'totalPesanan',
            'pesananPending',
            'pesananProses',
            'pesananSelesai',
            'totalPendapatan',
            'workerAktif',
            'totalProduk',
            'totalFisik',
            'totalJasa',
            'dataChart',
            'projects',
            'pesanMasuks',
            'unreadMessagesCount'
        ));
    }

    /**
     * Validate a pending order.
     */
    public function validateOrder(Request $request, Pesanan $pesanan): RedirectResponse
    {
        $pesanan->update(['status_pesanan' => 'Validated']);

        return redirect()->back()->with('success', 'Pesanan berhasil divalidasi.');
    }

    /**
     * Assign order to a worker.
     */
    public function assignTask(Request $request, Pesanan $pesanan): RedirectResponse
    {
        $request->validate(['worker_id' => 'required|exists:users,id']);

        Penugasan::create([
            'pesanan_id' => $pesanan->id,
            'worker_id' => $request->worker_id,
            'status_tugas' => 'Ditugaskan',
            'tanggal_disposisi' => now(),
        ]);

        $pesanan->update(['status_pesanan' => 'In Progress']);

        return redirect()->back()->with('success', 'Tugas berhasil diberikan kepada siswa/worker.');
    }

    /**
     * Store a new worker for the department.
     */
    public function storeWorker(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'worker',
            'jurusan_id' => auth()->user()->jurusan_id,
        ]);

        return redirect()->back()->with('success', 'Worker/siswa baru berhasil ditambahkan.');
    }

    /**
     * Delete or remove a worker from department.
     */
    public function deleteWorker(User $user): RedirectResponse
    {
        if ($user->role === 'worker' && $user->jurusan_id == auth()->user()->jurusan_id) {
            $user->delete();

            return redirect()->back()->with('success', 'Worker/siswa berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Anda tidak memiliki otoritas untuk menghapus akun ini.');
    }

    /**
     * Store a new project.
     */
    public function storeProject(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_projek' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'progress' => 'required|integer|min:0|max:100',
            'worker_id' => 'nullable|exists:users,id',
            'tenggat_waktu' => 'nullable|date',
        ]);

        Project::create([
            'jurusan_id' => auth()->user()->jurusan_id,
            'nama_projek' => $request->nama_projek,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
            'progress' => $request->progress,
            'worker_id' => $request->worker_id,
            'tenggat_waktu' => $request->tenggat_waktu,
        ]);

        return redirect()->back()->with('success', 'Projek baru berhasil ditambahkan.');
    }

    /**
     * Update project progress and status.
     */
    public function updateProject(Request $request, Project $project): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'progress' => 'required|integer|min:0|max:100',
            'worker_id' => 'nullable|exists:users,id',
            'tenggat_waktu' => 'nullable|date',
        ]);

        $project->update([
            'status' => $request->status,
            'progress' => $request->progress,
            'worker_id' => $request->worker_id,
            'tenggat_waktu' => $request->tenggat_waktu,
        ]);

        return redirect()->back()->with('success', 'Progress dan data projek berhasil diperbarui.');
    }

    /**
     * Delete a project.
     */
    public function deleteProject(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()->back()->with('success', 'Projek berhasil dihapus.');
    }

    /**
     * Mark message as read.
     */
    public function markMessageAsRead(PesanMasuk $pesanMasuk): RedirectResponse
    {
        $pesanMasuk->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Pesan ditandai sebagai sudah dibaca.');
    }
}
