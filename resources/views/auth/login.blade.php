<x-guest-layout>
    <style>
        .auth-title {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
            text-align: center;
        }

        .auth-subtitle {
            font-size: 13.5px;
            color: #64748b;
            text-align: center;
            margin-bottom: 24px;
        }

        .auth-form-group {
            margin-bottom: 18px;
        }

        .auth-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .auth-input {
            width: 100%;
            padding: 11px 14px;
            font-size: 14px;
            font-weight: 500;
            color: #0f172a;
            background-color: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            outline: none;
            transition: all 0.2s ease;
        }

        .auth-input:focus {
            background-color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .auth-input::placeholder {
            color: #94a3b8;
        }

        .auth-btn-primary {
            width: 100%;
            background-color: #2563eb;
            color: #ffffff;
            padding: 13px;
            font-size: 14.5px;
            font-weight: 700;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .auth-btn-primary:hover {
            background-color: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(37, 99, 235, 0.35);
        }

        .auth-switch-link {
            text-align: center;
            margin-top: 22px;
            padding-top: 18px;
            border-top: 1px solid #f1f5f9;
            font-size: 13px;
            color: #64748b;
        }

        .auth-switch-link a {
            color: #2563eb;
            font-weight: 700;
            text-decoration: none;
            margin-left: 4px;
        }

        .auth-switch-link a:hover {
            text-decoration: underline;
        }
    </style>

    <div class="auth-title">Masuk ke Akun</div>
    <div class="auth-subtitle">Silakan masukkan email dan kata sandi Anda</div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="auth-form-group">
            <label for="email" class="auth-label">Alamat Email</label>
            <input id="email" 
                   class="auth-input" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autofocus 
                   autocomplete="username" 
                   placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="auth-form-group">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                <label for="password" class="auth-label" style="margin-bottom: 0;">Kata Sandi</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size: 12px; color: #2563eb; text-decoration: none; font-weight: 600;">
                        Lupa kata sandi?
                    </a>
                @endif
            </div>
            <input id="password" 
                   class="auth-input" 
                   type="password" 
                   name="password" 
                   required 
                   autocomplete="current-password" 
                   placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; margin-top: 10px;">
            <label for="remember_me" style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px; color: #475569; user-select: none;">
                <input id="remember_me" type="checkbox" name="remember" style="width: 16px; height: 16px; accent-color: #2563eb; cursor: pointer;">
                <span>Ingat saya</span>
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="auth-btn-primary">
            <span>Masuk Sekarang</span>
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </button>

        <!-- Register Link -->
        @if (Route::has('register'))
            <div class="auth-switch-link">
                Belum memiliki akun?
                <a href="{{ route('register') }}">Daftar Akun Baru</a>
            </div>
        @endif
    </form>
</x-guest-layout>
