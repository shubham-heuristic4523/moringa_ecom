{{--
 | Layout: layouts/checkout.blade.php
 | Purpose: Minimal checkout shell — transactional topbar and minimal footer.
 | Usage  : @extends('layouts.checkout')
--}}
<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head', [
        'title'       => $title       ?? 'Checkout - LUXE',
        'description' => $description ?? null,
    ])
    <style>
        body { background-color: #FFFDF8; }
        input[type="radio"]:checked { accent-color: #9d4300; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>
</head>
<body class="font-body-default text-body-default text-on-surface antialiased">

    {{-- Minimal Checkout Topbar --}}
    <header class="bg-surface/80 backdrop-blur-md border-b border-outline-variant/30 sticky top-0 z-50">
        <div class="flex justify-between items-center h-16 px-gutter max-w-container-max mx-auto">
            <a href="{{ route('home') }}" class="font-display text-h4 font-bold text-on-surface">LUXE</a>
            <div class="flex items-center gap-2 text-on-surface-variant font-nav text-nav uppercase tracking-wider">
                <span class="material-symbols-outlined text-[18px]">lock</span>
                Secure Checkout
            </div>
        </div>
    </header>

    {{-- Flash Messages --}}
    <div class="max-w-[800px] mx-auto px-gutter pt-md">
        @include('partials.flash-messages')
    </div>

    {{-- Checkout Progress Bar --}}
    @hasSection('checkout-progress')
        @yield('checkout-progress')
    @endif

    {{-- Page Content --}}
    <main class="max-w-[800px] mx-auto px-gutter py-8xl min-h-screen">
        @yield('content')
    </main>

    {{-- Minimal Footer --}}
    <x-ui.footer-minimal />

    @include('partials.scripts')
</body>
</html>
