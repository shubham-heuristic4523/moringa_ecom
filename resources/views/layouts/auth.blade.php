{{--
 | Layout: layouts/auth.blade.php
 | Purpose: Admin portal auth shell (centered card, luxury gradient background).
 | Usage  : @extends('layouts.auth')
--}}
<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head', [
        'title'       => $title       ?? 'LUXE Admin | Secure Access',
        'description' => $description ?? null,
    ])
    <style>
        body { background-color: #FFFDF8; font-family: 'Geist', sans-serif; -webkit-font-smoothing: antialiased; }
        .luxury-gradient {
            background:
                radial-gradient(circle at top right, rgba(249,115,22,0.05), transparent),
                radial-gradient(circle at bottom left, rgba(0,101,145,0.03), transparent);
        }
        .admin-glass-panel {
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(0,0,0,0.05);
        }
        .input-focus-ring:focus {
            outline: none;
            border-color: #f97316;
            box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
        }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>
</head>
<body class="luxury-gradient min-h-screen flex flex-col items-center justify-center p-gutter">

    {{-- Auth Container --}}
    <main class="w-full max-w-[440px] z-10">

        {{-- Branding --}}
        <div class="text-center mb-8xl">
            <h1 class="font-display text-h4 font-bold text-[#111827] tracking-tight mb-xs">LUXE</h1>
            <p class="font-body-sm text-on-surface-variant uppercase tracking-widest text-[10px] font-semibold">
                Secure Management Portal
            </p>
        </div>

        {{-- Flash Messages --}}
        @include('partials.flash-messages')

        @yield('content')

        {{-- Footer --}}
        <footer class="mt-8xl text-center space-y-sm">
            <div class="flex justify-center items-center gap-md text-caption text-on-surface-variant opacity-60">
                <span>SYSTEM v2.4.0-PRIME</span>
                <span class="w-1 h-1 bg-on-surface-variant rounded-full"></span>
                <span>SECURE_ENCLAVE_ACTIVE</span>
            </div>
            <p class="text-caption text-on-surface-variant/40">
                © {{ date('Y') }} LUXE Ecommerce Management. Restricted Access.
            </p>
        </footer>

    </main>

    {{-- Background Decoration --}}
    <div class="fixed top-0 left-0 w-full h-full -z-10 overflow-hidden pointer-events-none" aria-hidden="true">
        <div class="absolute top-[10%] left-[10%] w-[400px] h-[400px] bg-primary/5 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-[10%] right-[10%] w-[300px] h-[300px] bg-tertiary/5 rounded-full blur-[80px]"></div>
    </div>

    @include('partials.scripts')
</body>
</html>
