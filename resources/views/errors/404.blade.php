{{--
 | Page: errors/404.blade.php
 | Source: empty_search_results/code.html (adapted as error page)
--}}
<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head', ['title' => '404 — Page Not Found | LUXE'])
</head>
<body class="bg-background text-on-background font-body-default antialiased min-h-screen flex flex-col">

    <x-navigation.topbar />

    <main class="flex-1 flex flex-col items-center justify-center px-gutter py-8xl text-center">
        <span class="material-symbols-outlined text-[96px] text-on-surface-variant/20 mb-2xl">search_off</span>
        <h1 class="font-h1-mobile md:font-h1 text-h1-mobile md:text-h1 text-on-surface mb-lg">Page Not Found</h1>
        <p class="font-body-lg text-body-lg text-on-surface-variant max-w-md mb-4xl">
            The page you're looking for doesn't exist or has been moved.
        </p>
        <div class="flex flex-col sm:flex-row gap-lg">
            <x-ui.button href="{{ route('home') }}" variant="primary" size="lg" icon="home" iconPosition="left">
                Go Home
            </x-ui.button>
            <x-ui.button href="{{ route('products.index') }}" variant="secondary" size="lg">
                Browse Products
            </x-ui.button>
        </div>
    </main>

    <x-ui.footer />
    @include('partials.scripts')
</body>
</html>
