<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Jurusan;
use App\Models\User;

class SuperadminController extends Controller
{
    public function index()
    {
        $totalRevenue = Pesanan::where('status_pesanan', 'Completed')->sum('total_harga');
        $totalOrders = Pesanan::count();
        $totalUsers = User::count();
        $totalJurusans = Jurusan::count();

        $pesanans = Pesanan::with(['user', 'detailPesanans.produk.jurusan'])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        return view('superadmin.dashboard', compact('totalRevenue', 'totalOrders', 'totalUsers', 'totalJurusans', 'pesanans'));
    }
}
