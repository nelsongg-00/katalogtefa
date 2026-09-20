<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\Pesanan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilkan overview dashboard Super Admin.
     */
    public function index(Request $request): View
    {
        $year = (int) $request->input('year', Carbon::now()->year);

        // Metrik Ringkasan Transaksi Global
        $allOrders = Pesanan::all();
        $totalOrders = $allOrders->count();
        $pendingOrders = $allOrders->filter(fn ($p) => in_array(strtolower($p->status_pesanan), ['pending', 'menunggu']))->count();
        $inProgressOrders = $allOrders->filter(fn ($p) => in_array(strtolower($p->status_pesanan), ['in progress', 'diproses', 'proses']))->count();
        $completedOrdersList = $allOrders->filter(fn ($p) => in_array(strtolower($p->status_pesanan), ['completed', 'selesai']));
        $completedOrders = $completedOrdersList->count();
        $cancelledOrders = $allOrders->filter(fn ($p) => in_array(strtolower($p->status_pesanan), ['cancelled', 'dibatalkan', 'batal']))->count();
        $totalRevenue = $completedOrdersList->sum('total_harga');

        // Metrik Pengguna & Jurusan
        $allUsers = User::all();
        $totalUsers = $allUsers->count();
        $totalAdmin = $allUsers->where('role', 'admin_jurusan')->count();
        $totalWorker = $allUsers->where('role', 'worker')->count();
        $totalClient = $allUsers->where('role', 'pelanggan')->count();

        $allJurusans = Jurusan::all();
        $totalJurusans = $allJurusans->count();
        $activeJurusans = $allJurusans->where('status_aktif', true)->count();

        // Tren Aktivitas 12 Bulan Sepanjang Tahun Berjalan
        $monthlyIncoming = array_fill(0, 12, 0);
        $monthlyCompleted = array_fill(0, 12, 0);

        foreach ($allOrders as $order) {
            $orderDate = $order->tanggal_pesan ? Carbon::parse($order->tanggal_pesan) : Carbon::parse($order->created_at);
            if ($orderDate->year === $year) {
                $monthIndex = $orderDate->month - 1;
                $monthlyIncoming[$monthIndex]++;
                if (in_array(strtolower($order->status_pesanan), ['completed', 'selesai'])) {
                    $monthlyCompleted[$monthIndex]++;
                }
            }
        }

        $maxMonthlyValue = max(2, ...$monthlyIncoming);
        $chartTop = ($maxMonthlyValue % 2 !== 0) ? $maxMonthlyValue + 1 : $maxMonthlyValue;
        $chartTicks = [
            round($chartTop * 0.0),
            round($chartTop * 0.25, 1),
            round($chartTop * 0.5, 1),
            round($chartTop * 0.75, 1),
            round($chartTop * 1.0),
        ];

        // Performa Omzet per Jurusan
        $ordersWithJurusan = Pesanan::with(['detailPesanans.produk.jurusan'])->get();
        $revenuePerJurusan = [];
        $maxOmzetPerJurusan = 1;

        foreach ($allJurusans as $jurusan) {
            $jurOrders = $ordersWithJurusan->filter(function ($p) use ($jurusan) {
                return $p->detailPesanans->contains(fn ($d) => $d->produk?->jurusan_id === $jurusan->id);
            });

            $jurCompleted = $jurOrders->filter(fn ($p) => in_array(strtolower($p->status_pesanan), ['completed', 'selesai']));
            $jurOmzet = (int) $jurCompleted->sum('total_harga');

            if ($jurOmzet > $maxOmzetPerJurusan) {
                $maxOmzetPerJurusan = $jurOmzet;
            }

            $revenuePerJurusan[] = [
                'jurusan' => $jurusan,
                'total_orders' => $jurOrders->count(),
                'completed_orders' => $jurCompleted->count(),
                'omzet' => $jurOmzet,
            ];
        }

        // 5 Transaksi Terbaru
        $recentOrders = Pesanan::with(['user', 'detailPesanans.produk.jurusan'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('superadmin.dashboard', compact(
            'year',
            'totalOrders',
            'pendingOrders',
            'inProgressOrders',
            'completedOrders',
            'cancelledOrders',
            'totalRevenue',
            'totalUsers',
            'totalAdmin',
            'totalWorker',
            'totalClient',
            'totalJurusans',
            'activeJurusans',
            'monthlyIncoming',
            'monthlyCompleted',
            'chartTop',
            'chartTicks',
            'revenuePerJurusan',
            'maxOmzetPerJurusan',
            'recentOrders'
        ));
    }
}
