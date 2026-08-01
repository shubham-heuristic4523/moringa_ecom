<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') | Moringa Admin</title>

    {{-- Fraunces (display/organic serif) + Plus Jakarta Sans (body/UI) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Font Awesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/admin.css', 'resources/js/sidebar.js', 'resources/js/navbar.js'])

    @stack('styles')
</head>

{{--
    data-sidebar="expanded"  -> "collapsed" (desktop) or "mobile-open" (mobile), via sidebar.js
    data-theme="light"       -> "dark", via navbar.js
    Both live on <body> so any component can style off them, see admin.css
--}}
<body
    class="font-body antialiased text-forest-950 selection:bg-moringa-200 selection:text-forest-950"
    data-sidebar="expanded"
    data-theme="light"
>
    <div class="admin-backdrop" aria-hidden="true"></div>

    <div class="admin-shell">

        @include('layouts.sidebar')

        <div class="admin-main">

            @include('layouts.navbar')

            <main class="admin-content" id="main-content">
                <div class="animate-fade-in">
                    @yield('content')
                    {{ $slot ?? '' }}
                </div>
            </main>

        </div>
    </div>

    @stack('scripts')
</body>
</html>
