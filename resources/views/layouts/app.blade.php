<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Toast Notification (Smart Redirect Feedback) -->
        @if(session('toast_success'))
            <div id="smart-toast" 
                 style="position: fixed; top: 24px; right: 24px; z-index: 9999; display: flex; align-items: center; gap: 12px; background: #0f172a; color: #ffffff; padding: 14px 20px; border-radius: 12px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.2); border-left: 5px solid #10b981; animation: toastSlideIn 0.35s cubic-bezier(0.16, 1, 0.3, 1); max-width: 420px;">
                <div style="width: 28px; height: 28px; border-radius: 50%; background: #10b981; display: grid; place-items: center; color: #fff; font-weight: 800; font-size: 14px; flex-shrink: 0;">
                    ✓
                </div>
                <div style="flex: 1; font-size: 13.5px; font-weight: 600; line-height: 1.4;">
                    {{ session('toast_success') }}
                </div>
                <button type="button" onclick="dismissToast()" style="background: none; border: none; color: #94a3b8; font-size: 20px; cursor: pointer; line-height: 1; padding: 0 4px;" aria-label="Tutup">
                    &times;
                </button>
            </div>

            <style>
            @keyframes toastSlideIn {
                from { transform: translateX(110%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes toastSlideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(110%); opacity: 0; }
            }
            </style>

            <script>
            function dismissToast() {
                const toast = document.getElementById('smart-toast');
                if (toast) {
                    toast.style.animation = 'toastSlideOut 0.3s forwards';
                    setTimeout(() => toast.remove(), 300);
                }
            }
            setTimeout(dismissToast, 3500);
            </script>
        @endif
    </body>
</html>
