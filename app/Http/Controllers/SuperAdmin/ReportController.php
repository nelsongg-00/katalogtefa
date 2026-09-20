<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Tampilkan rekapitulasi transaksi global dengan filter komprehensif.
     */
    public function index(Request $request): View
    {
        $data = $this->buildReportQuery($request);

        return view('superadmin.reports.index', $data);
    }

    /**
     * Tampilkan halaman khusus ramah cetak (print/PDF) tanpa sidebar/navbar.
     */
    public function print(Request $request): View
    {
        $data = $this->buildReportQuery($request);

        return view('superadmin.reports.print', $data);
    }

    /**
     * Koreksi data transaksi oleh Super Admin.
     */
    public function correct(Request $request, Pesanan $pesanan): RedirectResponse
    {
        $validated = $request->validate([
            'total_harga' => ['required', 'numeric', 'min:0'],
            'status_pesanan' => ['required', 'string'],
        ]);

        $pesanan->update([
            'total_harga' => (int) $validated['total_harga'],
            'status_pesanan' => $validated['status_pesanan'],
        ]);

        return redirect()->back()->with('success', 'Transaksi #'.$pesanan->id.' berhasil dikoreksi.');
    }

    /**
     * Helper untuk memproses query filter laporan transaksi global.
     *
     * @return array<string, mixed>
     */
    private function buildReportQuery(Request $request): array
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $jurusanId = $request->input('jurusan');
        $status = $request->input('status');
        $q = $request->input('q');

        $query = Pesanan::with(['user', 'detailPesanans.produk.jurusan']);

        if (! empty($fromDate)) {
            $query->whereDate('created_at', '>=', $fromDate);
        }

        if (! empty($toDate)) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        if (! empty($status)) {
            if (strtolower($status) === 'selesai' || strtolower($status) === 'completed') {
                $query->whereIn('status_pesanan', ['Completed', 'Selesai']);
            } elseif (strtolower($status) === 'diproses' || strtolower($status) === 'in progress') {
                $query->whereIn('status_pesanan', ['In Progress', 'Diproses']);
            } elseif (strtolower($status) === 'menunggu' || strtolower($status) === 'pending') {
                $query->whereIn('status_pesanan', ['Pending', 'Menunggu']);
            } elseif (strtolower($status) === 'dibatalkan' || strtolower($status) === 'cancelled') {
                $query->whereIn('status_pesanan', ['Cancelled', 'Dibatalkan']);
            } else {
                $query->where('status_pesanan', $status);
            }
        }

        if (! empty($jurusanId)) {
            $query->whereHas('detailPesanans.produk', function ($pQuery) use ($jurusanId) {
                $pQuery->where('jurusan_id', $jurusanId);
            });
        }

        if (! empty($q)) {
            $query->where(function ($w) use ($q) {
                $w->where('id', 'like', "%{$q}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$q}%"))
                    ->orWhereHas('detailPesanans.produk', fn ($p) => $p->where('nama_produk', 'like', "%{$q}%"));
            });
        }

        $transactions = $query->orderBy('created_at', 'desc')->get();

        // Hitung ringkasan transaksi
        $totalTransactions = $transactions->count();
        $completedTransactions = $transactions->filter(fn ($t) => in_array(strtolower($t->status_pesanan), ['completed', 'selesai']));
        $totalRevenue = $completedTransactions->sum('total_harga');
        $activeInProgress = $transactions->filter(fn ($t) => in_array(strtolower($t->status_pesanan), ['in progress', 'diproses', 'pending', 'menunggu']))->count();
        $cancelledCount = $transactions->filter(fn ($t) => in_array(strtolower($t->status_pesanan), ['cancelled', 'dibatalkan']))->count();

        $jurusans = Jurusan::orderBy('nama_jurusan', 'asc')->get();
        $selectedJurusan = $jurusanId ? Jurusan::find($jurusanId) : null;

        return [
            'transactions' => $transactions,
            'totalTransactions' => $totalTransactions,
            'totalRevenue' => $totalRevenue,
            'activeInProgress' => $activeInProgress,
            'cancelledCount' => $cancelledCount,
            'jurusans' => $jurusans,
            'selectedJurusan' => $selectedJurusan,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'status' => $status,
            'jurusanId' => $jurusanId,
            'q' => $q,
            'printedAt' => Carbon::now()->isoFormat('D MMMM Y, HH:mm'),
        ];
    }
}
