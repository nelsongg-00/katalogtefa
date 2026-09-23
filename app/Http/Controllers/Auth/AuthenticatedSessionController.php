<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        if ($request->filled('redirect')) {
            session()->put('url.intended', $request->query('redirect'));
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = auth()->user();
        $welcomeMessage = 'Selamat datang kembali, '.$user->name.'!';

        // Tentukan fallback default jika BUKAN intended checkout
        $defaultUrl = route('home');
        if (in_array($user->role, ['super_admin', 'superadmin', 'admin_jurusan', 'worker'])) {
            $roleRoute = match ($user->role) {
                'super_admin', 'superadmin' => 'superadmin.dashboard',
                'admin_jurusan' => 'admin.dashboard',
                'worker' => 'worker.dashboard',
                default => 'home',
            };
            $defaultUrl = route($roleRoute);
        }

        return redirect()->intended($defaultUrl)->with('toast_success', $welcomeMessage);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
