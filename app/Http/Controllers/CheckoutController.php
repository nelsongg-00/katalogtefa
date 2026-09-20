<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\Pesanan;
use App\Models\Product;
use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Tampilkan halaman konfirmasi pemesanan produk fisik (COD & Ambil di Tempat).
     */
    public function show(Product $product): View|RedirectResponse
    {
        if (! auth()->check()) {
            return redirect()->guest(route('login'))->with('status', 'Silakan login terlebih dahulu untuk melakukan checkout pemesanan.');
        }

        $product->load('jurusan');

        $lokasiPengambilan = $product->jurusan?->lokasi_pengambilan ?? 'Lab Teaching Factory SMKN 4 Tanjungpinang';

        return view('public.checkout', compact('product', 'lokasiPengambilan'));
    }

    /**
     * Proses pembuatan transaksi pesanan fisik COD.
     */
    public function store(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'jumlah' => ['required', 'integer', 'min:1'],
            'customer_phone' => ['nullable', 'string', 'max:30'],
            'catatan_pelanggan' => ['nullable', 'string', 'max:1000'],
        ]);

        $jumlah = (int) $request->input('jumlah', 1);

        // 1. Validasi ketersediaan stok
        if ($product->stok < $jumlah) {
            return back()->withErrors([
                'jumlah' => "Maaf, stok produk tidak mencukupi. Stok saat ini tersedia {$product->stok} unit.",
            ])->withInput();
        }

        $product->load('jurusan');
        $lokasiPengambilan = $product->jurusan?->lokasi_pengambilan ?? 'Lab Teaching Factory SMKN 4 Tanjungpinang';
        $totalHarga = $product->harga * $jumlah;

        // 2. Generate kode pelacakan unik format TEFA-FISIK-XXXX
        do {
            $orderCode = 'TEFA-FISIK-'.mt_rand(1000, 9999);
        } while (Order::where('order_code', $orderCode)->exists());

        // 3. Potong stok produk fisik secara otomatis
        $product->decrement('stok', $jumlah);

        // 4. Simpan ke tabel orders
        $order = Order::create([
            'order_code' => $orderCode,
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'department_id' => $product->jurusan_id,
            'customer_name' => auth()->user()->name,
            'customer_phone' => $request->customer_phone ?? auth()->user()->phone ?? '081234567890',
            'jumlah' => $jumlah,
            'total_biaya' => $totalHarga,
            'total_harga' => $totalHarga,
            'metode_pembayaran' => 'cod',
            'metode_pengiriman' => 'pickup',
            'lokasi_pengambilan' => $lokasiPengambilan,
            'status' => 'menunggu_konfirmasi',
            'catatan' => $request->catatan_pelanggan,
            'catatan_pelanggan' => $request->catatan_pelanggan,
        ]);

        // 5. Catat riwayat aktivitas ke tabel order_logs
        OrderLog::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'aksi' => 'buat_pesanan',
            'keterangan_log' => "Pesanan fisik COD dibuat oleh {$order->customer_name}. Menunggu konfirmasi dari Admin Jurusan.",
        ]);

        // 6. Sinkronisasi ke tabel Pesanan agar tercatat di rekapitulasi Super Admin
        $pesanan = Pesanan::create([
            'user_id' => auth()->id(),
            'status_pesanan' => 'Menunggu',
            'total_harga' => $totalHarga,
            'tanggal_pesan' => now(),
            'is_service_via_wa' => false,
        ]);

        $legacyProduk = Produk::firstOrCreate(
            ['nama_produk' => $product->nama_produk],
            [
                'jurusan_id' => $product->department_id,
                'tipe' => 'Produk Fisik',
                'harga' => $product->harga,
                'deskripsi' => $product->deskripsi,
                'stok' => $product->stok,
            ]
        );

        DetailPesanan::create([
            'pesanan_id' => $pesanan->id,
            'produk_id' => $legacyProduk->id,
            'jumlah' => $jumlah,
            'subtotal' => $totalHarga,
        ]);

        return redirect()->route('client.orders.show', $order->id)
            ->with('success', "Pesanan {$orderCode} berhasil dibuat! Mohon tunggu konfirmasi dari Admin Jurusan.");
    }
}
