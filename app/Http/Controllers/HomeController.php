<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Service;

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
        $services = Service::with('department')->where('is_active', true)->latest()->get();

        return view('public.jasa', compact('services'));
    }
}
