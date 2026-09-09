<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produk; // Memanggil Model Produk

class ProdukController extends Controller
{
    public function index()
    {
        // Mengambil semua data produk dari database
        $produk = Produk::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mengambil data produk TeFa',
            'data' => $produk
        ], 200);
    }
}