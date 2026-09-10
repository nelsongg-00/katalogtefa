<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Penugasan;
use App\Models\User;

class AdminJurusanController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $pesanans = Pesanan::with(['user', 'detailPesanans.produk'])
            ->whereHas('detailPesanans.produk', function($q) use ($user) {
                $q->where('jurusan_id', $user->jurusan_id);
            })->get();
            
        $workers = User::where('role', 'worker')->where('jurusan_id', $user->jurusan_id)->get();

        return view('admin.dashboard', compact('pesanans', 'workers'));
    }

    public function validateOrder(Request $request, Pesanan $pesanan)
    {
        $pesanan->update(['status_pesanan' => 'Validated']);
        return redirect()->back()->with('success', 'Order validated.');
    }

    public function assignTask(Request $request, Pesanan $pesanan)
    {
        $request->validate(['worker_id' => 'required|exists:users,id']);
        
        Penugasan::create([
            'pesanan_id' => $pesanan->id,
            'worker_id' => $request->worker_id,
            'status_tugas' => 'Ditugaskan',
            'tanggal_disposisi' => now(),
        ]);
        
        $pesanan->update(['status_pesanan' => 'In Progress']);

        return redirect()->back()->with('success', 'Task assigned to worker.');
    }
}
