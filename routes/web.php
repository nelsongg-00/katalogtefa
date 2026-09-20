<?php

use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\WorkerController as AdminWorkerController;
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

    // Admins Jurusan (Modular routes)
    Route::middleware('role:admin_jurusan')->group(function () {
        // 1. Dashboard / Ringkasan
        Route::get('/admin', [AdminJurusanController::class, 'index'])->name('admin.dashboard');
        Route::post('/admin/validate-order/{pesanan}', [AdminJurusanController::class, 'validateOrder'])->name('admin.validateOrder');
        Route::post('/admin/assign-task/{pesanan}', [AdminJurusanController::class, 'assignTask'])->name('admin.assignTask');

        // 2. CRUD Produk Fisik
        Route::resource('/admin/products', AdminProductController::class)->names('admin.products');

        // 3. Project Management
        Route::get('/admin/projects', [AdminProjectController::class, 'index'])->name('admin.projects.index');
        Route::post('/admin/projects', [AdminProjectController::class, 'store'])->name('admin.projects.store');
        Route::patch('/admin/projects/{project}', [AdminProjectController::class, 'update'])->name('admin.projects.update');
        Route::delete('/admin/projects/{project}', [AdminProjectController::class, 'destroy'])->name('admin.projects.destroy');

        // 4. Worker Management
        Route::get('/admin/workers', [AdminWorkerController::class, 'index'])->name('admin.workers.index');
        Route::post('/admin/workers', [AdminWorkerController::class, 'store'])->name('admin.workers.store');
        Route::delete('/admin/workers/{user}', [AdminWorkerController::class, 'destroy'])->name('admin.workers.destroy');

        // 5. Notification / Messages
        Route::get('/admin/messages', [AdminMessageController::class, 'index'])->name('admin.messages.index');
        Route::patch('/admin/messages/{pesanMasuk}/read', [AdminMessageController::class, 'markAsRead'])->name('admin.messages.read');
    });

    // Workers
    Route::middleware('role:worker')->group(function () {
        Route::get('/worker', [WorkerController::class, 'index'])->name('worker.index');
        Route::get('/worker/dashboard', [WorkerController::class, 'index'])->name('worker.dashboard');
        Route::get('/worker/projects/{id}', [WorkerController::class, 'showProject'])->name('worker.projects.show');
        Route::post('/worker/projects/{id}/log', [WorkerController::class, 'storeLog'])->name('worker.projects.log');
        Route::post('/worker/projects/{id}/submit', [WorkerController::class, 'submitProject'])->name('worker.projects.submit');
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
