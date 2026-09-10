<?php

use Illuminate\Support\Facades\Route;

// Rute untuk halaman Beranda
Route::get('/', function () {
    return view('public.user.beranda');
});

// --- TAMBAHKAN KODE DI BAWAH INI ---

// Rute untuk halaman Login
Route::get('/login', function () {
    return view('public.user.login'); // Mengarah ke file login.blade.php milikmu
})->name('login'); // <-- Bagian ini penting, ini yang dicari sama fungsi route('login')

// Rute untuk halaman Daftar/Register
Route::get('/daftar', function () {
    return view('public.user.daftar'); // Mengarah ke file daftar.blade.php milikmu
})->name('register'); // <-- Ini yang dicari sama fungsi route('register')
