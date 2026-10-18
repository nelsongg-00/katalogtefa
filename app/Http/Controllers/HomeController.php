<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\Pesanan;
use App\Models\PesanMasuk;
use App\Models\Product;
use App\Models\Project;
use App\Models\ProjectLog;
use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function produk(Request $request): View
    {
        $products = Product::with('jurusan')
            ->when(
                $request->filled('jurusan') && $request->string('jurusan')->toString() !== 'all',
                fn (Builder $query) => $this->scopeByJurusanKode($query, $request->string('jurusan')->toString())
            )
            ->when($request->filled('q'), function (Builder $query) use ($request) {
                $term = '%'.addcslashes($request->string('q')->toString(), '\\%_').'%';

                $query->where(fn (Builder $q) => $q
                    ->where('nama_produk', 'like', $term)
                    ->orWhere('deskripsi', 'like', $term));
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('public.produk', compact('products'));
    }

    /**
     * Halaman detail produk fisik (pengganti modal pop-up).
     */
    public function showProduct(Product $product): View
    {
        $product->load('jurusan');

        return view('public.produk.show', compact('product'));
    }

    public function jasa(Request $request): View
    {
        $services = Service::with('department')
            ->where('is_active', true)
            ->when(
                $request->filled('jurusan') && $request->string('jurusan')->toString() !== 'all',
                fn (Builder $query) => $this->scopeByJurusanKode($query, $request->string('jurusan')->toString())
            )
            ->when($request->filled('q'), function (Builder $query) use ($request) {
                $term = '%'.addcslashes($request->string('q')->toString(), '\\%_').'%';

                $query->where(fn (Builder $q) => $q
                    ->where('nama_layanan', 'like', $term)
                    ->orWhere('deskripsi', 'like', $term));
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('public.jasa', compact('services'));
    }

    /**
     * Halaman detail layanan jasa (pengganti modal pop-up).
     */
    public function showService(Service $service): View
    {
        $service->load('department');

        return view('public.jasa.show', compact('service'));
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

    /**
     * Filter katalog berdasarkan tab program keahlian (?jurusan=ANIMASI).
     *
     * Pola LIKE mereplikasi pemetaan str_contains di view katalog:
     * ANI → ANIMASI, PSPT/PSTV → PSPT, sisanya cocok sebagian dengan kode.
     */
    private function scopeByJurusanKode(Builder $query, string $tab): Builder
    {
        $tab = strtoupper($tab);

        $patterns = match ($tab) {
            'ANIMASI' => ['%ANI%'],
            'PSPT' => ['%PSPT%', '%PSTV%'],
            default => ['%'.$tab.'%'],
        };

        return $query->whereHas('jurusan', function (Builder $jurusanQuery) use ($patterns) {
            $jurusanQuery->where(function (Builder $q) use ($patterns) {
                foreach ($patterns as $pattern) {
                    $q->orWhere('kode', 'like', $pattern);
                }
            });
        });
    }
}
