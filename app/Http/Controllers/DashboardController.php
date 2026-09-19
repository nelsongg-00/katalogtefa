<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Redirect users to their specific dashboard based on their role.
     */
    public function index(): View|RedirectResponse
    {
        $role = auth()->user()->role;
        if ($role === 'super_admin') {
            return redirect()->route('superadmin.dashboard');
        } elseif ($role === 'admin_jurusan') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'worker') {
            return redirect()->route('worker.dashboard');
        }

        return view('dashboard'); // Client default
    }

    /**
     * Redirect to the department admin dashboard.
     */
    public function adminDashboard(): RedirectResponse
    {
        return redirect()->route('admin.dashboard');
    }

    /**
     * Redirect to the worker dashboard.
     */
    public function workerDashboard(): RedirectResponse
    {
        return redirect()->route('worker.dashboard');
    }

    /**
     * Display the order history for the logged-in client.
     */
    public function clientOrders(): View
    {
        $pesanans = Pesanan::with('detailPesanans.produk')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client.orders', compact('pesanans'));
    }
}
