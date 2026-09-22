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
            margin-bottom: 16px;
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
            margin-top: 20px;
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

    <div class="auth-title">Daftar Akun Baru</div>
    <div class="auth-subtitle">Buat akun untuk memesan produk & layanan TEFA</div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="auth-form-group">
            <label for="name" class="auth-label">Nama Lengkap</label>
            <input id="name" 
                   class="auth-input" 
                   type="text" 
                   name="name" 
                   value="{{ old('name') }}" 
                   required 
                   autofocus 
                   autocomplete="name" 
                   placeholder="Contoh: Budi Santoso" />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div class="auth-form-group">
            <label for="email" class="auth-label">Alamat Email</label>
            <input id="email" 
                   class="auth-input" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autocomplete="username" 
                   placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="auth-form-group">
            <label for="password" class="auth-label">Kata Sandi</label>
            <input id="password" 
                   class="auth-input" 
                   type="password" 
                   name="password" 
                   required 
                   autocomplete="new-password" 
                   placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div class="auth-form-group">
            <label for="password_confirmation" class="auth-label">Konfirmasi Kata Sandi</label>
            <input id="password_confirmation" 
                   class="auth-input" 
                   type="password" 
                   name="password_confirmation" 
                   required 
                   autocomplete="new-password" 
                   placeholder="Ulangi kata sandi" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <!-- Submit Button -->
        <button type="submit" class="auth-btn-primary">
            <span>Daftar Akun Baru</span>
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </button>

        <!-- Login Link -->
        <div class="auth-switch-link">
            Sudah memiliki akun?
            <a href="{{ route('login') }}">Masuk di Sini</a>
        </div>
    </form>
</x-guest-layout>
