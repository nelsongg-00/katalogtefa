<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $role = auth()->user()->role;
        if ($role == 'super_admin') {
            return redirect()->route('superadmin.dashboard');
        } elseif ($role == 'admin_jurusan') {
            return redirect()->route('admin.dashboard');
        } elseif ($role == 'worker') {
            return redirect()->route('worker.dashboard');
        }
        
        return view('dashboard'); // Client default
    }

    public function adminDashboard()
    {
        return view('admin.dashboard');
    }

    public function workerDashboard()
    {
        return view('worker.dashboard');
    }

    public function clientOrders()
    {
        $pesanans = \App\Models\Pesanan::with('detailPesanans.produk')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('client.orders', compact('pesanans'));
    }
}
