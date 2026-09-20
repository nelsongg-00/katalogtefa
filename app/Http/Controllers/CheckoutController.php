<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Tampilkan halaman konfirmasi pemesanan produk/jasa.
     */
    public function show(Produk $produk)
    {
        return view('client.checkout', compact('produk'));
    }

    /**
     * Buat transaksi pemesanan baru.
     */
    public function store(Request $request, Produk $produk)
    {
        $jumlah = max(1, (int) $request->input('jumlah', 1));
        $totalHarga = $produk->harga * $jumlah;

        $pesanan = Pesanan::create([
            'user_id' => auth()->id(),
            'status_pesanan' => 'Pending',
            'total_harga' => $totalHarga,
            'tanggal_pesan' => now(),
            'is_service_via_wa' => str_contains(strtolower($produk->tipe ?? ''), 'jasa'),
        ]);

        DetailPesanan::create([
            'pesanan_id' => $pesanan->id,
            'produk_id' => $produk->id,
            'jumlah' => $jumlah,
            'subtotal' => $totalHarga,
        ]);

        return redirect()->route('client.orders')->with('success', 'Pesanan #'.$pesanan->id.' berhasil dibuat. Silakan lakukan pembayaran.');
    }
}
