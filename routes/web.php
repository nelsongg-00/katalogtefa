<?php

use App\Http\Controllers\AdminJurusanController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\WorkerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/profil-sekolah', [HomeController::class, 'profil'])->name('profil');
Route::get('/produk', [HomeController::class, 'produk'])->name('produk');
Route::get('/layanan-jasa', [HomeController::class, 'jasa'])->name('jasa');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Superadmin
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/superadmin', [SuperadminController::class, 'index'])->name('superadmin.dashboard');
    });

    // Admins
    Route::middleware('role:admin_jurusan')->group(function () {
        Route::get('/admin', [AdminJurusanController::class, 'index'])->name('admin.dashboard');
        Route::post('/admin/validate-order/{pesanan}', [AdminJurusanController::class, 'validateOrder'])->name('admin.validateOrder');
        Route::post('/admin/assign-task/{pesanan}', [AdminJurusanController::class, 'assignTask'])->name('admin.assignTask');

        // Worker Management
        Route::post('/admin/workers', [AdminJurusanController::class, 'storeWorker'])->name('admin.workers.store');
        Route::delete('/admin/workers/{user}', [AdminJurusanController::class, 'deleteWorker'])->name('admin.workers.destroy');

        // Project Management
        Route::post('/admin/projects', [AdminJurusanController::class, 'storeProject'])->name('admin.projects.store');
        Route::patch('/admin/projects/{project}', [AdminJurusanController::class, 'updateProject'])->name('admin.projects.update');
        Route::delete('/admin/projects/{project}', [AdminJurusanController::class, 'deleteProject'])->name('admin.projects.destroy');

        // Notification / Messages
        Route::patch('/admin/messages/{pesanMasuk}/read', [AdminJurusanController::class, 'markMessageAsRead'])->name('admin.messages.read');
    });

    // Workers
    Route::middleware('role:worker')->group(function () {
        Route::get('/worker', [WorkerController::class, 'index'])->name('worker.dashboard');
        Route::post('/worker/update-progress/{penugasan}', [WorkerController::class, 'updateProgress'])->name('worker.updateProgress');
    });

    // Clients
    Route::middleware('role:pelanggan')->group(function () {
        Route::get('/my-orders', [DashboardController::class, 'clientOrders'])->name('client.orders');
        Route::post('/checkout/{produk}', [CheckoutController::class, 'store'])->name('checkout.store');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
