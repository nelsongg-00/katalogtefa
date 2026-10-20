<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\VerifyOtpNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class OtpVerificationController extends Controller
{
    /**
     * Masa berlaku satu kode OTP, dalam menit.
     */
    private const TTL_MINUTES = 5;

    /**
     * Display the OTP verification view.
     */
    public function create(): View
    {
        return view('auth.verify-otp');
    }

    /**
     * Verify the entered OTP, then hand the user to Breeze's reset flow
     * with a freshly minted token.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $email = session('email');
        $otp = session('otp');
        $expired = now()->timestamp > (int) session('otp_expires_at');

        if (! $email || ! $otp || $expired) {
            return redirect()->route('password.request')
                ->withErrors(['otp' => 'Kode OTP kedaluwarsa. Silakan minta kode baru.']);
        }

        if (! hash_equals($otp, $request->input('otp'))) {
            return back()->withErrors(['otp' => 'Kode OTP tidak sesuai.']);
        }

        // Sumber kebenaran email = session, bukan input tersembunyi dari form.
        $user = User::where('email', $email)->first();

        if (! $user) {
            session()->forget(['otp', 'otp_expires_at']);

            return redirect()->route('password.request')
                ->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        $token = Password::broker()->createToken($user);

        session()->forget(['otp', 'otp_expires_at']);

        // withInput mengisi field email di halaman reset tanpa menyentuh view-nya.
        return redirect()->route('password.reset', $token)
            ->withInput(['email' => $email]);
    }

    /**
     * Issue a new OTP for the e-mail sitting in the session.
     */
    public function resend(): RedirectResponse
    {
        $email = session('email');

        if (! $email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Silakan masukkan email terlebih dahulu.']);
        }

        static::issue($email);

        return redirect()->route('password.otp.show')
            ->with('status', 'Kode OTP baru telah dikirim ke email Anda.');
    }

    /**
     * Generate a 6-digit OTP, keep it in the session, and e-mail it.
     * Dipanggil juga oleh PasswordResetLinkController.
     */
    public static function issue(string $email): void
    {
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        session([
            'email' => $email,
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(self::TTL_MINUTES)->timestamp,
        ]);

        // Log dulu supaya kode langsung terbaca walau email gagal terkirim.
        $expires = now()->addMinutes(self::TTL_MINUTES)->setTimezone('Asia/Jakarta');
        Log::channel('otp')->info(
            "=== OTP REQUEST ===\n"
            ."Email   : {$email}\n"
            ."Kode    : {$otp}\n"
            .'Berlaku : '.self::TTL_MINUTES." menit, sampai {$expires->format('Y-m-d H:i:s')} WIB"
        );

        Notification::route('mail', $email)->notify(new VerifyOtpNotification($otp));
    }
}
