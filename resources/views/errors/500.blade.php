{{--
 | Page: errors/500.blade.php
--}}
<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head', ['title' => '500 — Server Error | LUXE'])
</head>
<body class="bg-background text-on-background font-body-default antialiased min-h-screen flex flex-col items-center justify-center">
    <div class="text-center px-gutter">
        <span class="material-symbols-outlined text-[96px] text-error/30 mb-2xl">error</span>
        <h1 class="font-h1-mobile md:font-h1 text-h1-mobile md:text-h1 text-on-surface mb-lg">Something Went Wrong</h1>
        <p class="font-body-lg text-body-lg text-on-surface-variant max-w-md mx-auto mb-4xl">
            We encountered an unexpected error. Our team has been notified.
        </p>
        <x-ui.button href="{{ route('home') }}" variant="primary" size="lg">Return Home</x-ui.button>
    </div>
    @include('partials.scripts')
</body>
</html>
