<x-guest-layout>
    <style>
        .auth-header-box {
            margin-bottom: 26px;
            text-align: left;
        }

        .auth-welcome-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 11.5px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .auth-title {
            font-size: 24px;
            font-weight: 900;
            color: #0a215e;
            margin-bottom: 6px;
            letter-spacing: -0.3px;
        }

        .auth-subtitle {
            font-size: 13.5px;
            color: #64748b;
            line-height: 1.5;
        }

        .auth-form-group {
            margin-bottom: 20px;
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

        .auth-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .auth-input-icon {
            position: absolute;
            left: 14px;
            color: #94a3b8;
            pointer-events: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }

        .auth-input {
            width: 100%;
            padding: 12px 42px 12px 42px;
            font-size: 14px;
            font-weight: 500;
            color: #0f172a;
            background-color: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-radius: 12px;
            outline: none;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .auth-input:focus {
            background-color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 3.5px rgba(37, 99, 235, 0.15);
        }

        .auth-input:focus + .auth-input-icon,
        .auth-input-wrapper:focus-within .auth-input-icon {
            color: #2563eb;
        }

        .auth-input::placeholder {
            color: #94a3b8;
        }

        .auth-toggle-pwd {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: color 0.2s;
        }

        .auth-toggle-pwd:hover {
            color: #2563eb;
        }

        .auth-btn-primary {
            width: 100%;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            padding: 14px;
            font-size: 15px;
            font-weight: 800;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 4px 16px rgba(37, 99, 235, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
        }

        .auth-btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.45);
        }

        .auth-btn-primary:active {
            transform: translateY(0);
        }

        .auth-switch-link {
            text-align: center;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
            font-size: 13.5px;
            color: #64748b;
        }

        .auth-switch-link a {
            color: #2563eb;
            font-weight: 800;
            text-decoration: none;
            margin-left: 4px;
            transition: color 0.15s;
        }

        .auth-switch-link a:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }
    </style>

    <div class="auth-header-box">
        <span class="auth-welcome-pill">👋 Akses Portal Siswa & Mitra</span>
        <h2 class="auth-title">Masuk ke Akun</h2>
        <p class="auth-subtitle">Masukkan alamat email dan kata sandi untuk melanjutkan.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="auth-form-group">
            <label for="email" class="auth-label">Alamat Email</label>
            <div class="auth-input-wrapper">
                <span class="auth-input-icon">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </span>
                <input id="email" 
                       class="auth-input" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus 
                       autocomplete="username" 
                       placeholder="nama@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="auth-form-group">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                <label for="password" class="auth-label" style="margin-bottom: 0;">Kata Sandi</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size: 12px; color: #2563eb; text-decoration: none; font-weight: 700;">
                        Lupa kata sandi?
                    </a>
                @endif
            </div>
            <div class="auth-input-wrapper">
                <span class="auth-input-icon">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </span>
                <input id="password" 
                       class="auth-input" 
                       type="password" 
                       name="password" 
                       required 
                       autocomplete="current-password" 
                       placeholder="••••••••" />
                <button type="button" 
                        class="auth-toggle-pwd" 
                        onclick="togglePwdVisibility('password', this)" 
                        title="Tampilkan / Sembunyikan Kata Sandi">
                    <svg class="eye-show" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg class="eye-hide" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; margin-top: 10px;">
            <label for="remember_me" style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13.5px; color: #475569; user-select: none;">
                <input id="remember_me" type="checkbox" name="remember" style="width: 17px; height: 17px; accent-color: #2563eb; cursor: pointer; border-radius: 4px;">
                <span style="font-weight: 500;">Ingat saya di perangkat ini</span>
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="auth-btn-primary">
            <span>Masuk Sekarang</span>
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
            </svg>
        </button>

        <!-- Register Link -->
        @if (Route::has('register'))
            <div class="auth-switch-link">
                Belum memiliki akun?
                <a href="{{ route('register') }}">Daftar Akun Baru</a>
            </div>
        @endif
    </form>

    <script>
        function togglePwdVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;

            const eyeShow = btn.querySelector('.eye-show');
            const eyeHide = btn.querySelector('.eye-hide');

            if (input.type === 'password') {
                input.type = 'text';
                if (eyeShow) eyeShow.style.display = 'none';
                if (eyeHide) eyeHide.style.display = 'block';
            } else {
                input.type = 'password';
                if (eyeShow) eyeShow.style.display = 'block';
                if (eyeHide) eyeHide.style.display = 'none';
            }
        }
    </script>
</x-guest-layout>
