<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Project;
use App\Models\ProjectLog;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of orders for the admin's department.
     */
    public function index(Request $request): View
    {
        $jurusanId = auth()->user()->jurusan_id;

        $ordersQuery = Order::with(['service', 'worker', 'project']);

        if ($jurusanId) {
            $ordersQuery->whereHas('service', function ($q) use ($jurusanId) {
                $q->where('department_id', $jurusanId);
            });
        }

        if ($request->filled('status')) {
            $ordersQuery->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $ordersQuery->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $orders = $ordersQuery->latest()->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new manual order.
     */
    public function create(): View
    {
        $jurusanId = auth()->user()->jurusan_id;

        $servicesQuery = Service::where('is_active', true);
        if ($jurusanId) {
            $servicesQuery->where('department_id', $jurusanId);
        }
        $services = $servicesQuery->orderBy('nama_layanan')->get();

        $workersQuery = User::where('role', 'worker');
        if ($jurusanId) {
            $workersQuery->where('jurusan_id', $jurusanId);
        }
        $workers = $workersQuery->orderBy('name')->get();

        return view('admin.orders.create', compact('services', 'workers'));
    }

    /**
     * Store a newly created order, auto-generating project and initial log.
     */
    public function store(Request $request): RedirectResponse
    {
        $jurusanId = auth()->user()->jurusan_id;

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:30',
            'service_id' => 'required|exists:services,id',
            'worker_id' => 'nullable|exists:users,id',
            'total_biaya' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
            'tenggat_waktu' => 'nullable|date',
        ]);

        $service = Service::findOrFail($request->service_id);
        if ($jurusanId && $service->department_id !== $jurusanId) {
            abort(403, 'Anda tidak memiliki akses ke layanan jurusan lain.');
        }

        // Generate unique order code: TEFA-XXXX
        do {
            $orderCode = 'TEFA-'.mt_rand(1000, 9999);
        } while (Order::where('order_code', $orderCode)->exists());

        // 1. Auto-create Project in projects table
        $project = Project::create([
            'jurusan_id' => $jurusanId ?? $service->department_id,
            'nama_projek' => "Pesanan {$orderCode} - {$service->nama_layanan} ({$request->customer_name})",
            'deskripsi' => $request->catatan ?? "Pesanan layanan {$service->nama_layanan} dari WhatsApp.",
            'status' => 'pending',
            'progress' => 0,
            'worker_id' => $request->worker_id,
            'tenggat_waktu' => $request->tenggat_waktu,
        ]);

        // 2. Auto-create initial ProjectLog for progress timeline
        ProjectLog::create([
            'project_id' => $project->id,
            'worker_id' => $request->worker_id ?? auth()->id(),
            'catatan' => 'Pesanan diterima & dicatat oleh Admin Jurusan. Menunggu antrean pengerjaan.',
        ]);

        // 3. Create Order
        $order = Order::create([
            'order_code' => $orderCode,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'service_id' => $service->id,
            'worker_id' => $request->worker_id,
            'project_id' => $project->id,
            'status' => 'pending',
            'total_biaya' => $request->total_biaya,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success', "Pesanan {$orderCode} berhasil dibuat! Link pelacakan publik siap dikirim ke WhatsApp pelanggan.")
            ->with('new_order_code', $orderCode);
    }

    /**
     * Update order status or assigned worker.
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        $this->authorizeOrder($order);

        $request->validate([
            'status' => 'required|in:pending,in_progress,review,completed,cancelled',
            'worker_id' => 'nullable|exists:users,id',
        ]);

        $order->update([
            'status' => $request->status,
            'worker_id' => $request->worker_id,
        ]);

        if ($order->project) {
            $projectStatus = match ($request->status) {
                'completed' => 'completed',
                'cancelled' => 'cancelled',
                'in_progress', 'review' => 'in_progress',
                default => 'pending',
            };

            $order->project->update([
                'status' => $projectStatus,
                'worker_id' => $request->worker_id,
                'progress' => $request->status === 'completed' ? 100 : $order->project->progress,
            ]);
        }

        return redirect()->route('admin.orders.index')->with('success', "Status pesanan {$order->order_code} berhasil diperbarui.");
    }

    /**
     * Remove the specified order.
     */
    public function destroy(Order $order): RedirectResponse
    {
        $this->authorizeOrder($order);

        if ($order->project) {
            $order->project->projectLogs()->delete();
            $order->project->delete();
        }

        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Pesanan dan data pelacakan terkait berhasil dihapus.');
    }

    /**
     * Authorize that the authenticated admin belongs to the order's department.
     */
    protected function authorizeOrder(Order $order): void
    {
        $jurusanId = auth()->user()->jurusan_id;
        if ($jurusanId && $order->service && $order->service->department_id !== $jurusanId) {
            abort(403, 'Aksi ini tidak diizinkan untuk jurusan Anda.');
        }
    }
}
