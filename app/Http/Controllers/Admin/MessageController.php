<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\PesanMasuk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    /**
     * Display a listing of messages/notifications.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $jurusanId = $user->jurusan_id ?? $user->department_id;
        $filter = $request->query('filter', 'all');

        $jurusanIds = $jurusanId ? [$jurusanId] : [];
        if ($user && $user->jurusan && $user->jurusan->kode) {
            $jurusanIds = Jurusan::where('kode', $user->jurusan->kode)->pluck('id')->toArray();
        }

        $pesanMasuksQuery = PesanMasuk::where(function ($q) use ($jurusanIds) {
            $q->whereNull('jurusan_id');
            if (! empty($jurusanIds)) {
                $q->orWhereIn('jurusan_id', $jurusanIds);
            }
        });

        $unreadCount = (clone $pesanMasuksQuery)->where('is_read', false)->count();

        if ($filter === 'unread') {
            $pesanMasuksQuery->where('is_read', false);
        }

        $pesanMasuks = $pesanMasuksQuery->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.messages.index', compact('pesanMasuks', 'unreadCount', 'filter'));
    }

    /**
     * Mark message as read.
     */
    public function markAsRead(PesanMasuk $pesanMasuk): RedirectResponse
    {
        $pesanMasuk->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Pesan ditandai sebagai sudah dibaca.');
    }
}
