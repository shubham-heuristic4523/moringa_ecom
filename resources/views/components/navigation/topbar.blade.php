{{--
 | Navigation Component: components/navigation/topbar.blade.php
 | Purpose : Storefront sticky top navigation bar.
 | Usage   : <x-navigation.topbar :cartCount="$cartCount" />
--}}
@props([
    'cartCount' => 0,
    'activeLink' => '',
])

<header
    class="bg-surface/80 dark:bg-inverse-surface/80 backdrop-blur-md docked full-width top-0 sticky z-50 border-b border-outline-variant/30 shadow-sm transition-all duration-300"
    id="global-nav"
>
    <div class="flex justify-between items-center h-16 px-gutter max-w-container-max mx-auto">

        {{-- Brand Logo --}}
        <a class="font-display text-h4 font-bold text-on-surface dark:text-inverse-on-surface tracking-tight"
           href="{{ route('home') }}">LUXE</a>

        {{-- Navigation Links (Desktop) --}}
        <nav class="hidden md:flex items-center gap-2xl" aria-label="Main Navigation">
            <a class="font-nav text-nav uppercase tracking-wider py-xs hover:text-primary transition-colors duration-200 nav-link
                {{ $activeLink === 'shop' ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant dark:text-surface-variant' }}"
               href="{{ route('products.index') }}">Shop</a>
            <a class="font-nav text-nav uppercase tracking-wider py-xs hover:text-primary transition-colors duration-200 nav-link
                {{ $activeLink === 'collections' ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant dark:text-surface-variant' }}"
               href="#">Collections</a>
            <a class="font-nav text-nav uppercase tracking-wider py-xs hover:text-primary transition-colors duration-200 nav-link
                {{ $activeLink === 'new-arrivals' ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant dark:text-surface-variant' }}"
               href="#">New Arrivals</a>
            <a class="font-nav text-nav uppercase tracking-wider py-xs hover:text-primary transition-colors duration-200 nav-link
                {{ $activeLink === 'sale' ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant dark:text-surface-variant' }}"
               href="#">Sale</a>
        </nav>

        {{-- Trailing Icons --}}
        <div class="flex items-center gap-lg text-primary dark:text-primary-fixed-dim">

            {{-- Search --}}
            <button aria-label="Search" class="hover:text-primary transition-colors duration-200 active:scale-95 transition-transform">
                <span class="material-symbols-outlined" data-icon="search">search</span>
            </button>

            {{-- Wishlist --}}
            <a href="{{ route('customer.wishlist') }}" aria-label="Wishlist" class="hover:text-primary transition-colors duration-200 active:scale-95 transition-transform">
                <span class="material-symbols-outlined" data-icon="favorite">favorite</span>
            </a>

            {{-- Cart --}}
            <a href="{{ route('customer.cart') }}" aria-label="Shopping Bag" class="hover:text-primary transition-colors duration-200 active:scale-95 transition-transform relative">
                <span class="material-symbols-outlined" data-icon="shopping_bag">shopping_bag</span>
                @if($cartCount > 0)
                    <span class="absolute -top-1 -right-1 bg-primary text-on-primary text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">
                        {{ $cartCount > 9 ? '9+' : $cartCount }}
                    </span>
                @endif
            </a>

            {{-- Account --}}
            <a href="{{ auth()->check() ? route('customer.dashboard') : route('auth.login') }}"
               aria-label="Account"
               class="hover:text-primary transition-colors duration-200 active:scale-95 transition-transform hidden md:block">
                <span class="material-symbols-outlined" data-icon="person">person</span>
            </a>

        </div>

    </div>
</header>
