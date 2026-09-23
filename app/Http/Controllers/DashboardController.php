<?php

namespace App\Http\Controllers;

use App\Models\Order;
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

        return redirect()->route('client.orders');
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
        $orders = Order::with(['product.jurusan', 'service.department', 'department'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        $pesanans = Pesanan::with('detailPesanans.produk')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('client.orders', compact('orders', 'pesanans'));
    }

    /**
     * Display status and pickup details for a specific order.
     */
    public function clientOrderDetail(Order $order): View
    {
        if ($order->user_id && $order->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
        }

        $order->load(['product.jurusan', 'department', 'orderLogs']);

        return view('client.orders.show', compact('order'));
    }
}
