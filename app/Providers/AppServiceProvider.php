<?php

namespace App\Providers;

use App\Models\Jurusan;
use App\Models\PesanMasuk;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.admin', function ($view) {
            if (auth()->check()) {
                $user = auth()->user();
                $jurusanId = $user->jurusan_id ?? $user->department_id;
                $jurusanIds = $jurusanId ? [$jurusanId] : [];
                if ($user->jurusan && $user->jurusan->kode) {
                    $jurusanIds = Jurusan::where('kode', $user->jurusan->kode)->pluck('id')->toArray();
                }

                $pesanMasuksQuery = PesanMasuk::where(function ($q) use ($jurusanIds) {
                    $q->whereNull('jurusan_id');
                    if (! empty($jurusanIds)) {
                        $q->orWhereIn('jurusan_id', $jurusanIds);
                    }
                });

                $unreadMessagesCount = (clone $pesanMasuksQuery)->where('is_read', false)->count();
                $recentMessages = $pesanMasuksQuery->orderBy('created_at', 'desc')->take(8)->get();

                $view->with([
                    'unreadMessagesCount' => $unreadMessagesCount,
                    'recentMessages' => $recentMessages,
                    'jurusan' => $user->jurusan,
                ]);
            }
        });
    }
}
