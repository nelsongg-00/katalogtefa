<?php

namespace App\Providers;

use App\Models\Jurusan;
use App\Models\PesanMasuk;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Events\NotificationSending;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
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
        // Catat email Breeze (verifikasi & reset) ke storage/logs/email.log
        // begitu dikirim, tanpa menunggu antrean apa pun.
        Event::listen(NotificationSending::class, function (NotificationSending $event): void {
            $email = $event->notifiable->email ?? null;

            if (! $email) {
                return;
            }

            $judul = match (true) {
                $event->notification instanceof VerifyEmail => 'EMAIL VERIFICATION (link verifikasi)',
                $event->notification instanceof ResetPassword => 'PASSWORD RESET (link reset)',
                default => null,
            };

            if ($judul === null) {
                return;
            }

            Log::channel('email')->info(
                "=== {$judul} ===\n"
                ."Email : {$email}\n"
                .'Nama  : '.($event->notifiable->name ?? '-')
            );
        });

        Paginator::defaultView('vendor.pagination.custom');
        Paginator::defaultSimpleView('vendor.pagination.custom');
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
