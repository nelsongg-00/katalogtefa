<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurusan;
use App\Models\Produk;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function profil()
    {
        return view('public.profil');
    }

    public function produk()
    {
        $produks = Produk::where('tipe', 'Produk Fisik')->get();
        return view('public.produk', compact('produks'));
    }

    public function jasa()
    {
        $jasas = Produk::where('tipe', 'Layanan Jasa')->get();
        return view('public.jasa', compact('jasas'));
    }
}
