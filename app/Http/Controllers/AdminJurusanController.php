<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Penugasan;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        return view('admin.index', compact(
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
            'dataChart'
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
}
