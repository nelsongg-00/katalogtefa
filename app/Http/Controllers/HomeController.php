<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderLog;
use App\Models\Pesanan;
use App\Models\PesanMasuk;
use App\Models\Product;
use App\Models\Project;
use App\Models\ProjectLog;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home');
    }

    public function profil(): View
    {
        return view('public.profil');
    }

    public function produk(): View
    {
        $products = Product::with('jurusan')->latest()->get();

        return view('public.produk', compact('products'));
    }

    public function jasa(): View
    {
        $services = Service::with('department')->where('is_active', true)->latest()->get();

        return view('public.jasa', compact('services'));
    }

    /**
     * Handle client service ordering / consultation and redirect to WhatsApp.
     */
    public function orderService(Service $service): RedirectResponse
    {
        $user = auth()->user();
        $departmentId = $service->department_id;

        // Generate unique order code: TEFA-JASA-XXXX
        do {
            $orderCode = 'TEFA-JASA-'.mt_rand(1000, 9999);
        } while (Order::where('order_code', $orderCode)->exists());

        // 1. Auto-create Project in projects table
        $project = Project::create([
            'jurusan_id' => $departmentId,
            'nama_projek' => "Pesanan {$orderCode} - {$service->nama_layanan} ({$user->name})",
            'deskripsi' => "Permintaan pesanan / konsultasi layanan {$service->nama_layanan} dari akun pelanggan {$user->name}.",
            'status' => 'pending',
            'progress' => 0,
        ]);

        // 2. Auto-create initial ProjectLog
        ProjectLog::create([
            'project_id' => $project->id,
            'worker_id' => $user->id,
            'catatan' => 'Permintaan pesanan / konsultasi jasa diajukan oleh pelanggan via sistem. Menunggu konfirmasi dan penugasan tim oleh Admin Jurusan.',
        ]);

        // 3. Create Order
        $order = Order::create([
            'order_code' => $orderCode,
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'customer_phone' => $user->phone ?? '081234567890',
            'service_id' => $service->id,
            'department_id' => $departmentId,
            'project_id' => $project->id,
            'status' => 'pending',
            'total_biaya' => $service->estimasi_harga,
            'total_harga' => $service->estimasi_harga,
            'catatan' => "Pemesanan & konsultasi layanan {$service->nama_layanan} oleh {$user->name} ({$user->email}).",
        ]);

        // 4. Record to order_logs
        OrderLog::create([
            'order_id' => $order->id,
            'user_id' => $user->id,
            'aksi' => 'buat_pesanan_jasa',
            'keterangan_log' => "Pemesanan & konsultasi layanan jasa dibuat oleh {$user->name}. Menunggu konfirmasi Admin Jurusan.",
        ]);

        // 5. Notify Admin Jurusan via pesan_masuks
        PesanMasuk::create([
            'jurusan_id' => $departmentId,
            'nama_pengirim' => $user->name,
            'email' => $user->email,
            'subjek' => 'Pemesanan Layanan Jasa Baru',
            'pesan' => "Pesanan Jasa #{$order->order_code} ({$service->nama_layanan}) diajukan oleh {$user->name}.",
            'is_read' => false,
        ]);

        // 6. Sinkronisasi ke tabel Pesanan agar masuk rekapitulasi transaksi
        Pesanan::create([
            'user_id' => $user->id,
            'status_pesanan' => 'Pending',
            'total_harga' => $service->estimasi_harga,
            'tanggal_pesan' => now(),
            'is_service_via_wa' => true,
        ]);

        $waPhone = '6281234567890';
        $waMessage = "Halo Admin TeFa SMKN 4 Tanjungpinang, saya {$user->name} ingin konsultasi / konfirmasi pemesanan layanan jasa *{$service->nama_layanan}*.\n".
            "Kode Pesanan: *{$order->order_code}*\n".
            'Estimasi Biaya: Rp '.number_format($service->estimasi_harga, 0, ',', '.')."\n".
            'Mohon informasi tindak lanjut dan jadwal pengerjaannya. Terima kasih!';

        $waUrl = 'https://wa.me/'.$waPhone.'?text='.rawurlencode($waMessage);

        return redirect()->away($waUrl);
    }
}
