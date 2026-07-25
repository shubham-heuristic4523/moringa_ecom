{{--
 | Layout: layouts/admin.blade.php
 | Purpose: Admin panel shell — sidebar + topbar + scrollable content canvas.
 | Usage  : @extends('layouts.admin')
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head', [
        'title'       => $title       ?? 'LUXE Admin',
        'description' => $description ?? 'LUXE Premium Management Console',
    ])
    <style>body { font-family: 'Geist', sans-serif; background-color: #f9f9ff; }</style>
</head>
<body class="bg-background text-on-surface h-screen flex overflow-hidden">

    {{-- Left Sidebar Navigation --}}
    <x-navigation.admin-sidebar :activeItem="$activeItem ?? 'dashboard'" />

    {{-- Main Content --}}
    <main class="flex-1 flex flex-col h-full overflow-hidden">

        {{-- Admin Topbar --}}
        <x-navigation.admin-topbar :breadcrumbs="$breadcrumbs ?? [['label' => 'Dashboard']]" />

        {{-- Flash Messages --}}
        <div class="px-gutter pt-md">
            @include('partials.flash-messages')
        </div>

        {{-- Scrollable Canvas --}}
        <div class="flex-1 overflow-y-auto p-gutter lg:p-4xl">
            <div class="max-w-container-max mx-auto flex flex-col gap-3xl">
                @yield('content')
            </div>
        </div>

    </main>

    @include('partials.scripts')
</body>
</html>
