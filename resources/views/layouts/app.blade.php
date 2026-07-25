{{--
 | Layout: layouts/app.blade.php
 | Purpose: Main storefront shell — announcement bar, topbar, content, footer, mobile nav.
 | Usage  : @extends('layouts.app')
--}}
<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head', [
        'title'       => $title       ?? config('app.name'),
        'description' => $description ?? null,
    ])
</head>
<body class="bg-background text-on-background font-body-default antialiased selection:bg-primary-container selection:text-on-primary-container">

    {{-- Announcement Bar --}}
    @include('partials.announcement-bar')

    {{-- Top Navigation --}}
    <x-navigation.topbar
        :cartCount="auth()->check() ? auth()->user()->cartItems()->count() : 0"
        :activeLink="$activeLink ?? ''"
    />

    {{-- Flash Messages --}}
    @include('partials.flash-messages')

    {{-- Main Page Content --}}
    <main class="pb-8xl md:pb-0">
        @yield('content')
    </main>

    {{-- Footer --}}
    <x-ui.footer />

    {{-- Mobile Bottom Navigation --}}
    <x-navigation.bottom-nav
        :cartCount="auth()->check() ? auth()->user()->cartItems()->count() : 0"
        :activeTab="$activeTab ?? 'home'"
    />

    {{-- Global Scripts --}}
    @include('partials.scripts')

</body>
</html>
