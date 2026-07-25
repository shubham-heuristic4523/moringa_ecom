{{--
 | Layout: layouts/guest.blade.php
 | Purpose: Auth/public-facing page shell (login, register, password reset).
 | Usage  : @extends('layouts.guest')
--}}
<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head', [
        'title'       => $title       ?? 'LUXE | Secure Access',
        'description' => $description ?? null,
    ])
    <style>
        body { font-family: 'Geist', sans-serif; background-color: #FFFDF8; color: #111827; }
        .shimmer-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            animation: shimmer 1.5s infinite;
        }
        @keyframes shimmer { 0%{transform:translateX(-100%)} 100%{transform:translateX(100%)} }
        .input-group input:focus ~ label,
        .input-group input:not(:placeholder-shown) ~ label {
            transform: translateY(-24px) scale(0.85); color: #9d4300;
        }
        .input-group label { transition: all 0.2s cubic-bezier(0.4,0,0.2,1); pointer-events: none; }
        .hidden-flow { display: none; }
        .fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.16,1,0.3,1) forwards; }
        @keyframes fadeInUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
    </style>
</head>
<body class="min-h-screen flex flex-col md:flex-row overflow-x-hidden">

    {{-- Mobile Brand Header --}}
    <header class="md:hidden flex items-center justify-between p-lg bg-surface border-b border-outline-variant/30 sticky top-0 z-50">
        <a href="{{ route('home') }}" class="font-display text-h5 font-bold tracking-tight text-on-surface">LUXE</a>
        <button class="material-symbols-outlined text-on-surface-variant" aria-label="Help">help_outline</button>
    </header>

    {{-- Left Lifestyle Panel (Desktop) --}}
    @hasSection('auth-panel')
        @yield('auth-panel')
    @else
        <section class="hidden md:flex md:w-1/2 lg:w-3/5 h-screen relative sticky top-0 overflow-hidden">
            <div class="absolute inset-0 z-0 scale-105 transition-transform duration-[10s] hover:scale-100">
                <div class="w-full h-full bg-cover bg-center"
                     style="background-image: url('{{ $panelImage ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuBe9DOoDhE4rcRKVquazWNB54RxMKSMjrGxQrDihaUHf_mZwjgvFla-v4mJI-CIsEJhXSgrGaACwK6n92yxP6-R1qIm3HP0TKGVVnJpqC25wrY4FNLDAZHS6ck9yxWrXxctZBON_N6KKj38-tKYfRMUXLdhJyxhmOlcBSH_YmNeUrLy0a-ClOqKfuxV-OXoeqRTlryeXAP4ghKE4MzuvdV_H1rxgTcbKFTRhyP4E8jHq3WkpVyxMHqJkteyCfJugdBFGvFjKabJ8uQ' }}')">
                </div>
            </div>
            <div class="absolute inset-0 bg-black/10 z-10"></div>
            <div class="absolute bottom-4xl left-4xl z-20 max-w-lg text-white">
                <span class="font-display text-h3 md:text-h1 mb-md block">LUXE</span>
                <p class="font-body-lg text-white/90 leading-relaxed">
                    Experience the intersection of high-performance utility and precise luxury. Your journey to the exceptional begins here.
                </p>
            </div>
        </section>
    @endif

    {{-- Right Form Canvas --}}
    <main class="w-full md:w-1/2 lg:w-2/5 flex flex-col justify-center px-gutter py-8xl md:px-8xl bg-surface">
        <div class="max-w-md mx-auto w-full">

            {{-- Desktop Logo --}}
            <div class="hidden md:block mb-8xl">
                <a href="{{ route('home') }}" class="font-display text-h4 font-bold text-on-surface">LUXE</a>
            </div>

            {{-- Flash Messages --}}
            @include('partials.flash-messages')

            @yield('content')

        </div>
    </main>

    @include('partials.scripts')
</body>
</html>
