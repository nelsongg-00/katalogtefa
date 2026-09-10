<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\DetailPesanan;

class CheckoutController extends Controller
{
    public function store(Request $request, Produk $produk)
    {
        $pesanan = Pesanan::create([
            'user_id' => auth()->id(),
            'status_pesanan' => 'Pending',
            'total_harga' => $produk->harga,
            'tanggal_pesan' => now(),
            'is_service_via_wa' => false,
        ]);

        DetailPesanan::create([
            'pesanan_id' => $pesanan->id,
            'produk_id' => $produk->id,
            'jumlah' => 1,
            'subtotal' => $produk->harga,
        ]);

        return redirect()->route('client.orders')->with('success', 'Order created successfully. Please upload payment.');
    }
}
