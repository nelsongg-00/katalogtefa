<?php

namespace App\Http\Controllers;

use App\Models\Product;

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
        $products = Product::latest()->get();

        return view('public.produk', compact('products'));
    }

    public function jasa()
    {
        $jasas = Produk::where('tipe', 'Layanan Jasa')->get();

        return view('public.jasa', compact('jasas'));
    }
}
