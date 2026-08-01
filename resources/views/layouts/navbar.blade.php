{{-- resources/views/layouts/navbar.blade.php
     Include with @include('layouts.navbar') inside the main column, next to
     @include('layouts.sidebar'). Requires admin.css + navbar.js via @vite. --}}
<header class="admin-navbar font-body">

    {{-- Mobile sidebar trigger --}}
    <button type="button" id="sidebar-mobile-btn" class="navbar-icon-btn lg:hidden" aria-label="Open sidebar">
        <i class="fa-solid fa-bars"></i>
    </button>

    {{-- Page title / breadcrumb slot --}}
    <div class="min-w-0 flex-1">
        <h1 class="truncate font-display text-lg font-semibold text-forest-950 dark:text-cream-50">
            @yield('page-title', 'Dashboard')
        </h1>
        <p class="hidden text-xs text-forest-700/70 dark:text-moringa-200/70 sm:block">
            @yield('page-subtitle', 'Welcome back — here’s what’s happening today.')
        </p>
    </div>

    {{-- Search --}}
    <div class="navbar-search hidden w-full max-w-xs md:flex">
        <i class="fa-solid fa-magnifying-glass text-sm text-forest-700/60 dark:text-moringa-200/60"></i>
        <input type="text" placeholder="Search orders, products…" aria-label="Search">
    </div>

    <div class="flex items-center gap-2">

        {{-- Theme toggle --}}
        <button type="button" id="theme-toggle-btn" class="theme-toggle" aria-label="Toggle dark mode">
            <span class="theme-toggle-knob">
                <i class="fa-solid fa-sun" id="theme-icon-light"></i>
                <i class="fa-solid fa-moon hidden" id="theme-icon-dark"></i>
            </span>
        </button>

        {{-- Notifications --}}
        <div class="relative" data-dropdown-root>
            <button type="button" class="navbar-icon-btn" data-dropdown-toggle aria-label="Notifications">
                <i class="fa-solid fa-bell"></i>
                <span class="navbar-dot has-pulse"></span>
            </button>
            <div class="navbar-dropdown" data-dropdown-panel>
                <p class="px-2 py-1.5 text-xs font-semibold uppercase tracking-wide text-forest-700/60 dark:text-moringa-200/60">
                    Notifications
                </p>
                <a href="#" class="dropdown-item">
                    <i class="fa-solid fa-box text-moringa-600 dark:text-moringa-300"></i>
                    <span>New order #1042 placed</span>
                </a>
                <a href="#" class="dropdown-item">
                    <i class="fa-solid fa-triangle-exclamation text-gold-500"></i>
                    <span>Moringa Powder 250g low in stock</span>
                </a>
                <a href="#" class="dropdown-item">
                    <i class="fa-solid fa-star text-moringa-600 dark:text-moringa-300"></i>
                    <span>New 5★ review received</span>
                </a>
            </div>
        </div>

        {{-- Profile --}}
        <div class="relative" data-dropdown-root>
            <button type="button" class="flex items-center gap-2 rounded-full py-1 pl-1 pr-2 transition-colors hover:bg-moringa-200/50 dark:hover:bg-moringa-700/40" data-dropdown-toggle>
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-moringa-400 to-forest-600 text-sm font-semibold text-white">
                    {{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}
                </span>
                <span class="hidden text-left sm:block">
                    <span class="block text-sm font-semibold leading-tight text-forest-950 dark:text-cream-50">
                        {{ auth()->user()?->name ?? 'Admin' }}
                    </span>
                    <span class="block text-[0.7rem] leading-tight text-forest-700/60 dark:text-moringa-200/60">Administrator</span>
                </span>
                <i class="fa-solid fa-chevron-down navbar-chevron text-xs text-forest-700/60 dark:text-moringa-200/60" data-dropdown-chevron></i>
            </button>
            <div class="navbar-dropdown" data-dropdown-panel>
                <a href="#" class="dropdown-item"><i class="fa-solid fa-user"></i><span>My Profile</span></a>
                <a href="#" class="dropdown-item"><i class="fa-solid fa-gear"></i><span>Account Settings</span></a>
                <hr class="my-1 border-forest-900/10 dark:border-moringa-200/10">
                <form method="POST" action="{{ url('/logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item w-full text-left text-red-600">
                        <i class="fa-solid fa-right-from-bracket"></i><span>Log Out</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
