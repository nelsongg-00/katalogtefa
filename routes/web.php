<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/profil-sekolah', [HomeController::class, 'profil'])->name('profil');
Route::get('/produk', [HomeController::class, 'produk'])->name('produk');
Route::get('/layanan-jasa', [HomeController::class, 'jasa'])->name('jasa');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Superadmin
    Route::middleware('role:super_admin')->group(function() {
        Route::get('/superadmin', [\App\Http\Controllers\SuperadminController::class, 'index'])->name('superadmin.dashboard');
    });

    // Admins
    Route::middleware('role:admin_jurusan')->group(function() {
        Route::get('/admin', [\App\Http\Controllers\AdminJurusanController::class, 'index'])->name('admin.dashboard');
        Route::post('/admin/validate-order/{pesanan}', [\App\Http\Controllers\AdminJurusanController::class, 'validateOrder'])->name('admin.validateOrder');
        Route::post('/admin/assign-task/{pesanan}', [\App\Http\Controllers\AdminJurusanController::class, 'assignTask'])->name('admin.assignTask');
    });

    // Workers
    Route::middleware('role:worker')->group(function() {
        Route::get('/worker', [\App\Http\Controllers\WorkerController::class, 'index'])->name('worker.dashboard');
        Route::post('/worker/update-progress/{penugasan}', [\App\Http\Controllers\WorkerController::class, 'updateProgress'])->name('worker.updateProgress');
    });

    // Clients
    Route::middleware('role:pelanggan')->group(function() {
        Route::get('/my-orders', [DashboardController::class, 'clientOrders'])->name('client.orders');
        Route::post('/checkout/{produk}', [\App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
