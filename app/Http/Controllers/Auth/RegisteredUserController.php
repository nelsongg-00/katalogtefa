<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pelanggan',
            'is_active' => true,
        ]);

        event(new Registered($user));

        Auth::login($user);

        $request->session()->regenerate();

        $welcomeMessage = 'Pendaftaran berhasil! Selamat datang, '.$user->name.'!';

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
}
