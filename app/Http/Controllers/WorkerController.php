<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penugasan;
use App\Models\Progres;

class WorkerController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $penugasans = Penugasan::with(['pesanan.detailPesanans.produk', 'progres'])
            ->where('worker_id', $user->id)
            ->get();

        return view('worker.dashboard', compact('penugasans'));
    }

    public function updateProgress(Request $request, Penugasan $penugasan)
    {
        $request->validate(['keterangan_progres' => 'required']);
        
        Progres::create([
            'penugasan_id' => $penugasan->id,
            'keterangan_progres' => $request->keterangan_progres,
            'tanggal_update' => now(),
        ]);
        
        if($request->status == 'Selesai') {
            $penugasan->update(['status_tugas' => 'Selesai']);
            $penugasan->pesanan->update(['status_pesanan' => 'Completed']);
        } else {
            $penugasan->update(['status_tugas' => 'Diproses']);
        }

        return redirect()->back()->with('success', 'Progress updated.');
    }
}
