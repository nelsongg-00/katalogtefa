<?php

use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\WorkerController as AdminWorkerController;
use App\Http\Controllers\AdminJurusanController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\DepartmentController as SuperAdminDepartmentController;
use App\Http\Controllers\SuperAdmin\ReportController as SuperAdminReportController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\WorkerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/profil-sekolah', [HomeController::class, 'profil'])->name('profil');
Route::get('/produk', [HomeController::class, 'produk'])->name('produk');
Route::get('/layanan-jasa', [HomeController::class, 'jasa'])->name('jasa');
Route::get('/lacak', [TrackingController::class, 'index'])->name('order.tracking.index');
Route::get('/lacak/{order_code}', [TrackingController::class, 'show'])->name('order.track');
Route::post('/lacak/search', [TrackingController::class, 'search'])->name('order.search');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Superadmin (Modular routes)
    Route::middleware('role:super_admin')->prefix('superadmin')->name('superadmin.')->group(function () {
        // 1. Dashboard Overview & Statistik Global
        Route::get('/', [SuperAdminDashboardController::class, 'index'])->name('dashboard');

        // 2. Manajemen Data Master Jurusan
        Route::get('/departments', [SuperAdminDepartmentController::class, 'index'])->name('departments.index');
        Route::post('/departments', [SuperAdminDepartmentController::class, 'store'])->name('departments.store');
        Route::put('/departments/{jurusan}', [SuperAdminDepartmentController::class, 'update'])->name('departments.update');
        Route::patch('/departments/{jurusan}/toggle', [SuperAdminDepartmentController::class, 'toggle'])->name('departments.toggle');
        Route::delete('/departments/{jurusan}', [SuperAdminDepartmentController::class, 'destroy'])->name('departments.destroy');

        // 3. Manajemen User (CRUD semua akun sistem & penetapan jurusan)
        Route::get('/users', [SuperAdminUserController::class, 'index'])->name('users.index');
        Route::post('/users', [SuperAdminUserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [SuperAdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [SuperAdminUserController::class, 'destroy'])->name('users.destroy');

        // 4. Laporan & Rekapitulasi Transaksi Global & Cetak
        Route::get('/reports', [SuperAdminReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/print', [SuperAdminReportController::class, 'print'])->name('reports.print');
        Route::patch('/reports/{pesanan}/koreksi', [SuperAdminReportController::class, 'correct'])->name('reports.correct');
    });

    // Admins Jurusan (Modular routes)
    Route::middleware('role:admin_jurusan')->group(function () {
        // 1. Dashboard / Ringkasan
        Route::get('/admin', [AdminJurusanController::class, 'index'])->name('admin.dashboard');
        Route::post('/admin/validate-order/{pesanan}', [AdminJurusanController::class, 'validateOrder'])->name('admin.validateOrder');
        Route::post('/admin/assign-task/{pesanan}', [AdminJurusanController::class, 'assignTask'])->name('admin.assignTask');

        // 2. CRUD Produk Fisik
        Route::resource('/admin/products', AdminProductController::class)->names('admin.products');

        // 3. CRUD Layanan Jasa
        Route::resource('/admin/services', AdminServiceController::class)->names('admin.services');

        // 4. Pencatatan Pesanan Manual WhatsApp & Tracking
        Route::resource('/admin/orders', AdminOrderController::class)->names('admin.orders');

        // 5. Project Management
        Route::get('/admin/projects', [AdminProjectController::class, 'index'])->name('admin.projects.index');
        Route::post('/admin/projects', [AdminProjectController::class, 'store'])->name('admin.projects.store');
        Route::patch('/admin/projects/{project}', [AdminProjectController::class, 'update'])->name('admin.projects.update');
        Route::delete('/admin/projects/{project}', [AdminProjectController::class, 'destroy'])->name('admin.projects.destroy');

        // 6. Worker Management
        Route::get('/admin/workers', [AdminWorkerController::class, 'index'])->name('admin.workers.index');
        Route::post('/admin/workers', [AdminWorkerController::class, 'store'])->name('admin.workers.store');
        Route::delete('/admin/workers/{user}', [AdminWorkerController::class, 'destroy'])->name('admin.workers.destroy');

        // 7. Notification / Messages
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
        Route::get('/checkout/{produk}', [CheckoutController::class, 'show'])->name('checkout.show');
        Route::post('/checkout/{produk}', [CheckoutController::class, 'store'])->name('checkout.store');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
