<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrackingController extends Controller
{
    /**
     * Display the tracking search landing page.
     */
    public function index(): View
    {
        return view('public.tracking');
    }

    /**
     * Display order tracking details by unique order code.
     */
    public function show(string $order_code): View
    {
        $orderCodeClean = strtoupper(trim($order_code));

        $order = Order::with([
            'service.department',
            'worker',
            'project.projectLogs.worker',
        ])->where('order_code', $orderCodeClean)->first();

        return view('public.tracking', [
            'order' => $order,
            'searchedCode' => $orderCodeClean,
        ]);
    }

    /**
     * Handle search form submission and redirect to clean tracking URL.
     */
    public function search(Request $request): RedirectResponse
    {
        $request->validate([
            'order_code' => 'required|string|max:50',
        ]);

        $code = strtoupper(trim($request->order_code));

        return redirect()->route('order.track', $code);
    }
}
