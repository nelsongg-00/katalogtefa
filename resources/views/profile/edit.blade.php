<x-app-layout>
    <style>
        .profile-page-header {
            background: linear-gradient(135deg, #0a215e 0%, #1e3a8a 100%);
            color: #ffffff;
            padding: 40px 20px;
            text-align: center;
        }

        .profile-page-header h1 {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 6px;
        }

        .profile-page-header p {
            color: #cbd5e1;
            font-size: 14px;
            max-width: 600px;
            margin: 0 auto;
        }

        .profile-container {
            max-width: 860px;
            margin: 30px auto 60px;
            padding: 0 20px;
        }

        .profile-user-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            padding: 26px 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            display: flex;
            align-items: center;
            gap: 22px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .profile-avatar-circle {
            width: 68px;
            height: 68px;
            min-width: 68px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            font-weight: 800;
            font-size: 26px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
            text-transform: uppercase;
        }

        .profile-user-info h2 {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .profile-role-pill {
            font-size: 11px;
            font-weight: 800;
            padding: 3px 10px;
            border-radius: 999px;
            letter-spacing: 0.5px;
        }

        .profile-card-section {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            margin-bottom: 24px;
        }

        .profile-section-title {
            font-size: 17px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .profile-section-desc {
            font-size: 13.5px;
            color: #64748b;
            margin-bottom: 22px;
        }

        .profile-form-group {
            margin-bottom: 18px;
        }

        .profile-form-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .profile-form-input {
            width: 100%;
            max-width: 480px;
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

        .profile-form-input:focus {
            background-color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .profile-btn-save {
            background: #2563eb;
            color: #ffffff;
            padding: 10px 24px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 13.5px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25);
        }

        .profile-btn-save:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        .profile-btn-danger {
            background: #fee2e2;
            color: #dc2626;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 13px;
            border: 1px solid #fecaca;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .profile-btn-danger:hover {
            background: #fecaca;
            color: #b91c1c;
        }
    </style>

    <!-- Header Hero -->
    <div class="profile-page-header">
        <h1>Pengaturan Profil Akun</h1>
        <p>Kelola data identitas akun, alamat email, dan keamanan kata sandi Anda.</p>
    </div>

    <div class="profile-container">

        <!-- User Identity Summary Card -->
        @php
            $u = auth()->user();
            $initial = strtoupper(substr($u->name, 0, 1));
            $roleLabel = match($u->role ?? 'pelanggan') {
                'super_admin' => 'Super Admin',
                'admin_jurusan' => 'Admin Jurusan',
                'worker' => 'Worker TEFA',
                default => 'Pelanggan',
            };
            $rolePillStyle = match($u->role ?? 'pelanggan') {
                'super_admin' => 'background: #fffbeb; color: #b45309; border: 1px solid #fde68a;',
                'admin_jurusan' => 'background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe;',
                'worker' => 'background: #f5f3ff; color: #7c3aed; border: 1px solid #ddd6fe;',
                default => 'background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0;',
            };
        @endphp

        <div class="profile-user-card">
            <div class="profile-avatar-circle">
                {{ $initial }}
            </div>
            <div class="profile-user-info">
                <h2>
                    <span>{{ $u->name }}</span>
                    <span class="profile-role-pill" style="{{ $rolePillStyle }}">
                        {{ $roleLabel }}
                    </span>
                </h2>
                <div style="font-size: 14px; color: #64748b; margin-bottom: 4px;">{{ $u->email }}</div>
                <div style="font-size: 12px; color: #94a3b8;">
                    Bergabung sejak: {{ $u->created_at ? $u->created_at->translatedFormat('d F Y') : '-' }}
                </div>
            </div>
        </div>

        <!-- Section 1: Informasi Profil -->
        <div class="profile-card-section">
            @include('profile.partials.update-profile-information-form')
        </div>

        <!-- Section 2: Keamanan & Kata Sandi -->
        <div class="profile-card-section">
            @include('profile.partials.update-password-form')
        </div>

        <!-- Section 3: Hapus Akun -->
        <div class="profile-card-section" style="border-color: #fee2e2;">
            @include('profile.partials.delete-user-form')
        </div>

    </div>
</x-app-layout>
