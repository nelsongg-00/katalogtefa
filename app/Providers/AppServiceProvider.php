<?php

namespace App\Providers;

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
                $jurusanId = auth()->user()->jurusan_id;
                $pesanMasuksQuery = PesanMasuk::where(function ($q) use ($jurusanId) {
                    $q->whereNull('jurusan_id');
                    if ($jurusanId) {
                        $q->orWhere('jurusan_id', $jurusanId);
                    }
                });

                $unreadMessagesCount = (clone $pesanMasuksQuery)->where('is_read', false)->count();
                $recentMessages = $pesanMasuksQuery->orderBy('created_at', 'desc')->take(8)->get();

                $view->with([
                    'unreadMessagesCount' => $unreadMessagesCount,
                    'recentMessages' => $recentMessages,
                    'jurusan' => auth()->user()->jurusan,
                ]);
            }
        });
    }
}
